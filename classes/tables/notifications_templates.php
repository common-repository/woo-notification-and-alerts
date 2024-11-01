<?php
class tableNotifications_templatesWns extends tableWns {
    public function __construct() {
        $this->_table = '@__notifications_templates';
        $this->_id = 'id';
        $this->_alias = 'wns_notifications_templates';
        $this->_addField('id', 'text', 'int')
			->_addField('parent_id', 'text', 'int')
			->_addField('notification_id', 'text', 'int')
			->_addField('title', 'text', 'varchar')
			->_addField('img', 'text', 'varchar')
			->_addField('setting_data', 'text', 'text')	// Is stat value - unique
			->_addField('template_code', 'text', 'text')
			->_addField('is_activate', 'text', 'int')
			->_addField('is_pro', 'text', 'int');
    }
}
