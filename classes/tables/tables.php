<?php
class tableNotificationsWns extends tableWns {
	public function __construct() {
		$this->_table = '@__notifications';
		$this->_id = 'id';
		$this->_alias = 'wns_notifications';
		$this->_addField('id', 'text', 'int')
		     ->_addField('title', 'text', 'varchar')
		     ->_addField('setting_data', 'text', 'text');
	}
}
