jQuery(document).ready(function(){
	var $deactivateLnk = jQuery('#the-list tr[data-plugin="'+ wnsPluginsData.plugName+ '"] .row-actions .deactivate a');
	//console.log($deactivateLnk);
	if($deactivateLnk && $deactivateLnk.size()) {
		var $deactivateForm = jQuery('#wnsDeactivateForm');
		var $deactivateWnd = jQuery('#wnsDeactivateWnd').dialog({
			modal:    true
		,	autoOpen: false
		,	width: 500
		,	height: 390
		,	buttons:  {
				'Submit & Deactivate': function() {
					$deactivateForm.submit();
				}
			}
		});
		var $wndButtonset = $deactivateWnd.parents('.ui-dialog:first')
			.find('.ui-dialog-buttonpane .ui-dialog-buttonset')
		,	$deactivateDlgBtn = $deactivateWnd.find('.wnsDeactivateSkipDataBtn')
		,	deactivateUrl = $deactivateLnk.attr('href');
		$deactivateDlgBtn.attr('href', deactivateUrl);
		$wndButtonset.append( $deactivateDlgBtn );
		$deactivateLnk.click(function(){
			$deactivateWnd.dialog('open');
			return false;
		});
		
		$deactivateForm.submit(function(){
			var $btn = $wndButtonset.find('button:first');
			$btn.width( $btn.width() );	// Ha:)
			$btn.showLoaderWns();
			jQuery(this).sendFormWns({
				btn: $btn
			,	onSuccess: function(res) {
					toeRedirect( deactivateUrl );
				}
			});
			return false;
		});
		$deactivateForm.find('[name="deactivate_reason"]').change(function(){
			jQuery('.wnsDeactivateDescShell').slideUp( g_wnsAnimationSpeed );
			if(jQuery(this).prop('checked')) {
				var $descShell = jQuery(this).parents('.wnsDeactivateReasonShell:first').find('.wnsDeactivateDescShell');
				if($descShell && $descShell.size()) {
					$descShell.slideDown( g_wnsAnimationSpeed );
				}
			}
		});
	}
});