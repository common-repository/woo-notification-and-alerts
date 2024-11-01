(function($) {
	$(document).ready(function () {
		jQuery('body').on('click', '.wnsDialogSave', function(){
			jQuery('.wnsDialogSave').attr('disabled','disabled').prop('disabled',true);
		})
		jQuery('a[href$="admin.php?page=wns-filters&tab=woonotifications#wnsadd"]').attr('href', '#wnsadd');

			if( jQuery('#wnsAddDialog').length ) {
			var $createBtn = jQuery('.create-table'),
				$error = jQuery('#formError'),
				$input = jQuery('#addDialog_title'),
				$inputDuplicateId = jQuery('#addDialog_duplicateid'),
				$dialog = jQuery('#wnsAddDialog').dialog({
					width: 480,
					modal: true,
					autoOpen: false,
					open: function () {
						jQuery('#wnsAddDialog').keypress(function(e) {
							if (e.keyCode == $.ui.keyCode.ENTER) {
								e.preventDefault();
								jQuery('.wnsDialogSave').click();
							}
						});
					},
					close: function () {
						window.location.hash = '';
					},
					buttons: {
						Save: function (event) {
							$error.fadeOut();

							jQuery.sendFormWns({
								data: {
									mod: 'woonotifications',
									action: 'save',
									title: $input.val(),
									duplicateId: $inputDuplicateId.val()
								},
								onSuccess: function(res) {
									if(!res.error) {
										var currentUrl = window.location.href;
										if (res.data.edit_link && currentUrl !== res.data.edit_link) {
											toeRedirect(res.data.edit_link);
										}
									}else{
										$error.find('p').text(res.errors.title);
										$error.fadeIn();
									}
								}
							});
						}
					},
					create:function () {
						jQuery(this).closest(".ui-dialog").find(".ui-dialog-buttonset button").first().addClass("wnsDialogSave");
					}
				});

			$input.on('focus', function () {
				$error.fadeOut();
			});

			$createBtn.on('click', function () {
				$dialog.dialog('open');
			});
		}

		jQuery(window).on('hashchange', function () {
			if (window.location.hash === '#wnsadd') {
				// To prevent error if data not loaded completely
				setTimeout(function() {
					if(typeof $dialog !== 'undefined'){
						$dialog.dialog('open');
					}
				}, 500);
			}
		}).trigger('hashchange');

		jQuery('#toplevel_page_wns-filters .wp-submenu-wrap li:has(a[href$="admin.php?page=wns-filters"])').on('click', function(e){
			e.preventDefault();
			showAddDialog();
		});

		jQuery(document.body).off('click', '.supsystic-navigation li:has(a[href$="admin.php?page=wns-filters&tab=woonotifications#wnsadd"])');
		jQuery(document.body).on('click', '.supsystic-navigation li:has(a[href$="admin.php?page=wns-filters&tab=woonotifications#wnsadd"])', function(e){
			e.preventDefault();
			showAddDialog();
		});

		function showAddDialog(){
			setTimeout(function() {
				$dialog.dialog('open');
			}, 500);
		}


		jQuery(document.body).on('click','.wnsDuplicateFilter',function(e){
			e.preventDefault();
			var duplicateFilterId = jQuery(this).attr("data-filter-id");
			jQuery('#addDialog_duplicateid').val(duplicateFilterId);
			showAddDialog();
    		return false;
		})

	});
})(jQuery);
