<?php $rtds = !empty($this->settings['settings']['rtd']) ? $this->settings['settings']['rtd'] : array(); ?>

<?php foreach ($rtds as $key => $rtd) {?>

<?php if ($rtd['id'] == 0) {?>
	<div class="wns-rtd-tmp" style="display:none;">
<?php }?>

<div class="wns-tab-rtd-container" data-rtd="<?php echo $key ?>">

	<div class="row wns-tab-col-row-option">
		<div class="col-md-8">
			<input type="hidden" class="rtd_id" name="settings[rtd][<?php echo $key ?>][id]" readonly value="<?php echo $rtd['id'] ?>" >

			<?php echo htmlWns::text('settings[rtd]['.$key.'][name]', array(
				'value' => (isset($rtd['name']) ? $rtd['name'] : ''),
				'attrs' => 'class="rtd_name" title="'. __('Click to copy text rule code', WNS_LANG_CODE) .'" readonly ',
			))?>
		</div>
		<div class="col-md-4">
			<div class="wns-rtd-buttons">
				<button class="wns-rtd-button wns-rtd-button-duplicate" title="<?php echo __('Duplicate', WNS_LANG_CODE)?>"><i class="fa fa-files-o" aria-hidden="true"></i></button>
				<button class="wns-rtd-button wns-rtd-button-remove" title="<?php echo __('Delete', WNS_LANG_CODE)?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
			</div>
		</div>
	</div>

</div>

<?php if ($rtd['id'] == 0) {?>
	</div>
<?php }?>

<?php } ?>
