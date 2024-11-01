<?php
class woonotifications_templatesModelWns extends modelWns {
	public function __construct() {
		$this->_setTbl('notifications_templates');
	}
	public function getTemplates($unsetChildren = false) {
		$templates = $this->getFromTbl();

		foreach ($templates as $key => $template) {
			if ($unsetChildren && $template['parent_id'] != 0) {
				unset($templates[$key]);
				continue;
			}
			$templates[$key]['setting_data'] = !empty($template['setting_data']) ? unserialize( htmlspecialchars_decode($template['setting_data'], ENT_QUOTES) ) : array();
			//$templates[$key]['template_code'] = !empty($template['template_code']) ? htmlspecialchars($template['template_code']) : '';
		}
		return $templates;
	}
	public function getTemplateById($id) {
		$template = $this->getById($id);
		$template['setting_data'] = !empty($template['setting_data']) ? unserialize( htmlspecialchars_decode($template['setting_data'], ENT_QUOTES) ) : array();
		$template['template_code'] = !empty($template['template_code']) ? htmlspecialchars_decode($template['template_code'], ENT_QUOTES) : '';
		return $template;
	}
	public function getTemplateByNotificationId($notificationId) {
		$template = dbWns::get("SELECT * FROM @__notifications_templates WHERE notification_id = $notificationId LIMIT 1");
		$template = !empty($template[0]) ? $template[0] : array();
		if (!empty($template)) {
			$template['setting_data'] = !empty($template['setting_data']) ? unserialize( htmlspecialchars_decode($template['setting_data'], ENT_QUOTES) ) : array();
			$template['template_code'] = !empty($template['template_code']) ? htmlspecialchars_decode($template['template_code'], ENT_QUOTES) : '';
		}
		return $template;
	}
	public function insertTemplate($template) {
		$template['id'] = '';
		$template['setting_data'] = !empty($template['setting_data']) ? htmlspecialchars(serialize($template['setting_data']), ENT_QUOTES) : '';
		$template['template_code'] = !empty($template['template_code']) ? htmlspecialchars($template['template_code'], ENT_QUOTES) : '';
		if ( $this->insert($template) ) {
			return true;
		}
		return false;
	}
	public function updateTemplate($template) {
		$template['setting_data'] = !empty($template['setting_data']) ? htmlspecialchars(serialize($template['setting_data']), ENT_QUOTES) : '';
		$template['template_code'] = !empty($template['template_code']) ? htmlspecialchars($template['template_code'], ENT_QUOTES) : '';
		$where = array('notification_id' => $template['notification_id']);
		if ( $this->update($template, $where) ) {
			return true;
		}
		return false;
	}
}
