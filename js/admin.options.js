var wnsAdminFormChanged = [];
window.onbeforeunload = function(){
	// If there are at lease one unsaved form - show message for confirnation for page leave
	if(wnsAdminFormChanged.length)
		return 'Some changes were not-saved. Are you sure you want to leave?';
};
jQuery(document).ready(function(){
		jQuery('body').on('click','.wns-link', function(){
			jQuery(this).find('.wns-tab-col-row-option').each(function(index){
				var biggestNum = 0;
				jQuery(this).find('div').each(function(index) {
					var currentNum = jQuery(this).height();
				    currentNum = parseInt(currentNum);
				    if (currentNum > biggestNum) {
				        biggestNum = currentNum;
				    }
				})
				jQuery(this).find('div').css('min-height',biggestNum);
			})
		})

		resizeContentHeight();

		function resizeContentHeight() {
			var	heightMenu = jQuery('#adminmenuback').height(),
			heightHeader = jQuery('.woobewoo-header').height(),
			heightHeaderWp = jQuery('#wpadminbar').height(),
			winHeight = jQuery(window).height();

			heightPluginContent = winHeight - heightHeader - heightHeaderWp;

			jQuery('.woobewoo-sidebar').css('min-height', heightPluginContent);
			jQuery('.woobewoo-content').css('min-height', heightPluginContent);
			jQuery('.woobewoo-sidebar-navigation-wrapper').css('min-height', heightPluginContent);
		}
		var wrapper;
		// Events by clicking on the navigation link
		jQuery(".wns-link").click(function() {

			// If current clicked link not already active with data-action=stay
			if ( !jQuery(this).hasClass('wns-link-active') ) {

				var dataParent = jQuery(this).attr('data-parent'),
					dataChildren = jQuery(this).attr('data-children'),
					dataAction = jQuery(this).attr('data-action'),
					parentEl = jQuery('.wns-link[data-children='+dataParent+']'),
					parentElParent = parentEl.attr('data-parent'),
					parentElChildren = parentEl.attr('data-children'),
					thisTitle = jQuery('.wns-link[data-children='+dataChildren+']').html();

				// Hide other (data-action="stay") and change active class name to this object
				if ( dataAction == 'stay' ) {
					var dataChildrenStay = jQuery('.wns-link-active').attr('data-children');
					//jQuery('.'+dataChildrenStay+'').hide();
					jQuery('.wns-link').removeClass('wns-link-active');
					jQuery(this).addClass('wns-link-active');
				}
				// Hide (data-action="stay") tab and remove active class if current link without data-action=stay
				else {
					var dataChildrenStay = jQuery('.wns-link-active').attr('data-children');
					jQuery('.'+dataChildrenStay+'').hide();
					jQuery('.wns-link').removeClass('wns-link-active');
					jQuery('.wns-link').hide();
					jQuery('.wns-main-nav-breadcrumbs').show().attr('data-parent', dataParent).attr('data-children', dataChildren);
					jQuery('.wns-main-nav-breadcrumbs .woobewoo-tab-label').html(thisTitle);
				}

				jQuery('[data-parent='+dataChildren+']').show();
				wrapper = jQuery('[data-parent='+dataChildren+']').closest('div');
				wrapper.show();
				jQuery('.'+dataChildren+'').show();

			}
			// Hide (data-action="stay") tab and remove active class
			else {

				var dataParent = jQuery(this).attr('data-parent'),
					dataChildren = jQuery(this).attr('data-children'),
					dataAction = jQuery(this).attr('data-action');

				jQuery(this).removeClass('wns-link-active');
				jQuery('.'+dataChildren+'').hide();

			}

		});

		// Go back/prev through the navigation tree
		jQuery('.wns-main-nav-breadcrumbs').click( function(e) {

			if ( e.target.classList.contains('wns-main-nav-home') ) { return false };

			var dataParent = jQuery(this).attr('data-parent'),
			 	dataChildren = jQuery(this).attr('data-children'),
			 	parentEl = jQuery('.wns-link[data-children='+dataParent+']'),
			 	parentElParent = parentEl.attr('data-parent'),
			 	parentElChildren = parentEl.attr('data-children'),
				parentElTitle = jQuery('.wns-link[data-children='+parentElChildren+']').html();

			jQuery(this).find('.woobewoo-tab-label').html(parentElTitle);

			if (dataParent !== 'none') {
				jQuery('.'+dataChildren+'').hide();
				jQuery('.'+dataParent+'').show();
				jQuery('.'+dataParent+'').find('.wns-link').show();
			} else {
				jQuery('.wns-main-nav-children').hide();
				jQuery('.wns-link').show();
				jQuery(this).find('.woobewoo-tab-label').html('');
				jQuery(this).hide();
			}

			jQuery(this).attr('data-parent', parentElParent);
			jQuery(this).attr('data-children', parentElChildren);

		});

		// Return to the base tab in the navigation tree
		jQuery(".wns-main-nav-home").click(function(){

			jQuery(".woobewoo-navigation .wns-main-nav-breadcrumbs").hide();
			jQuery(".woobewoo-navigation .wns-main-nav .wns-link").show();
			jQuery(".woobewoo-navigation .wns-main-nav .wns-main-nav-children").hide();
			jQuery(".wns-main-nav-children").hide();

		});

	//wnsInitMainPromoPopup();
	if(typeof(wnsActiveTab) != 'undefined' && wnsActiveTab != 'main_page' && jQuery('#toplevel_page_wns-comparison-slider').hasClass('wp-has-current-submenu')) {
		var subMenus = jQuery('#toplevel_page_wns-comparison-slider').find('.wp-submenu li');
		subMenus.removeClass('current').each(function(){
			if(jQuery(this).find('a[href$="&tab='+ wnsActiveTab+ '"]').size()) {
				jQuery(this).addClass('current');
			}
		});
	}

	// Timeout - is to count only user changes, because some changes can be done auto when form is loaded
	setTimeout(function() {
		// If some changes was made in those forms and they were not saved - show message for confirnation before page reload
		var formsPreventLeave = [];
		if(formsPreventLeave && formsPreventLeave.length) {
			jQuery('#'+ formsPreventLeave.join(', #')).find('input,select').change(function(){
				var formId = jQuery(this).parents('form:first').attr('id');
				changeAdminFormWns(formId);
			});
			jQuery('#'+ formsPreventLeave.join(', #')).find('input[type=text],textarea').keyup(function(){
				var formId = jQuery(this).parents('form:first').attr('id');
				changeAdminFormWns(formId);
			});
			jQuery('#'+ formsPreventLeave.join(', #')).submit(function(){
				adminFormSavedWns( jQuery(this).attr('id') );
			});
		}
	}, 1000);

	if(jQuery('.wnsInputsWithDescrForm').size()) {
		jQuery('.wnsInputsWithDescrForm').find('input[type=checkbox][data-optkey]').change(function(){
			var optKey = jQuery(this).data('optkey')
			,	descShell = jQuery('#wnsFormOptDetails_'+ optKey);
			if(descShell.size()) {
				if(jQuery(this).attr('checked')) {
					descShell.slideDown( 300 );
				} else {
					descShell.slideUp( 300 );
				}
			}
		}).trigger('change');
	}
	wnsInitStickyItem();
	// wnsInitCustomCheckRadio();
	//wnsInitCustomSelect();

	jQuery('.wnsFieldsetToggled').each(function(){
		var self = this;
		jQuery(self).find('.wnsFieldsetContent').hide();
		jQuery(self).find('.wnsFieldsetToggleBtn').click(function(){
			var icon = jQuery(this).find('i')
			,	show = icon.hasClass('fa-plus');
			show ? icon.removeClass('fa-plus').addClass('fa-minus') : icon.removeClass('fa-minus').addClass('fa-plus');
			jQuery(self).find('.wnsFieldsetContent').slideToggle( 300, function(){
				if(show) {
					jQuery(this).find('textarea').each(function(i, el){
						if(typeof(this.CodeMirrorEditor) !== 'undefined') {
							this.CodeMirrorEditor.refresh();
						}
					});
				}
			} );
			return false;
		});
	});
	// Go to Top button init
	if(jQuery('#wnsPopupGoToTopBtn').size()) {
		jQuery('#wnsPopupGoToTopBtn').click(function(){
			jQuery('html, body').animate({
				scrollTop: 0
			}, 1000);
			jQuery(this).parents('#wnsPopupGoToTop:first').hide();
			return false;
		});
	}
	// Tooltipster initialization
	/*var tooltipsterSettings = {
		contentAsHTML: true
	,	interactive: true
	,	speed: 0
	,	delay: 0
	//,	animation: 'swing'
	,	maxWidth: 450
	};
	if(jQuery('.supsystic-tooltip').size()) {
		tooltipsterSettings.position = 'top-left';
		jQuery('.supsystic-tooltip').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-bottom').size()) {
		tooltipsterSettings.position = 'bottom-left';
		jQuery('.supsystic-tooltip-bottom').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-left').size()) {
		tooltipsterSettings.position = 'left';
		jQuery('.supsystic-tooltip-left').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-right').size()) {
		tooltipsterSettings.position = 'right';
		jQuery('.supsystic-tooltip-right').tooltipster( tooltipsterSettings );
	}*/
	wnsInitTooltips();
	jQuery(document.body).on('changeTooltips', function () {
		wnsInitTooltips('.wnsNotificationsBlock');
	});
	if(jQuery('.wnsCopyTextCode').size()) {
		setTimeout(function(){	// Give it some time - wait until all other elements will be initialized
			var cloneWidthElement =  jQuery('<span class="sup-shortcode" />').appendTo('.supsystic-plugin');
			jQuery('.wnsCopyTextCode').attr('readonly', 'readonly').click(function(){
				this.setSelectionRange(0, this.value.length);
			}).focus(function(){
				this.setSelectionRange(0, this.value.length);
			});
			jQuery('input.wnsCopyTextCode').each(function(){
				cloneWidthElement.html( str_replace(jQuery(this).val(), '<', 'P') );
				var parentSelector = jQuery(this).data('parent-selector')
				,	parentWidth = (parentSelector && parentSelector != ''
						? jQuery(this).parents(parentSelector+ ':first')
						: jQuery(this).parent()
					).width()
				,	txtWidth = cloneWidthElement.width();
				if(parentWidth <= 0 || parentWidth > txtWidth) {
					jQuery(this).width( cloneWidthElement.width() );
				}
			});
			cloneWidthElement.remove();
		}, 500);
	}
	// Check for showing review notice after a week usage
    wnsInitPlugNotices();
	//jQuery(".supsystic-plugin .tooltipstered").removeAttr("title");
});
function wnsInitTooltips( selector ) {
	var tooltipsterSettings = {
		contentAsHTML: true
	,	interactive: true
	,	speed: 0
	,	delay: 0
	//,	animation: 'swing'
	,	maxWidth: 450
	}
	,	findPos = {
		'.supsystic-tooltip': 'top-left'
	,	'.supsystic-tooltip-bottom': 'bottom-left'
	,	'.supsystic-tooltip-left': 'left'
	,	'.supsystic-tooltip-right': 'right'
	}
	,	$findIn = selector ? jQuery( selector ) : false;
	for(var k in findPos) {
		if(typeof(k) === 'string') {
			var $tips = $findIn ? $findIn.find( k ) : jQuery( k ).not('.sup-no-init');
			if($tips && $tips.size()) {
				tooltipsterSettings.position = findPos[ k ];
				// Fallback for case if library was not loaded
				if(!$tips.tooltipster) continue;
				$tips.tooltipster( tooltipsterSettings );
			}
		}
	}
}
function changeAdminFormWns(formId) {
	if(jQuery.inArray(formId, wnsAdminFormChanged) == -1)
		wnsAdminFormChanged.push(formId);
}
function adminFormSavedWns(formId) {
	if(wnsAdminFormChanged.length) {
		for(var i in wnsAdminFormChanged) {
			if(wnsAdminFormChanged[i] == formId) {
				wnsAdminFormChanged.pop(i);
			}
		}
	}
}
function checkAdminFormSaved() {
	if(wnsAdminFormChanged.length) {
		if(!confirm(toeLangWns('Some changes were not-saved. Are you sure you want to leave?'))) {
			return false;
		}
		wnsAdminFormChanged = [];	// Clear unsaved forms array - if user wanted to do this
	}
	return true;
}
function isAdminFormChanged(formId) {
	if(wnsAdminFormChanged.length) {
		for(var i in wnsAdminFormChanged) {
			if(wnsAdminFormChanged[i] == formId) {
				return true;
			}
		}
	}
	return false;
}
/*Some items should be always on users screen*/
function wnsInitStickyItem() {
	jQuery(window).scroll(function(){
		var stickiItemsSelectors = [/*'.ui-jqgrid-hdiv', */'.supsystic-sticky']
		,	elementsUsePaddingNext = [/*'.ui-jqgrid-hdiv', */'.supsystic-bar']	// For example - if we stick row - then all other should not offest to top after we will place element as fixed
		,	wpTollbarHeight = 32
		,	wndScrollTop = jQuery(window).scrollTop() + wpTollbarHeight
		,	footer = jQuery('.wnsAdminFooterShell')
		,	footerHeight = footer && footer.size() ? footer.height() : 0
		,	docHeight = jQuery(document).height()
		,	wasSticking = false
		,	wasUnSticking = false;
		/*if(jQuery('#wpbody-content .update-nag').size()) {	// Not used for now
			wpTollbarHeight += parseInt(jQuery('#wpbody-content .update-nag').outerHeight());
		}*/
		for(var i = 0; i < stickiItemsSelectors.length; i++) {
			jQuery(stickiItemsSelectors[ i ]).each(function(){
				var element = jQuery(this);
				if(element && element.size() && !element.hasClass('sticky-ignore')) {
					var scrollMinPos = element.offset().top
					,	prevScrollMinPos = parseInt(element.data('scrollMinPos'))
					,	useNextElementPadding = toeInArray(stickiItemsSelectors[ i ], elementsUsePaddingNext) !== -1 || element.hasClass('sticky-padd-next')
					,	currentScrollTop = wndScrollTop
					,	calcPrevHeight = element.data('prev-height')
					,	currentBorderHeight = wpTollbarHeight
					,	usePrevHeight = 0;
					if(calcPrevHeight) {
						usePrevHeight = jQuery(calcPrevHeight).outerHeight();
						currentBorderHeight += usePrevHeight;
					}
					if(currentScrollTop > scrollMinPos && !element.hasClass('supsystic-sticky-active')) {	// Start sticking
						if(element.hasClass('sticky-save-width')) {
							element.width( element.width() );
							//element.addClass('sticky-full-width');
						}
						element.addClass('supsystic-sticky-active').data('scrollMinPos', scrollMinPos).css({
							'top': currentBorderHeight
						});
						if(useNextElementPadding) {
							//element.addClass('supsystic-sticky-active-bordered');
							var nextElement = element.next();
							if(nextElement && nextElement.size()) {
								nextElement.data('prevPaddingTop', nextElement.css('padding-top'));
								var addToNextPadding = parseInt(element.data('next-padding-add'));
								addToNextPadding = addToNextPadding ? addToNextPadding : 0;
								nextElement.css({
									'padding-top': (element.hasClass('sticky-outer-height') ? element.outerHeight() : element.height()) + usePrevHeight + addToNextPadding
								});
							}
						}
						wasSticking = true;
						element.trigger('startSticky');
					} else if(!isNaN(prevScrollMinPos) && currentScrollTop <= prevScrollMinPos) {	// Stop sticking
						element.removeClass('supsystic-sticky-active').data('scrollMinPos', 0).css({
							//'top': 0
						});
						if(element.hasClass('sticky-save-width')) {
							if(element.hasClass('sticky-base-width-auto')) {
								element.css('width', 'auto');
							}
							//element.removeClass('sticky-full-width');
						}
						if(useNextElementPadding) {
							//element.removeClass('supsystic-sticky-active-bordered');
							var nextElement = element.next();
							if(nextElement && nextElement.size()) {
								var nextPrevPaddingTop = parseInt(nextElement.data('prevPaddingTop'));
								if(isNaN(nextPrevPaddingTop))
									nextPrevPaddingTop = 0;
								nextElement.css({
									'padding-top': nextPrevPaddingTop
								});
							}
						}
						element.trigger('stopSticky');
						wasUnSticking = true;
					} else {	// Check new stick position
						if(element.hasClass('supsystic-sticky-active')) {
							if(footerHeight) {
								var elementHeight = element.height()
								,	heightCorrection = 32
								,	topDiff = docHeight - footerHeight - (currentScrollTop + elementHeight + heightCorrection);
								if(topDiff < 0) {
									element.css({
										'top': currentBorderHeight + topDiff
									});
								} else {
									element.css({
										'top': currentBorderHeight
									});
								}
							}
							// If at least on element is still sticking - count it as all is working
							wasSticking = wasUnSticking = false;
						}
					}
				}
			});
		}
		if(wasSticking) {
			if(jQuery('#wnsPopupGoToTop').size())
				jQuery('#wnsPopupGoToTop').show();
		} else if(wasUnSticking) {
			if(jQuery('#wnsPopupGoToTop').size())
				jQuery('#wnsPopupGoToTop').hide();
		}
	});
}
function wnsInitCustomCheckRadio(selector) {
	if(!jQuery.fn.iCheck) return;
	if(!selector)
		selector = document;
	jQuery(selector).find('input').iCheck('destroy').iCheck({
		checkboxClass: 'icheckbox_minimal'
	,	radioClass: 'iradio_minimal'
	}).on('ifChanged', function(e){
		// for checkboxHiddenVal type, see class htmlWns
		jQuery(this).trigger('change');
		if(jQuery(this).hasClass('cbox')) {
			var parentRow = jQuery(this).parents('.jqgrow:first');
			if(parentRow && parentRow.size()) {
				jQuery(this).parents('td:first').trigger('click');
			} else {
				var checkId = jQuery(this).attr('id');
				if(checkId && checkId != '' && strpos(checkId, 'cb_') === 0) {
					var parentTblId = str_replace(checkId, 'cb_', '');
					if(parentTblId && parentTblId != '' && jQuery('#'+ parentTblId).size()) {
						jQuery('#'+ parentTblId).find('input[type=checkbox]').iCheck('update');
					}
				}
			}
		}
	}).on('ifClicked', function(e){
		jQuery(this).trigger('click');
	});
}
function wnsCheckDestroy(checkbox) {
	if(!jQuery.fn.iCheck) return;
	jQuery(checkbox).iCheck('destroy');
}
function wnsCheckDestroyArea(selector) {
	if(!jQuery.fn.iCheck) return;
	jQuery(selector).find('input[type=checkbox]').iCheck('destroy');
}
function wnsCheckUpdate(checkbox) {
	if(!jQuery.fn.iCheck) return;
	jQuery(checkbox).iCheck('update');
}
function wnsCheckUpdateArea(selector) {
	if(!jQuery.fn.iCheck) return;
	jQuery(selector).find('input[type=checkbox]').iCheck('update');
}
function wnsGetTxtEditorVal(id) {
	if(typeof(tinyMCE) !== 'undefined'
		&& tinyMCE.get( id )
		&& !jQuery('#'+ id).is(':visible')
		&& tinyMCE.get( id ).getDoc
		&& typeof(tinyMCE.get( id ).getDoc) == 'function'
		&& tinyMCE.get( id ).getDoc()
	)
		return tinyMCE.get( id ).getContent();
	else
		return jQuery('#'+ id).val();
}
function wnsSetTxtEditorVal(id, content) {
	if(typeof(tinyMCE) !== 'undefined'
		&& tinyMCE
		&& tinyMCE.get( id )
		&& !jQuery('#'+ id).is(':visible')
		&& tinyMCE.get( id ).getDoc
		&& typeof(tinyMCE.get( id ).getDoc) == 'function'
		&& tinyMCE.get( id ).getDoc()
	)
		tinyMCE.get( id ).setContent(content);
	else
		jQuery('#'+ id).val( content );
}
/**
 * Add data to jqGrid object post params search
 * @param {object} param Search params to set
 * @param {string} gridSelectorId ID of grid table html element
 */
function wnsGridSetListSearch(param, gridSelectorId) {
	jQuery('#'+ gridSelectorId).setGridParam({
		postData: {
			search: param
		}
	});
}
/**
 * Set data to jqGrid object post params search and trigger search
 * @param {object} param Search params to set
 * @param {string} gridSelectorId ID of grid table html element
 */
function wnsGridDoListSearch(param, gridSelectorId) {
	wnsGridSetListSearch(param, gridSelectorId);
	jQuery('#'+ gridSelectorId).trigger( 'reloadGrid' );
}
/**
 * Get row data from jqGrid
 * @param {number} id Item ID (from database for example)
 * @param {string} gridSelectorId ID of grid table html element
 * @return {object} Row data
 */
function wnsGetGridDataById(id, gridSelectorId) {
	var rowId = getGridRowId(id, gridSelectorId);
	if(rowId) {
		return jQuery('#'+ gridSelectorId).jqGrid ('getRowData', rowId);
	}
	return false;
}
/**
 * Get cell data from jqGrid
 * @param {number} id Item ID (from database for example)
 * @param {string} column Column name
 * @param {string} gridSelectorId ID of grid table html element
 * @return {string} Cell data
 */
function wnsGetGridColDataById(id, column, gridSelectorId) {
	var rowId = getGridRowId(id, gridSelectorId);
	if(rowId) {
		return jQuery('#'+ gridSelectorId).jqGrid ('getCell', rowId, column);
	}
	return false;
}
/**
 * Get grid row ID (ID of table row) from item ID (from database ID for example)
 * @param {number} id Item ID (from database for example)
 * @param {string} gridSelectorId ID of grid table html element
 * @return {number} Table row ID
 */
function getGridRowId(id, gridSelectorId) {
	var rowId = parseInt(jQuery('#'+ gridSelectorId).find('[aria-describedby='+ gridSelectorId+ '_id][title='+ id+ ']').parent('tr:first').index());
	if(!rowId) {
		console.log('CAN NOT FIND ITEM WITH ID  '+ id);
		return false;
	}
	return rowId;
}
function prepareToPlotDate(data) {
	if(typeof(data) === 'string') {
		if(data) {
			data = str_replace(data, '/', '-');
			return (new Date(data)).getTime();
		}
	}
	return data;
}
function wnsInitPlugNotices() {
	var $notices = jQuery('.supsystic-admin-notice');
	if($notices && $notices.size()) {
		$notices.each(function(){
			jQuery(this).find('.notice-dismiss').click(function(){
				var $notice = jQuery(this).parents('.supsystic-admin-notice');
				if(!$notice.data('stats-sent')) {
					// User closed this message - that is his choise, let's respect this and save it's saved status
					jQuery.sendFormWns({
						data: {mod: 'promo', action: 'addNoticeAction', code: $notice.data('code'), choice: 'hide'}
					});
				}
			});
			jQuery(this).find('[data-statistic-code]').click(function(){
				var href = jQuery(this).attr('href')
				,	$notice = jQuery(this).parents('.supsystic-admin-notice');
				jQuery.sendFormWns({
					data: {mod: 'promo', action: 'addNoticeAction', code: $notice.data('code'), choice: jQuery(this).data('statistic-code')}
				});
				$notice.data('stats-sent', 1).find('.notice-dismiss').trigger('click');
				if(!href || href === '' || href === '#')
					return false;
			});
			var $enbStatsBtn = jQuery(this).find('.wnsEnbStatsAdBtn');
			if($enbStatsBtn && $enbStatsBtn.size()) {
				$enbStatsBtn.click(function(){
					jQuery.sendFormWns({
						data: {mod: 'promo', action: 'enbStatsOpt'}
					});
					return false;
				});
			}
		});
	}
}
/**
 * Main promo popup will show each time user will try to modify PRO option with free version only
 */
function wnsGetMainPromoPopup() {
	if(jQuery('#wnsOptInProWnd').hasClass('ui-dialog-content')) {
		return jQuery('#wnsOptInProWnd');
	}
	return jQuery('#wnsOptInProWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 540
	,	height: 200
	,	open: function() {
			jQuery('#wnsOptWndTemplateTxt').hide();
			jQuery('#wnsOptWndOptionTxt').show();
		}
	});
}
function wnsInitMainPromoPopup() {
	if(!WNS_DATA.isPro) {
		var $proOptWnd = wnsGetMainPromoPopup();
		jQuery('.wnsProOpt').change(function(e){
			e.stopPropagation();
			var needShow = true
			,	isRadio = jQuery(this).attr('type') == 'radio'
			,	isCheck = jQuery(this).attr('type') == 'checkbox';
			if(isRadio && !jQuery(this).attr('checked')) {
				needShow = false;
			}
			if(!needShow) {
				return;
			}
			if(isRadio) {
				jQuery('input[name="'+ jQuery(this).attr('name')+ '"]:first').parents('label:first').click();
				if(jQuery(this).parents('.iradio_minimal:first').size()) {
					var self = this;
					setTimeout(function(){
						jQuery(self).parents('.iradio_minimal:first').removeClass('checked');
					}, 10);
				}
			}
			var parent = null;
			if(jQuery(this).parents('#wnsPopupMainOpts').size()) {
				parent = jQuery(this).parents('label:first');
			} else if(jQuery(this).parents('.wnsPopupOptRow:first').size()) {
				parent = jQuery(this).parents('.wnsPopupOptRow:first');
			} else {
				parent = jQuery(this).parents('tr:first');
			}
			if(!parent.size()) return;
			var promoLink = parent.find('.wnsProOptMiniLabel a').attr('href');
			if(promoLink && promoLink != '') {
				jQuery('#wnsOptInProWnd a').attr('href', promoLink);
			}
			$proOptWnd.dialog('open');
			return false;
		});
	}
}
