<style type="text/css">
	.wnsDeactivateDescShell {
		display: none;
		margin-left: 25px;
		margin-top: 5px;
	}
	.wnsDeactivateReasonShell {
		display: block;
		margin-bottom: 10px;
	}
	#wnsDeactivateWnd input[type="text"],
	#wnsDeactivateWnd textarea {
		width: 100%;
	}
	#wnsDeactivateWnd h4 {
		line-height: 1.53em;
	}
	#wnsDeactivateWnd + .ui-dialog-buttonpane .ui-dialog-buttonset {
		float: none;
	}
	.wnsDeactivateSkipDataBtn {
		float: right;
		margin-top: 15px;
		text-decoration: none;
		color: #777 !important;
	}
</style>
<div id="wnsDeactivateWnd" style="display: none;" title="<?php _e('Your Feedback', WNS_LANG_CODE)?>">
	<h4><?php printf(__('If you have a moment, please share why you are deactivating %s', WNS_LANG_CODE), WNS_WP_PLUGIN_NAME)?></h4>
	<form id="wnsDeactivateForm">
		<label class="wnsDeactivateReasonShell">
			<?php echo htmlWns::radiobutton('deactivate_reason', array(
				'value' => 'not_working',
			))?>
			<?php _e('Couldn\'t get the plugin to work', WNS_LANG_CODE)?>
			<div class="wnsDeactivateDescShell">
				<?php printf(__('If you have a question, <a href="%s" target="_blank">contact us</a> and will do our best to help you'), 'https://woobewoo.com/contact-us/?utm_source=plugin&utm_medium=deactivated_contact&utm_campaign=popup')?>
			</div>
		</label>
		<label class="wnsDeactivateReasonShell">
			<?php echo htmlWns::radiobutton('deactivate_reason', array(
				'value' => 'found_better',
			))?>
			<?php _e('I found a better plugin', WNS_LANG_CODE)?>
			<div class="wnsDeactivateDescShell">
				<?php echo htmlWns::text('better_plugin', array(
					'placeholder' => __('If it\'s possible, specify plugin name', WNS_LANG_CODE),
				))?>
			</div>
		</label>
		<label class="wnsDeactivateReasonShell">
			<?php echo htmlWns::radiobutton('deactivate_reason', array(
				'value' => 'not_need',
			))?>
			<?php _e('I no longer need the plugin', WNS_LANG_CODE)?>
		</label>
		<label class="wnsDeactivateReasonShell">
			<?php echo htmlWns::radiobutton('deactivate_reason', array(
				'value' => 'temporary',
			))?>
			<?php _e('It\'s a temporary deactivation', WNS_LANG_CODE)?>
		</label>
		<label class="wnsDeactivateReasonShell">
			<?php echo htmlWns::radiobutton('deactivate_reason', array(
				'value' => 'other',
			))?>
			<?php _e('Other', WNS_LANG_CODE)?>
			<div class="wnsDeactivateDescShell">
				<?php echo htmlWns::text('other', array(
					'placeholder' => __('What is the reason?', WNS_LANG_CODE),
				))?>
			</div>
		</label>
		<?php echo htmlWns::hidden('mod', array('value' => 'promo'))?>
		<?php echo htmlWns::hidden('action', array('value' => 'saveDeactivateData'))?>
	</form>
	<a href="" class="wnsDeactivateSkipDataBtn"><?php _e('Skip & Deactivate', WNS_LANG_CODE)?></a>
</div>