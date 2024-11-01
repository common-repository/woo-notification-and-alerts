<?php
class mailControllerWns extends controllerWns {
	public function testEmail() {
		$res = new responseWns();
		$email = reqWns::getVar('test_email', 'post');
		if($this->getModel()->testEmail($email)) {
			$res->addMessage(__('Now check your email inbox / spam folders for test mail.'));
		} else 
			$res->pushError ($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function saveMailTestRes() {
		$res = new responseWns();
		$result = (int) reqWns::getVar('result', 'post');
		frameWns::_()->getModule('options')->getModel()->save('mail_function_work', $result);
		$res->ajaxExec();
	}
	public function saveOptions() {
		$res = new responseWns();
		$optsModel = frameWns::_()->getModule('options')->getModel();
		$submitData = reqWns::get('post');
		if($optsModel->saveGroup($submitData)) {
			$res->addMessage(__('Done', WNS_LANG_CODE));
		} else
			$res->pushError ($optsModel->getErrors());
		$res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			WNS_USERLEVELS => array(
				WNS_ADMIN => array('testEmail', 'saveMailTestRes', 'saveOptions')
			),
		);
	}
}
