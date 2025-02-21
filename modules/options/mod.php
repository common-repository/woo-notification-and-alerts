<?php
class optionsWns extends moduleWns {
	private $_tabs = array();
	private $_options = array();
	private $_optionsToCategoires = array();	// For faster search

	public function init() {
//		dispatcherWns::addAction('afterModulesInit', array($this, 'initAllOptValues'));
		add_action('init', array($this, 'initAllOptValues'), 99);	// It should be init after all languages was inited (frame::connectLang)
		dispatcherWns::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
	}
	public function initAllOptValues() {
		// Just to make sure - that we loaded all default options values
		$this->getAll();
	}
    /**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
    public function get($code) {
        return $this->getModel()->get($code);
    }
	/**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
	public function isEmpty($code) {
		return $this->getModel()->isEmpty($code);
	}
	public function getAllowedPublicOptions() {
		$allowKeys = array('add_love_link', 'disable_autosave');
		$res = array();
		foreach($allowKeys as $k) {
			$res[ $k ] = $this->get($k);
		}
		return $res;
	}
	public function getAdminPage() {
		if(!installerWns::isUsed()) {
			installerWns::setUsed();	// Show this welcome page - only one time
			frameWns::_()->getModule('promo')->getModel()->bigStatAdd('Welcome Show');
			frameWns::_()->getModule('options')->getModel()->save('plug_welcome_show', time());	// Remember this
		} /*else {
		 * // No actually welcome page for now
			return frameWns::_()->getModule('promo')->showWelcomePage();
		}*/
		return $this->getView()->getAdminPage();
	}
	public function addAdminTab($tabs) {
		$tabs['settings'] = array(
			'label' => __('Settings', WNS_LANG_CODE), 'callback' => array($this, 'getSettingsTabContent'), 'fa_icon' => 'fa-gear', 'sort_order' => 30,
		);
		return $tabs;
	}
	public function getSettingsTabContent() {
		return $this->getView()->getSettingsTabContent();
	}
	public function getTabs() {
		if(empty($this->_tabs)) {
			$this->_tabs = dispatcherWns::applyFilters('mainAdminTabs', array(
				//'main_page' => array('label' => __('Main Page', WNS_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'wp_icon' => 'dashicons-admin-home', 'sort_order' => 0),
			));
			foreach($this->_tabs as $tabKey => $tab) {
				if(!isset($this->_tabs[ $tabKey ]['url'])) {
					$this->_tabs[ $tabKey ]['url'] = $this->getTabUrl( $tabKey );
				}
			}
			uasort($this->_tabs, array($this, 'sortTabsClb'));
		}
		return $this->_tabs;
	}
	public function sortTabsClb($a, $b) {
		if(isset($a['sort_order']) && isset($b['sort_order'])) {
			if($a['sort_order'] > $b['sort_order'])
				return 1;
			if($a['sort_order'] < $b['sort_order'])
				return -1;
		}
		return 0;
	}
	public function getTab($tabKey) {
		$this->getTabs();
		return isset($this->_tabs[ $tabKey ]) ? $this->_tabs[ $tabKey ] : false;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getActiveTab() {
		$reqTab = sanitize_text_field(reqWns::getVar('tab'));
		return empty($reqTab) ? 'woonotifications' : $reqTab;
	}
	public function getTabUrl($tab = '') {
		static $mainUrl;
		if(empty($mainUrl)) {
			$mainUrl = frameWns::_()->getModule('adminmenu')->getMainLink();
		}
		return empty($tab) ? $mainUrl : $mainUrl. '&tab='. $tab;
	}
	public function getRolesList() {
		if(!function_exists('get_editable_roles')) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );
		}
		return get_editable_roles();
	}
	public function getAvailableUserRolesSelect() {
		$rolesList = $this->getRolesList();
		$rolesListForSelect = array();
		foreach($rolesList as $rKey => $rData) {
			$rolesListForSelect[ $rKey ] = $rData['name'];
		}
		return $rolesListForSelect;
	}
	public function getAll() {
		if(empty($this->_options)) {
			$defSendmailPath = @ini_get('sendmail_path');
			 if (empty($defSendmailPath) && !stristr($defSendmailPath, 'sendmail')) {
				$defSendmailPath = '/usr/sbin/sendmail';
			}
			$this->_options = dispatcherWns::applyFilters('optionsDefine', array(
				'general' => array(
					'label' => __('General', WNS_LANG_CODE),
					'opts' => array(
						'send_stats' => array('label' => __('Send usage statistics', WNS_LANG_CODE), 'desc' => __('Send information about what plugin options you prefer to use, this will help us make our solution better for You.', WNS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
//						'count_product_shop' => array('label' => __('Set number of displayed products', WNS_LANG_CODE), 'desc' => __('Set number of displayed products. Leave blank for the default value.', WNS_LANG_CODE), 'def' => '', 'html' => 'input'),
//						'move_sidebar' => array('label' => __('Move Sidebar To Top For Mobile', WNS_LANG_CODE), 'desc' => __('Turn on if you want the sidebar to appear above content on mobile devices.', WNS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
//						'disable_subscribe_ip_antispam' => array('label' => __('Disable blocking Subscription from same IP', WNS_LANG_CODE), 'desc' => __('By default our plugin have feature to block subscriptions from same IP more then one time per hour - to avoid spam subscribers. But you can disable this feature here.', WNS_LANG_CODE), 'def' => '1', 'html' => 'checkboxHiddenVal'),
//						'disable_autosave' => array('label' => __('Disable autosave on PopUp Edit', WNS_LANG_CODE), 'desc' => __('By default our plugin will make autosave all your changes that you do in PopUp edit screen, but you can disable this feature here. Just don\'t forget to save your PopUp each time you make any changes in it.', WNS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
//						'add_love_link' => array('label' => __('Enable promo link', WNS_LANG_CODE), 'desc' => __('We are trying to make our plugin better for you, and you can help us with this. Just check this option - and small promotion link will be added in the bottom of your PopUp. This is easy for you - but very helpful for us!', WNS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
//						'access_roles' => array('label' => __('User role can use plugin', WNS_LANG_CODE), 'desc' => __('User with next roles will have access to whole plugin from admin area.', WNS_LANG_CODE), 'def' => 'administrator', 'html' => 'selectlist', 'options' => array($this, 'getAvailableUserRolesSelect'), 'pro' => ''),
//						'foot_assets' => array('label' => __('Load Assets in Footer', WNS_LANG_CODE), 'desc' => __('Force load all plugin CSS and JavaScript files in footer - to increase page load speed. Please make sure that you have correct footer.php file in your WordPress theme with wp_footer() function call in it.', WNS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
//						'disable_email_html_type' => array('label' => __('Disable HTML Emails content type', WNS_LANG_CODE), 'desc' => __('Some servers fail send emails with HTML content type: content-type = "text/html", so if you have problems with sending emails from our plugn - try to disable this feature here.', WNS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
//						'use_local_cdn' => array('label' => __('Disable CDN usage', WNS_LANG_CODE), 'desc' => esc_html(sprintf(__('By default our plugin is using CDN server to store there part of it\'s files - images, javascript and CSS libraries. This was designed in that way to reduce plugin size, make it lighter and easier for usage. But if you need to store all files - on your server - you can disable this option here, then upload plugin CDN files to your own site. To make it work correctly - check our article that describe how you need to do this <a href="%s" target="_blank">here</a>.', WNS_LANG_CODE), 'https://woobewoo.com/disable-cdn-usage-popup-plugin/')), 'def' => '0', 'html' => 'checkboxHiddenVal'),
//						'mail_send_engine' => array('label' => __('Send With', WNS_LANG_CODE), 'desc' => __('You can send your emails with different email sendng engines.', WNS_LANG_CODE), 'def' => 'wp_mail', 'html' => 'selectbox',
//							'options' => array('wp_mail' => __('WordPress PHP Mail', WNS_LANG_CODE), 'smtp' => __('Third party providers (SMTP)', WNS_LANG_CODE), 'sendmail' => __('Sendmail', WNS_LANG_CODE))),
//
//						'smtp_host' => array('label' => __('SMTP Hostname', WNS_LANG_CODE), 'desc' => __('e.g. smtp.mydomain.com', WNS_LANG_CODE), 'html' => 'text', 'connect' => 'mail_send_engine:smtp'),
//						'smtp_login' => array('label' => __('SMTP Login', WNS_LANG_CODE), 'desc' => __('Your email login', WNS_LANG_CODE), 'html' => 'text', 'connect' => 'mail_send_engine:smtp'),
//						'smtp_pass' => array('label' => __('SMTP Password', WNS_LANG_CODE), 'desc' => __('Your emaail password', WNS_LANG_CODE), 'html' => 'password', 'connect' => 'mail_send_engine:smtp'),
//						'smtp_port' => array('label' => __('SMTP Port', WNS_LANG_CODE), 'desc' => __('Port for your SMTP provider', WNS_LANG_CODE), 'html' => 'text', 'connect' => 'mail_send_engine:smtp'),
//						'smtp_secure' => array('label' => __('SMTP Secure', WNS_LANG_CODE), 'desc' => __('Use secure SMTP connection. If you enable this option - make sure that your server support such secure connections.', WNS_LANG_CODE), 'html' => 'selectbox', 'connect' => 'mail_send_engine:smtp',
//							'options' => array('' => __('No', WNS_LANG_CODE), 'ssl' => 'SSL', 'tls' => 'TLS'), 'def' => ''),
//
//						'sendmail_path' => array('label' => __('Sendmail Path', WNS_LANG_CODE), 'desc' => __('You can check it on your server, or ask about it - in your hosting provider.', WNS_LANG_CODE), 'html' => 'text', 'connect' => 'mail_send_engine:sendmail', 'def' => $defSendmailPath),
					),
				),
			));
			$isPro = frameWns::_()->getModule('promo')->isPro();
			foreach($this->_options as $catKey => $cData) {
				foreach($cData['opts'] as $optKey => $opt) {
					$this->_optionsToCategoires[ $optKey ] = $catKey;
					if(isset($opt['pro']) && !$isPro) {
						$this->_options[ $catKey ]['opts'][ $optKey ]['pro'] = frameWns::_()->getModule('promo')->generateMainLink('utm_source=plugin&utm_medium='. $optKey. '&utm_campaign=popup');
					}
				}
			}
			$this->getModel()->fillInValues( $this->_options );
		}
		return $this->_options;
	}
	public function getFullCat($cat) {
		$this->getAll();
		return isset($this->_options[ $cat ]) ? $this->_options[ $cat ] : false;
	}
	public function getCatOpts($cat) {
		$opts = $this->getFullCat($cat);
		return $opts ? $opts['opts'] : false;
	}
}
