<?php
class woonotifications_widgetsViewWns extends viewWns {
    public function displayWidget($instance) {
		if(isset($instance['id']) && $instance['id']) {
			echo do_shortcode('['.WNS_SHORTCODE.' id='.$instance['id'].']');
		}
    }
    public function displayForm($data, $widget) {
		frameWns::_()->addStyle('woonotifications_widget', $this->getModule()->getModPath(). 'css/gmap_widget.css');
		$notifications = frameWns::_()->getModule('woonotifications')->getModel()->getFromTbl();
		$notificationsOpts = array();
		if(empty($notifications)) {
			$notificationsOpts[0] = __('You have no notifications', WNS_LANG_CODE);
		} else {
			$notificationsOpts[0] = 'Select';
			foreach($notifications as $notification) {
				$notificationsOpts[ $notification['id'] ] = $notification['title'];
			}
		}
		$this->assign('notificationsOpts', $notificationsOpts);
        $this->displayWidgetForm($data, $widget);
    }
}
