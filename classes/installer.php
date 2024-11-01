<?php
class installerWns {
	static public $update_to_version_method = '';
	static private $_firstTimeActivated = false;
	static public function init( $isUpdate = false ) {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$current_version = get_option($wpPrefix. WNS_DB_PREF. 'db_version', 0);
		if(!$current_version)
			self::$_firstTimeActivated = true;
		/**
		 * modules
		 */
		if (!dbWns::exist("@__modules")) {
			dbDelta(dbWns::prepareQuery("CREATE TABLE IF NOT EXISTS `@__modules` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `code` varchar(32) NOT NULL,
			  `active` tinyint(1) NOT NULL DEFAULT '0',
			  `type_id` tinyint(1) NOT NULL DEFAULT '0',
			  `label` varchar(64) DEFAULT NULL,
			  `ex_plug_dir` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `code` (`code`)
			) DEFAULT CHARSET=utf8;"));
			dbWns::query("INSERT INTO `@__modules` (id, code, active, type_id, label) VALUES
				(NULL, 'adminmenu',1,1,'Admin Menu'),
				(NULL, 'options',1,1,'Options'),
				(NULL, 'user',1,1,'Users'),
				(NULL, 'pages',1,1,'Pages'),
				(NULL, 'templates',1,1,'templates'),
				(NULL, 'promo',1,1,'promo'),
				(NULL, 'admin_nav',1,1,'admin_nav'),
				(NULL, 'woonotifications',1,1,'woonotifications'),
				(NULL, 'woonotifications_widgets',1,1,'woonotifications_widgets'),
				(NULL, 'woonotifications_templates',1,1,'woonotifications_templates'),
				(NULL, 'mail',1,1,'mail');");
		}
		/**
		 *  modules_type
		 */
		if(!dbWns::exist("@__modules_type")) {
			dbDelta(dbWns::prepareQuery("CREATE TABLE IF NOT EXISTS `@__modules_type` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `label` varchar(32) NOT NULL,
			  PRIMARY KEY (`id`)
			) AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;"));
			dbWns::query("INSERT INTO `@__modules_type` VALUES
				(1,'system'),
				(6,'addons');");
		}
		/**
		 * tables table
		 */
		if (!dbWns::exist("@__notifications")) {
			dbDelta(dbWns::prepareQuery("CREATE TABLE IF NOT EXISTS `@__notifications` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(128) NULL DEFAULT NULL,
				`setting_data` TEXT NOT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;"));
		}
		/**
		 * templates table
		 */
		if (!dbWns::exist("@__notifications_templates")) {
			dbDelta(dbWns::prepareQuery("CREATE TABLE IF NOT EXISTS `@__notifications_templates` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`parent_id` INT(11) NOT NULL,
				`notification_id` INT(11) NOT NULL,
				`title` VARCHAR(128) NOT NULL,
				`img` VARCHAR(128) NOT NULL,
				`setting_data` TEXT NOT NULL,
				`template_code` TEXT NOT NULL,
				`is_activate` INT(11) NOT NULL,
				`is_pro` INT(11) NOT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;"));
		}
		/**
		* Plugin usage statistwns
		*/
		if(!dbWns::exist("@__usage_stat")) {
			dbDelta(dbWns::prepareQuery("CREATE TABLE `@__usage_stat` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(64) NOT NULL,
			  `visits` int(11) NOT NULL DEFAULT '0',
			  `spent_time` int(11) NOT NULL DEFAULT '0',
			  `modify_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  UNIQUE INDEX `code` (`code`),
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8"));
			dbWns::query("INSERT INTO `@__usage_stat` (code, visits) VALUES ('installed', 1)");
		}
		installerDbUpdaterWns::runUpdate();
		if($current_version && !self::$_firstTimeActivated) {
			self::setUsed();
			// For users that just updated our plugin - don't need tp show step-by-step tutorial
			update_user_meta(get_current_user_id(), WNS_CODE . '-tour-hst', array('closed' => 1));
		}

		self::initBaseTemplates();

		update_option($wpPrefix. WNS_DB_PREF. 'db_version', WNS_VERSION);
		add_option($wpPrefix. WNS_DB_PREF. 'db_installed', 1);
	}

	static public function initBaseTemplates() {
		$data = array (
			'0' => array (
				'parent_id' => 0,
				'id' => 1,
				'notification_id' => 0,
				'title' => 'Base template',
				'img' => 'base_template.jpg',
				'setting_data' => 'a:3:{s:13:&quot;template_name&quot;;s:13:&quot;base_template&quot;;s:10:&quot;text_count&quot;;s:1:&quot;2&quot;;s:12:&quot;template_tag&quot;;s:9:&quot;ecommerce&quot;;}',
				'template_code' => '&lt;div class=&quot;wns_base_template&quot;&gt;	&lt;div class=&quot;wnsTemplateCloseButton&quot;&gt;&lt;span class=&quot;fa fa-fw fa-close&quot;&gt;&lt;/span&gt;&lt;/div&gt;
	&lt;div class=&quot;wnsTemplateTitle&quot;&gt;		[text_1]	&lt;/div&gt;	&lt;div class=&quot;wnsTemplateContent&quot;&gt;		[text_2]	&lt;/div&gt;&lt;/div&gt;',
				'is_activate' => 0,
				'is_pro' => 0,
			),
		);
		foreach ($data as $d) {
			self::installBaseTemplates($d, $d['id']);
		};
	}

	static public function installBaseTemplates($data, $id) {
		$tbl = '@__notifications_templates';
		$id = (int) dbWns::get("SELECT id FROM $tbl WHERE id = '$id'", 'one');
		$action = $id ? 'UPDATE' : 'INSERT INTO';
		$values = array();
		foreach ($data as $k => $v) {
			$values[] = "$k = \"$v\"";
		};
		$valuesStr = implode(',', $values);
		$query = "$action $tbl SET $valuesStr";
		if($action == 'UPDATE')
			$query .= " WHERE id = '$id' ";
		if (dbWns::query($query)) {
			return $action == 'UPDATE' ? $id : dbWns::insertID();
		};
		return false;
	}

	static public function setUsed() {
		update_option(WNS_DB_PREF. 'plug_was_used', 1);
	}
	static public function isUsed() {
		//return true;	// No welcome page for now
		//return 0;
		return (int) get_option(WNS_DB_PREF. 'plug_was_used');
	}
	static public function delete() {
		self::_checkSendStat('delete');
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.WNS_DB_PREF."modules`");
		$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.WNS_DB_PREF."modules_type`");
		$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.WNS_DB_PREF."usage_stat`");
		//$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.WNS_DB_PREF."filters`");
		delete_option($wpPrefix. WNS_DB_PREF. 'db_version');
		delete_option($wpPrefix. WNS_DB_PREF. 'db_installed');
	}
	static public function deactivate() {
		self::_checkSendStat('deactivate');
	}
	static private function _checkSendStat($statCode) {
		if(class_exists('frameWns')
			&& frameWns::_()->getModule('promo')
			&& frameWns::_()->getModule('options')
		) {
			frameWns::_()->getModule('promo')->getModel()->saveUsageStat( $statCode );
			frameWns::_()->getModule('promo')->getModel()->checkAndSend( true );
		}
	}
	static public function update() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$currentVersion = get_option($wpPrefix. WNS_DB_PREF. 'db_version', 0);
		if(!$currentVersion || version_compare(WNS_VERSION, $currentVersion, '>')) {
			self::init( true );
			update_option($wpPrefix. WNS_DB_PREF. 'db_version', WNS_VERSION);
		}
	}


}
