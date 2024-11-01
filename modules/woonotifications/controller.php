<?php
class woonotificationsControllerWns extends controllerWns {

	protected $_code = 'woonotifications';

	protected function _prepareTextLikeSearch($val) {
		$query = '(title LIKE "%'. $val. '%"';
		if(is_numeric($val)) {
			$query .= ' OR id LIKE "%'. (int) $val. '%"';
		}
		$query .= ')';
		return $query;
	}
	public function _prepareListForTbl($data){
		foreach($data as $key => $row){
			$id = $row['id'];
			$shortcode = "[".WNS_SHORTCODE." id=".$id."]";
			$showPrewiewButton = "<button data-id='".$id."' data-text_rule='".$shortcode."' class='button button-primary button-prewiew' style='margin-top: 1px;'>".__('Prewiew', WNS_LANG_CODE)."</button>";
            $titleUrl = "<a href=".$this->getModule()->getEditLink( $id ).">".$row['title']." <i class='fa fa-fw fa-pencil'></i></a> <a data-filter-id='".$id."' class='wnsDuplicateFilter' href='' title='".__('Duplicate notification', WNS_LANG_CODE)."'><i class='fa fa-fw fa-clone'></i></a>";

            $data[$key]['shortcode'] = $shortcode;
			$data[$key]['rewiew'] = $showPrewiewButton;
			$data[$key]['title'] = $titleUrl;
		}
		return $data;
	}
	public function getProductsList(){
		$res = new responseWns();
        $data = reqWns::get('post');

		if (isset($data) && $data) {
			$data['title'] = !empty($data['title']) ? $data['title'] : false;
			if ($data['title']) {
				$productArray = frameWns::_()->getModule('woonotifications')->searchProductsLikeTitle($data['title']);
			}
			$res->addMessage(__('Done', WNS_LANG_CODE));
			$res->addData('products', $productArray);
		} else {
			$res->pushError($this->getModule('woonotifications')->getErrors());
		}

		return $res->ajaxExec();
	}
	public function drawNotificationAjax(){
        $res = new responseWns();
        $data = reqWns::get('post');
        if (isset($data) && $data) {
			$html = '';
			//$isPro = frameWns::_()->isPro();

			$templateTitle = !empty($data['settings']['template']['template_title']) ? $data['settings']['template']['template_title'] : 'base_template';

			$styles[] = 'css/frontend.woonotifications.css';
			$scripts[] = 'js/jquery.countdown.min.js';
			$scripts[] = 'js/frontend.woonotifications.js';

			foreach ($styles as $style) {
				$html .= "<link rel='stylesheet' href='". frameWns::_()->getModule('woonotifications')->getModPath(). $style. "' type='text/css' media='all' />";
			}
			foreach ($scripts as $script) {
				$html .= "<script type='text/javascript' src='". frameWns::_()->getModule('woonotifications')->getModPath(). $script. "'></script>";
			}

			$html .= "<link rel='stylesheet' href='". frameWns::_()->getModule('woonotifications_templates')->getModPath(). 'css/frontend.' .$templateTitle. ".css' type='text/css' media='all' />";
			$html .= "<script type='text/javascript' src='". frameWns::_()->getModule('woonotifications_templates')->getModPath(). 'js/frontend.' .$templateTitle. ".js'></script>";

			$data['preview'] = true;

			$html .= frameWns::_()->getModule('woonotifications')->render($data);

            $res->setHtml($html);
        } else {
			$res->pushError($this->getModule('woonotifications')->getErrors());
			//$res->pushError(__('Empty or invalid data procided', WCU_LANG_CODE));
		}

        $res->ajaxExec();
    }

	public function save(){
		$res = new responseWns();
		if(($id = $this->getModel('woonotifications')->save(reqWns::get('post'))) != false) {
			$res->addMessage(__('Done', WNS_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel('woonotifications')->getErrors());
		return $res->ajaxExec();
	}

	public function deleteByID(){
		$res = new responseWns();

		if($this->getModel('woonotifications')->delete(reqWns::get('post')) != false){
			$res->addMessage(__('Done', WNS_LANG_CODE));
		}else{
			$res->pushError ($this->getModel('woonotifications')->getErrors());
		}
		return $res->ajaxExec();
	}

	public function createTable(){
		$res = new responseWns();
		if(($id = $this->getModel('woonotifications')->save(reqWns::get('post'))) != false) {
			$res->addMessage(__('Done', WNS_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel('woonotifications')->getErrors());
		return $res->ajaxExec();
	}

}
