(function ($, app) {

	function WnsFrontendPage() {
		this.$obj = this;
		return this.$obj;
	}

	function getCookie(name) {
	  var matches = document.cookie.match(new RegExp(
	    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	  ));
	  return matches ? decodeURIComponent(matches[1]) : undefined;
	}

	function setCookie(name, value, options) {
		  if (getCookie(name) === undefined) {

		  options = options || {};

		  var expires = options.expires;

		  if (typeof expires == "number" && expires) {
		    var d = new Date();
		    d.setTime(d.getTime() + expires * 1000);
		    expires = options.expires = d;
		  }
		  if (expires && expires.toUTCString) {
		    options.expires = expires.toUTCString();
		  }

		  value = encodeURIComponent(value);

		  var updatedCookie = name + "=" + value;

		  for (var propName in options) {
		    updatedCookie += "; " + propName;
		    var propValue = options[propName];
		    if (propValue !== true) {
		      updatedCookie += "=" + propValue;
		    }
	  }

	  document.cookie = updatedCookie;
	}
	}

	WnsFrontendPage.prototype.init = (function () {
		var _thisObj = this.$obj;
		_thisObj.eventsFrontend();
		_thisObj.runCustomJs();
		_thisObj.addCustomCss();
	});

	WnsFrontendPage.prototype.eventsFrontend = (function () {
		var _thisObj = this.$obj;
		var wnsNotificationsBox = new Array();

		var timeOnPageStart = new Date().getTime();
		setCookie("wnsTimeOnSite", timeOnPageStart);

		jQuery('.wnsNotificationPopup .wnsTemplateCloseButton').on('click',function(){
			var id = jQuery(this).closest('.wnsNotificationPopup').attr('id');
			jQuery('#'+id).fadeOut();
			jQuery('#'+id+'_overlay').fadeOut();
		});

		jQuery('body').find('.wnsNotificationPopup').each(function(){
			if (typeof window.wnsPreview !== "undefined" && window.wnsPreview == true) {
				return false;
			}
			var thisEl = jQuery(this),
				thisElSettings = thisEl.attr('data-settings'),
				thisElSettings = JSON.parse(thisElSettings),
				wnsNotificationBox = new Array(),
				displayRules = thisElSettings['display_rules']['and'],
				id = jQuery(this).attr('id');

			wnsNotificationsBox[id] = new Array();

			displayRulesArr = new Array();
			for (var prop in displayRules) {
				displayRulesArr.push(displayRules[prop]);
			}

			wnsNotificationsBox[id]['notification_id'] = jQuery(this).attr('id');
			wnsNotificationsBox[id]['ready'] = new Array();
			wnsNotificationsBox[id]['display_rules'] = displayRulesArr;

			wnsNotificationsBox[id]['timer'] = setInterval( checkNotificationById.bind(null, id), 500);
		});

		var idleTime = 0;
		//Increment the idle time counter every minute.

		var idleInterval = setInterval(timerIncrement, 1000);
		//Zero the idle timer on mouse movement.

		jQuery(document).mousemove(function (e) {
			idleTime = 0;
		});

		jQuery(document).keypress(function (e) {
			idleTime = 0;
		});

		function timerIncrement() {
		    idleTime = idleTime + 1;
		}

		function dayOfWeekAsInteger(day) {
		  return ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"].indexOf(day);
		}

		function checkNotificationById(id) {
			if (typeof window.wnsPreview !== "undefined" && window.wnsPreview == true) {
				return false;
			}
			wnsNotificationsBox[id]['ready'] = new Array();
			wnsNotificationsBox[id]['display_rules'].forEach(function(index, value){
				wnsNotificationsBox[id]['ready'].push(0);
				let keyAnd = value;
				for(var q=index['or'].length;q-->0;) {
					let option =  index['or'][q]['main'],
						second = index['or'][q]['second'],
						value = (index['or'][q]['value']) ? index['or'][q]['value'] : 0,
						operator = '===';

						if (second === 'more') {
							operator = '>';
						} else if (second === 'less') {
							operator = '<';
						} else if (second === 'exactly') {
							operator = '===';
						}

						switch (option) {
							case 'time_on_page':
								var currentTime = new Date().getTime();
								currentTime = Math.floor( (currentTime - timeOnPageStart) / 1000);
								if ( eval(currentTime + operator + value) ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
							case 'time_on_site':
								var currentTime = new Date().getTime();
								let timeOnSite = getCookie("wnsTimeOnSite");
								currentTime = Math.floor( (currentTime - timeOnSite) / 1000);
								if ( eval(currentTime + operator + value) ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
							case 'distance_scrolled':
								let scrollTop = jQuery(window).scrollTop();
								if ( eval(scrollTop + operator + value) ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
							case 'the_current_date':
								var currentTime = new Date().toISOString().slice(0,10);
								currentTime = Date.parse(currentTime);
								value = Date.parse(value);
								if ( eval(currentTime + operator + value) ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
							case 'the_current_time':
								var currentTime = new Date();
								currentTime = Date.parse('01/01/2011 ' + currentTime.getHours() + ':' +currentTime.getMinutes() );
								value = Date.parse('01/01/2011 '+ value);
								if ( eval(currentTime + operator + value) ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
							case 'the_current_day':
								var currentTime = new Date().getDay();
								value = dayOfWeekAsInteger(value);
								if ( eval(currentTime + operator + value) ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
							case 'visitor_inactive_time':
								if ( eval(idleTime + operator + value) ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
							case 'visitor_is_new':
								let visitorIsNew = getCookie("wnsVisitorIsNew");
								if ( typeof(visitorIsNew) === 'undefined' ) {
									wnsNotificationsBox[id]['ready'][keyAnd] = 1;
								}
							break;
						}
				}
				// index['or'].forEach(function(subIndex, subValue){
				//
				// });
			});

			if (!wnsNotificationsBox[id]['ready'].includes(0)) {
				clearInterval(wnsNotificationsBox[id]['timer']);
				id = wnsNotificationsBox[id]['notification_id'];
				jQuery('#'+id).fadeIn();
				jQuery('#'+id+'_overlay').fadeIn();
			}

			setCookie("wnsVisitorIsNew", false);
		}

		jQuery('body').find('.wns-countdown-timestamp').each(function(){
			var targetDate = jQuery(this).attr('data-targettime'),
				timeFormat = jQuery(this).attr('data-timeformat'),
				newTimeFormat = '';
			switch (timeFormat) {
				case 'h:i:s' :
					newTimeFormat = '%I:%M:%S';
					var utc = new Date().toJSON().slice(0,10);
					targetDate = Date.parse(utc + ' ' + targetDate);
				break;
				case 'h:i' :
					newTimeFormat = '%I:%M';
					var utc = new Date().toJSON().slice(0,10);
					targetDate = Date.parse(utc + ' ' + targetDate + ':00');
				break;
				case 'Y-m-d' :
					newTimeFormat = '%Y-%m-%n';
				break;
				case 'Y-m-d h:i:s' :
					newTimeFormat = '%Y-%m-%n %H:%M:%S';
				break;
			}
			if (newTimeFormat !== '') {
				jQuery(this).countdown(targetDate, function(event) {
					jQuery(this).html(event.strftime(newTimeFormat));
				});
			}
		});



	});

	WnsFrontendPage.prototype.runCustomJs = (function () {
		var _thisObj = this.$obj;
	});

	WnsFrontendPage.prototype.addCustomCss = (function () {
		var _thisObj = this.$obj;
	});

	jQuery(document).ready(function () {
		window.wnsFrontendPage = new WnsFrontendPage();
		window.wnsFrontendPage.init();
	});

}(window.jQuery));
