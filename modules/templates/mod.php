<?php
class templatesWns extends moduleWns {
    protected $_styles = array();
	private $_cdnUrl = '';

	public function __construct($d) {
		parent::__construct($d);
		$this->getCdnUrl();	// Init CDN URL
	}
	public function getCdnUrl() {
		if(empty($this->_cdnUrl)) {
			if((int) frameWns::_()->getModule('options')->get('use_local_cdn')) {
				$uploadsDir = wp_upload_dir( null, false );
				$this->_cdnUrl = $uploadsDir['baseurl']. '/'. WNS_CODE. '/';
				if(uriWns::isHttps()) {
					$this->_cdnUrl = str_replace('http://', 'https://', $this->_cdnUrl);
				}
				dispatcherWns::addFilter('externalCdnUrl', array($this, 'modifyExternalToLocalCdn'));
			} else {
				$this->_cdnUrl = (uriWns::isHttps() ? 'https' : 'http'). '://woobewoo-14700.kxcdn.com/';
			}
		}
		return $this->_cdnUrl;
	}
	public function modifyExternalToLocalCdn( $url ) {
		$url = str_replace(
			array('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css'),
			array($this->getModPath(). 'lib/font-awesome'),
			$url);
		return $url;
	}
    public function init() {
        if (is_admin()) {
			if($isAdminPlugOptsPage = frameWns::_()->isAdminPlugOptsPage()) {
				$this->loadCoreJs();
				$this->loadAdminCoreJs();
				$this->loadCoreCss();
				$this->loadChosenSelects();
				frameWns::_()->addScript('adminOptionsWns', WNS_JS_PATH. 'admin.options.js', array(), false, true);
				add_action('admin_enqueue_scripts', array($this, 'loadMediaScripts'));
				add_action('init', array($this, 'connectAdditionalAdminAssets'));
			}
			// Some common styles - that need to be on all admin pages - be careful with them
			frameWns::_()->addStyle('supsystic-for-all-admin-'. WNS_CODE, WNS_CSS_PATH. 'supsystic-for-all-admin.css');
		}
        parent::init();
    }
	public function connectAdditionalAdminAssets() {
		if(is_rtl()) {
			frameWns::_()->addStyle('styleWns-rtl', WNS_CSS_PATH. 'style-rtl.css');
		}
	}
	public function loadMediaScripts() {
		if(function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}
	public function loadAdminCoreJs() {
		frameWns::_()->addScript('jquery-ui-dialog');
		frameWns::_()->addScript('jquery-ui-slider');
		frameWns::_()->addScript('wp-color-picker');
		frameWns::_()->addScript('icheck', WNS_JS_PATH. 'icheck.min.js');
	}
	public function load_dashicons(){
    	wp_enqueue_style('dashicons');
	}
	public function loadCoreJs() {
		static $loaded = false;
		if(!$loaded) {
			frameWns::_()->addScript('jquery');

			frameWns::_()->addScript('commonWns', WNS_JS_PATH. 'common.js');
			frameWns::_()->addScript('coreWns', WNS_JS_PATH. 'core.js');

			$ajaxurl = admin_url('admin-ajax.php');
			$jsData = array(
				'siteUrl'					=> WNS_SITE_URL,
				'imgPath'					=> WNS_IMG_PATH,
				'cssPath'					=> WNS_CSS_PATH,
				'loader'					=> WNS_LOADER_IMG,
				'close'						=> WNS_IMG_PATH. 'cross.gif',
				'ajaxurl'					=> $ajaxurl,
				'options'					=> frameWns::_()->getModule('options')->getAllowedPublicOptions(),
				'WNS_CODE'					=> WNS_CODE,
				//'ball_loader'				=> WNS_IMG_PATH. 'ajax-loader-ball.gif',
				//'ok_icon'					=> WNS_IMG_PATH. 'ok-icon.png',
				'jsPath'					=> WNS_JS_PATH,
			);
			if(is_admin()) {
				$jsData['isPro'] = frameWns::_()->getModule('promo')->isPro();
				$jsData['mainLink'] = frameWns::_()->getModule('promo')->getMainLink();
			}
			$jsData = dispatcherWns::applyFilters('jsInitVariables', $jsData);
			frameWns::_()->addJSVar('coreWns', 'WNS_DATA', $jsData);
			$this->loadTooltipster();
			$loaded = true;
		}
	}
	public function loadTooltipster() {
		frameWns::_()->addScript('tooltipster', $this->getModPath(). 'lib/tooltipster/jquery.tooltipster.min.js');
		frameWns::_()->addStyle('tooltipster', $this->getModPath(). 'lib/tooltipster/tooltipster.css');
	}
	public function loadSlimscroll() {
		frameWns::_()->addScript('jquery.slimscroll', WNS_JS_PATH. 'slimscroll.min.js');
	}
	public function loadCodemirror() {
		frameWns::_()->addStyle('wnsCodemirror', $this->getModPath(). 'lib/codemirror/codemirror.css');
		frameWns::_()->addStyle('codemirror-addon-hint', $this->getModPath(). 'lib/codemirror/addon/hint/show-hint.css');
		frameWns::_()->addScript('wnsCodemirror', $this->getModPath(). 'lib/codemirror/codemirror.js');
		frameWns::_()->addScript('codemirror-addon-show-hint', $this->getModPath(). 'lib/codemirror/addon/hint/show-hint.js');
		frameWns::_()->addScript('codemirror-addon-xml-hint', $this->getModPath(). 'lib/codemirror/addon/hint/xml-hint.js');
		frameWns::_()->addScript('codemirror-addon-html-hint', $this->getModPath(). 'lib/codemirror/addon/hint/html-hint.js');
		frameWns::_()->addScript('codemirror-mode-xml', $this->getModPath(). 'lib/codemirror/mode/xml/xml.js');
		frameWns::_()->addScript('codemirror-mode-javascript', $this->getModPath(). 'lib/codemirror/mode/javascript/javascript.js');
		frameWns::_()->addScript('codemirror-mode-css', $this->getModPath(). 'lib/codemirror/mode/css/css.js');
		frameWns::_()->addScript('codemirror-mode-htmlmixed', $this->getModPath(). 'lib/codemirror/mode/htmlmixed/htmlmixed.js');
	}
	public function loadCoreCss() {		
		$this->_styles = array(
			'styleWns'			=> array('path' => WNS_CSS_PATH. 'style.css', 'for' => 'admin'),
			'supsystic-uiWns'	=> array('path' => WNS_CSS_PATH. 'supsystic-ui.css', 'for' => 'admin'),
			'dashicons'			=> array('for' => 'admin'),
			'bootstrap-alerts'	=> array('path' => WNS_CSS_PATH. 'bootstrap-alerts.css', 'for' => 'admin'),
			'icheck'			=> array('path' => WNS_CSS_PATH. 'jquery.icheck.css', 'for' => 'admin'),
			//'uniform'			=> array('path' => WNS_CSS_PATH. 'uniform.default.css', 'for' => 'admin'),
			'wp-color-picker'	=> array('for' => 'admin'),
		);
		foreach($this->_styles as $s => $sInfo) {
			if(!empty($sInfo['path'])) {
				frameWns::_()->addStyle($s, $sInfo['path']);
			} else {
				frameWns::_()->addStyle($s);
			}
		}
		$this->loadFontAwesome();
	}
	public function loadJqueryUi() {
		static $loaded = false;
		if(!$loaded) {
			frameWns::_()->addStyle('jquery-ui', WNS_CSS_PATH. 'jquery-ui.min.css');
			frameWns::_()->addStyle('jquery-ui.structure', WNS_CSS_PATH. 'jquery-ui.structure.min.css');
			frameWns::_()->addStyle('jquery-ui.theme', WNS_CSS_PATH. 'jquery-ui.theme.min.css');
			frameWns::_()->addStyle('jquery-slider', WNS_CSS_PATH. 'jquery-slider.css');
			$loaded = true;
		}
	}
	public function loadJqGrid() {
		static $loaded = false;
		if(!$loaded) {
			$this->loadJqueryUi();
			frameWns::_()->addScript('jq-grid', $this->getModPath(). 'lib/jqgrid/jquery.jqGrid.min.js');
			frameWns::_()->addStyle('jq-grid', $this->getModPath(). 'lib/jqgrid/ui.jqgrid.css');
			$langToLoad = utilsWns::getLangCode2Letter();
			$availableLocales = array('ar','bg','bg1251','cat','cn','cs','da','de','dk','el','en','es','fa','fi','fr','gl','he','hr','hr1250','hu','id','is','it','ja','kr','lt','mne','nl','no','pl','pt','pt','ro','ru','sk','sr','sr','sv','th','tr','tw','ua','vi');
			if(!in_array($langToLoad, $availableLocales)) {
				$langToLoad = 'en';
			}
			frameWns::_()->addScript('jq-grid-lang', $this->getModPath(). 'lib/jqgrid/i18n/grid.locale-'. $langToLoad. '.js');
			$loaded = true;
		}
	}
	public function loadFontAwesome() {
		frameWns::_()->addStyle('font-awesomeWns', WNS_CSS_PATH. 'font-awesome.min.css');
	}
	public function loadChosenSelects() {
		frameWns::_()->addStyle('jquery.chosen', $this->getModPath(). 'lib/chosen/chosen.min.css');
		frameWns::_()->addScript('jquery.chosen', $this->getModPath(). 'lib/chosen/chosen.jquery.min.js');
	}
	public function loadDatePicker() {
		frameWns::_()->addScript('jquery-ui-datepicker');
	}
	public function loadJqplot() {
		static $loaded = false;
		if(!$loaded) {
			$jqplotDir = $this->getModPath(). 'lib/jqplot/';

			frameWns::_()->addStyle('jquery.jqplot', $jqplotDir. 'jquery.jqplot.min.css');

			frameWns::_()->addScript('jplot', $jqplotDir. 'jquery.jqplot.min.js');
			frameWns::_()->addScript('jqplot.canvasAxisLabelRenderer', $jqplotDir. 'jqplot.canvasAxisLabelRenderer.min.js');
			frameWns::_()->addScript('jqplot.canvasTextRenderer', $jqplotDir. 'jqplot.canvasTextRenderer.min.js');
			frameWns::_()->addScript('jqplot.dateAxisRenderer', $jqplotDir. 'jqplot.dateAxisRenderer.min.js');
			frameWns::_()->addScript('jqplot.canvasAxisTickRenderer', $jqplotDir. 'jqplot.canvasAxisTickRenderer.min.js');
			frameWns::_()->addScript('jqplot.highlighter', $jqplotDir. 'jqplot.highlighter.min.js');
			frameWns::_()->addScript('jqplot.cursor', $jqplotDir. 'jqplot.cursor.min.js');
			frameWns::_()->addScript('jqplot.barRenderer', $jqplotDir. 'jqplot.barRenderer.min.js');
			frameWns::_()->addScript('jqplot.categoryAxisRenderer', $jqplotDir. 'jqplot.categoryAxisRenderer.min.js');
			frameWns::_()->addScript('jqplot.pointLabels', $jqplotDir. 'jqplot.pointLabels.min.js');
			frameWns::_()->addScript('jqplot.pieRenderer', $jqplotDir. 'jqplot.pieRenderer.min.js');
			$loaded = true;
		}
	}
	public function loadSortable() {
		static $loaded = false;
		if(!$loaded) {
			frameWns::_()->addScript('jquery-ui-core');
			frameWns::_()->addScript('jquery-ui-widget');
			frameWns::_()->addScript('jquery-ui-mouse');

			frameWns::_()->addScript('jquery-ui-draggable');
			frameWns::_()->addScript('jquery-ui-sortable');
			$loaded = true;
		}
	}
	public function loadMagicAnims() {
		static $loaded = false;
		if(!$loaded) {
			frameWns::_()->addStyle('magic.anim', $this->getModPath(). 'css/magic.min.css');
			$loaded = true;
		}
	}
	public function loadCssAnims() {
		static $loaded = false;
		if(!$loaded) {
			frameWns::_()->addStyle('animate.styles', WNS_CSS_PATH. 'animate.min.css');
			$loaded = true;
		}
	}
	public function loadBootstrapSimple() {
		static $loaded = false;
		if(!$loaded) {
			frameWns::_()->addStyle('bootstrap-simple', WNS_CSS_PATH. 'bootstrap-simple.css');
			$loaded = true;
		}
	}
	public function loadGoogleFont( $font ) {
		static $loaded = array();
		if(!isset($loaded[ $font ])) {
			frameWns::_()->addStyle('google.font.'. str_replace(array(' '), '-', $font), 'https://fonts.googleapis.com/css?family='. urlencode($font));
			$loaded[ $font ] = 1;
		}
	}
	public function loadBxSlider() {
		static $loaded = false;
		if(!$loaded) {
			frameWns::_()->addStyle('bx-slider', WNS_JS_PATH. 'bx-slider/jquery.bxslider.css');
			frameWns::_()->addScript('bx-slider', WNS_JS_PATH. 'bx-slider/jquery.bxslider.min.js');
			$loaded = true;
		}
	}
}
