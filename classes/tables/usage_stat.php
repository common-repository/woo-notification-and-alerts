<?php
class tableUsage_statWns extends tableWns {
    public function __construct() {
        $this->_table = '@__usage_stat';
        $this->_id = 'id';     
        $this->_alias = 'sup_usage_stat';
        $this->_addField('id', 'hidden', 'int', 0, __('id', WNS_LANG_CODE))
			->_addField('code', 'hidden', 'text', 0, __('code', WNS_LANG_CODE))
			->_addField('visits', 'hidden', 'int', 0, __('visits', WNS_LANG_CODE))
			->_addField('spent_time', 'hidden', 'int', 0, __('spent_time', WNS_LANG_CODE))
			->_addField('modify_timestamp', 'hidden', 'int', 0, __('modify_timestamp', WNS_LANG_CODE));
    }
}