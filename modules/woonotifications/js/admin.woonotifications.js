(function ($, app) {

    function goTo(item) {
        jQuery('html,body').animate({'scrollTop': jQuery(item).offset().top - 30}, 1000);

        return false;
    }

	function AdminPage() {
		this.$obj = this;
		this.$allowMultipleFilters = ['wnsAttribute', 'wnsBrand', 'wnsCustomMeta'];
		this.$multiSelectFields = ['f_mlist[]'];
		this.$noOptionsFilters = [''];
		return this.$obj;
	}

	AdminPage.prototype.init = (function () {
		var _thisObj = this.$obj;
		_thisObj.eventsAdminPage();
		_thisObj.eventsFilters();
		_thisObj.setupPriceByHands();
		_thisObj.initEditPage();
	});

	AdminPage.prototype.chooseIconPopup = (function () {
		var _thisObj = this.$obj;
		if(typeof(_thisObj.chooseIconPopup) == 'function') {
			_thisObj.chooseIconPopup();
		}
	});

	AdminPage.prototype.initEditPage = (function () {
		var _thisObj = this.$obj;
		var cssEditor = CodeMirror.fromTextArea(jQuery('#customCss').get(0), {
			mode: 'text/css'
		,	lineWrapping: true
		,	lineNumbers: true
		,	matchBrackets: true
		,	autoCloseBrackets: true
		});
		setTimeout(function() {
			cssEditor.refresh();
		},1);
		jQuery('#customCss').get(0).CodeMirrorEditor = cssEditor;
	});

	AdminPage.prototype.setupPriceByHands = (function () {
		var _this = this.$obj;
		var options = {
			modal: true
			, autoOpen: false
			, width: 600
			, height: 400
			, buttons: {
				OK: function () {
					var emptyInput = false;
					var options = '';
					var range = jQuery('#wnsSetupPriceRangeByHand .wnsRangeByHand');

					//check if input is empty
					range.find('input').each(function () {
						if(!jQuery(this).val()) {
							jQuery(this).addClass('wnsWarning');
							emptyInput = true;
						}
					});

					if(!emptyInput){
						var rangeCount = range.length;
						var i = 1;
						range.each(function () {
							var el = jQuery(this);
							options += el.find('.wnsRangeByHandHandlerFrom input').val() + ',';
							if(i === rangeCount){
								options += el.find('.wnsRangeByHandHandlerTo input').val();
							}else{
								options += el.find('.wnsRangeByHandHandlerTo input').val() + ',';
							}

							i++;
						});

						jQuery('input[name="f_range_by_hands_values"]').val(options);
						$container.empty();
						$container.dialog('close');
						_this.saveFilters();
					}

				}
				, Cancel: function () {
					$container.empty();
					$container.dialog('close');

				}
			}
		};
		var $container = jQuery('<div id="wnsSetupPriceRangeByHand"></div>').dialog( options );

		jQuery('body').on('click', '.wnsRangeByHandSetup', function (e) {
			e.preventDefault();
			var appendTemplate = '';
			var priceRange = jQuery('input[name="f_range_by_hands_values"]').val();
			var template = jQuery('.wnsRangeByHandTemplate').clone().html();
			var templAddButton = jQuery('.wnsRangeByHandTemplateAddButton').clone().html();
			$container.empty();

			if(priceRange.length <= 0){
				for(var i = 1; i < 2; i++ ){
					appendTemplate += template;
				}
				appendTemplate += templAddButton;
				$container.append(appendTemplate);
				$container.dialog("option", "title", 'Price Range');
				$container.dialog('open');
			}else{
				var priceRangeArray = priceRange.split(",");
				for(var i = 0; i < priceRangeArray.length/2; i++ ){
					appendTemplate += template;
				}

				appendTemplate += templAddButton;
				$container.append(appendTemplate);
				$container.dialog("option", "title", 'Price Range');
				$container.dialog('open');

				var k = 0;
				jQuery('#wnsSetupPriceRangeByHand input').each(function(){
					var input = jQuery(this);
					if(k < priceRangeArray.length){
						input.val(priceRangeArray[k]);
						k++;
					}else{
						input.closest('.wnsRangeByHand').remove();
					}
				});
			}

		});

		jQuery('body').on('click', '.wnsAddPriceRange', function (e) {
			e.preventDefault();
			var templates = jQuery('.wnsRangeByHandTemplate').clone().html();
			jQuery(templates).insertBefore('.wnsRangeByHandAddButton');
			sortablePrice();
		});

		jQuery('body').on('click', '.wnsRangeByHandRemove', function(e){
			e.preventDefault();
			var _this = jQuery(this);
			_this.closest('.wnsRangeByHand').remove();
		});

		//make properties sortable
		function sortablePrice(){
			jQuery("#wnsSetupPriceRangeByHand").sortable({
				//containment: "parent",
				cursor: "move",
				axis: "y",
				handle: ".wnsRangeByHandHandler"
			});
		}
		sortablePrice();

	});

	AdminPage.prototype.eventsAdminPage = (function () {
		window.wnsPreview = true;
		var _thisObj = this.$obj;
		// Initialize Main Tabs
		var $mainTabsContent = jQuery('.row-tab'),
			$mainTabs = jQuery('.wnsSub.tabs-wrapper.wnsMainTabs .button'),
			$currentTab = $mainTabs.filter('.current').attr('href');

		$mainTabsContent.filter($currentTab).addClass('active');

		$mainTabs.on('click', function (e) {
			e.preventDefault();
			var $this = jQuery(this),
				$curTab = $this.attr('href');

			$mainTabsContent.removeClass('active');
			$mainTabs.filter('.current').removeClass('current');
			$this.addClass('current');
			$mainTabsContent.filter($curTab).addClass('active');
		});

		//change Border color in preview and ajax save
		jQuery('.wnsColorObserver .wp-color-result').attr('id', 'wp-color-result-border');

		jQuery('.wns-link').each(function(index){
			var dataChildren = jQuery(this).attr('data-children'),
				dataParent = jQuery(this).attr('data-parent');
				jQuery(this).attr('href','#'+'_'+dataParent+'_'+dataChildren);
		});

		var pathname = window.location.hash;
		if (pathname) {
			var pathData = pathname.split('_'),
				dataParent = pathData[1],
				dataChildren = pathData[2];

			jQuery('body').find('.wns-link[data-children="'+dataParent+'"][data-parent="none"]').trigger('click');
			jQuery('body').find('.wns-link[data-parent="'+dataParent+'"][data-children="'+dataChildren+'"]').trigger('click');
		}

		checkNavArrow();

		function checkNavArrow() {
			jQuery('.wns-link').removeClass('wns-link-arrow-down');
			jQuery('body').find('.wns-main-nav-children').each(function(){
				if ( jQuery(this).attr('style') == "display: block;" ) {
					var navChildren = jQuery(this).attr('class').split(' ').pop();
					var navElement = jQuery('.wns-link[data-action="stay"][data-children="'+navChildren+'"]').addClass('wns-link-arrow-down');
				}
			});
		}

		jQuery('.wns-link').on('click',function(){
			checkNavArrow();
		})

		var observer = new MutationObserver(styleChangedCallback);
		observer.observe(document.getElementById('wp-color-result-border'), {
			attributes: true,
			attributeFilter: ['style'],
		});
		var oldIndex = document.getElementById('wp-color-result-border').style.backgroundColor;

		function styleChangedCallback(mutations) {
			var newIndex = mutations[0].target.style.backgroundColor;
			if (newIndex !== oldIndex) {
				jQuery('.supsystic-filter-loader').not('.spinner').css('color', newIndex);
			}
		}

		jQuery('.chooseLoaderIcon').on('click', function(e){
			e.preventDefault();
			_thisObj.chooseIconPopup();
		});

		jQuery('textarea').each(function(i, el){
			if(typeof(this.CodeMirrorEditor) !== 'undefined') {
				this.CodeMirrorEditor.refresh();
			}
		});

		var tagArray = [];
		jQuery('.wns-template-parent').each(function(){
			var tagKey = jQuery(this).attr('data-tag-key');
			tagArray.push(tagKey);
		})

		tagArray.forEach(function(value){
			var li = '<li class="wns-tmp-tag" data-tag-key="'+value+'">'+value+'</li>';
			jQuery('.wns-tmp-tags ul').append(li);
		})

		jQuery('body').on('click', '.wns-tmp-tag', function(){
			var tagKey = jQuery(this).attr('data-tag-key');
			jQuery('body').find('.wns-tmp-tag').removeClass('wns-tmp-tag-active');
			jQuery(this).addClass('wns-tmp-tag-active');

			if (tagKey === 'all') {
				jQuery('.wns-template-parent').show();
			} else {
				jQuery('.wns-template-parent').hide();
				jQuery('.wns-template-parent[data-tag-key="'+tagKey+'"]').show();
			}
		});

		jQuery('.wns-tmp-search-submit').on('click',function(){
			var search = jQuery('.wns-tmp-search-input').val().toLowerCase();
			var dataArr = [];
			jQuery('body').find('.wns-tmp-tag').removeClass('wns-tmp-tag-active');
			jQuery('.wns-template-title-text').each(function(value){
				var thisEl = jQuery(this);
				var thisElText = thisEl.html().toLowerCase();
				if (thisElText.includes(search)) {
					dataArr.push(thisEl.closest('.wns-template-parent').attr('data-template-id'));
				}
			});
			jQuery('.wns-template-parent').hide();
			dataArr.forEach(function(value){
				jQuery('.wns-template-parent[data-template-id="'+value+'"]').show();
			})
		});

		jQuery("[data-children='wns-tab-design-custom-css']").on('click',function(){
			jQuery('#customCss').get(0).CodeMirrorEditor.refresh();
		})

		jQuery("#buttonSave").on('click',function(){

			// jQuery('.wns-rtd-value').removeClass('wns-input-is-invalid');
			//
			// var error = false;
			//
			// jQuery('body').find('.wns-rtd-value').each(function(){
			//    var closest = jQuery(this).closest('.wns-rtd-params-tmp');
			//    if (closest.length == 0) {
			// 	   var mainVal = jQuery(this).closest('.wns-tab-col-row-option').find('.wns-rtd-select-main').val();
			// 	   var secVal = jQuery(this).closest('.wns-tab-col-row-option').find('.wns-rtd-second').val();
			// 	   var curVal = jQuery(this).val();
			// 	   if (curVal == '') {
			// 		   error = true;
			// 		   jQuery(this).addClass('wns-input-is-invalid');
			// 	   }
			//    }
			// })
			//
			// if (error) return true;

			jQuery('[data-rtd="rtd_0"] select').attr('disabled', true).prop('disabled', true);
			jQuery('[data-rtd="rtd_0"] input').attr('disabled', true).prop('disabled', true);

			jQuery('[data-text="text_0"] select').attr('disabled', true).prop('disabled', true);
			jQuery('[data-text="text_0"] input').attr('disabled', true).prop('disabled', true);

			jQuery('[data-text_rule="text_rule_0"] select').attr('disabled', true).prop('disabled', true);
			jQuery('[data-text_rule="text_rule_0"] input').attr('disabled', true).prop('disabled', true);

			jQuery('#buttonSave .fa').css('display','inline-block');

			jQuery('#wp_editor_text_prepare').attr('disabled', true).prop('disabled', true);

			if ("undefined"!==typeof tinyMCE) {
				tinyMCE.triggerSave();
			}

			jQuery('#wnsNotificationsEditForm').submit();

			jQuery('[data-rtd="rtd_0"] select').attr('disabled', false).prop('disabled', false);
			jQuery('[data-rtd="rtd_0"] input').attr('disabled', false).prop('disabled', false);

			jQuery('[data-text="text_0"] select').attr('disabled', false).prop('disabled', false);
			jQuery('[data-text="text_0"] input').attr('disabled', false).prop('disabled', false);

			jQuery('[data-text_rule="text_rule_0"] select').attr('disabled', false).prop('disabled', false);
			jQuery('[data-text_rule="text_rule_0"] input').attr('disabled', false).prop('disabled', false);

			jQuery('#wp_editor_text_prepare').attr('disabled', false).prop('disabled', false);
		})

		jQuery('#wnsNotificationsEditForm').submit(function (e) {
			e.preventDefault();
			jQuery("#wnsNotificationTitleMainTxt").val( jQuery("#wnsNotificationTitleTxt").val() );
			if(jQuery('#customCss').get(0).CodeMirrorEditor)
				jQuery('#customCss').val( jQuery('#customCss').get(0).CodeMirrorEditor.getValue());
			_thisObj.saveFilters();
			var _this = jQuery(this);
			setTimeout(function() {
				_this.sendFormWns({
					btn: jQuery('#buttonSave')
					, onSuccess: function (res) {
						jQuery('#buttonSave .fa').hide();
						var currentUrl = window.location.href;
            var hash = window.location.hash;
						if (!res.error && res.data.edit_link && currentUrl !== res.data.edit_link) {
							toeRedirect(res.data.edit_link+hash);
						}
					}
				});
			}, 200);

			return false;

		});

		jQuery('body').on('click', '#buttonDelete', function (e) {
			e.preventDefault();
			var deleteForm = confirm("Are you sure you want to delete filter?")
		    if (deleteForm) {
				var id = jQuery('#wnsNotificationsEditForm').attr('data-table-id');

				if (id) {
					var data = {
						mod: 'woonotifications',
						action: 'deleteByID',
						id: id,
						pl: 'wns',
						reqType: "ajax"
					};
					jQuery.ajax({
						url: url,
						data: data,
						type: 'POST',
						success: function (res) {
							var redirectUrl = jQuery('#wnsNotificationsEditForm').attr('data-href');
							if (!res.error) {
								toeRedirect(redirectUrl);
							}
						}
					});
				}
			} else {
				return false;
			}
			return false;


		});

		// Work with shortcode copy text
		jQuery('#wnsCopyTextCodeExamples').on('change', function (e) {
			var optName = jQuery(this).val();
			switch (optName) {
				case 'shortcode' :
					jQuery('.wnsCopyTextCodeShowBlock').hide();
					jQuery('.wnsCopyTextCodeShowBlock.shortcode').show();
					break;
				case 'phpcode' :
					jQuery('.wnsCopyTextCodeShowBlock').hide();
					jQuery('.wnsCopyTextCodeShowBlock.phpcode').show();
					break;
				case 'shortcode_product' :
					jQuery('.wnsCopyTextCodeShowBlock').hide();
					jQuery('.wnsCopyTextCodeShowBlock.shortcode_product').show();
					break;
				case 'phpcode_product' :
					jQuery('.wnsCopyTextCodeShowBlock').hide();
					jQuery('.wnsCopyTextCodeShowBlock.phpcode_product').show();
					break;
			}
		});

		//-- Work with title --//
		$('#wnsNotificationTitleShell').on('click', function(){
			$('#wnsNotificationTitleLabel').hide();
			$('#wnsNotificationTitleTxt').show();
		});

		$('#wnsNotificationTitleTxt').on('focusout', function(){
			var filterTitle = $(this).val();
			$('#wnsNotificationTitleLabel').text(filterTitle);
			$('#wnsNotificationTitleTxt').hide();
			$('#wnsNotificationTitleLabel').show();
			$('#buttonSave').trigger('click');
		});
		//-- Work with title --//

		jQuery('body').on('focus', '.wnsNotification div > input', function() {
			if( typeof jQuery(this).attr('placeholder') !== 'undefined' && jQuery(this).attr('placeholder').length > 0){
				jQuery(this).attr('data-placeholder', jQuery(this).attr('placeholder') );
				jQuery(this).attr('placeholder', '');
			}
		});
		jQuery('body').on('blur', '.wnsNotification div > input', function() {
			jQuery(this).attr('placeholder', jQuery(this).attr('data-placeholder'));
		});

		jQuery('input[name="settings[enable_ajax]"]').on('change', function () {
			jQuery('input[name="settings[show_filtering_button]"]').prop('checked', false);
			jQuery('input[name="settings[show_filtering_button]"]').prop('checked', !jQuery(this).is(':checked'));
		}).trigger('change');

		jQuery('input[name="settings[show_filtering_button]"]').on('change', function () {
			jQuery('input[name="settings[enable_ajax]"]').prop('checked', false);
			jQuery('input[name="settings[enable_ajax]"]').prop('checked', !jQuery(this).is(':checked'));
		}).trigger('change');

		jQuery(document).ready(function(){
			jQuery('body').on('click', '.ms-options-wrap button', function(e){
				e.preventDefault();
			})

			jQuery('body').on('click', '.text_rule_name', function(){
				jQuery(this).select();
				 document.execCommand('copy');
			});

			jQuery('body').on('change', '.wns-shortcode-data-children', function() {
				var dataShortcode = jQuery(this).closest('.wns-tab-text_rule-container').attr('data-text_rule'),
					dataType = jQuery(this).val(),
					dataSelect = jQuery(this).closest('.wns-text-rules-row').attr('data-select');
				jQuery('[data-text_rule="'+dataShortcode+'"] [data-parent="'+dataSelect+'"][data-children]').hide();
				jQuery('[data-text_rule="'+dataShortcode+'"] [data-parent="'+dataSelect+'"][data-children="'+dataType+'"]').show();
			})

			jQuery('body').on('change', '.wns-shortcode-select', function() {
				var dataShortcode = jQuery(this).closest('.wns-tab-text_rule-container').attr('data-text_rule'),
				 	dataType = jQuery(this).val();
				jQuery('[data-text_rule="'+dataShortcode+'"] .wns-text-rules-row').hide();
				jQuery('[data-text_rule="'+dataShortcode+'"] [data-select="'+dataType+'"]').show();
			})

			jQuery('body').on('click', '.wns-template-parent', function(e){
				jQuery(".wns-template-parent").removeClass('wns-template-activate');
				jQuery(this).addClass('wns-template-activate');
				var tmpId = jQuery(this).attr('data-template-id');
				jQuery('body').find('[name="settings[template_id]"]').val(tmpId);
			})

			jQuery('body').on('click', '.wns-tab-rtd-container', function(e){
				e.preventDefault();
				wnsShowOptionObj(this, 'rtd');
			})

			jQuery('body').on('click', '.wns-tab-text-container', function(e){
				e.preventDefault();
				wnsShowOptionObj(this, 'text');
			})

			jQuery('body').on('click', '.wns-rtd-button-duplicate', function(e){
				e.preventDefault();
				wnsOptButtonAction(this, 'rtd', 'Rules to Display', true, 'clone');
			})

			jQuery('body').on('click', '.wns-text-button-duplicate', function(e){
				e.preventDefault();
				wnsOptButtonAction(this, 'text', 'Text', true, 'clone');
			})

			jQuery('body').on('click', '.wns-text-rules-button-duplicate', function(e){
				e.preventDefault();
				wnsOptButtonAction(this, 'text_rule', 'Text Rule', false, 'clone');
			})

			jQuery('.wns-add-rtd').click(function(){
				wnsOptButtonAction(this, 'rtd', 'Rules to Display', true, 'add');
			})

			jQuery('.wns-add-text').click(function(){
				wnsOptButtonAction(this, 'text', 'Text', true, 'add');
			})

			jQuery('.wns-add-text_rule').click(function(){
				wnsOptButtonAction(this, 'text_rule', 'Text Rule', false, 'add');
			})

			jQuery('body').on('click', '.wns-text-rules-button-remove', function(e){
				e.preventDefault();
				wnsDeleteOptionButton(this, 'text_rule', false);
			})

			jQuery('body').on('click', '.wns-text-button-remove', function(e){
				e.preventDefault();
				wnsDeleteOptionButton(this, 'text');
			})

			jQuery('body').on('click', '.wns-rtd-button-remove', function(e){
				e.preventDefault();
				wnsDeleteOptionButton(this, 'rtd');
			})

			toggleRemoveOrButton();

			jQuery('body').on('click', '.wns-tab-rtd-button-or', function(e){
				e.preventDefault();
				var thisDataRtd = jQuery(this).closest('.wns-tab-rtd-params-container').attr('data-rtd'),
					newRtdId = jQuery('body').find('.wns-tab-rtd-container[data-rtd="'+thisDataRtd+'"]').find('.rtd_id').val(),
					copyHtml = jQuery('[data-rtd="rtd_0"]').find('.wns-tab-rtd-wrapper-or').find('.wns-tab-col-row-rtd').html(),
					lastOrInContainer = jQuery(this).closest('.wns-tab-rtd-wrapper-and').find('.wns-tab-rtd-wrapper-or').last().find('.wns-tab-col-row-rtd').attr('data-or'),
					lastAndInContainer = jQuery(this).closest('.wns-tab-rtd-wrapper-and').find('.wns-tab-rtd-wrapper-or').last().find('.wns-tab-col-row-rtd').attr('data-and');

				lastOrInContainer = parseInt(lastOrInContainer) + 1;

				copyHtml = copyHtml.replace(/\[and\]\[0\]/g,'[and]['+lastAndInContainer+']')
								   .replace(/\[or\]\[0\]/g,'[or]['+lastOrInContainer+']')
								   .replace(/rtd_0/g,'rtd_'+newRtdId);

				jQuery(this).closest('.wns-tab-rtd-wrapper-and').find('.wns-tab-rtd-wrapper-or-containers').append('<div class="wns-tab-rtd-wrapper-or" data-and="'+lastAndInContainer+'"><div class="row wns-tab-col-row-option wns-tab-col-row-rtd" data-and="'+lastAndInContainer+'" data-or="'+lastOrInContainer+'">'+copyHtml+'</div></div>');

				toggleRemoveOrButton();
			})

			jQuery('body').on('click', '.wns-tab-rtd-button-and', function(e){
				e.preventDefault();
				var thisDataRtd = jQuery(this).closest('.wns-tab-rtd-params-container').attr('data-rtd'),
					newRtdId = jQuery('body').find('.wns-tab-rtd-container[data-rtd="'+thisDataRtd+'"]').find('.rtd_id').val(),
					copyHtml = jQuery('[data-rtd="rtd_0"]').find('.wns-tab-rtd-wrapper-and').html(),
					lastAndInContainer = jQuery(this).closest('.wns-tab-rtd-params-container').find('.wns-tab-rtd-wrapper-and').last().attr('data-and');

				lastAndInContainer = parseInt(lastAndInContainer) + 1;

				copyHtml = copyHtml.replace(/\[and\]\[0\]/g,'[and]['+lastAndInContainer+']')
								   .replace(/rtd_0/g,'rtd_'+newRtdId)
								   .replace(/data-and="0"/g,'data-and="'+lastAndInContainer+'"');

				jQuery(this).closest('.wns-tab-rtd-params-container').find('.wns-tab-rtd-wrapper-and-containers').append('<div class="wns-tab-rtd-wrapper-and" data-and="'+lastAndInContainer+'">'+copyHtml+'</div>');
				toggleRemoveOrButton();
			})

			jQuery('body').on('click', '.wns-rtd-button-remove-or', function(e){

				e.preventDefault();

				var andNumber = jQuery(this).closest('.wns-tab-rtd-wrapper-and').attr('data-and'),
					rtdNumber = jQuery(this).closest('.wns-tab-rtd-params-container').attr('data-rtd'),
					elemLength = jQuery(this).closest('.wns-tab-rtd-wrapper-and').find('.wns-tab-rtd-wrapper-or').length;

				jQuery(this).closest('.wns-tab-rtd-wrapper-or').remove();

				if (elemLength-1 === 0) {
					jQuery('[data-rtd="'+rtdNumber+'"] .wns-tab-rtd-wrapper-and[data-and="'+andNumber+'"]').remove();
				}

				toggleRemoveOrButton();
			})

			jQuery('body').find('.wns-shortcode-select-multiple').multiselect();

			jQuery('body').find('.wns-tab-col-row-option select').trigger('change');

			setDateTimePickerToInputs();

			jQuery('body').on('change', '.wns-rtd-select-main', function(e){
				var inputVal = jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-value');
				if (typeof jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-value').datepicker().data('datepicker')  !== "undefined") {
					inputVal.datepicker().data('datepicker').destroy();
					setDateTimePickerToInputs();
					inputVal.val('').html('').attr('value','').data('value','');
				}
			});

			jQuery('body').on('change', '.wns-rtd-select-main', function(e){
				var elVal = jQuery(this).val();
				if (elVal === 'visitor_is_new') {
					jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-value').hide();
					jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-second').hide();
				} else {
					jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-value').show();
					jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-second').show();
				}
				if (elVal === 'time_on_site') {
					jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-second option[value="exactly"]').attr('disabled',true);
				} else if (elVal === 'time_on_page') {
					jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-second option[value="exactly"]').attr('disabled',true);
				} else {
					jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-second option[value="exactly"]').attr('disabled',false);
				}
			});

			jQuery('body').find('[value="visitor_is_new"][selected="true"]').closest('.wns-rtd-select-main').trigger('change');


			function setDateTimePickerToInputs() {
				jQuery('body').find('.wns-rtd-value').each(function(){
					var main = jQuery(this).closest('.wns-tab-col-row-rtd').find('.wns-rtd-select-main');
					var oldVal = jQuery(this).val();
					if (main.val() === 'the_current_date') {
						jQuery(this).datepicker({
							language: 'en',
							dateFormat: 'yyyy-mm-dd'
						});
					}
					if (main.val() === 'the_current_time') {
						jQuery(this).datepicker({
							timepicker: true,
							language: 'en',
							dateFormat: ' ',
							timeFormat: 'h:i'
						});
					}
					if (main.val() === 'the_current_day') {
						jQuery(this).datepicker({
							view: 'days',
							minView: 'days',
							maxView: 'days',
							language: 'en',
							dateFormat: 'DD'
						});
					}
					jQuery(this).val(oldVal).html(oldVal).attr('value',oldVal).data('value',oldVal);
				});
			}



		})

		function getMaxIdNumber(elClass) {
			var biggestNum = 0;
			jQuery('body').find('.'+elClass).each(function(index) {
				var currentNum = jQuery(this).val();
			    currentNum = parseInt(currentNum);
			    if (currentNum > biggestNum) {
			        biggestNum = currentNum;
			    }
			})
			return biggestNum+1;
		}

		function wnsShowOptionObj(buttonObj, objName, hasWorkspace = true) {
			var dataText = jQuery(buttonObj).attr('data-'+objName+'');
			jQuery('body').find('.wns-tab-'+objName+'-container').removeClass('wns-tab-'+objName+'-container-active');
			jQuery(buttonObj).addClass('wns-tab-'+objName+'-container-active');
			if (hasWorkspace) {
				jQuery('body').find('.wns-tab-'+objName+'-params-container').hide();
				jQuery('body').find('.wns-tab-'+objName+'-params-container[data-'+objName+'="'+dataText+'"]').show();
			}
		}

		function wnsDeleteOptionButton(buttonObj, objName, hasWorkspace = true) {
			if (hasWorkspace) {
				var dataText = jQuery(buttonObj).closest('.wns-tab-'+objName+'-container').attr('data-'+objName+'');
				jQuery('.wns-new-'+objName+'-params-block [data-'+objName+'="'+dataText+'"]').remove();
			}
			jQuery(buttonObj).closest('.wns-tab-'+objName+'-container').remove();
		}

		function toggleRemoveOrButton() {
			jQuery('body').find('.wns-rtd-button-remove-or').show();
			//var elLength = jQuery('body').find('.wns-tab-rtd-wrapper-or').length;
			// if (elLength == 2) {
			// 	jQuery('body').find('.wns-rtd-button-remove-or').hide();
			// }
			jQuery('body').find('.wns-tab-rtd-params-container').each(function(){
				var elLength = jQuery(this).find('.wns-tab-rtd-wrapper-or').length;
					if (typeof elLength !== 'undefined' && elLength == 1) {
						jQuery(this).find('.wns-rtd-button-remove-or').hide();
					}
			})
		}

		function textarea_to_tinymce(id){
		    if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function" ) {
		        tinyMCE.execCommand('mceAddControl', false, id);
		    }
		}

		function wnsOptButtonAction(buttonObj, objName, objFullName, hasWorkspace = false, action = 'clone') {
			var shortcodeHtml = jQuery(buttonObj).closest('.wns-tab-'+objName+'-container').html(),
				id = (action === 'add') ? 0 : jQuery(buttonObj).closest('.wns-tab-'+objName+'-container').find('.'+objName+'_id').val(),
				dataText = jQuery(buttonObj).closest('.wns-tab-'+objName+'-container').attr('data-'+objName+''),
				newNum = getMaxIdNumber(''+objName+'_id'),
				text1 = ''+objName+'_'+id,
				text2 = ''+objFullName+' '+id,
				text3 = 'value="'+id+'"',
				shortcodesTmpTextarea = jQuery('.wns-tab-'+objName+'-params-container[data-'+objName+'="'+dataText+'"]').html();

			if (action === 'add') {
				shortcodeHtml = jQuery('.wns-'+objName+'-tmp').html();
				if (hasWorkspace) {
					shortcodesTmpTextarea = jQuery('.wns-'+objName+'-params-tmp').html();
				}
			}

			//Add opt ID's + 1 in sidebar
			shortcodeHtml = shortcodeHtml.replace(new RegExp(text1, 'g'),''+objName+'_'+newNum)
										 .replace(new RegExp(text2, 'g'),''+objFullName+' '+newNum)
										 .replace(new RegExp(text3, 'g'),'value="'+newNum+'"');

			if (hasWorkspace) {
				//Add opt ID's + 1 in workspace
				shortcodesTmpTextarea = shortcodesTmpTextarea.replace(new RegExp(text1, 'g'),''+objName+'_'+newNum)
															 .replace(new RegExp(text2, 'g'),''+objFullName+' '+newNum)
															 .replace(new RegExp(text3, 'g'),'value="'+newNum+'"');
			}

			if (action === 'clone') {
				 //Prepare wrapper for clone
				shortcodeHtml = '<div class="wns-tab-'+objName+'-container" data-'+objName+'="'+objName+'_'+newNum+'">' + shortcodeHtml + '</div>';

				//Past clone to
				jQuery('.wns-new-'+objName+'-block').append(shortcodeHtml);

				if (hasWorkspace) {
					//Prepare wrapper for clone
					shortcodesTmpTextarea = '<div class="wns-tab-'+objName+'-params-container" data-'+objName+'="'+objName+'_'+newNum+'">' + shortcodesTmpTextarea + '</div>';
					//Past clone
					jQuery('.wns-new-'+objName+'-params-block').append(shortcodesTmpTextarea);
				}
			} else if (action === 'add') {
				jQuery('.wns-new-'+objName+'-block').append(shortcodeHtml);
				if (hasWorkspace) {
					jQuery('.wns-new-'+objName+'-params-block').append(shortcodesTmpTextarea);
				}
			}
			if (objName === 'text_rule') {
				jQuery('body').find('.wns-tab-text_rule-container').last().find('.ms-options-wrap').remove();
				jQuery('body').find('.wns-tab-text_rule-container').find('.wns-shortcode-select-multiple').multiselect();
				jQuery('body').find('.wns-tab-text_rule-container').find('.wns-shortcode-select-multiple').multiselect('reload');
			}
			jQuery('body').find('#settings_content_texts_content_text_'+newNum).wp_editor();
		}

	});

	AdminPage.prototype.eventsFilters = (function () {
		var _this = this.$obj;
		var _noOptionsFilters = this.$noOptionsFilters;
		var wnsGetPreviewInit = false;

		jQuery(document).ready(function(){
			jQuery(".chosen-choices").sortable();
			if ("undefined"!==typeof tinyMCE) {
				tinyMCE.DOM.bind(document, 'change', function(e) {
					tinyMCE.triggerSave();
					getPreviewAjax();
				});
			}
			jQuery('.wp-color-picker').on('change', function(){
				getPreviewAjax();
			});
		});

		jQuery('body').find('.wnsTemplateCloseButton').off();

		jQuery("body").on('change', "[name='f_show_inputs']", function (e) {
			e.preventDefault();
			if(jQuery(this).prop('checked')) {
				if (jQuery("[name='f_skin_type']").val() == 'default') {
					jQuery(".f_show_inputs_enabled_tooltip").show();
				} else {
					jQuery(".f_show_inputs_enabled_tooltip").hide();
				}
				jQuery(".f_show_inputs_enabled_position").show();
				jQuery(".f_show_inputs_enabled_currency").show();
			} else {
				jQuery(".f_show_inputs_enabled_tooltip").hide();
				jQuery(".f_show_inputs_enabled_position").hide();
				jQuery(".f_show_inputs_enabled_currency").hide();
				jQuery("[name='f_currency_position']").val('before');
				jQuery("[name='f_currency_show_as']").val('symbol');
				jQuery("[name='f_price_tooltip_show_as']").prop("checked",false);
				jQuery("[name='f_price_tooltip_show_as']").attr("checked",false);
			}
		});

		jQuery("body").on('change', "[name='f_skin_type']", function (e) {
			e.preventDefault();
			if(jQuery(this).val() == 'default') {
				if ( jQuery("[name='f_show_inputs']").prop("checked") ) {
					jQuery(".f_show_inputs_enabled_tooltip").show();
				} else {
					jQuery(".f_show_inputs_enabled_tooltip").hide();
				}
			} else {
				jQuery(".f_show_inputs_enabled_tooltip").hide();
				jQuery("[name='f_price_tooltip_show_as']").prop("checked",false);
				jQuery("[name='f_price_tooltip_show_as']").attr("checked",false);
			}
		});
		getPreviewAjax();

		jQuery("body").on("click keyup", "#wnsNotificationsEditForm .wp-editor-area", function(){
			if ("undefined"!==typeof tinyMCE) {
				tinyMCE.triggerSave();
				getPreviewAjax();
			}
		});

		jQuery("body").on("click keyup", "#wnsNotificationsEditForm input", function(){
			getPreviewAjax();
		});
		jQuery("body").on("change", "#wnsNotificationsEditForm select", function(){
			getPreviewAjax();
		});
		jQuery("body").on("change", "#wnsNotificationsEditForm .mce-content-body", function(){
			getPreviewAjax();
		});
		var wnsWaitResponse = false;
		function getPreviewAjax() {
			if (wnsWaitResponse == false) {
				wnsWaitResponse = true;
				jQuery('#wnsNotificationsEditForm').sendFormWns({
					data: jQuery('#wnsNotificationsEditForm').serializeAnythingWns()
				,	appendData: {mod: 'woonotifications', action: 'drawNotificationAjax'}
				,	onSuccess: function(res) {
						if(!res.error) {
							jQuery(".wns-tab-preview-wrapper-inner").html(res.html);
							jQuery(".wns-tab-preview-wrapper-inner").find("input").attr("name",'');
							jQuery(".wns-tab-preview-wrapper-inner").find("select").attr("name",'');
							jQuery(".wns-tab-preview-wrapper-inner").find("input[type=number]").attr("type",'');
							jQuery(".wns-tab-preview-wrapper-inner").find("select").attr("type",'');
							jQuery('body').find('.wnsTemplateCloseButton').off();
						}
						wnsWaitResponse = false;
					},
				});
			}
		}

		jQuery('body').on('change keyup','.wns-product-search', function(){
			var object = jQuery(this);
			var	title = jQuery(this).val();
			var select = jQuery(this).closest('[data-select="product"]').find('.wns-shortcode-select-multiple');
			var parent = jQuery(this).closest('[data-select="product"]');
			var searchWrapper = jQuery(this).closest('[data-parent="product"]');
			getProductsBySearch(parent, select, title, searchWrapper);
		});

		jQuery('body').on('click', '.wns-search-select-row', function(){
			var prodText = jQuery(this).attr('data-value');
			var prodId = jQuery(this).attr('data-id');
			var select = jQuery(this).closest('[data-select="product"]').find('.wns-shortcode-select-multiple');

			if ( !(select.find("option[value='"+prodId+"']").length > 0) ) {
				select.append(jQuery('<option>', {
				    value: prodId,
				    text: prodText
				}));
			}

			select.multiselect('reload');
		});

		jQuery(document).mouseup(function (e){
				var div = jQuery(".wns-search-select");
				if (!div.is(e.target)
				    && div.has(e.target).length === 0) {
					div.hide();
				}
		});

		var wnsWaitResponse2 = true;
		function getProductsBySearch(parent, select, title, searchWrapper) {
			if (wnsWaitResponse2 == true) {
				wnsWaitResponse2 = true;
				jQuery('#wnsNotificationsEditForm').sendFormWns({
					data: jQuery('#wnsNotificationsEditForm').serializeAnythingWns()
				,	appendData: {title: title, mod: 'woonotifications', action: 'getProductsList'}
				,	onSuccess: function(res) {
						if(!res.error) {

							searchWrapper.find('.wns-search-select').remove();

							var html = '<div class="wns-search-select">';
								jQuery.each(res.data.products, function (i, item) {
									html += '<div class="wns-search-select-row" data-id="'+i+'" data-value="'+item+'">'+item+'</div>';
								});
							html += '</div>';

							searchWrapper.append(html);
						}
						wnsWaitResponse2 = true;
					},
				});
			}
		}

        function wnsAddFilter(id, text){
            var optionsTemplate = jQuery('.wnsTemplates .wnsOptionsTemplate table[data-filter="'+id+'"]').clone();
            var blockTemplate = jQuery('.wnsTemplates .wnsNotificationsBlockTemplate')
                .clone()
                .removeClass('wnsNotificationsBlockTemplate')
                .attr('data-filter', id)
                .attr('data-title', optionsTemplate.attr('data-title'));
            if(_noOptionsFilters.includes(id)){
                blockTemplate.find('.wnsToggle').css({'visibility':'hidden'});
            }
            blockTemplate.find('.wnsOptions').html(optionsTemplate);
            blockTemplate.find('.wnsNotificationTitle').text(text);

            jQuery('.wnsNotificationsBlock').append(blockTemplate);
            _this.initAttributeFilter(blockTemplate);

            //refresh data in ['settings']['filters']
            jQuery(document.body).trigger('changeTooltips');
        }

		//add new filter
		jQuery('.wnsChooseBlock button').on('click', function(e){
			e.preventDefault();
			var text = jQuery(this).text();
			var id = jQuery(this).attr('id');

			//check if filter exist
			if( jQuery('.wnsNotification[data-filter="'+id+'"]').length ){
				//check if allows multiple filters
				if( _this.$allowMultipleFilters.includes(id) ){
					//add more filter for this type
					wnsAddFilter(id, text);
					//check if current filter already exist if yes make visible delete icon
					if(jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="' + id + '"]').length > 1){
						jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="' + id + '"]').find('.wnsDelete').removeClass('wnsVisibilityHidden');
					}
				}
			}else{
				//add filter
				wnsAddFilter(id, text);
			}
		});

		//remove existing filter
		jQuery('.wnsNotificationsBlock').on('click', '.wnsNotification a.wnsDelete', function(e){
			e.preventDefault();
			var _this = jQuery(this);
			var id = _this.attr('data-filter');

			jQuery(this).closest('.wnsNotification').remove();
			if(jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="' + id + '"]').length < 1){
				jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="' + id + '"]').find('.wnsDelete').addClass('wnsVisibilityHidden');
			}
			//refresh data in ['settings']['filters']
		});

		//show / hide filter options
		jQuery('.wnsNotificationsBlock').on('click', '.wnsNotification a.wnsToggle', function(e){
			e.preventDefault();
			var el = jQuery(this);
			var i = el.find('i');
			var span = el.find('.wnsTextSt_1');

			if (i.hasClass('fa-chevron-down')){
				i.removeClass('fa-chevron-down').addClass('fa-chevron-up');
				span.text(span.attr('data-title-open'));
				el.closest('.wnsNotification').find('.wnsOptions').removeClass('wnsHidden');
			}else{
				i.removeClass('fa-chevron-up').addClass('fa-chevron-down');
				span.text(span.attr('data-title-close'));
				el.closest('.wnsNotification').find('.wnsOptions').addClass('wnsHidden');
			}

			//refresh data in ['settings']['filters']
		});

		//make properties sortable
		jQuery(".wnsNotificationsBlock").sortable({
			cursor: "move",
			axis: "y",
			handle: ".wnsMove",
			stop: function () {
				//jQuery('#buttonSave').trigger('click');
				_this.saveFilters();
				//_this.saveFilters();
				//getPreviewAjax();
			},
		});

		//after load page display filters tab
		displayFiltersTab();

		function displayFiltersTab(){
			jQuery('.wnsNotificationsBlock').html('');
			var defFilters = [
					{'id':'wnsPrice'},
					{'id':'wnsPriceRange'},
					{'id':'wnsSortBy'},
					{'id':'wnsCategory'},
					{'id':'wnsTags'},
					{'id':'wnsAuthor'},
					{'id':'wnsFeatured'},
					{'id':'wnsOnSale'},
					{'id':'wnsInStock'},
					{'id':'wnsRating'},
					{'id':'wnsSearchText'},
				];
			try{
				var filters = JSON.parse(jQuery('input[name="settings[filters][order]"]').val()),
					cntFilters = filters.length;
				defFilters.forEach(function(value){
					var found = false,
						id = value.id;
					for(var i = 0; i < cntFilters; i++) {
						if(filters[i].id == id) {
							found = true;
							break;
						}
					}
					if(!found) {
						filters.push(Object.assign({}, value));
					}
				});
			}catch(e){
				var filters = defFilters;
			}

			filters.forEach(function (value) {
				var id = value.id,
					template = jQuery('.wnsTemplates .wnsOptionsTemplate table[data-filter="'+id+'"]');
				if(template.length == 0) return true;

				var settings = value.settings,
					optionsTemplate = template.clone(),
					text = optionsTemplate.find('input[name=f_name]').val(),
					isDisabled = (optionsTemplate.attr('data-disabled') == '1');

				if( typeof settings !== 'undefined' ) {
					optionsTemplate.find('input, select').map(function (index, elm) {
						var name = elm.name;
						if (elm.type === 'checkbox') {

							if (elm.name === 'f_options[]') {
								if (settings[name]) {
									var checkedArr = settings[name].split(",");
									if (checkedArr.includes(elm.value)) {
										jQuery(elm).prop("checked", true);
									}
								}
							} else {
								jQuery(elm).prop("checked", settings[name]);
							}

						} else if (elm.type === 'select-multiple') {
							if (_this.$multiSelectFields.includes(elm.name)) {
								if (settings[name]) {
									var selectedArr = settings[name].split(",");
									jQuery.each(selectedArr, function (i, e) {
										var option = jQuery(elm).find("option[value='" + e + "']");
										option.remove();
										jQuery(elm).append(option);
										jQuery(elm).find("option[value='" + e + "']").prop("selected", true);
									});
								}
							}
						} else {
							if(typeof settings[name] !== 'undefined') {
								elm.value = settings[name];
							}
						}
					});
				}
				var blockTemplate = jQuery('.wnsTemplates .wnsNotificationsBlockTemplate')
					.clone()
					.removeClass('wnsNotificationsBlockTemplate')
					.attr('data-filter', id)
					.attr('data-title', text),
					title = text;
				blockTemplate.find('.wnsOptions').html(optionsTemplate);
				if( id === 'wnsAttribute' ){
					title = blockTemplate.find('select[name="f_list"] option:selected').text();
					text = text + ' - ' + title;
				}
				if(_noOptionsFilters.includes(id)){
					blockTemplate.find('.wnsToggle').css({'visibility':'hidden'});
				}
				blockTemplate.find('.wnsNotificationTitle').text(text);
				if( typeof settings !== 'undefined' ){
					blockTemplate.find('.wnsNotificationFrontDescOpt input').val(settings['f_description']);
					if(settings['f_enable'] == true){
						blockTemplate.find('.wnsEnable input').prop( "checked", true );
					}
					if(typeof settings['f_title'] !== 'undefined' && settings['f_title'].length > 0) {
						title = settings['f_title'];
					}
				}
				blockTemplate.find('.wnsNotificationFrontTitleOpt input').val(title);
				if(isDisabled) {
					blockTemplate.find('input').prop('disabled', true);
				}
				jQuery('.wnsNotificationsBlock').append(blockTemplate);

				if(jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="' + id + '"]').length > 1){
					jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="' + id + '"]').find('.wnsDelete').removeClass('wnsVisibilityHidden');
				}
				if(id === 'wnsAttribute'){
					setTimeout(function() {
						_this.initAttributeFilter(blockTemplate, settings);
					}, 200);
				}
			});
			jQuery('#wnsNotificationsEditForm select[name="f_mlist[]"]').chosen({ width:"95%" });
			jQuery(document.body).trigger('changeTooltips');

			//filter Price - options
			var filterPrice = jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="wnsPrice"]');
			if(filterPrice.length) {
				var defaultSlider = filterPrice.find('#wnsSliderRange'),
					minValue = 200,
					maxValue = 600,
					minSelector = filterPrice.find('#wnsMinPrice').val(minValue),
					maxSelector = filterPrice.find('#wnsMaxPrice').val(maxValue);
				defaultSlider.slider({
					range: true,
					orientation: "horizontal",
					min: 0,
					max: 1000,
					values: [minValue, maxValue],
					step: 1,
					slide: function (event, ui) {
						minSelector.val(ui.values[0]);
						maxSelector.val(ui.values[1]);
					}
				});
				filterPrice.find('input[name="f_show_inputs"]').on('change', function(e){
					e.preventDefault();
					if($(this).prop('checked')) {
						filterPrice.find('.wnsPriceInputs').show();
					} else {
						filterPrice.find('.wnsPriceInputs').hide();
					}
				}).trigger('change');
				minSelector.on('change', function(e){
					e.preventDefault();
					defaultSlider.slider('values', 0, $(this).val());
				});
				maxSelector.on('change', function(e){
					e.preventDefault();
					defaultSlider.slider('values', 1, $(this).val());
				});
				filterPrice.find('select[name="f_skin_type"].wnsWithProAd').on('change', function(e){
					e.preventDefault();
					filterPrice.find('.wnsPriceSkinPro').addClass('wnsHidden');
					filterPrice.find('.wnsPriceSkinPro[data-type="'+$(this).val()+'"]').removeClass('wnsHidden');
				}).trigger('change');
			}


			//filter Price Range - options
			var filterPriceRange = jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="wnsPriceRange"]');
			if(filterPriceRange.length) {
				filterPriceRange.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					if($(this).val() == 'dropdown') {
						filterPriceRange.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else {
						filterPriceRange.find('input[name="f_dropdown_first_option_text"]').closest('tr').hide();
					}
				}).trigger('change');
			}

			//filter Category - options
			var filterCategory = jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="wnsCategory"]');
			if(filterCategory.length) {
				filterCategory.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					var frontendType = $(this).val();
					filterCategory.find('input[name="f_dropdown_first_option_text"], input[name="f_show_search_input"], input[name^="f_multi"], select[name^="f_multi"]').closest('tr').hide();
					if(frontendType == 'dropdown') {
						filterCategory.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else if(frontendType == 'list'){
						filterCategory.find('input[name="f_show_search_input"]').closest('tr').css('display', 'table-row');
					} else {
						filterCategory.find('input[name^="f_'+frontendType+'"], select[name^="f_'+frontendType+'"]').closest('tr').css('display', 'table-row');
					}
				}).trigger('change');
			}

			//filter Tags - options
			var filterTags = jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="wnsTags"]');
			if(filterTags.length) {
				filterTags.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					filterTags.find('input[name="f_dropdown_first_option_text"], input[name="f_show_search_input"]').closest('tr').hide();
					var frontendType = $(this).val();
					if(frontendType == 'dropdown') {
						filterTags.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else if(frontendType == 'list'){
						filterTags.find('input[name="f_show_search_input"]').closest('tr').css('display', 'table-row');
					}
				}).trigger('change');
			}

			//filter Author - options
			var filterAuthor = jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="wnsAuthor"]');
			if(filterAuthor.length) {
				filterAuthor.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					filterAuthor.find('input[name="f_dropdown_first_option_text"], input[name="f_show_search_input"]').closest('tr').hide();
					var frontendType = $(this).val();
					if(frontendType == 'dropdown') {
						filterAuthor.find('input[name="f_dropdown_first_option_text"]').closest('tr').css('display', 'table-row');
					} else if(frontendType == 'list'){
						filterAuthor.find('input[name="f_show_search_input"]').closest('tr').css('display', 'table-row');
					}
				}).trigger('change');
			}

			//filter Rating - options
			var filterRating = jQuery('.wnsNotificationsBlock .wnsNotification[data-filter="wnsRating"]');
			if(filterRating.length) {
				filterRating.find('select[name="f_frontend_type"]').on('change', function(e){
					e.preventDefault();
					var el = $(this),
						value = el.val();

					filterRating.find('.wnsTypeSwitchable').addClass('wnsHidden');
					filterRating.find('.wnsTypeSwitchable[data-type~="'+value+'"]').removeClass('wnsHidden');
					if(el.hasClass('wnsWithProAd')) {
						filterRating.find('.wnsRatingTypePro').addClass('wnsHidden');
						filterRating.find('.wnsRatingTypePro[data-type="'+value+'"]').removeClass('wnsHidden');
					}
				}).trigger('change');
			}
		}

		jQuery("body").on('change', 'select[name="f_list"]', function (e) {
			e.preventDefault();
			var _this = jQuery(this),
				changedName = _this.val() == 0 ? '' : ' - ' + _this.find('option:selected').text(),
				startName = _this.closest('.wnsNotification').attr('data-title'),
				fullTitle = startName + changedName;
			_this.closest('.wnsNotification').find('.wnsNotificationTitle').text(fullTitle);

		});

		jQuery('.wnsNotificationsBlock').on('change', '.wnsAutomaticOrByHand input', function(){
			var _this = jQuery(this);
			var id = _this.closest('.wnsAutomaticOrByHand').attr('id');
			var checked = _this.prop('checked');

			jQuery('.wnsAutomaticOrByHand').not('#'+id).find('input').prop('checked', !checked );
		});

		//Sort by filter. Disable unchecking last two checkbox.
		jQuery('body').on('click', '.wnsNotification[data-filter="wnsSortBy"] input[name="f_options[]"]', function (e) {
			var countCheckedCheckbox = jQuery('.wnsNotification[data-filter="wnsSortBy"]').find('input[name="f_options[]"]:checked').length;
			if(countCheckedCheckbox < 2){
				e.preventDefault();
				return false;
			}
		});

		//Sort by filter. Disable unchecking last two checkbox.
		jQuery('body').on('click', '.wnsNotification[data-filter="wnsInStock"] input[name="f_options[]"]', function (e) {
			var countCheckedCheckbox = jQuery('.wnsNotification[data-filter="wnsInStock"]').find('input[name="f_options[]"]:checked').length;
			if(countCheckedCheckbox < 2){
				e.preventDefault();
				return false;
			}
		});

		// duplicate cat/tags filter
        jQuery('body').on('click', '.wnsDuplicateButton', function (e) {
        	var duplicateFilter = jQuery(this).closest('.wnsNotification');
        	var id = duplicateFilter.data('filter');
        	var text = 'Filter '+ (jQuery('.wnsNotification').length + 1);
            wnsAddFilter(id, text);
            setTimeout(function(){
            	goTo(jQuery('.wnsNotification:last-child'));
			},300);
            displayFiltersTab();

            return false;
        });
	});

	AdminPage.prototype.initAttributeFilter = (function(filter, settings) {
		var _thisObj = this.$obj;
		if(typeof(_thisObj.initAttributeColorFilter) == 'function') {
			_thisObj.initAttributeColorFilter(filter, settings);
		}

		filter.find('select[name="f_frontend_type"]').on('change', function(e){
			e.preventDefault();
			var el = $(this),
				value = el.val();

			filter.find('.wnsTypeSwitchable').addClass('wnsHidden');
			filter.find('.wnsTypeSwitchable[data-type~="'+value+'"]').removeClass('wnsHidden');
			if(el.hasClass('wnsWithProAd')) {
				filter.find('.wnsAttributesTypePro').addClass('wnsHidden');
				filter.find('.wnsAttributesTypePro[data-type="'+value+'"]').removeClass('wnsHidden');
			}
		}).trigger('change');
	});

	AdminPage.prototype.saveFilters = (function () {
		var _this = this.$obj;
		var filtersArr = [];
		var i = 0;
		if( jQuery('.wnsNotificationsBlock .wnsNotification').length <=0 ){
			return;
		}
		jQuery('.wnsNotification').not('.wnsNotificationsBlockTemplate').each(function () {
			var valueToPush = {},
				el = jQuery(this),
				id = 'wnsNotification'+i,
				items = {},
				title = el.find('input[name="f_title"]');
			el.attr('id', id);

			if(title.val() == '') {
				title.val(el.find('.wnsNotificationTitle').text());
			}

			jQuery("#" + id +" input, #" + id +" select").map(function(index, elm) {

				if(elm.type === 'checkbox'){
					//for multi checkbox
					if(elm.name === 'f_options[]'){
						if(elm.checked){
							if(typeof items[elm.name] !== 'undefined'){
								var temp = items[elm.name];
								temp = temp + ',' + jQuery(elm).val();
								items[elm.name] = temp;
							}else{
								items[elm.name] = jQuery(elm).val();
							}
						}
					}else{
						items[elm.name] = elm.checked;
					}
				}else if(elm.type === 'select-multiple'){
					if( _this.$multiSelectFields.includes(elm.name) ){
						//add more filter for this type
						var arrayValues = jQuery(elm).getSelectionOrder();
						//arrayValues = arrayValues.reverse();
						//console.log(arrayValues);
						if(arrayValues){
							items[elm.name] = arrayValues.toString();
						}
					}
				}else{
					items[elm.name] = jQuery(elm).val();
				}
			});
			valueToPush['id'] = el.attr('data-filter');
			valueToPush['settings'] = items;
			filtersArr.push(valueToPush);
			i++;
		});

		var filtersJson = JSON.stringify(filtersArr);
		jQuery('input[name="settings[filters][order]"]').val(filtersJson);

	});

	jQuery(document).ready(function () {
		window.wnsAdminPage = new AdminPage();
		window.wnsAdminPage.init();
	});

}(window.jQuery, window.supsystic));
