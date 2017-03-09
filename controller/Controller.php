<?php

require_once(__DIR__. "/../dispatcher.php");

interface Controller {
	public function execute();
}

class ControllerImpl implements Controller {
	
	protected $requestParameters;
	protected $data = array();
	protected $redirect;

	public function setRequestParameters($requestParameters) {
		$this->requestParameters = $requestParameters;
	}

	public function getRequestParameters() {
		return $this->requestParameters;
	}

	public function setData($key, $data) {
		$this->data[$key] = $data;
	}

	protected function parseRequestParameters() {


		foreach ($this->requestParameters as $key => $value) {
			if(!is_array($value)) {
				$this->data[htmlspecialchars($key)] = htmlspecialchars($value);
			} else {
				foreach ($value as $arraykey => $arrayvalue) {
					$value[htmlspecialchars($arraykey)] = htmlspecialchars($arrayvalue);
				}

				$this->data[htmlspecialchars($key)] = $value;
			}
		}
	}

	public function getData() {
		return $this->data;
	}

	public function getUserId() {
		return $this->data['userId'];
	}

	public function getRedirect() {
		return $this->redirect;
	}

	public function setRedirect($redirect) {
		$this->redirect = $redirect;
		$this->data['redirect'] = $redirect;
	}

	public function execute() {
		$this->parseRequestParameters();
		$this->data['userId'] = AuthenticationHelper::getAuthenticatedUser();
	}
}

?>