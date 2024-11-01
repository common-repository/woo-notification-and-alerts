<?php
class woonotificationsViewWns extends viewWns {

	public $wnsSettings = array();
	public $wnsAllProductCategories = array();
	public $wnsAllProducts = array();
	public $wnsAllUserNames = array();
	public $wnsAllTags = array();

	public function getTabContent() {
		frameWns::_()->getModule('templates')->loadJqGrid();
		frameWns::_()->addScript('admin.woonotifications.list', $this->getModule()->getModPath(). 'js/admin.woonotifications.list.js');
		frameWns::_()->addScript('adminCreateTableWns', $this->getModule()->getModPath(). 'js/create-notification.js', array(), false, true);
		frameWns::_()->getModule('templates')->loadFontAwesome();
		frameWns::_()->addJSVar('admin.woonotifications.list', 'wnsTblDataUrl', uriWns::mod('woonotifications', 'getListForTbl', array('reqType' => 'ajax')));
		frameWns::_()->addJSVar('admin.woonotifications.list', 'url', admin_url('admin-ajax.php'));
		frameWns::_()->getModule('templates')->loadBootstrapSimple();
		frameWns::_()->addStyle('admin.woonotifications', $this->getModule()->getModPath(). 'css/admin.woonotifications.css');
		$this->assign('addNewLink', frameWns::_()->getModule('options')->getTabUrl('woonotifications#wnsadd'));
		frameWns::_()->addStyle('admin.woobewoo.template', WNS_CSS_PATH. 'admin.woobewoo.template.css');
		return parent::getContent('woonotificationsAdmin');
	}

	public function getEditTabContentHeader($idIn) {
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();
		if(!$isWooCommercePluginActivated) { return; }

		$idIn = isset($idIn) ? (int) $idIn : 0;
		$notificationSettings = $this->getModel('woonotifications')->getById($idIn);
		$settings = frameWns::_()->getModule('woonotifications')->unserialize($notificationSettings['setting_data']);
		$link = frameWns::_()->getModule('options')->getTabUrl( $this->getCode() );
		$proLink = frameWns::_()->getModule('promo')->getWooBeWooPluginLink();

		$this->assign('proLink', $proLink);
		$this->assign('link', $link);
		$this->assign('settings', $settings);
		$this->assign('notificationSettings', $notificationSettings);
		$this->assign('is_pro', frameWns::_()->isPro());

		return parent::getContent('woonotificationsPartHeaderEdit');
	}

	public function getEditTabContent($idIn) {
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();
		if(!$isWooCommercePluginActivated) { return; }

		frameWns::_()->getModule('templates')->loadChosenSelects();
		frameWns::_()->getModule('templates')->loadBootstrapSimple();
		frameWns::_()->getModule('templates')->loadJqueryUi();

		frameWns::_()->addScript('notify-js', WNS_JS_PATH. 'notify.js', array(), false, true);
		frameWns::_()->addScript('chosen.order.jquery.min.js', $this->getModule()->getModPath(). 'js/chosen.order.jquery.min.js');
		frameWns::_()->addScript('admin.woonotifications', $this->getModule()->getModPath(). 'js/admin.woonotifications.js');
		frameWns::_()->addScript('admin.wp.colorpicker.alhpa.js', $this->getModule()->getModPath(). 'js/admin.wp.colorpicker.alpha.js');
		frameWns::_()->addScript('adminCreateTableWns', $this->getModule()->getModPath().'js/create-notification.js', array(), false, true);
		frameWns::_()->addScript('frontend.multiselect', $this->getModule()->getModPath(). 'js/frontend.multiselect.js');
		frameWns::_()->addScript('js-wp-editor', $this->getModule()->getModPath(). 'js/js-wp-editor.min.js');
		frameWns::_()->addJSVar('admin.woonotifications', 'url', admin_url('admin-ajax.php'));
		frameWns::_()->addScript('datepicker', $this->getModule()->getModPath(). 'js/datepicker.min.js');
		frameWns::_()->addScript('datepicker.en', $this->getModule()->getModPath(). 'js/datepicker.en.js');

		$ap_vars = array (
			'url' => get_home_url(),
			'includes_url' => includes_url(),
		);

		frameWns::_()->addJSVar('admin.woonotifications', 'ap_vars', $ap_vars);

		frameWns::_()->addStyle('multiselect', $this->getModule()->getModPath(). 'css/multiselect.css');
		frameWns::_()->addStyle('loaders', $this->getModule()->getModPath(). 'css/loaders.css');
		frameWns::_()->addStyle('admin.woobewoo.template', WNS_CSS_PATH. 'admin.woobewoo.template.css');
		frameWns::_()->addStyle('admin.woonotifications', $this->getModule()->getModPath(). 'css/admin.woonotifications.css');
		frameWns::_()->addStyle('datepicker', $this->getModule()->getModPath(). 'css/datepicker.min.css');

		dispatcherWns::doAction('addScriptsContent', true);

		$idIn = isset($idIn) ? (int) $idIn : 0;
		$notificationSettings = $this->getModel('woonotifications')->getById($idIn);
		$this->wnsSettings = frameWns::_()->getModule('woonotifications')->unserialize($notificationSettings['setting_data']);
		self::prepareDefaultCloneParams();
		$productCategories = frameWns::_()->getModule('woonotifications')->getAllProductCategories();
		// $productsList =  frameWns::_()->getModule('woonotifications')->getAllProducts();
		$productsList = array();
		$timeFormatList =  frameWns::_()->getModule('woonotifications')->getTimeFormats();
		$tagsList = frameWns::_()->getModule('woonotifications')->getAllTags();
		$textRulesTypes = frameWns::_()->getModule('woonotifications')->getTextRulesTypes();
		$usersNameList = frameWns::_()->getModule('woonotifications')->getAllUsersName();
		$link = frameWns::_()->getModule('options')->getTabUrl( $this->getCode() );
		$proLink = frameWns::_()->getModule('promo')->getWooBeWooPluginLink();
		$options = frameWns::_()->getModule('woonotifications')->getRulesToDisplayParams();
		$templates = frameWns::_()->getModule('woonotifications_templates')->getModel()->getTemplates(true);
		$notification = frameWns::_()->getModule('woonotifications_templates')->getModel()->getTemplateByNotificationId($idIn);

		if ( empty($this->wnsSettings['settings']['rtd']) || count($this->wnsSettings['settings']['rtd']) < 2 ) {
			self::prepareRtdForEmptyNotification();
		}

		if ( empty($this->wnsSettings['settings']['content']['texts']) || count($this->wnsSettings['settings']['content']['texts']) < 2 ) {
			self::prepareTlForEmptyNotification();
			self::prepareTlSelectForEmptyNotification();
		}

		if ( empty($this->wnsSettings['settings']['content']['text_rules']) || count($this->wnsSettings['settings']['content']['text_rules']) < 2 ) {
			self::prepareTrlForEmptyNotification();
		}

		$this->wnsSettings['display_rules_list'] = (!empty($this->wnsSettings['settings']['rtd'])) ? self::getDisplayRulesList($this->wnsSettings['settings']['rtd']) : array();
		$this->wnsSettings['text_list'] = (!empty($this->wnsSettings['settings']['content']['texts'])) ? self::getTextList($this->wnsSettings['settings']['content']['texts']) : array();
		$this->wnsSettings['count_of_text'] = (!empty($notification['setting_data']['text_count'])) ? $notification['setting_data']['text_count'] : 0;

		$templateImgPath = frameWns::_()->getModule('woonotifications_templates')->getModPath(). 'img/';

		$this->assign('proLink', $proLink);
		$this->assign('link', $link);
		$this->assign('settings', $this->wnsSettings);
		$this->assign('notificationSettings', $notificationSettings);
		$this->assign('productsList', $productsList);
		$this->assign('timeFormatList', $timeFormatList);
		$this->assign('tagsList', $tagsList);
		$this->assign('usersNameList', $usersNameList);
		$this->assign('productCategories', $productCategories);
		$this->assign('options', $options);
		$this->assign('templates', $templates);
		$this->assign('notification', $notification);
		$this->assign('textRulesTypes', $textRulesTypes);
		$this->assign('templateImgPath', $templateImgPath);

		$this->assign('breadcrumbs', frameWns::_()->getModule('admin_nav')->getView()->getBreadcrumbs());
		$this->assign('navigation', frameWns::_()->getModule('options')->getView()->getModulesNavigation());
		$this->assign('is_pro', frameWns::_()->isPro());

		return parent::getContent('woonotificationsPageEditAdmin');
	}

	public function renderHtml($params) {
		$idIn = isset($params['id']) ? (int) $params['id'] : 0;
		if(!$idIn){
			return false;
		}

		$preview = isset($params['preview']) && $params['preview'] ? true : false;

		frameWns::_()->getModule('templates')->loadFontAwesome();
		frameWns::_()->addStyle('frontend.woonotifications', $this->getModule()->getModPath(). 'css/frontend.woonotifications.css');
		frameWns::_()->addScript('frontend.woonotifications', $this->getModule()->getModPath(). 'js/frontend.woonotifications.js');
		frameWns::_()->addScript('jquery.countdown', $this->getModule()->getModPath(). 'js/jquery.countdown.min.js');

		$idIn = isset($idIn) ? (int) $idIn : 0;
		$notification = $this->getModel('woonotifications')->getById($idIn);

		$divId = uniqid();

		if (!$preview) {
			$this->wnsSettings = frameWns::_()->getModule('woonotifications')->unserialize($notification['setting_data']);
		} else {
			foreach ($params['settings']['content']['texts'] as $key => $paramsText) {
				$params['settings']['content']['texts'][$key]['content'] = stripcslashes($paramsText['content']);
			}
			$this->wnsSettings = $params;
		}

		if ( empty($this->wnsSettings['settings']['rtd']) || count($this->wnsSettings['settings']['rtd']) < 1 ) {
			self::prepareRtdForEmptyNotification();
		}

		if ( empty($this->wnsSettings['settings']['content']['texts']) || count($this->wnsSettings['settings']['content']['texts']) < 1 ) {
			self::prepareTlForEmptyNotification();
			self::prepareTlSelectForEmptyNotification();
		}

		if ( empty($this->wnsSettings['settings']['content']['text_rules']) || count($this->wnsSettings['settings']['content']['text_rules']) < 1 ) {
			self::prepareTrlForEmptyNotification();
		}

		$displayRuleActiveId = $this->wnsSettings['settings']['template']['display_rule_active'];
		$dataSettings['display_rules'] = $this->wnsSettings['settings']['rtd']['rtd_'.$displayRuleActiveId];

		$dataSettings = htmlentities(utilsWns::jsonEncode($dataSettings));

		$this->wnsAllProductCategories = frameWns::_()->getModule('woonotifications')->getAllProductCategories();
		//$this->wnsAllProducts = frameWns::_()->getModule('woonotifications')->getAllProducts();
		$this->wnsAllProducts = array();
		$this->wnsAllUserNames = frameWns::_()->getModule('woonotifications')->getAllUsersName();
		$this->wnsAllTags = frameWns::_()->getModule('woonotifications')->getAllTags();

		$template = frameWns::_()->getModule('woonotifications_templates')->getModel()->getTemplateByNotificationId($idIn);
		$templateTitle = !empty($template['setting_data']['template_name']) ? $template['setting_data']['template_name'] : '';
		$template = self::prepareTemplateHtml($template);

		frameWns::_()->addScript('frontend.'.$templateTitle, frameWns::_()->getModule('woonotifications_templates')->getModPath(). 'js/frontend.'.$templateTitle.'.js');
		frameWns::_()->addStyle('frontend.'.$templateTitle, frameWns::_()->getModule('woonotifications_templates')->getModPath(). 'css/frontend.'.$templateTitle.'.css');

		$this->assign('template', $template);
		$this->assign('templateTitle', $templateTitle);
		$this->assign('divId', $divId);
		$this->assign('dataSettings', $dataSettings);
		$this->assign('isPreview', $preview);
		$this->assign('settings', $this->wnsSettings['settings']);

		return parent::getContent('woonotificationsHtml');
	}

	public function prepareTemplateHtml($template) {
		$title = !empty($template['title']) ? $template['title'] : '';
		$setting_data = !empty($template['setting_data']) ? $template['setting_data'] : array();
		$templateHtml = !empty($template['template_code']) ? $template['template_code'] : '';
		$countOfText = !empty($setting_data['text_count']) ? $setting_data['text_count'] : array();
		$settings = $this->wnsSettings;
		$displayRules = !empty($settings['settings']['template']['display_rule_active']) ? $settings['settings']['template']['display_rule_active'] : array();
		$textList = array();

		if ( !empty($settings['settings']['template']['text']) ) {

			foreach ($settings['settings']['template']['text'] as $key => $text) {
				$fullText = frameWns::_()->getModule('woonotifications')->getView()->getTextById($text);
				$textList[] = $fullText;
			}

			for ($i = 0; $i <= $countOfText-1; $i++) {
			   $textCount = $i + 1;
			   $templateHtml = str_replace("[text_". $textCount ."]", $textList[$i], $templateHtml);
			}

			$textRulesMatchesId = array();
			$regex = "/\[text_rule_([a-zA-Z0-9_]*)\]/";
			preg_match_all($regex, $templateHtml, $textRulesMatchesId);

			$textRuleCount = substr_count($templateHtml, '[text_rule_');

			for ($i = 0; $i <= $textRuleCount-1; $i++) {
			   $id = isset($textRulesMatchesId[1][$i]) ? $textRulesMatchesId[1][$i] : false;
			   if ($id) {
				   $textRuleOutput = frameWns::_()->getModule('woonotifications')->getView()->getTextRuleById($id);
	    		   $templateHtml = str_replace("[text_rule_".$id."]", $textRuleOutput, $templateHtml);
			   }
			}

			return $templateHtml;

		}

		return '';

	}

	public function getTextById($textId) {
		$text = !empty($this->wnsSettings['settings']['content']['texts']['text_'.$textId]['content'])
				?	$this->wnsSettings['settings']['content']['texts']['text_'.$textId]['content']
				:	'';
		return $text;
	}

	public function getTextRuleById($textRuleId) {
		$textRule = !empty($this->wnsSettings['settings']['content']['text_rules']['text_rule_'.$textRuleId])
				?	$this->wnsSettings['settings']['content']['text_rules']['text_rule_'.$textRuleId]
				:	'';
		$type = !empty($textRule['type']) ? $textRule['type'] : '';
		$answer = '';
		if (!empty($type)) {
			switch ($type) {
				case 'numbers' :
					$option = !empty($textRule['numbers']['select']) ? $textRule['numbers']['select'] : '';
					if ($option == 'random') {
						$from = isset($textRule['numbers']['random']['from']) ? $textRule['numbers']['random']['from'] : '';
						$to = isset($textRule['numbers']['random']['to']) ? $textRule['numbers']['random']['to'] : '';
						$answer = rand( (int) $from , (int) $to );
					} else if ($option == 'exact') {
						$exact = isset($textRule['numbers']['exact']) ? $textRule['numbers']['exact'] : '';
						$answer = $exact;
					}
					return $answer;
				case 'text' :
					$option = !empty($textRule['text']['select']) ? $textRule['text']['select'] : '';
					if ($option == 'random') {
						$text = isset($textRule['text']['random']) ? $textRule['text']['random'] : '';
						$textArr = explode('~', $text);
						if (!empty($textArr)) {
							$text = $textArr[array_rand($textArr, 1)];
							$text = str_replace(' ', '', $text);
						}
						$answer = $text;
					} else if ($option == 'one-by-one') {
						$text = isset($textRule['text']['one-by-one']) ? $textRule['text']['one-by-one'] : '';
						$answer = $text;
					}
					return $answer;
				break;
				case 'categories' :
					$option = !empty($textRule['categories']['select']) ? $textRule['categories']['select'] : '';
					if ($option == 'custom') {
						$currentCategories = isset($textRule['categories']['custom']) ? $textRule['categories']['custom'] : array();
						$categoriesTitlesList = array();
						foreach ($currentCategories as $category) {
							$categoriesTitlesList[] = $this->wnsAllProductCategories[(int)$category];
						}
						$answer = $categoriesTitlesList[array_rand($categoriesTitlesList, 1)];
					} else if ($option == 'user') {
						global $wp_query;
						if ( is_product_category() ) {
		                    $answer = single_term_title('', 0);
		                }
						if ( is_product() ) {
		                    $answer = single_term_title('', 0);
		                }
					}
					return $answer;
				break;
				case 'tags' :
					$currentTags = isset($textRule['tags']) ? $textRule['tags'] : array();
					$tagsTitleList = array();
					foreach ($currentTags as $tag) {
						$tagsTitleList[] = $this->wnsAllTags[(int)$tag];
					}
					$answer = $tagsTitleList[array_rand($tagsTitleList, 1)];
					return $answer;
				break;
				case 'product' :
					$option = !empty($textRule['product']['select']) ? $textRule['product']['select'] : '';
					if ($option == 'one-by-one') {
						$productsList = isset($textRule['product']['one-by-one']) ? $textRule['product']['one-by-one'] : array();
						$productsTitlesList = array();
						$answer = '';
						foreach ($productsList as $product) {
							$title = get_the_title((int)$product);
							$link = get_permalink((int)$product);
							$answer .= '<a href="'.$link.'" title="'.$title.'" target="_blank">'.$title.'</a> ';
						}
					} else if ($option == 'random') {
						$productsList = isset($textRule['product']['random']) ? $textRule['product']['random'] : array();
						$productsTitlesList = array();
						foreach ($productsList as $key => $product) {
							$productsTitlesList[$key]['title'] = get_the_title((int)$product);
							$productsTitlesList[$key]['link'] = get_permalink((int)$product);
						}
						$listcount = count($productsTitlesList);
						$listcount = rand ( (int)0, (int)$listcount-1 );
						$answer = $productsTitlesList[$listcount];
						$answer = '<a href="'.$answer['link'].'" title="'.$answer['title'].'" target="_blank">'.$answer['title'].'</a>';
					}
					return $answer;
				break;
				case 'location' :
					$option = !empty($textRule['location']) ? $textRule['location'] : '';
					$realIp = frameWns::_()->getModule('woonotifications')->getRealIpAddr();

					$country = frameWns::_()->getModule('woonotifications')->getLocationInfoByIp($realIp, 'country');
					$city = frameWns::_()->getModule('woonotifications')->getLocationInfoByIp($realIp, 'city');

					if ($option == 'user_loc') {
						$answer = $city.', '.$country;
					} else if ($option == 'city_nearest') {
						$nearby = frameWns::_()->getModule('woonotifications')->getLocationInfoByIp($realIp, 'nearby');

						$locationList = array();
						if(is_array($nearby)) {
							foreach ($nearby as $key => $location) {
								$locationList[$key]['city'] = $location[1];
								$locationList[$key]['country'] = $location[3];
							}
						
							$listcount = count($locationList);
							$listcount = rand ( (int)0, (int)$listcount-1 );

							$answer = $locationList[$listcount];
							$answer = $answer['city'].', '.$answer['country'];
						}
					}
					return $answer;
				break;
				case 'time' :
					$format = !empty($textRule['time']['format']) ? $textRule['time']['format'] : '';
					$type = !empty($textRule['time']['type']) ? $textRule['time']['type'] : '';
					$timestamp = '';
					if ($type == 'current') {
						$time = current_time($format);
						$answer = $time;
					} else if ($type == 'countdown') {
						$time = current_time($format);
						$targetTime = !empty($textRule['time']['countdown']) ? $textRule['time']['countdown'] : '';
						$answer = '<span class="wns-countdown-timestamp" data-timeformat="'.$format.'" data-targettime="'.$targetTime.'">'.$targetTime.'</span>';
					}
					return $answer;
				break;
				case 'users' :
					$option = !empty($textRule['users']['select']) ? $textRule['users']['select'] : '';
					if ($option == 'current') {
						$title = get_the_author_meta( 'display_name', (int)get_current_user_id() );
						$link = get_author_posts_url( (int)get_current_user_id() );
						$answer = '<a href="'.$link.'" title="'.$title.'" target="_blank">'.$title.'</a>';
					} else if ($option == 'random') {
						$productsList = isset($textRule['users']['random']) ? $textRule['users']['random'] : array();
						$productsTitlesList = array();
						foreach ($productsList as $key => $product) {
							$productsTitlesList[$key]['title'] = $this->wnsAllUserNames[(int)$product];
							$productsTitlesList[$key]['link'] = get_author_posts_url((int)$product);
						}
						$listcount = count($productsTitlesList);
						$listcount = rand ( (int)0, (int)$listcount-1 );
						$answer = $productsTitlesList[$listcount];
						$answer = '<a href="'.$answer['link'].'" title="'.$answer['title'].'" target="_blank">'.$answer['title'].'</a>';
					}
					return $answer;
				break;
				// case 'discount_code' :
				// break;
				default:
				break;
			}
		}
	}

	public function getDisplayRulesList($rtds) {
		$rtdList = array();
		if (!empty($rtds)) {
			foreach ($rtds as $rtd) {
				$id = !empty($rtd['id']) ? $rtd['id'] : 0;
				$name = !empty($rtd['name']) ? $rtd['name'] : '';
				if (!empty($id)) {
				 $rtdList[$id] = $name;
				}
			}
		}
		return $rtdList;
	}

	public function getTextList($texts) {
		$textsList = array();
		if (!empty($texts)) {
			foreach ($texts as $text) {
				$id = !empty($text['id']) ? $text['id'] : 0;
				$name = !empty($text['name']) ? $text['name'] : '';
				if (!empty($id)) {
					$textsList[$id] = $name;
				}
			}
		}
		return $textsList;
	}

	public function prepareTlForEmptyNotification() {
		$this->wnsSettings['settings']['content']['texts']['text_1'] = array(
			'id' => 1,
			'name' => 'Text 1',
			'content' => 'Interesting fact!',
		);
		$this->wnsSettings['settings']['content']['texts']['text_2'] = array(
			'id' => 2,
			'name' => 'Text 2',
			'content' => 'Today [text_rule_1] users bought products on our website.',
		);
	}

	public function prepareTrlForEmptyNotification() {
		$this->wnsSettings['settings']['content']['text_rules']['text_rule_1'] = array(
			'id' => 1,
			'name' => '[text_rule_1]',
			'type' => 'numbers',
			'numbers' => array(
				'select' => 'random',
				'random' => array(
					'from' => 5,
					'to' => 10,
				),
			),
		);
	}

	public function prepareRtdForEmptyNotification() {
		$this->wnsSettings['settings']['rtd']['rtd_1'] = array(
			'id' => 1,
			'name' => 'Rules to Display 1',
			'and' => array(
				'0' => array(
					'or' => array(
						'0' => array(
							'main' => 'time_on_page',
							'second' => 'more',
							'value' => 10,
						),
					),
				),
			),
			'content' => 'Interesting fact!',
		);
		$this->wnsSettings['settings']['template']['display_rule_active'] = 1;
	}

	public function prepareDefaultCloneParams() {
		$this->wnsSettings['settings']['content']['texts']['text_0'] = array(
			'id' => 0,
			'name' => 'Text 0',
			'content' => '',
		);

		$this->wnsSettings['settings']['rtd']['rtd_0'] = array(
			'id' => 0,
			'name' => 'Rules to Display 0',
			'content' => '',
			'and' => array(
				'0' => array(
					'or' => array(
						'0' => array(
							'main' => '',
							'second' => '',
							'value' => '',
						),
					),
				),
			),
		);

		$this->wnsSettings['settings']['content']['text_rules']['text_rule_0'] = array(
			'id' => 0,
			'name' => '[text_rule_0]',
			'type' => 'numbers',
			'numbers' => array(
				'select' => 'random',
				'random' => array(
					'from' => '',
					'to' => '',
				),
				'exact' => '',
			),
			'text' => array(
				'select' => 'random',
				'random' => '',
				'one-by-one' => '',
			),
			'categories' => array(
				'select' => 'user',
				'custom' => array(),
				'user' => '',
			),
			'tags' => '',
			'product' => array(
				'select' => 'random',
				'random' => array(),
				'one-by-one' => array(),
			),
			'location' => '',
			'time' => array(
				'format' => 'hi',
				'type' => 'current',
				'countdown' => '',
			),
			'users' => array(
				'select' => 'current',
				'current' => '',
				'random' => '',
			),
		);
	}

	public function prepareTlSelectForEmptyNotification() {
		$this->wnsSettings['settings']['template']['text']['1'] = 1;
		$this->wnsSettings['settings']['template']['text']['2'] = 2;
	}

}
