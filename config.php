<?php
    global $wpdb;
    if (!defined('WPLANG') || WPLANG == '') {
        define('WNS_WPLANG', 'en_GB');
    } else {
        define('WNS_WPLANG', WPLANG);
    }
    if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

    define('WNS_PLUG_NAME', basename(dirname(__FILE__)));
    define('WNS_DIR', WP_PLUGIN_DIR. DS. WNS_PLUG_NAME. DS);
    define('WNS_TPL_DIR', WNS_DIR. 'tpl'. DS);
    define('WNS_CLASSES_DIR', WNS_DIR. 'classes'. DS);
    define('WNS_TABLES_DIR', WNS_CLASSES_DIR. 'tables'. DS);
	define('WNS_HELPERS_DIR', WNS_CLASSES_DIR. 'helpers'. DS);
    define('WNS_LANG_DIR', WNS_DIR. 'languages'. DS);
    define('WNS_IMG_DIR', WNS_DIR. 'img'. DS);
    define('WNS_TEMPLATES_DIR', WNS_DIR. 'templates'. DS);
    define('WNS_MODULES_DIR', WNS_DIR. 'modules'. DS);
    define('WNS_FILES_DIR', WNS_DIR. 'files'. DS);
    define('WNS_ADMIN_DIR', ABSPATH. 'wp-admin'. DS);

	define('WNS_PLUGINS_URL', plugins_url());
    define('WNS_SITE_URL', get_bloginfo('wpurl'). '/');
    define('WNS_JS_PATH', WNS_PLUGINS_URL. '/'. WNS_PLUG_NAME. '/js/');
    define('WNS_CSS_PATH', WNS_PLUGINS_URL. '/'. WNS_PLUG_NAME. '/css/');
    define('WNS_IMG_PATH', WNS_PLUGINS_URL. '/'. WNS_PLUG_NAME. '/img/');
    define('WNS_MODULES_PATH', WNS_PLUGINS_URL. '/'. WNS_PLUG_NAME. '/modules/');
    define('WNS_TEMPLATES_PATH', WNS_PLUGINS_URL. '/'. WNS_PLUG_NAME. '/templates/');
    define('WNS_JS_DIR', WNS_DIR. 'js/');

    define('WNS_URL', WNS_SITE_URL);

    define('WNS_LOADER_IMG', WNS_IMG_PATH. 'loading.gif');
	define('WNS_TIME_FORMAT', 'H:i:s');
    define('WNS_DATE_DL', '/');
    define('WNS_DATE_FORMAT', 'm/d/Y');
    define('WNS_DATE_FORMAT_HIS', 'm/d/Y ('. WNS_TIME_FORMAT. ')');
    define('WNS_DATE_FORMAT_JS', 'mm/dd/yy');
    define('WNS_DATE_FORMAT_CONVERT', '%m/%d/%Y');
    define('WNS_WPDB_PREF', $wpdb->prefix);
    define('WNS_DB_PREF', 'wns_');
    define('WNS_MAIN_FILE', 'wns.php');

    define('WNS_DEFAULT', 'default');
    define('WNS_CURRENT', 'current');

	define('WNS_EOL', "\n");

    define('WNS_PLUGIN_INSTALLED', true);
    define('WNS_VERSION', '1.1.7');
    define('WNS_USER', 'user');

    define('WNS_CLASS_PREFIX', 'wnsc');
    define('WNS_FREE_VERSION', false);
	define('WNS_TEST_MODE', true);

    define('WNS_SUCCESS', 'Success');
    define('WNS_FAILED', 'Failed');
	define('WNS_ERRORS', 'wnsErrors');

	define('WNS_ADMIN',	'admin');
	define('WNS_LOGGED','logged');
	define('WNS_GUEST',	'guest');

	define('WNS_ALL', 'all');

	define('WNS_METHODS', 'methods');
	define('WNS_USERLEVELS', 'userlevels');
	/**
	 * Framework instance code
	 */
	define('WNS_CODE', 'wns');
	define('WNS_LANG_CODE', 'woo-notifications');
	/**
	 * Plugin name
	 */
	define('WNS_WP_PLUGIN_NAME', 'Recent Sales Notifications for WooCommerce');
	/**
	 * Custom defined for plugin
	 */
	define('WNS_SHORTCODE', 'wns-notifications');
