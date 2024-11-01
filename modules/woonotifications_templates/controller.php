<?php
class woonotifications_templatesControllerWns extends controllerWns {
	public function getPermissions() {
		return array(
			WNS_USERLEVELS => array(
				WNS_ADMIN => array()
			),
		);
	}
}
