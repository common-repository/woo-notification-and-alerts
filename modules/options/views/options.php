<?php
class optionsViewWns extends viewWns {
	private $_news = array();
	// public function getNewFeatures() {
	// 	$res = array();
	// 	$readmePath = WNS_DIR. 'readme.txt';
	// 	if(file_exists($readmePath)) {
	// 		$readmeContent = @file_get_contents($readmePath);
	// 		if(!empty($readmeContent)) {
	// 			$matchedData = '';
	// 			if(preg_match('/= '. WNS_VERSION. ' =(.+)=.+=/isU', $readmeContent, $matches)) {
	// 				$matchedData = $matches[1];
	// 			} elseif(preg_match('/= '. WNS_VERSION. ' =(.+)/is', $readmeContent, $matches)) {
	// 				$matchedData = $matches[1];
	// 			}
	// 			$matchedData = trim($matchedData);
	// 			if(!empty($matchedData)) {
	// 				$res = array_map('trim', explode("\n", $matchedData));
	// 			}
	// 		}
	// 	}
	// 	return $res;
	// }
    public function getAdminPage() {
		$tabs = $this->getModule()->getTabs();
		$activeTab = $this->getModule()->getActiveTab();
		$content = 'No tab content found - ERROR';
		if(isset($tabs[ $activeTab ]) && isset($tabs[ $activeTab ]['callback'])) {
			//frameWns::_()->getModule('promo')->getModel()->saveUsageStat('tab.'. $activeTab);
			$content = call_user_func($tabs[ $activeTab ]['callback']);
		}
		if(isset($tabs[ $activeTab ]) && isset($tabs[ $activeTab ]['callback_header'])) {
			$header = call_user_func($tabs[ $activeTab ]['callback_header']);
		} else {
			$header = '';
		}
		$activeParentTabs = array();
		foreach($tabs as $tabKey => $tab) {
			if($tabKey == $activeTab && isset($tab['child_of'])) {
				$activeTab = $tab['child_of'];
				//$activeParentTabs[] = $tab['child_of'];
			}
		}
		frameWns::_()->addJSVar('adminOptionsWns', 'wnsActiveTab', $activeTab);
		$this->assign('tabs', $tabs);
		$this->assign('activeTab', $activeTab);
		$this->assign('content', $content);
		$this->assign('header', $header);
		$this->assign('mainUrl', $this->getModule()->getTabUrl());
		$this->assign('activeParentTabs', $activeParentTabs);
		$this->assign('breadcrumbs', frameWns::_()->getModule('admin_nav')->getView()->getBreadcrumbs());
		$this->assign('mainLink', frameWns::_()->getModule('promo')->getMainLink());

		frameWns::_()->addScript('adminCreateTableWns', frameWns::_()->getModule('woonotifications')->getModPath(). 'js/create-notification.js', array(), false, true);
		frameWns::_()->addJSVar('adminCreateTableWns', 'url', admin_url('admin-ajax.php'));

		parent::display('optionsAdminPage');
    }
	public function getModulesNavigation() {
		$tabs = $this->getModule()->getTabs();
		$activeTab = $this->getModule()->getActiveTab();
		foreach($tabs as $tabKey => $tab) {
			if($tabKey == $activeTab && isset($tab['child_of'])) {
				$activeTab = $tab['child_of'];
			}
		}
		$activeParentTabs = array();
		frameWns::_()->addJSVar('adminOptionsWns', 'wnsActiveTab', $activeTab);
		$this->assign('tabs', $tabs);
		$this->assign('activeTab', $activeTab);
		$this->assign('mainUrl', $this->getModule()->getTabUrl());
		$this->assign('activeParentTabs', $activeParentTabs);
		$this->assign('mainLink', frameWns::_()->getModule('promo')->getMainLink());
		frameWns::_()->addScript('adminCreateTableWns', frameWns::_()->getModule('woonotifications')->getModPath(). 'js/create-notification.js', array(), false, true);
		frameWns::_()->addJSVar('adminCreateTableWns', 'url', admin_url('admin-ajax.php'));
		return parent::getContent('optionsModulesNavigation');
    }
	public function sortOptsSet($a, $b) {
		if($a['weight'] > $b['weight'])
			return -1;
		if($a['weight'] < $b['weight'])
			return 1;
		return 0;
	}
	public function getTabContent() {
		frameWns::_()->addScript('admin.mainoptions', $this->getModule()->getModPath(). 'js/admin.mainoptions.js');
		return parent::getContent('optionsAdminMain');
	}
	public function serverSettings() {
		global $wpdb;
		$this->assign('systemInfo', array(
            'Operating System' => array('value' => PHP_OS),
            'PHP Version' => array('value' => PHP_VERSION),
            'Server Software' => array('value' => $_SERVER['SERVER_SOFTWARE']),
			'MySQL' => array('value' =>  $wpdb->db_version()),
            'PHP Allow URL Fopen' => array('value' => ini_get('allow_url_fopen') ? 'Yes' : 'No'),
            'PHP Memory Limit' => array('value' => ini_get('memory_limit')),
            'PHP Max Post Size' => array('value' => ini_get('post_max_size')),
            'PHP Max Upload Filesize' => array('value' => ini_get('upload_max_filesize')),
            'PHP Max Script Execute Time' => array('value' => ini_get('max_execution_time')),
            'PHP EXIF Support' => array('value' => extension_loaded('exif') ? 'Yes' : 'No'),
            'PHP EXIF Version' => array('value' => phpversion('exif')),
            'PHP XML Support' => array('value' => extension_loaded('libxml') ? 'Yes' : 'No', 'error' => !extension_loaded('libxml')),
        ));
		return parent::display('_serverSettings');
	}
	public function getSettingsTabContent() {
		frameWns::_()->addScript('admin.settings', $this->getModule()->getModPath(). 'js/admin.settings.js');
		frameWns::_()->getModule('templates')->loadJqueryUi();

		$options = frameWns::_()->getModule('options')->getAll();
		$this->assign('options', $options);
		$this->assign('exportAllSubscribersUrl', uriWns::mod('subscribe', 'getWpCsvList'));
		return parent::getContent('optionsSettingsTabContent');
	}
}
