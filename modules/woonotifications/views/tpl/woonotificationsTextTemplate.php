<?php $texts = !empty($this->settings['settings']['content']['texts']) ? $this->settings['settings']['content']['texts'] : array(); ?>

<?php foreach ($texts as $key => $text) {?>

<?php if ($text['id'] == 0) {?>
	<div class="wns-text-tmp" style="display:none;">
<?php }?>

<div class="wns-tab-text-container" data-text="<?php echo $key ?>">

	<div class="row wns-tab-col-row-option">
		<div class="col-md-6">
			<input type="hidden" class="text_id" name="settings[content][texts][<?php echo $key ?>][id]" readonly value="<?php echo $text['id'] ?>" >
			<input type="text" class="text_name" name="settings[content][texts][<?php echo $key ?>][name]" readonly value="<?php echo $text['name'] ?>">
		</div>
		<div class="col-md-6">
			<div class="wns-text-buttons">
				<button class="wns-text-button wns-text-button-duplicate" title="<?php echo __('Duplicate', WNS_LANG_CODE)?>"><i class="fa fa-files-o" aria-hidden="true"></i></button>
				<button class="wns-text-button wns-text-button-remove" title="<?php echo __('Delete', WNS_LANG_CODE)?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
			</div>
		</div>
	</div>

</div>

<?php if ($text['id'] == 0) {?>
	</div>
<?php }?>

<?php } ?>
