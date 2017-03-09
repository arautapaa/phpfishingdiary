<?php

require_once('Controller.php');

class LoginController extends ControllerImpl implements Controller {
	public function execute() {
		parent::execute();

		$user = UserQuery::create()->findOneByUsername($this->data['username']);

		$errorKey = $this->checkCredentials($user);

		if($errorKey != null) {
			$this->data['errorKey'] = $errorKey;
			$this->setRedirect("/login/");
		} else {
			AuthenticationHelper::login($user, true);
			$this->setRedirect("/fishes/");
		}
	}

	private function checkCredentials($user) {

		if($user == null || $user->getUserId() == null) {
			return "NO USER";
		}

		$password = $this->data['password'] . $user->getSalt();

		for($i = 0; $i < 100; $i++) {
			$password = hash('sha512', sha1($password));
		}

		if($user->getPassword() != $password) {
			return "ERROR_IN_LOGIN";
		}

		return null;
	}
}



?>