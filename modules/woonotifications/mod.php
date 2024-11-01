<?php
class woonotificationsWns extends moduleWns {
	public function init() {
		dispatcherWns::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		add_shortcode(WNS_SHORTCODE, array($this, 'render'));
		if(is_admin()) {
			add_action('admin_notices', array($this, 'showAdminErrors'));
			$this->loadCodemirror();
		}
		frameWns::_()->addScript('jquery-ui-autocomplete', '', array('jquery'), false, true);
		$options = frameWns::_()->getModule('options')->getModel('options')->getAll();
	}
	public function addAdminTab($tabs) {
		$tabs[ $this->getCode(). '#wnsadd' ] = array(
			'label' => __('Add New Notification', WNS_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-plus-circle', 'sort_order' => 10, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode() ] = array(
			'label' => __('Show All Notifications', WNS_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-list', 'sort_order' => 20,
		);
		$tabs[ $this->getCode(). '_edit' ] = array(
			'label' => __('Edit', WNS_LANG_CODE), 'callback' => array($this, 'getEditTabContent'), 'callback_header' => array($this, 'getEditTabContentHeader'), 'sort_order' => 20, 'child_of' => $this->getCode(), 'hidden' => 1, 'add_bread' => $this->getCode(),
		);
		return $tabs;
	}
	public function getCurrencyPrice($price) {
		return apply_filters('raw_woocommerce_price', $price);
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getEditTabContent() {
		$id = reqWns::getVar('id', 'get');
		return $this->getView()->getEditTabContent( $id );
	}
	public function getEditTabContentHeader() {
		$id = reqWns::getVar('id', 'get');
		return $this->getView()->getEditTabContentHeader( $id );
	}
	public function getEditLink($id, $tableTab = '') {
		$link = frameWns::_()->getModule('options')->getTabUrl( $this->getCode(). '_edit' );
		$link .= '&id='. $id;
		if(!empty($tableTab)) {
			$link .= '#'. $tableTab;
		}
		return $link;
	}
	public function render($params){
		return $this->getView()->renderHtml($params);
	}
	public function showAdminErrors() {
		// check WooCommerce is installed and activated
		if(!$this->isWooCommercePluginActivated()) {
			// WooCommerce install url
			$wooCommerceInstallUrl = add_query_arg(
				array(
					's' => 'WooCommerce',
					'tab' => 'search',
					'type' => 'term',
				),
				admin_url( 'plugin-install.php' )
			);
			$tableView = $this->getView();
			$tableView->assign('errorMsg',
				$this->translate('For work with "')
				. WNS_WP_PLUGIN_NAME
				. $this->translate('" plugin, You need to install and activate <a target="_blank" href="' . $wooCommerceInstallUrl . '">WooCommerce</a> plugin')
			);
			// check current module
			if(reqWns::getVar('page') == WNS_SHORTCODE) {
				// show message
				echo $tableView->getContent('showAdminNotice');
			}
		}
	}
	public function getAllUsersName() {
		global $wpdb;
		$users = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
		$usersArr = array();
		foreach ($users as $user) {
			$usersArr[$user->ID] = $user->display_name;
		}
		return $usersArr;
	}
	public function searchProductsByIDs($ids) {
		global $wpdb;
		$product = array(
		    'post_type' => 'product',
		    'post_status' => 'publish',
		    'posts_per_page' => -1,
		    'fields' => 'ids',
			'post__in' => $ids,
		);
		$productsArr = array();
		$products = get_posts($product);
		foreach ($products as $product) {
			$productsArr[$product] = get_the_title($product);
		}
		return $productsArr;
	}
	public function searchProductsLikeTitle($title) {
		global $wpdb;
		$search_query = 'SELECT ID FROM wp_posts
                         WHERE post_type = "product"
                         AND post_title LIKE %s LIMIT 5';
		$like = '%'.$title.'%';
		$results = $wpdb->get_results($wpdb->prepare($search_query, $like));
		foreach($results as $key => $array){
			$quote_ids[] = $array->ID;
		}
		$product = array(
		    'post_type' => 'product',
		    'post_status' => 'publish',
		    'posts_per_page' => -1,
		    'fields' => 'ids',
			'post__in' => $quote_ids,
		);
		$productsArr = array();
		$products = get_posts($product);
		foreach ($products as $product) {
			$productsArr[$product] = get_the_title($product);
		}
		return $productsArr;
	}
	public function getAllProducts() {
		global $wpdb;
		$product = array(
		    'post_type' => 'product',
		    'post_status' => 'publish',
		    'posts_per_page' => -1,
		    'fields' => 'ids'
		);
		$productsArr = array();
		$products = get_posts($product);
		foreach ($products as $product) {
			$productsArr[$product] = get_the_title($product);
		}
		return $productsArr;
	}
	public function getAllTags() {
		global $wpdb;
		$terms = get_terms( 'product_tag' );
		$term_array = array();
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		    foreach ( $terms as $term ) {
		        $term_array[$term->term_id] = $term->name;
		    }
		}
		return $term_array;
	}
	public function getAllProductCategories() {
        global $wpdb;
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );
        $array = array();
        $product_categories = get_terms('product_cat', $cat_args);
        if (!empty($product_categories)) {
            foreach ($product_categories as $p) {
                $array[ $p->term_taxonomy_id ] = $p->name;
            }
        }
        return $array;
    }

	public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

	public function getLocationInfoByIp($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	    $output = NULL;
	    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
	        $ip = $_SERVER["REMOTE_ADDR"];
	        if ($deep_detect) {
	            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	    }
	    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	    $support    = array("country", "countrycode", "state", "region", "city", "location", "address", "nearby");
	    $continents = array(
	        "AF" => "Africa",
	        "AN" => "Antarctica",
	        "AS" => "Asia",
	        "EU" => "Europe",
	        "OC" => "Australia (Oceania)",
	        "NA" => "North America",
	        "SA" => "South America"
	    );
	    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
			$url = "http://www.geoplugin.net/json.gp?ip={$ip}";
			$res = $this->_fileGetContents($url);
			if(!is_wp_error($res) && is_string($res)) {
				$ipdat = json_decode($res, true);

		        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
		            switch ($purpose) {
		                case "location":
		                    $output = array(
		                        "city"           => @$ipdat->geoplugin_city,
		                        "state"          => @$ipdat->geoplugin_regionName,
		                        "country"        => @$ipdat->geoplugin_countryName,
		                        "country_code"   => @$ipdat->geoplugin_countryCode,
		                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
		                        "continent_code" => @$ipdat->geoplugin_continentCode
		                    );
		                    break;
		                case "address":
		                    $address = array($ipdat->geoplugin_countryName);
		                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
		                        $address[] = $ipdat->geoplugin_regionName;
		                    if (@strlen($ipdat->geoplugin_city) >= 1)
		                        $address[] = $ipdat->geoplugin_city;
		                    $output = implode(", ", array_reverse($address));
		                    break;
		                case "city":
		                    $output = @$ipdat->geoplugin_city;
		                    break;
		                case "state":
		                    $output = @$ipdat->geoplugin_regionName;
		                    break;
		                case "region":
		                    $output = @$ipdat->geoplugin_regionName;
		                    break;
		                case "country":
		                    $output = @$ipdat->geoplugin_countryName;
		                    break;
		                case "countrycode":
		                    $output = @$ipdat->geoplugin_countryCode;
		                    break;
										case "nearby":
											$url = "http://gd.geobytes.com/GetNearbyCities?radius=500&ip=".$ip."&limit=10";
											$nearby_cities = $this->_fileGetContents($url);
									    $nearby_cities = json_decode($nearby_cities, true);
			                $output = $nearby_cities;
			                break;
		            		}
		        }
		    }
	    }
	    return $output;
	}

	public function isWooCommercePluginActivated() {
		return class_exists('WooCommerce');
	}
	public function getRulesToDisplayParams() {
		return array(
			'main' => array(
				'time_on_page' => __('Time on page (sec)', WNS_LANG_CODE),
				'time_on_site' => __('Time on site (sec)', WNS_LANG_CODE),
				'distance_scrolled' => __('Distance scrolled (px)', WNS_LANG_CODE),
				'visitor_inactive_time' => __('Visitor inactive time (sec)', WNS_LANG_CODE),
				'the_current_date' => __('The current date (YYYY-MM-DD)', WNS_LANG_CODE),
				'the_current_time' => __('The current time (h:i)', WNS_LANG_CODE),
				'the_current_day' => __('The current day (DD)', WNS_LANG_CODE),
				'visitor_is_new' => __('Visitor is new', WNS_LANG_CODE),
			),
			'second' => array(
				'more' => 'more',
				'less' => 'less',
				'exactly' => 'exactly',
			),
		);
	}
	public function getTimeFormats() {
		return array(
			'h:i' => '22:30 (h:i)',
			'h:i:s' => '22:30:45 (h:i:s)',
			'Y-m-d' => '2018-01-10 (Y-m-d)',
			'Y-m-d h:i:s' => '2019-01-10 22:30:45 (Y-m-d h:i:s)',
		);
	}
	public function getTextRulesTypes() {
		return array(
			'default' => __('Select type', WNS_LANG_CODE),
			'numbers' => __('Numbers', WNS_LANG_CODE),
			'text' => __('Text', WNS_LANG_CODE),
			'categories' => __('Categories', WNS_LANG_CODE),
			'tags' => __('Tags', WNS_LANG_CODE),
			'product' => __('Product', WNS_LANG_CODE),
			'location' => __('Location', WNS_LANG_CODE),
			'time' => __('Time', WNS_LANG_CODE),
			'users' => __('Users', WNS_LANG_CODE),
			//'discount_code' => __('Discount Code', WNS_LANG_CODE),
		);
	}
	public function loadCodemirror() {
		frameWns::_()->addStyle('ptsCodemirror', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/codemirror.css');
		frameWns::_()->addStyle('codemirror-addon-hint', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/addon/hint/show-hint.css');
		frameWns::_()->addScript('ptsCodemirror', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/codemirror.js');
		frameWns::_()->addScript('codemirror-addon-show-hint', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/addon/hint/show-hint.js');
		frameWns::_()->addScript('codemirror-addon-xml-hint', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/addon/hint/xml-hint.js');
		frameWns::_()->addScript('codemirror-addon-html-hint', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/addon/hint/html-hint.js');
		frameWns::_()->addScript('codemirror-mode-xml', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/mode/xml/xml.js');
		frameWns::_()->addScript('codemirror-mode-javascript', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/mode/javascript/javascript.js');
		frameWns::_()->addScript('codemirror-mode-css', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/mode/css/css.js');
		frameWns::_()->addScript('codemirror-mode-htmlmixed', frameWns::_()->getModule('woonotifications')->getModPath(). 'lib/codemirror/mode/htmlmixed/htmlmixed.js');
	}
	public function saveNotification($notificationId, $templateId) {
		$notificationId = isset($notificationId) ? $notificationId : '';
		$templateId = isset($templateId) ? $templateId : '';
		$template = frameWns::_()->getModule('woonotifications_templates')->getModel()->getTemplateById($templateId);
		$templateHtml = $template['template_code'];
		$countOfText = $template['setting_data']['text_count'];

		$template['template_code'] = $templateHtml;
		$template['notification_id'] = $notificationId;
		$notificationTemplate = frameWns::_()->getModule('woonotifications_templates')->getModel()->getTemplateByNotificationId($notificationId);
		if ( isset($notificationTemplate['id']) ) {
			$notificationTemplateId = $notificationTemplate['id'];
		}
		if ( isset($notificationTemplateId) ) {
			$template['parent_id'] = $template['id'];
			$template['id'] = $notificationTemplateId;
			return frameWns::_()->getModule('woonotifications_templates')->getModel()->updateTemplate($template);
		} else {
			$template['parent_id'] = $template['id'];
			return frameWns::_()->getModule('woonotifications_templates')->getModel()->insertTemplate($template);
		}
	}
	public function unserialize($data) {
			$data = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
	            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
	        }, $data );
			$data = @unserialize($data);

			if (!empty($data['settings']['custom_css'])) {
				$data['settings']['custom_css'] = base64_decode($data['settings']['custom_css']);
			}

			return $data;
    }
	private function _fileGetContents($url) {
		if (!empty($url)) {
			$data = wp_remote_get($url);
			return wp_remote_retrieve_body($data);
		} else {
			return array();
		}
	}
}
