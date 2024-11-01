<?php
class optionsControllerWns extends controllerWns {
	public function saveGroup() {
		$res = new responseWns();
		if($this->getModel()->saveGroup(reqWns::get('post'))) {
			$res->addMessage(__('Done', WNS_LANG_CODE));
		} else
			$res->pushError ($this->getModel('options')->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			WNS_USERLEVELS => array(
				WNS_ADMIN => array('saveGroup')
			),
		);
	}
}

