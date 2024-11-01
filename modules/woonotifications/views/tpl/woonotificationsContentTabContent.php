<div class="wns-main-nav-children wns-tab-content"></div>
<div class="wns-main-nav-children wns-tab-content-text">

	<div class="wns-tab-content-title"><?php echo __('Text', WNS_LANG_CODE)?></div>

	<div style="display:none !important">
		<?php wp_editor( '', 'wp_editor_text_prepare', array('media_buttons' => true, 'textarea_name' => 'wp_editor_text_prepare_textarea') ); ?>
	</div>

	<div class="wns-tab-content-wrapper">

		<p><?php echo __('Here you can create, duplicate and remove custom text for your notification template by using static text and custom text rules.', WNS_LANG_CODE)?></p>

		<div class="wns-new-text-params-block">
			<?php $texts = !empty($this->settings['settings']['content']['texts']) ? $this->settings['settings']['content']['texts'] : array(); ?>

			<?php foreach ($texts as $key => $text) {?>

				<?php if ($text['id'] == 0) {?>
					<div class="wns-text-params-tmp" style="display:none;">
				<?php }?>

					<?php $value = isset($text['content']) ? $text['content'] : ''; ?>
					<?php $editor_id = 'settings_content_texts_content_'.$key; ?>
					<?php $settings = array('media_buttons' => true, 'textarea_name' => 'settings[content][texts]['.$key.'][content]'); ?>

					<div class="wns-tab-text-params-container" data-text="<?php echo $key ?>">
						<?php if ($text['id'] == 0) {
								echo htmlWns::textarea('settings[content][texts]['.$key.'][content]', array(
									'value' => $value,
									'attrs' => 'class="wns-text-textarea" id="settings_content_texts_content_'.$key.'"',
								));
						} else {
							wp_editor( $value, $editor_id, $settings );
						}?>
					</div>

				<?php if ($text['id'] == 0) {?>
					</div>
				<?php }?>

			<?php } ?>
		</div>

		<p><?php echo __('Example: Today [text_rule_1] users bought [text_rule_2] in the city [text_rule_3]', WNS_LANG_CODE)?></p>
	</div>

</div>
