<div class="wnsAdminFooterShell wnsHidden">
	<div class="wnsAdminFooterCell">
		<?php echo WNS_WP_PLUGIN_NAME?>
		<?php _e('Version', WNS_LANG_CODE)?>:
		<a target="_blank" href="http://wordpress.org/plugins/popup-by-supsystic/changelog/"><?php echo WNS_VERSION?></a>
	</div>
	<div class="wnsAdminFooterCell">|</div>
	<?php  if(!frameWns::_()->getModule(implode('', array('l','ic','e','ns','e')))) {?>
	<div class="wnsAdminFooterCell">
		<?php _e('Go', WNS_LANG_CODE)?>&nbsp;<a target="_blank" href="<?php echo $this->getModule()->getMainLink();?>"><?php _e('PRO', WNS_LANG_CODE)?></a>
	</div>
	<div class="wnsAdminFooterCell">|</div>
	<?php } ?>
	<div class="wnsAdminFooterCell">
		<a target="_blank" href="http://wordpress.org/support/plugin/popup-by-supsystic"><?php _e('Support', WNS_LANG_CODE)?></a>
	</div>
	<div class="wnsAdminFooterCell">|</div>
	<div class="wnsAdminFooterCell">
		Add your <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/popup-by-supsystic?filter=5#postform">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on wordpress.org.
	</div>
</div>