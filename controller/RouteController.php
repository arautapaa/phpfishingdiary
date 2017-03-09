<?php 

require_once("/dispatcher.php");
require_once("Controller.php");

class RouteController extends ControllerImpl implements Controller {
	public function execute() {
		$this->data['routes'] = json_decode(FileHelper::getContentByFile("/config/route.json"));
	}
}

?>