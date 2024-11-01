<?php
class mailViewWns extends viewWns {
	public function getTabContent() {
		frameWns::_()->getModule('templates')->loadJqueryUi();
		frameWns::_()->addScript('admin.'. $this->getCode(), $this->getModule()->getModPath(). 'js/admin.'. $this->getCode(). '.js');
		
		$this->assign('options', frameWns::_()->getModule('options')->getCatOpts( $this->getCode() ));
		$this->assign('testEmail', frameWns::_()->getModule('options')->get('notify_email'));
		return parent::getContent('mailAdmin');
	}
}
