<?php

require_once('Controller.php');

class LogoutController extends ControllerImpl implements Controller {
	public function execute() {
		parent::execute();
	
		AuthenticationHelper::logout();

		$this->setRedirect("/login/");
	}
}

?>