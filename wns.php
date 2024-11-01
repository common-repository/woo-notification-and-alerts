<?php
/**
 * Plugin Name: Recent Sales Notifications for WooCommerce
 * Plugin URI: https://woobewoo.com/product/woocommerce-popup-notifications/
 * Description: Easy create and customize popup notifications.
 * Version: 1.1.7
 * Author: woobewoo
 * Author URI: https://woobewoo.com/
 * Text Domain: woo-notification-and-alerts
 * Domain Path: /languages
 * WC requires at least: 3.4.0
 * WC tested up to: 9.3.3
 **/
	/**
	 * Base config constants and functions
	 */
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'config.php');
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'functions.php');
	add_action( 'before_woocommerce_init', function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );

	/**
	 * Connect all required core classes
	 */
    importClassWns('dbWns');
    importClassWns('installerWns');
    importClassWns('baseObjectWns');
    importClassWns('moduleWns');
    importClassWns('modelWns');
    importClassWns('viewWns');
    importClassWns('controllerWns');
    importClassWns('helperWns');
    importClassWns('dispatcherWns');
    importClassWns('fieldWns');
    importClassWns('tableWns');
    importClassWns('frameWns');
	/**
	 * @deprecated since version 1.0.1
	 */
    importClassWns('langWns');
    importClassWns('reqWns');
    importClassWns('uriWns');
    importClassWns('htmlWns');
    importClassWns('responseWns');
    importClassWns('fieldAdapterWns');
    importClassWns('validatorWns');
    importClassWns('errorsWns');
    importClassWns('utilsWns');
    importClassWns('modInstallerWns');
	importClassWns('installerDbUpdaterWns');
	importClassWns('dateWns');
	/**
	 * Check plugin version - maybe we need to update database, and check global errors in request
	 */
    installerWns::update();
    errorsWns::init();
    /**
	 * Start application
	 */
    frameWns::_()->parseRoute();
    frameWns::_()->init();
    frameWns::_()->exec();
