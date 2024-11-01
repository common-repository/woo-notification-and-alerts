jQuery(document).ready(function(){
	jQuery('#wnsMailTestForm').submit(function(){
		jQuery(this).sendFormWns({
			btn: jQuery(this).find('button:first')
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#wnsMailTestForm').slideUp( 300 );
					jQuery('#wnsMailTestResShell').slideDown( 300 );
				}
			}
		});
		return false;
	});
	jQuery('.wnsMailTestResBtn').click(function(){
		var result = parseInt(jQuery(this).data('res'));
		jQuery.sendFormWns({
			btn: this
		,	data: {mod: 'mail', action: 'saveMailTestRes', result: result}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#wnsMailTestResShell').slideUp( 300 );
					jQuery('#'+ (result ? 'wnsMailTestResSuccess' : 'wnsMailTestResFail')).slideDown( 300 );
				}
			}
		});
		return false;
	});
	jQuery('#wnsMailSettingsForm').submit(function(){
		jQuery(this).sendFormWns({
			btn: jQuery(this).find('button:first')
		});
		return false; 
	});
});