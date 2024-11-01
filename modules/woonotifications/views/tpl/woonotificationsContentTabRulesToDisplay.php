<div class="wns-main-nav-children wns-tab-rtd">
	<div class="wns-tab-content-title"><?php echo __('Rules to Display', WNS_LANG_CODE)?></div>

	<div class="wns-tab-content-wrapper">

		<p><?php echo __('Display rules parameters will help you determine how and when to display the notification.', WNS_LANG_CODE)?></p>

		<div class="wns-new-rtd-params-block">
			<?php $rtds = !empty($this->settings['settings']['rtd']) ? $this->settings['settings']['rtd'] : array(); ?>

			<?php foreach ($rtds as $key => $rtd) {?>

			<?php if ($rtd['id'] == 0) {?>
				<div class="wns-rtd-params-tmp" style="display:none;">
			<?php }?>

				<div class="wns-tab-rtd-params-container" data-rtd="<?php echo $key ?>">

						<div class="wns-tab-rtd-wrapper-and-containers">
							<?php foreach ($rtd['and'] as $keyAnd => $and) { ?>
							<div class="wns-tab-rtd-wrapper-and" data-and="<?php echo $keyAnd ?>">
								<div class="wns-tab-rtd-wrapper-or-containers">
										<?php foreach ($and['or'] as $keyOr => $or) { ?>
											<div class="wns-tab-rtd-wrapper-or" data-and="<?php echo $keyAnd ?>" data-or="<?php echo $keyOr ?>">
											<div class="row wns-tab-col-row-option wns-tab-col-row-rtd" data-and="<?php echo $keyAnd ?>" data-or="<?php echo $keyOr ?>">
												<div class="col-md-3">
													<?php echo htmlWns::selectbox('settings[rtd]['.$key.'][and]['.$keyAnd.'][or]['.$keyOr.'][main]', array(
														'options' => $this->options['main'],
														'value' => (isset($rtd['and'][$keyAnd]['or'][$keyOr]['main']) ? $rtd['and'][$keyAnd]['or'][$keyOr]['main'] : ''),
														'attrs' => 'class="wns-rtd-select-main" style="width:100%"',
													))?>
												</div>
												<div class="col-md-3">
													<?php echo htmlWns::selectbox('settings[rtd]['.$key.'][and]['.$keyAnd.'][or]['.$keyOr.'][second]', array(
														'options' => $this->options['second'],
														'value' => (isset($rtd['and'][$keyAnd]['or'][$keyOr]['second']) ? $rtd['and'][$keyAnd]['or'][$keyOr]['second'] : ''),
														'attrs' => 'class="wns-rtd-second" style="width:100%"',
													))?>
												</div>
												<div class="col-md-3"  style="position:relative;">
													<?php echo htmlWns::text('settings[rtd]['.$key.'][and]['.$keyAnd.'][or]['.$keyOr.'][value]', array(
														'value' => (isset($rtd['and'][$keyAnd]['or'][$keyOr]['value']) ? $rtd['and'][$keyAnd]['or'][$keyOr]['value'] : ''),
														'attrs' => 'class="wns-rtd-value" style="width:100%"',
														'placeholder' => __('value', WNS_LANG_CODE),
													))?>
												</div>
												<div class="col-md-2">
												</div>
												<div class="col-md-1" align="right">
													<button class="wns-rtd-button wns-rtd-button-remove-or" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
												</div>
											</div>
											</div>
										<?php } ?>
									</div>
									<div class="wns-tab-rtd-button-or"><?php echo __('Add "Or" Rule', WNS_LANG_CODE)?></div>
								</div>
								<?php } ?>
							</div>

							<div class="wns-tab-rtd-button-and"><?php echo __('Add "And" Rule', WNS_LANG_CODE)?></div>

				</div>

			<?php if ($rtd['id'] == 0) {?>
				</div>
			<?php }?>

			<?php } ?>
		</div>

	</div>
</div>
