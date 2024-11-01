<div class="wpfWidgetRow wpfMapRow">
	<div class="wpfWidgetRowCell wpfFirstCell">
		<label for="<?php echo $this->widget->get_field_id('id')?>"><?php _e('Select woonotifcations', WNS_LANG_CODE)?>:</label>
	</div>
	<div class="wpfWidgetRowCell wpfLastCell">
		<?php echo htmlWNs::selectbox($this->widget->get_field_name('id'), array(
			'attrs' => 'id="'. $this->widget->get_field_id('id'). '"',
			'value' => isset($this->data['id']) ? $this->data['id'] : 0,
			'options' => $this->notificationsOpts,
		));?>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.wpfWidgetRowCell select option[value="0"]').prop('disabled',true);
	});
</script>
