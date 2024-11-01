<a href="#" class="wns-link" data-children="wns-tab-content" data-parent="none"><span class="woobewoo-tab-label"><?php echo __('Content', WNS_LANG_CODE); ?></span></a>
<div class="wns-main-nav-children wns-tab-content">
	<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-content-text" data-parent="wns-tab-content"><span class="woobewoo-tab-label"><?php echo __('Text', WNS_LANG_CODE); ?></span></a>
	<div class="wns-main-nav-children wns-tab-content-text">

		<div class="wns-new-text-block">
			<?php require_once 'woonotificationsTextTemplate.php' ?>
		</div>

		<div class="wns-add-text"><?php echo __('Add text', WNS_LANG_CODE); ?></div>

	</div>
	<a href="#" class="wns-link" data-action="stay" data-children="wns-tab-content-text-rules" data-parent="wns-tab-content"><span class="woobewoo-tab-label"><?php echo __('Text Rules', WNS_LANG_CODE); ?></span></a>
	<div class="wns-main-nav-children wns-tab-content-text-rules">

		<div class="wns-new-text_rule-block">
			<?php require_once 'woonotificationsTextRuleTemplate.php' ?>
		</div>

		<div class="wns-add-text_rule"><?php echo __('Add text rule', WNS_LANG_CODE); ?></div>

	</div>
</div>
