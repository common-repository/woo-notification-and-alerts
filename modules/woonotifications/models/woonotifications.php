<?php
class woonotificationsModelWns extends modelWns {
	public function __construct() {
		$this->_setTbl('notifications');
	}
	public function save($data = array()){
		$id = isset($data['id']) ? ($data['id']) : false;
		$templateId = isset($data['settings']['template_id']) ? ($data['settings']['template_id']) : 1;
		$title = isset($data['title']) ? ($data['title']) : false;
		$duplicateId = isset($data['duplicateId']) ? ($data['duplicateId']) : false;
        //already created filter
        if(!empty($id) && !empty($title)) {
            $data['id'] = (string)$id;
            $statusUpdate = $this->updateById( $data , $id );
						$saveNotification = frameWns::_()->getModule('woonotifications')->saveNotification($id, $templateId);
            if($statusUpdate){
                return $id;
            }
        } else if( empty($id) && empty($duplicateId)){  //empty filter
            $idInsert = $this->insert( $data );
            if($idInsert){
                if(empty($data['title'])){
                    $data['title'] = date('y-m-d H:i:s');
                }
								$data['id'] = (string)$idInsert;
								$saveNotification = frameWns::_()->getModule('woonotifications')->saveNotification($idInsert, $templateId);
                $this->updateById( $data , $idInsert );
            }
            return $idInsert;
        } else if( empty($id) && !empty($duplicateId) ){  //duplicate filter
						$duplicateData = $this->getById($duplicateId);
						$settings = frameWns::_()->getModule('woonotifications')->unserialize($duplicateData['setting_data']);
						$duplicateData['settings'] = $settings['settings'];
						$duplicateData['title'] = !empty($data['title']) ? $data['title'] : date('y-m-d H:i:s');
						$duplicateData['id'] = '';
						$idInsert = $this->insert( $duplicateData );
						$saveNotification = frameWns::_()->getModule('woonotifications')->saveNotification($idInsert, $templateId);
            return $idInsert;
        } else //empty title
            $this->pushError (__('Title can\'t be empty or more than 255 characters', WNS_LANG_CODE), 'title');
        return false;
    }
	protected function _dataSave($data, $update = false){
        $settings = isset($data['settings']) ? $data['settings'] : array();
		$data['settings']['custom_css'] = isset($settings['custom_css']) ? base64_encode($settings['custom_css']) : '';
		$data['settings']['js_editor'] = isset($settings['js_editor']) ? base64_encode($settings['js_editor']) : '';
		if (!empty($data['settings']['content']['texts'])) {
			foreach ($data['settings']['content']['texts'] as $key => $text) {
				$data['settings']['content']['texts'][$key]['content'] = str_replace(array("\r\n", "\r", "\n"), '<br>', $text['content']);
			}
		}
		$settingData = array('settings' => $data['settings']);
		$data['setting_data'] = serialize($settingData);
		return $data;
	}
}
