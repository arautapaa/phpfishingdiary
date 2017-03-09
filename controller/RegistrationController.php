<?php 

require_once('Controller.php');

class RegistrationController extends ControllerImpl implements Controller {
	public function execute() {
		parent::execute();

		$user = new User();
		$salt = uniqid(mt_rand(), true);
		$password = $this->data['password'] . $salt;

		for($i = 0; $i < 100; $i++) {
			$password = hash('sha512', sha1($password));
		}

		$user->setSalt($salt);
		$user->setUserName($this->data['username']);
		$user->setPassword($password);

		AuthenticationHelper::login($user, true);

		$this->setRedirect("/fishes/");
	}
}

?>