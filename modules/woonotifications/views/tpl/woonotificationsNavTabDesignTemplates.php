<div class="row wns-tab-col-row-option">
	<div class="col-md-12">
		<?php _e('Template title:', WNS_LANG_CODE)?> <span class="wns-tab-design-templates-tmp-title"><?php echo $this->notification['title'] ?></b>
	</div>
</div>

<input type="hidden" name="settings[template][template_title]" readonly value="<?php echo $this->notification['setting_data']['template_name'] ?>" >

<div class="row wns-tab-col-row-option">
	<div class="col-md-6">
		<?php _e('Select display rules:', WNS_LANG_CODE)?>
	</div>
	<div class="col-md-6">
		<?php echo htmlWns::selectbox('settings[template][display_rule_active]', array(
			'options' => $this->settings['display_rules_list'],
			'value' => (isset($this->settings['settings']['template']['display_rule_active']) ? $this->settings['settings']['template']['display_rule_active'] : '1'),
		))?>
	</div>
</div>

<?php
	for ($i = 1; $i <= $this->settings['count_of_text']; $i++) {
?>
		<div class="row wns-tab-col-row-option">
			<div class="col-md-6">
				<?php _e('Select text', WNS_LANG_CODE)?> <?php echo $i ?>:
			</div>
			<div class="col-md-6">
				<?php echo htmlWns::selectbox('settings[template][text]['.$i.']', array(
					'options' => $this->settings['text_list'],
					'value' => (isset($this->settings['settings']['template']['text'][$i]) ? $this->settings['settings']['template']['text'][$i] : '1'),
				))?>
			</div>
		</div>
<?php
	}
?>
