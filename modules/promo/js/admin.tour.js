var g_wnsCurrTour = null
,	g_wnsTourOpenedWithTab = false
,	g_wnsAdminTourDissmissed = false;
jQuery(document).ready(function(){
	setTimeout(function(){
		if(typeof(wnsAdminTourData) !== 'undefined' && wnsAdminTourData.tour) {
			jQuery('body').append( wnsAdminTourData.html );
			wnsAdminTourData._$ = jQuery('#supsystic-admin-tour');
			for(var tourId in wnsAdminTourData.tour) {
				if(wnsAdminTourData.tour[ tourId ].points) {
					for(var pointId in wnsAdminTourData.tour[ tourId ].points) {
						_wnsOpenPointer(tourId, pointId);
						break;	// Open only first one
					}
				}
			}
			for(var tourId in wnsAdminTourData.tour) {
				if(wnsAdminTourData.tour[ tourId ].points) {
					for(var pointId in wnsAdminTourData.tour[ tourId ].points) {
						if(wnsAdminTourData.tour[ tourId ].points[ pointId ].sub_tab) {
							var subTab = wnsAdminTourData.tour[ tourId ].points[ pointId ].sub_tab;
							jQuery('a[href="'+ subTab+ '"]')
								.data('tourId', tourId)
								.data('pointId', pointId);
							var tabChangeEvt = str_replace(subTab, '#', '')+ '_tabSwitch';
							jQuery(document).bind(tabChangeEvt, function(event, selector) {
								if(!g_wnsTourOpenedWithTab && !g_wnsAdminTourDissmissed) {
									var $clickTab = jQuery('a[href="'+ selector+ '"]');
									_wnsOpenPointer($clickTab.data('tourId'), $clickTab.data('pointId'));
								}
							});
						}
					}
				}
			}
		}
	}, 500);
});

function _wnsOpenPointerAndPopupTab(tourId, pointId, tab) {
	g_wnsTourOpenedWithTab = true;
	jQuery('#wnsPopupEditTabs').wpTabs('activate', tab);
	_wnsOpenPointer(tourId, pointId);
	g_wnsTourOpenedWithTab = false;
}
function _wnsOpenPointer(tourId, pointId) {
	var pointer = wnsAdminTourData.tour[ tourId ].points[ pointId ];
	var $content = wnsAdminTourData._$.find('#supsystic-'+ tourId+ '-'+ pointId);
	if(!jQuery(pointer.target) || !jQuery(pointer.target).size())
		return;
	if(g_wnsCurrTour) {
		_wnsTourSendNext(g_wnsCurrTour._tourId, g_wnsCurrTour._pointId);
		g_wnsCurrTour.element.pointer('close');
		g_wnsCurrTour = null;
	}
	if(pointer.sub_tab && jQuery('#wnsPopupEditTabs').wpTabs('getActiveTab') != pointer.sub_tab) {
		return;
	}
	var options = jQuery.extend( pointer.options, {
		content: $content.find('.supsystic-tour-content').html()
	,	pointerClass: 'wp-pointer supsystic-pointer'
	,	close: function() {

		}
	,	buttons: function(event, t) {
			g_wnsCurrTour = t;
			g_wnsCurrTour._tourId = tourId;
			g_wnsCurrTour._pointId = pointId;
			var $btnsShell = $content.find('.supsystic-tour-btns')
			,	$closeBtn = $btnsShell.find('.close')
			,	$finishBtn = $btnsShell.find('.supsystic-tour-finish-btn');

			if($finishBtn && $finishBtn.size()) {
				$finishBtn.click(function(e){
					e.preventDefault();
					jQuery.sendFormWns({
						msgElID: 'noMessages'
					,	data: {mod: 'promo', action: 'addTourFinish', tourId: tourId, pointId: pointId}
					});
					g_wnsCurrTour.element.pointer('close');
				});
			}
			if($closeBtn && $closeBtn.size()) {
				$closeBtn.bind( 'click.pointer', function(e) {
					e.preventDefault();
					jQuery.sendFormWns({
						msgElID: 'noMessages'
					,	data: {mod: 'promo', action: 'closeTour', tourId: tourId, pointId: pointId}
					});
					t.element.pointer('close');
					g_wnsAdminTourDissmissed = true;
				});
			}
			return $btnsShell;
		}
	});
	jQuery(pointer.target).pointer( options ).pointer('open');
	var minTop = 10
	,	pointerTop = parseInt(g_wnsCurrTour.pointer.css('top'));
	if(!isNaN(pointerTop) && pointerTop < minTop) {
		g_wnsCurrTour.pointer.css('top', minTop+ 'px');
	}
}
function _wnsTourSendNext(tourId, pointId) {
	jQuery.sendFormWns({
		msgElID: 'noMessages'
	,	data: {mod: 'promo', action: 'addTourStep', tourId: tourId, pointId: pointId}
	});
}