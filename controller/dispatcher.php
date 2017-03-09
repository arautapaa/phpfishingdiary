
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "config.php";
require_once "/model/MySqlConnect.php";

class ConfigHelper {
	public static function getConfig($file) {
		return json_decode(FileHelper::getContentByFile($file));
	}
}

class AuthenticationHelper {
	public static function getAuthenticatedUser() {
		$userId = null;

		if(isset($_COOKIE['authentication'])) {
			$user = UserQuery::create()->findOneByHash($_COOKIE['authentication']);

			if($user != null && $user->getUserId() != null) {
				$expiryTimestamp = strtotime($user->getSessionexpire()->format("Y-m-d H:i:s"));
				if($expiryTimestamp > time()) {
					self::login($user);

					$userId = $user->getUserId();
				}
			}
		} 

		return $userId;
	}

	public static function login($user, $generateHash = false) {	
		$currenttime = time();
		$expirytime = $currenttime + (3600);

		if($generateHash) {
			$cookievalue = hash('sha256', sha1($user->getUserId() . "/" . $user->getUsername() . "/" . $currenttime));
			$user->setHash($cookievalue);
		} 

		setcookie("authentication", $user->getHash(), $expirytime, "/");

		$user->setSessionexpire(date("Y-m-d H:i:s", $expirytime));
		$user->save();

		
	}

	public static function logout() {
		if(isset($_COOKIE['authentication'])) {
			$user = UserQuery::create()->findOneByHash($_COOKIE['authentication']);

			if($user->getUserId() != null) {
				$user->setSessionexpire(date("Y-m-d H:i:s"), time() - 3600);
				$user->save();
			}

			setcookie("authentication", "", time() - 3600, "/");
		} 

		
	}
}

class TokenHelper {
	public static function checkToken($service, $userId, $token) {
		$validToken = false;

		$userToken = UsertokenQuery::create()->filterByUserId($userId)->filterByService($service)->find();


		var_dump(count($userToken));

		if(count($userToken) == 1) {
			if($userToken[0]->getToken() == $token) {
				$validToken = true;
			}
		}
		// everytime we check the token, we have to generate it yeah
		self::generateToken($service, $userId);

		return $validToken;
	}

	public static function getToken($service, $userId = null) {
		if($userId == null) {
			$userId = AuthenticationHelper::getAuthenticatedUser();
		}
		
		$userToken = UsertokenQuery::create()->filterByUserId($userId)->filterByService($service)->find();

		if(count($userToken) == 0) {
			$token = self::generateToken($service, $userId);
		} else {
			$token = $userToken[0]->getToken();
		}

		return $token;
	}

	public static function generateToken($service, $userId) {
		if($userId == null) {
			$userId = AuthenticationHelper::getAuthenticatedUser();
		}

		$token = hash('sha512', sha1(md5(microtime() . $service . $userId)));
		
		$userToken = UsertokenQuery::create()->filterByUserId($userId)->filterByService($service)->find();

		if(count($userToken) == 1) {
			$userToken[0]->setToken($token);
			$userToken[0]->save();
		} else {
			$userToken = new Usertoken();

			$userToken->setUserId($userId);
			$userToken->setService($service);
			$userToken->setToken($token);
			$userToken->save();
		}

		return $token;
	}
}

class FileHelper {
	public static function getContentByFile($file) {
		ob_start();
		include $file;
		$content = ob_get_clean();
		return $content;
	}
}

class Router{

	private $url; 
	private $parameters;

	private $configJSON;
	private $configFile;

	private $view;
	private $controllerName;
	private $controller;

	private $needsAuthentication;

	public function __construct($url, $parameters) {
		$this->url = $url;
		$this->parameters = $parameters;
		$this->routeFile = "config/route.json";
		$this->initConfig();
	}

	public function execute() {
		$urlparts = parse_url($this->url);
		$path = $urlparts['path'];
		$protocol = "http";

		if(isset($urlparts['scheme'])) {
			$protocol = $urlparts['scheme'];
		}

		$requestParameters = null;

		foreach($this->configJSON->patterns as $route) {
			if($this->checkPattern($path, $route->pattern)) {
				if(($route->https == null || !$route->https) || (isset($route->https) && $protocol == 'https' && $route->https)) {
					if(array_key_exists("view", $route)) {
						$this->view = $route->view;
					}
					if(array_key_exists("controller", $route)) {
						$this->controllerName = $route->controller;
					}
					$this->needsAuthentication = $route->authenticate && AuthenticationHelper::getAuthenticatedUser() == null;

					$requestParameters = $this->getRequestParameters($route->pattern, $path);

					break;
				}
			}
		}



		// and if the user has access, let's do the controller shit 
		if(!$this->needsAuthentication && $this->controllerName != null) {
			include($this->controllerName->path);
			$controllerName = $this->controllerName->name;
			$this->controller = new $controllerName();
			$this->controller->setRequestParameters($requestParameters);
			$this->controller->setData('url', $path);
			$this->controller->execute();
		}
	}

	private function checkPattern($path, $pattern) {
		return preg_match($pattern, $path);
	}

	private function getRequestParameters($pattern, $path) {

		$requestParameters = array();

		if(preg_match($pattern, $path, $matches)) {
			foreach ($matches as $key => $value) {
				if(preg_match("/[A-Za-z]+/", $key)) {
					$requestParameters[$key] = $value;
				}
			}
		}

		foreach($this->parameters as $key => $value) {
			$requestParameters[$key] = $value;
		}

		return $requestParameters;
	}

	public function needsAuthentication() {
		return $this->needsAuthentication; 
	}

	public function getView() {
		return $this->view;
	}
	public function getController() {
		return $this->controller;
	}


	private function initConfig() {
		$this->configJSON = json_decode(FileHelper::getContentByFile($this->routeFile));
	}
}

Class Dispatcher {

	private $url;
	private $parameters;
	private $queryString;
	private $output;
	private $config;
	private $configFile;

	private $router;

	public function __construct($url, $parameters, $queryString = null) {
		$this->url = $url;
		$this->parameters = $parameters;
		$this->queryString = $queryString;
		$this->configFile = "config/config.json";
		$this->initConfig();
	}


	public function doRequest() {
		$this->router = new Router($this->getUrl(), $this->parameters);
		$this->router->execute();

		$controller = $this->router->getController();
		$view = $this->router->getView();

		$this->setOutput($this->renderView($controller, $view));
	}

	public function renderView($controller, $view) {
		$outputFile = null;
		$output = null;
		
		// if the view doesn't exist, let's get the 404 file
		if($controller != null && $controller->getRedirect() != null) {
			$outputFile = $this->config->redirect_file;
		} else if($view == null) {
			$outputFile	= $this->config->notFoundFile;
		// if we need authentication, go get the login file
		} else if($this->getRouter()->needsAuthentication()) {
			$outputFile = $this->config->login_file;
		// and if the view is fine and dandy, let's go and show the view
		} else {
			$outputFile = $view;
		}

		// if the controller is there, we have to extract the variables and set them for view
		if($controller != null) {
			if($this->queryString != null) {
				$controller->setData('queryString', $this->queryString);
			}
			extract($controller->getData());

			ob_start();
			include $outputFile;
			$content = ob_get_clean();

		// else, we can call the same action in function 
		} else {
			$content = FileHelper::getContentByFile($outputFile);
		}

		return $content;


	}

	private function initConfig() {
		$this->config = json_decode(FileHelper::getContentByFile($this->configFile));
	}

	public function getUrl() {
		return $this->url;
	}
	public function setUrl($url) {
		$this->url = $url;
	}
	public function getOutput() {
		return $this->output;
	}
	public function setOutput($output) {
		$this->output = $output;
	}
	public function getRouter() {
		return $this->router;
	}
	public function setRouter($router) {
		$this->router = $router;
	}
}

$request_array = $_POST;
$queryString = null;

// ultimate hax, because dispatcher rewrite won't work with $_GET
if($_SERVER['REQUEST_METHOD'] == 'GET' && strpos($_SERVER['REQUEST_URI'], '?') > -1) {
	$queryString = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);
	parse_str($queryString, $request_array);
}

$dispatcher = new Dispatcher($_SERVER['REQUEST_URI'], $request_array, $queryString);
$dispatcher->doRequest();
echo $dispatcher->getOutput();


?>