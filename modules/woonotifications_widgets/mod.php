<?php
class woonotifications_widgetsWns extends moduleWns {
	public function init() {
        parent::init();
        add_action('widgets_init', array($this, 'registerWidget'));
    }
    public function registerWidget() {
        return register_widget('wnsWoonotificationsWidget');
    }
}
/**
 * Maps widget class
 */
class wnsWoonotificationsWidget extends WP_Widget {
    public function __construct() {
        $widgetOps = array(
            'classname' => 'wnsWoonotificationsWidget',
            'description' => __('Display notifications', WNS_LANG_CODE)
        );
		parent::__construct( 'wnsWoonotificationsWidget', WNS_WP_PLUGIN_NAME, $widgetOps );
    }
    public function widget($args, $instance) {
		extract($args);
	   	extract($instance);
		frameWns::_()->getModule('woonotifications_widgets')->getView()->displayWidget($instance);
    }
    public function form($instance) {
		extract($instance);
		frameWns::_()->getModule('woonotifications_widgets')->getView()->displayForm($instance, $this);
    }
	public function update($new_instance, $old_instance) {
		//frameGmp::_()->getModule('promo')->getModel()->saveUsageStat('map.widget.update');
		return $new_instance;
	}
}
