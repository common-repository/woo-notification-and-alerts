<?php
class promoControllerWns extends controllerWns {
    public function welcomePageSaveInfo() {
		$res = new responseWns();
		installerWns::setUsed();
		if($this->getModel()->welcomePageSaveInfo(reqWns::get('get'))) {
			$res->addMessage(__('Information was saved. Thank you!', WNS_LANG_CODE));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$originalPage = reqWns::getVar('original_page');
		$http = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
		if(strpos($originalPage, $http. $_SERVER['HTTP_HOST']) !== 0) {
			$originalPage = '';
		}
		redirectWns($originalPage);
	}
	public function sendContact() {
		$res = new responseWns();
		$time = time();
		$prevSendTime = (int) get_option(WNS_CODE. '_last__time_contact_send');
		if($prevSendTime && ($time - $prevSendTime) < 5 * 60) {	// Only one message per five minutes
			$res->pushError(__('Please don\'t send contact requests so often - wait for response for your previous requests.'));
			$res->ajaxExec();
		}
        $data = reqWns::get('post');
        $fields = $this->getModule()->getContactFormFields();
		foreach($fields as $fName => $fData) {
			$validate = isset($fData['validate']) ? $fData['validate'] : false;
			$data[ $fName ] = isset($data[ $fName ]) ? trim($data[ $fName ]) : '';
			if($validate) {
				$error = '';
				foreach($validate as $v) {
					if(!empty($error))
						break;
					switch($v) {
						case 'notEmpty':
							if(empty($data[ $fName ])) {
								$error = $fData['html'] == 'selectbox' ? __('Please select %s', WNS_LANG_CODE) : __('Please enter %s', WNS_LANG_CODE);
								$error = sprintf($error, $fData['label']);
							}
							break;
						case 'email':
							if(!is_email($data[ $fName ])) 
								$error = __('Please enter valid email address', WNS_LANG_CODE);
							break;
					}
					if(!empty($error)) {
						$res->pushError($error, $fName);
					}
				}
			}
		}
		if(!$res->error()) {
			$msg = 'Message from: '. get_bloginfo('name').', Host: '. $_SERVER['HTTP_HOST']. '<br />';
			$msg .= 'Plugin: '. WNS_WP_PLUGIN_NAME. '<br />';
			foreach($fields as $fName => $fData) {
				if(in_array($fName, array('name', 'email', 'subject'))) continue;
				if($fName == 'category')
					$data[ $fName ] = $fData['options'][ $data[ $fName ] ];
                $msg .= '<b>'. $fData['label']. '</b>: '. nl2br($data[ $fName ]). '<br />';
            }
			if(frameWns::_()->getModule('mail')->send('support@supsystic.zendesk.com', $data['subject'], $msg, $data['name'], $data['email'])) {
				update_option(WNS_CODE. '_last__time_contact_send', $time);
			} else {
				$res->pushError( frameWns::_()->getModule('mail')->getMailErrors() );
			}
			
		}
        $res->ajaxExec();
	}
	public function addNoticeAction() {
		$res = new responseWns();
		$code = reqWns::getVar('code', 'post');
		$choice = reqWns::getVar('choice', 'post');
		if(!empty($code) && !empty($choice)) {
			$optModel = frameWns::_()->getModule('options')->getModel();
			switch($choice) {
				case 'hide':
					$optModel->save('hide_'. $code, 1);
					break;
				case 'later':
					$optModel->save('later_'. $code, time());
					break;
				case 'done':
					$optModel->save('done_'. $code, 1);
					if($code == 'enb_promo_link_msg') {
						$optModel->save('add_love_link', 1);
					}
					break;
			}
			$this->getModel()->saveUsageStat($code. '.'. $choice, true);
			$this->getModel()->checkAndSend( true );
		}
		$res->ajaxExec();
	}
	public function addTourStep() {
		$res = new responseWns();
		if($this->getModel()->addTourStep(reqWns::get('post'))) {
			$res->addMessage(__('Information was saved. Thank you!', WNS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function closeTour() {
		$res = new responseWns();
		if($this->getModel()->closeTour(reqWns::get('post'))) {
			$res->addMessage(__('Information was saved. Thank you!', WNS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function addTourFinish() {
		$res = new responseWns();
		if($this->getModel()->addTourFinish(reqWns::get('post'))) {
			$res->addMessage(__('Information was saved. Thank you!', WNS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function saveDeactivateData() {
		$res = new responseWns();
		if($this->getModel()->saveDeactivateData(reqWns::get('post'))) {
			$res->addMessage(__('Thank you for Feedback!', WNS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function enbStatsOpt() {
		$res = new responseWns();
		frameWns::_()->getModule('options')->getModel()->save('send_stats', 1);
		$res->ajaxExec();
	}
	/**
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			WNS_USERLEVELS => array(
				WNS_ADMIN => array('welcomePageSaveInfo', 'sendContact', 'addNoticeAction', 
					'addStep', 'closeTour', 'addTourFinish', 'saveDeactivateData', 'enbStatsOpt')
			),
		);
	}
}