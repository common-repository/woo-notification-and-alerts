<div class="wns-main-nav-children wns-tab-design"></div>
<div class="wns-main-nav-children wns-tab-design-templates">

	<?php $activateId = isset($this->settings['settings']['template_id']) ? $this->settings['settings']['template_id'] : '1'; ?>

	<input type="hidden" name="settings[template_id]" value="<?php echo $activateId ?>">

	<div class="wns-tab-content-title"><?php echo __('Templates', WNS_LANG_CODE)?></div>

	<div class="wns-tmp-search-wrapper">
		<input class="wns-tmp-search-input" placeholder="<?php echo __('Search by template name or tag', WNS_LANG_CODE)?>">
		<div class="wns-tmp-search-submit"><i class="fa fa-search" aria-hidden="true"></i></div>
	</div>
	<div class="wns-tmp-tags">
		<div class="wns-tmp-tag-title"><?php echo __('Tags', WNS_LANG_CODE)?>:</div>
		<ul>
			<li class="wns-tmp-tag wns-tmp-tag-active" data-tag-key="all"><?php echo __('show all', WNS_LANG_CODE)?></li>
		</ul>
	</div>

	<div class="wns-templates-wrapper">
		<?php foreach ($this->templates as $template) { ?>
			<?php $isActivate = ($template['id'] == $activateId) ? 'wns-template-activate' : '';?>
			<div class="wns-template-parent <?php echo $isActivate ?>"  data-tag-key="<?php echo $template['setting_data']['template_tag'] ?>" data-template-id="<?php echo $template['id']?>">
				<div class="wns-template">
					<div class="wns-template-wrapper">
						<div class="wns-template-thubmnail">
							<img class="wns-template-thubmnail-img" title="<?php echo __('Activate', WNS_LANG_CODE)?> <?php echo $template['title']?>" src="<?php echo $this->templateImgPath.$template['img']?>">
						</div>
						<div class="wns-template-title-wrapper">
							<div class="wns-template-title">
								<div class="wns-template-title-text"><?php echo $template['title']?></div>
								<div class="wns-template-title-tag"><?php echo $template['setting_data']['template_tag'] ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>

</div>
<div class="wns-main-nav-children wns-tab-design-view-styles"></div>
<div class="wns-main-nav-children wns-tab-design-custom-css"></div>
<div class="wns-main-nav-children wns-tab-design-view-styles-position"></div>
<div class="wns-main-nav-children wns-tab-design-view-styles-background-overlay"></div>
<div class="wns-main-nav-children wns-tab-design-view-styles-borders"></div>
<div class="wns-main-nav-children wns-tab-design-view-styles-text"></div>
<div class="wns-main-nav-children wns-tab-design-view-styles-close-button"></div>
