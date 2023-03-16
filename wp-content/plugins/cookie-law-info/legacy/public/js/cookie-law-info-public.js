CLI_ACCEPT_COOKIE_NAME = (typeof CLI_ACCEPT_COOKIE_NAME !== 'undefined' ? CLI_ACCEPT_COOKIE_NAME : 'viewed_cookie_policy');
CLI_PREFERNCE_COOKIE = (typeof CLI_PREFERNCE_COOKIE !== 'undefined' ? CLI_PREFERNCE_COOKIE : 'CookieLawInfoConsent');
CLI_ACCEPT_COOKIE_EXPIRE = (typeof CLI_ACCEPT_COOKIE_EXPIRE !== 'undefined' ? CLI_ACCEPT_COOKIE_EXPIRE : 365);
CLI_COOKIEBAR_AS_POPUP = (typeof CLI_COOKIEBAR_AS_POPUP !== 'undefined' ? CLI_COOKIEBAR_AS_POPUP : false);
var CLI_Cookie = {
	set: function (name, value, days) {
		var secure = "";
		if (true === Boolean(Cli_Data.secure_cookies)) {
			secure = ";secure";
		}
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			var expires = "; expires=" + date.toGMTString();
		} else {
			var expires = "";
		}
		document.cookie = name + "=" + value + secure + expires + "; path=/";
		if (days < 1) {
			host_name = window.location.hostname;
			document.cookie = name + "=" + value + expires + "; path=/; domain=." + host_name + ";";
			if (host_name.indexOf("www") != 1) {
				var host_name_withoutwww = host_name.replace('www', '');
				document.cookie = name + "=" + value + secure + expires + "; path=/; domain=" + host_name_withoutwww + ";";
			}
			host_name = host_name.substring(host_name.lastIndexOf(".", host_name.lastIndexOf(".") - 1));
			document.cookie = name + "=" + value + secure + expires + "; path=/; domain=" + host_name + ";";
		}
	},
	read: function (name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1, c.length);
			}
			if (c.indexOf(nameEQ) === 0) {
				return c.substring(nameEQ.length, c.length);
			}
		}
		return null;
	},
	erase: function (name) {
		this.set(name, "", -10);
	},
	exists: function (name) {
		return (this.read(name) !== null);
	},
	getallcookies: function () {
		var pairs = document.cookie.split(";");
		var cookieslist = {};
		for (var i = 0; i < pairs.length; i++) {
			var pair = pairs[i].split("=");
			cookieslist[(pair[0] + '').trim()] = unescape(pair[1]);
		}
		return cookieslist;
	}
}
var CLI =
{
	bar_config: {},
	showagain_config: {},
	allowedCategories: [],
	js_blocking_enabled: false,
	set: function (args) {
		if (typeof JSON.parse !== "function") {
			console.log("CookieLawInfo requires JSON.parse but your browser doesn't support it");
			return;
		}
		if (typeof args.settings !== 'object') {
			this.settings = JSON.parse(args.settings);
		} else {
			this.settings = args.settings;
		}
		this.js_blocking_enabled = Boolean(Cli_Data.js_blocking);
		this.settings = args.settings;
		this.bar_elm = jQuery(this.settings.notify_div_id);
		this.showagain_elm = jQuery(this.settings.showagain_div_id);
		this.settingsModal = jQuery('#cliSettingsPopup');

		/* buttons */
		this.main_button = jQuery('.cli-plugin-main-button');
		this.main_link = jQuery('.cli-plugin-main-link');
		this.reject_link = jQuery('.cookie_action_close_header_reject');
		this.delete_link = jQuery(".cookielawinfo-cookie-delete");
		this.settings_button = jQuery('.cli_settings_button');
		this.accept_all_button = jQuery('.wt-cli-accept-all-btn');
		if (this.settings.cookie_bar_as == 'popup') {
			CLI_COOKIEBAR_AS_POPUP = true;
		}
		this.mayBeSetPreferenceCookie();
		this.addStyleAttribute();
		this.configBar();
		this.toggleBar();
		this.attachDelete();
		this.attachEvents();
		this.configButtons();
		this.reviewConsent();
		var cli_hidebar_on_readmore = this.hideBarInReadMoreLink();
		if (Boolean(this.settings.scroll_close) === true && cli_hidebar_on_readmore === false) {
			window.addEventListener("scroll", CLI.closeOnScroll, false);
		}

	},
	hideBarInReadMoreLink: function () {
		if (Boolean(CLI.settings.button_2_hidebar) === true && this.main_link.length > 0 && this.main_link.hasClass('cli-minimize-bar')) {
			this.hideHeader();
			cliBlocker.cookieBar(false);
			this.showagain_elm.slideDown(this.settings.animate_speed_show);
			return true;
		}
		return false;
	},
	attachEvents: function () {
		jQuery(document).on(
			'click',
			'.wt-cli-privacy-btn',
			function (e) {
				e.preventDefault();
				CLI.accept_close();
				CLI.settingsPopUpClose();
			}
		);

		jQuery('.wt-cli-accept-btn').on(
			"click",
			function (e) {
				e.preventDefault();
				CLI.acceptRejectCookies(jQuery(this));
			});
		jQuery('.wt-cli-accept-all-btn').on(
			"click",
			function (e) {
				e.preventDefault();
				CLI.acceptRejectCookies(jQuery(this), 'accept');
			});
		jQuery('.wt-cli-reject-btn').on(
			"click",
			function (e) {
				e.preventDefault();
				CLI.acceptRejectCookies(jQuery(this), 'reject');
			});
		this.settingsPopUp();
		this.settingsTabbedAccordion();
		this.toggleUserPreferenceCheckBox();
		this.hideCookieBarOnClose();
		this.cookieLawInfoRunCallBacks();

	},
	acceptRejectCookies(element, action = 'custom') {
		var open_link = element[0].hasAttribute("href") && element.attr("href") != '#' ? true : false;
		var new_window = false;
		if (action == 'accept') {
			this.enableAllCookies();
			this.accept_close();
			new_window = CLI.settings.button_7_new_win ? true : false;

		} else if (action == 'reject') {
			this.disableAllCookies();
			this.reject_close();
			new_window = Boolean(this.settings.button_3_new_win) ? true : false;
		} else {
			this.accept_close();
			new_window = Boolean(this.settings.button_1_new_win) ? true : false;
		}
		if (open_link) {
			if (new_window) {
				window.open(element.attr("href"), '_blank');
			} else {
				window.location.href = element.attr("href");
			}
		}
	},
	toggleUserPreferenceCheckBox: function () {

		jQuery('.cli-user-preference-checkbox').each(
			function () {

				categoryCookie = 'cookielawinfo-' + jQuery(this).attr('data-id');
				categoryCookieValue = CLI_Cookie.read(categoryCookie);
				if (categoryCookieValue == null) {
					if (jQuery(this).is(':checked')) {
						CLI_Cookie.set(categoryCookie, 'yes', CLI_ACCEPT_COOKIE_EXPIRE);
					} else {
						CLI_Cookie.set(categoryCookie, 'no', CLI_ACCEPT_COOKIE_EXPIRE);
					}
				} else {
					if (categoryCookieValue == "yes") {
						jQuery(this).prop("checked", true);
					} else {
						jQuery(this).prop("checked", false);
					}

				}

			}
		);
		jQuery('.cli-user-preference-checkbox').on(
			"click",
			function (e) {
				var dataID = jQuery(this).attr('data-id');
				var currentToggleElm = jQuery('.cli-user-preference-checkbox[data-id=' + dataID + ']');
				if (jQuery(this).is(':checked')) {
					CLI_Cookie.set('cookielawinfo-' + dataID, 'yes', CLI_ACCEPT_COOKIE_EXPIRE);
					currentToggleElm.prop('checked', true);
				} else {
					CLI_Cookie.set('cookielawinfo-' + dataID, 'no', CLI_ACCEPT_COOKIE_EXPIRE);
					currentToggleElm.prop('checked', false);
				}
				CLI.checkCategories();
				CLI.generateConsent();
			}
		);

	},
	settingsPopUp: function () {
		jQuery(document).on(
			'click',
			'.cli_settings_button',
			function (e) {
				e.preventDefault();
				CLI.settingsModal.addClass("cli-show").css({ 'opacity': 0 }).animate({ 'opacity': 1 });
				CLI.settingsModal.removeClass('cli-blowup cli-out').addClass("cli-blowup");
				jQuery('body').addClass("cli-modal-open");
				jQuery(".cli-settings-overlay").addClass("cli-show");
				jQuery("#cookie-law-info-bar").css({ 'opacity': .1 });
				if (!jQuery('.cli-settings-mobile').is(':visible')) {
					CLI.settingsModal.find('.cli-nav-link:eq(0)').trigger("click");
				}
			}
		);
		jQuery('#cliModalClose').on(
			"click",
			function (e) {
				CLI.settingsPopUpClose();
			}
		);
		CLI.settingsModal.on(
			"click",
			function (e) {
				if (!(document.getElementsByClassName('cli-modal-dialog')[0].contains(e.target))) {
					CLI.settingsPopUpClose();
				}
			}
		);
		jQuery('.cli_enable_all_btn').on(
			"click",
			function (e) {
				var cli_toggle_btn = jQuery(this);
				var enable_text = cli_toggle_btn.attr('data-enable-text');
				var disable_text = cli_toggle_btn.attr('data-disable-text');
				if (cli_toggle_btn.hasClass('cli-enabled')) {
					CLI.disableAllCookies();
					cli_toggle_btn.html(enable_text);
				} else {
					CLI.enableAllCookies();
					cli_toggle_btn.html(disable_text);

				}
				jQuery(this).toggleClass('cli-enabled');
			}
		);

		this.privacyReadmore();
	},
	settingsTabbedAccordion: function () {
		jQuery(".cli-tab-header").on(
			"click",
			function (e) {
				if (!(jQuery(e.target).hasClass('cli-slider') || jQuery(e.target).hasClass('cli-user-preference-checkbox'))) {
					if (jQuery(this).hasClass("cli-tab-active")) {
						jQuery(this).removeClass("cli-tab-active");
						jQuery(this)
							.siblings(".cli-tab-content")
							.slideUp(200);

					} else {
						jQuery(".cli-tab-header").removeClass("cli-tab-active");
						jQuery(this).addClass("cli-tab-active");
						jQuery(".cli-tab-content").slideUp(200);
						jQuery(this)
							.siblings(".cli-tab-content")
							.slideDown(200);
					}
				}
			}
		);
	},
	settingsPopUpClose: function () {
		this.settingsModal.removeClass('cli-show');
		this.settingsModal.addClass('cli-out');
		jQuery('body').removeClass("cli-modal-open");
		jQuery(".cli-settings-overlay").removeClass("cli-show");
		jQuery("#cookie-law-info-bar").css({ 'opacity': 1 });
	},
	privacyReadmore: function () {
		var el = jQuery('.cli-privacy-content .cli-privacy-content-text');
		if (el.length > 0) {
			var clone = el.clone(),
				originalHtml = clone.html(),
				originalHeight = el.outerHeight(),
				Trunc = {
					addReadmore: function (textBlock) {
						if (textBlock.html().length > 250) {
							jQuery('.cli-privacy-readmore').show();
						} else {
							jQuery('.cli-privacy-readmore').hide();
						}
					},
					truncateText: function (textBlock) {
						var strippedText = jQuery('<div />').html(textBlock.html());
						strippedText.find('table').remove();
						textBlock.html(strippedText.html());
						currentText = textBlock.text();
						if (currentText.trim().length > 250) {
							var newStr = currentText.substring(0, 250);
							textBlock.empty().html(newStr).append('...');
						}
					},
					replaceText: function (textBlock, original) {
						return textBlock.html(original);
					}

				};
			Trunc.addReadmore(el);
			Trunc.truncateText(el);
			jQuery('a.cli-privacy-readmore').on(
				"click",
				function (e) {
					e.preventDefault();
					if (jQuery('.cli-privacy-overview').hasClass('cli-collapsed')) {
						Trunc.truncateText(el);
						jQuery('.cli-privacy-overview').removeClass('cli-collapsed');
						el.css('height', '100%');
					} else {
						jQuery('.cli-privacy-overview').addClass('cli-collapsed');
						Trunc.replaceText(el, originalHtml);
					}

				}
			);
		}

	},
	attachDelete: function () {
		this.delete_link.on(
			"click",
			function (e) {
				CLI_Cookie.erase(CLI_ACCEPT_COOKIE_NAME);
				for (var k in Cli_Data.nn_cookie_ids) {
					CLI_Cookie.erase(Cli_Data.nn_cookie_ids[k]);
				}
				CLI.generateConsent();
				return false;
			}
		);

	},
	configButtons: function () {
		/*[cookie_button] */
		this.main_button.css('color', this.settings.button_1_link_colour);
		if (Boolean(this.settings.button_1_as_button)) {
			this.main_button.css('background-color', this.settings.button_1_button_colour);

			this.main_button.on(
				'mouseenter',
				function () {
					jQuery(this).css('background-color', CLI.settings.button_1_button_hover);
				}
			)
				.on(
					'mouseleave',
					function () {
						jQuery(this).css('background-color', CLI.settings.button_1_button_colour);
					}
				);
		}

		/* [cookie_link] */
		this.main_link.css('color', this.settings.button_2_link_colour);
		if (Boolean(this.settings.button_2_as_button)) {
			this.main_link.css('background-color', this.settings.button_2_button_colour);

			this.main_link.on(
				'mouseenter',
				function () {
					jQuery(this).css('background-color', CLI.settings.button_2_button_hover);
				}
			)
				.on(
					'mouseleave',
					function () {
						jQuery(this).css('background-color', CLI.settings.button_2_button_colour);
					}
				);

		}
		/* [cookie_reject] */
		this.reject_link.css('color', this.settings.button_3_link_colour);
		if (Boolean(this.settings.button_3_as_button)) {

			this.reject_link.css('background-color', this.settings.button_3_button_colour);
			this.reject_link.on(
				'mouseenter',
				function () {
					jQuery(this).css('background-color', CLI.settings.button_3_button_hover);
				}
			)
				.on(
					'mouseleave',
					function () {
						jQuery(this).css('background-color', CLI.settings.button_3_button_colour);
					}
				);
		}
		/* [cookie_settings] */
		this.settings_button.css('color', this.settings.button_4_link_colour);
		if (Boolean(this.settings.button_4_as_button)) {
			this.settings_button.css('background-color', this.settings.button_4_button_colour);
			this.settings_button.on(
				'mouseenter',
				function () {
					jQuery(this).css('background-color', CLI.settings.button_4_button_hover);
				}
			)
				.on(
					'mouseleave',
					function () {
						jQuery(this).css('background-color', CLI.settings.button_4_button_colour);
					}
				);
		}
		/* [cookie_accept_all] */
		this.accept_all_button.css('color', this.settings.button_7_link_colour);
		if (this.settings.button_7_as_button) {
			this.accept_all_button.css('background-color', this.settings.button_7_button_colour);
			this.accept_all_button.on(
				'mouseenter',
				function () {
					jQuery(this).css('background-color', CLI.settings.button_7_button_hover);
				}
			)
				.on(
					'mouseleave',
					function () {
						jQuery(this).css('background-color', CLI.settings.button_7_button_colour);
					}
				);
		}
	},
	toggleBar: function () {
		if (CLI_COOKIEBAR_AS_POPUP) {
			this.barAsPopUp(1);
		}
		if (CLI.settings.cookie_bar_as == 'widget') {
			this.barAsWidget(1);
		}
		if (!CLI_Cookie.exists(CLI_ACCEPT_COOKIE_NAME)) {
			this.displayHeader();
		} else {
			this.hideHeader();
		}
		if (Boolean(this.settings.show_once_yn)) {
			setTimeout(
				function () {
					CLI.close_header();
				},
				CLI.settings.show_once
			);
		}
		if (CLI.js_blocking_enabled === false) {
			if (Boolean(Cli_Data.ccpaEnabled) === true) {
				if (Cli_Data.ccpaType === 'ccpa' && Boolean(Cli_Data.ccpaBarEnabled) === false) {
					cliBlocker.cookieBar(false);
				}
			} else {
				jQuery('.wt-cli-ccpa-opt-out,.wt-cli-ccpa-checkbox,.wt-cli-ccpa-element').remove();
			}
		}

		this.showagain_elm.on(
			"click",
			function (e) {
				e.preventDefault();
				CLI.showagain_elm.slideUp(
					CLI.settings.animate_speed_hide,
					function () {
						CLI.bar_elm.slideDown(CLI.settings.animate_speed_show);
						if (CLI_COOKIEBAR_AS_POPUP) {
							CLI.showPopupOverlay();
						}
					}
				);
			}
		);
	},
	configShowAgain: function () {
		this.showagain_config = {
			'background-color': this.settings.background,
			'color': this.l1hs(this.settings.text),
			'position': 'fixed',
			'font-family': this.settings.font_family
		};
		if (Boolean(this.settings.border_on)) {
			var border_to_hide = 'border-' + this.settings.notify_position_vertical;
			this.showagain_config['border'] = '1px solid ' + this.l1hs(this.settings.border);
			this.showagain_config[border_to_hide] = 'none';
		}
		var cli_win = jQuery(window);
		var cli_winw = cli_win.width();
		var showagain_x_pos = this.settings.showagain_x_position;
		if (cli_winw < 300) {
			showagain_x_pos = 10;
			this.showagain_config.width = cli_winw - 20;
		} else {
			this.showagain_config.width = 'auto';
		}
		var cli_defw = cli_winw > 400 ? 500 : cli_winw - 20;
		if (CLI_COOKIEBAR_AS_POPUP) { /* cookie bar as popup */
			var sa_pos = this.settings.popup_showagain_position;
			var sa_pos_arr = sa_pos.split('-');
			if (sa_pos_arr[1] == 'left') {
				this.showagain_config.left = showagain_x_pos;
			} else if (sa_pos_arr[1] == 'right') {
				this.showagain_config.right = showagain_x_pos;
			}
			if (sa_pos_arr[0] == 'top') {
				this.showagain_config.top = 0;

			} else if (sa_pos_arr[0] == 'bottom') {
				this.showagain_config.bottom = 0;
			}
			this.bar_config['position'] = 'fixed';

		} else if (this.settings.cookie_bar_as == 'widget') {
			this.showagain_config.bottom = 0;
			if (this.settings.widget_position == 'left') {
				this.showagain_config.left = showagain_x_pos;
			} else if (this.settings.widget_position == 'right') {
				this.showagain_config.right = showagain_x_pos;
			}
		} else {
			if (this.settings.notify_position_vertical == "top") {
				this.showagain_config.top = '0';
			} else if (this.settings.notify_position_vertical == "bottom") {
				this.bar_config['position'] = 'fixed';
				this.bar_config['bottom'] = '0';
				this.showagain_config.bottom = '0';
			}
			if (this.settings.notify_position_horizontal == "left") {
				this.showagain_config.left = showagain_x_pos;
			} else if (this.settings.notify_position_horizontal == "right") {
				this.showagain_config.right = showagain_x_pos;
			}
		}
		this.showagain_elm.css(this.showagain_config);
	},
	configBar: function () {
		this.bar_config = {
			'background-color': this.settings.background,
			'color': this.settings.text,
			'font-family': this.settings.font_family
		};
		if (this.settings.notify_position_vertical == "top") {
			this.bar_config['top'] = '0';
			if (Boolean(this.settings.header_fix) === true) {
				this.bar_config['position'] = 'fixed';
			}
		} else {
			this.bar_config['bottom'] = '0';
		}
		this.configShowAgain();
		this.bar_elm.css(this.bar_config).hide();
	},
	l1hs: function (str) {
		if (str.charAt(0) == "#") {
			str = str.substring(1, str.length);
		} else {
			return "#" + str;
		}
		return this.l1hs(str);
	},
	close_header: function () {
		CLI_Cookie.set(CLI_ACCEPT_COOKIE_NAME, 'yes', CLI_ACCEPT_COOKIE_EXPIRE);
		this.hideHeader();
	},
	accept_close: function () {
		this.hidePopupOverlay();
		this.generateConsent();
		this.cookieLawInfoRunCallBacks();

		CLI_Cookie.set(CLI_ACCEPT_COOKIE_NAME, 'yes', CLI_ACCEPT_COOKIE_EXPIRE);
		if (Boolean(this.settings.notify_animate_hide)) {
			if (CLI.js_blocking_enabled === true) {
				this.bar_elm.slideUp(this.settings.animate_speed_hide, cliBlocker.runScripts);
			} else {
				this.bar_elm.slideUp(this.settings.animate_speed_hide);
			}

		} else {
			if (CLI.js_blocking_enabled === true) {
				this.bar_elm.hide(0, cliBlocker.runScripts);

			} else {
				this.bar_elm.hide();
			}
		}
		if (Boolean(this.settings.showagain_tab)) {
			this.showagain_elm.slideDown(this.settings.animate_speed_show);
		}
		if (Boolean(this.settings.accept_close_reload) === true) {
			this.reload_current_page();
		}
		return false;
	},
	reject_close: function () {
		this.hidePopupOverlay();
		this.generateConsent();
		this.cookieLawInfoRunCallBacks();
		for (var k in Cli_Data.nn_cookie_ids) {
			CLI_Cookie.erase(Cli_Data.nn_cookie_ids[k]);
		}
		CLI_Cookie.set(CLI_ACCEPT_COOKIE_NAME, 'no', CLI_ACCEPT_COOKIE_EXPIRE);

		if (Boolean(this.settings.notify_animate_hide)) {
			if (CLI.js_blocking_enabled === true) {

				this.bar_elm.slideUp(this.settings.animate_speed_hide, cliBlocker.runScripts);

			} else {

				this.bar_elm.slideUp(this.settings.animate_speed_hide);
			}

		} else {
			if (CLI.js_blocking_enabled === true) {

				this.bar_elm.hide(cliBlocker.runScripts);

			} else {

				this.bar_elm.hide();

			}

		}
		if (Boolean(this.settings.showagain_tab)) {
			this.showagain_elm.slideDown(this.settings.animate_speed_show);
		}
		if (Boolean(this.settings.reject_close_reload) === true) {
			this.reload_current_page();
		}
		return false;
	},
	reload_current_page: function () {

		window.location.reload(true);
	},
	closeOnScroll: function () {
		if (window.pageYOffset > 100 && !CLI_Cookie.read(CLI_ACCEPT_COOKIE_NAME)) {
			CLI.accept_close();
			if (Boolean(CLI.settings.scroll_close_reload) === true) {
				window.location.reload();
			}
			window.removeEventListener("scroll", CLI.closeOnScroll, false);
		}
	},
	displayHeader: function () {
		if (Boolean(this.settings.notify_animate_show)) {
			this.bar_elm.slideDown(this.settings.animate_speed_show);
		} else {
			this.bar_elm.show();
		}
		this.showagain_elm.hide();
		if (CLI_COOKIEBAR_AS_POPUP) {
			this.showPopupOverlay();
		}
	},
	hideHeader: function () {
		if (Boolean(this.settings.showagain_tab)) {
			if (Boolean(this.settings.notify_animate_show)) {
				this.showagain_elm.slideDown(this.settings.animate_speed_show);
			} else {
				this.showagain_elm.show();
			}
		} else {
			this.showagain_elm.hide();
		}
		this.bar_elm.slideUp(this.settings.animate_speed_show);
		this.hidePopupOverlay();
	},
	hidePopupOverlay: function () {
		jQuery('body').removeClass("cli-barmodal-open");
		jQuery(".cli-popupbar-overlay").removeClass("cli-show");
	},
	showPopupOverlay: function () {
		if (this.bar_elm.length) {
			if (Boolean(this.settings.popup_overlay)) {
				jQuery('body').addClass("cli-barmodal-open");
				jQuery(".cli-popupbar-overlay").addClass("cli-show");
			}
		}

	},
	barAsWidget: function (a) {
		var cli_elm = this.bar_elm;
		cli_elm.attr('data-cli-type', 'widget');
		var cli_win = jQuery(window);
		var cli_winh = cli_win.height() - 40;
		var cli_winw = cli_win.width();
		var cli_defw = cli_winw > 400 ? 300 : cli_winw - 30;
		cli_elm.css(
			{
				'width': cli_defw, 'height': 'auto', 'max-height': cli_winh, 'overflow': 'auto', 'position': 'fixed', 'box-sizing': 'border-box'
			}
		);
		if (this.checkifStyleAttributeExist() === false) {
			cli_elm.css({ 'padding': '25px 15px' });
		}
		if (this.settings.widget_position == 'left') {
			cli_elm.css(
				{
					'left': '15px', 'right': 'auto', 'bottom': '15px', 'top': 'auto'
				}
			);
		} else {
			cli_elm.css(
				{
					'left': 'auto', 'right': '15px', 'bottom': '15px', 'top': 'auto'
				}
			);
		}
		if (a) {
			this.setResize();
		}
	},
	barAsPopUp: function (a) {
		if (typeof cookie_law_info_bar_as_popup === 'function') {
			return false;
		}
		var cli_elm = this.bar_elm;
		cli_elm.attr('data-cli-type', 'popup');
		var cli_win = jQuery(window);
		var cli_winh = cli_win.height() - 40;
		var cli_winw = cli_win.width();
		var cli_defw = cli_winw > 700 ? 500 : cli_winw - 20;

		cli_elm.css(
			{
				'width': cli_defw, 'height': 'auto', 'max-height': cli_winh, 'bottom': '', 'top': '50%', 'left': '50%', 'margin-left': (cli_defw / 2) * -1, 'margin-top': '-100px', 'overflow': 'auto'
			}
		).addClass('cli-bar-popup cli-modal-content');
		if (this.checkifStyleAttributeExist() === false) {
			cli_elm.css({ 'padding': '25px 15px' });
		}
		cli_h = cli_elm.height();
		li_h = cli_h < 200 ? 200 : cli_h;
		cli_elm.css({ 'top': '50%', 'margin-top': ((cli_h / 2) + 30) * -1 });
		setTimeout(
			function () {
				cli_elm.css(
					{
						'bottom': ''
					}
				);
			},
			100
		);
		if (a) {
			this.setResize();
		}
	},
	setResize: function () {
		var resizeTmr = null;
		jQuery(window).resize(
			function () {
				clearTimeout(resizeTmr);
				resizeTmr = setTimeout(
					function () {
						if (CLI_COOKIEBAR_AS_POPUP) {
							CLI.barAsPopUp();
						}
						if (CLI.settings.cookie_bar_as == 'widget') {
							CLI.barAsWidget();
						}
						CLI.configShowAgain();
					},
					500
				);
			}
		);
	},
	enableAllCookies: function () {

		jQuery('.cli-user-preference-checkbox').each(
			function () {
				var cli_chkbox_elm = jQuery(this);
				var cli_chkbox_data_id = cli_chkbox_elm.attr('data-id');
				if (cli_chkbox_data_id != 'checkbox-necessary') {
					cli_chkbox_elm.prop('checked', true);
					CLI_Cookie.set('cookielawinfo-' + cli_chkbox_data_id, 'yes', CLI_ACCEPT_COOKIE_EXPIRE);
				}
			}
		);
	},
	disableAllCookies: function () {
		jQuery('.cli-user-preference-checkbox').each(
			function () {

				var cli_chkbox_elm = jQuery(this);
				var cli_chkbox_data_id = cli_chkbox_elm.attr('data-id');
				cliCategorySlug = cli_chkbox_data_id.replace('checkbox-', '');
				if (Cli_Data.strictlyEnabled.indexOf(cliCategorySlug) === -1) {
					cli_chkbox_elm.prop('checked', false);
					CLI_Cookie.set('cookielawinfo-' + cli_chkbox_data_id, 'no', CLI_ACCEPT_COOKIE_EXPIRE);
				}
			}
		);
	},
	hideCookieBarOnClose: function () {
		jQuery(document).on(
			'click',
			'.cli_cookie_close_button',
			function (e) {
				e.preventDefault();
				var elm = jQuery(this);
				if (Cli_Data.ccpaType === 'ccpa') {
					CLI.enableAllCookies();
				}
				CLI.accept_close();
			}
		);
	},
	checkCategories: function () {
		var cliAllowedCategories = [];
		var cli_categories = {};
		jQuery('.cli-user-preference-checkbox').each(
			function () {
				var status = false;
				cli_chkbox_elm = jQuery(this);
				cli_chkbox_data_id = cli_chkbox_elm.attr('data-id');
				cli_chkbox_data_id = cli_chkbox_data_id.replace('checkbox-', '');
				cli_chkbox_data_id_trimmed = cli_chkbox_data_id.replace('-', '_')
				if (jQuery(cli_chkbox_elm).is(':checked')) {
					status = true;
					cliAllowedCategories.push(cli_chkbox_data_id);
				}

				cli_categories[cli_chkbox_data_id_trimmed] = status;
			}
		);
		CLI.allowedCategories = cliAllowedCategories;
	},
	cookieLawInfoRunCallBacks: function () {
		this.checkCategories();
		if (CLI_Cookie.read(CLI_ACCEPT_COOKIE_NAME) == 'yes') {
			if ("function" == typeof CookieLawInfo_Accept_Callback) {
				CookieLawInfo_Accept_Callback();
			}
		}
	},
	generateConsent: function () {
		var preferenceCookie = CLI_Cookie.read(CLI_PREFERNCE_COOKIE);
		cliConsent = {};
		if (preferenceCookie !== null) {
			cliConsent = window.atob(preferenceCookie);
			cliConsent = JSON.parse(cliConsent);
		}
		cliConsent.ver = Cli_Data.consentVersion;
		categories = [];
		jQuery('.cli-user-preference-checkbox').each(
			function () {
				categoryVal = '';
				cli_chkbox_data_id = jQuery(this).attr('data-id');
				cli_chkbox_data_id = cli_chkbox_data_id.replace('checkbox-', '');
				if (jQuery(this).is(':checked')) {
					categoryVal = true;
				} else {
					categoryVal = false;
				}
				cliConsent[cli_chkbox_data_id] = categoryVal;
			}
		);
		cliConsent = JSON.stringify(cliConsent);
		cliConsent = window.btoa(cliConsent);
		CLI_Cookie.set(CLI_PREFERNCE_COOKIE, cliConsent, CLI_ACCEPT_COOKIE_EXPIRE);
	},
	addStyleAttribute: function () {
		var bar = this.bar_elm;
		var styleClass = '';
		if (jQuery(bar).find('.cli-bar-container').length > 0) {
			styleClass = jQuery('.cli-bar-container').attr('class');
			styleClass = styleClass.replace('cli-bar-container', '');
			styleClass = styleClass.trim();
			jQuery(bar).attr('data-cli-style', styleClass);
		}
	},
	getParameterByName: function (name, url) {
		if (!url) {
			url = window.location.href;
		}
		name = name.replace(/[\[\]]/g, '\\$&');
		var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
			results = regex.exec(url);
		if (!results) {
			return null;
		}
		if (!results[2]) {
			return '';
		}
		return decodeURIComponent(results[2].replace(/\+/g, ' '));
	},
	CookieLawInfo_Callback: function (enableBar, enableBlocking) {
		enableBar = typeof enableBar !== 'undefined' ? enableBar : true;
		enableBlocking = typeof enableBlocking !== 'undefined' ? enableBlocking : true;
		if (CLI.js_blocking_enabled === true && Boolean(Cli_Data.custom_integration) === true) {
			cliBlocker.cookieBar(enableBar);
			cliBlocker.runScripts(enableBlocking);
		}
	},
	checkifStyleAttributeExist: function () {
		var exist = false;
		var attr = this.bar_elm.attr('data-cli-style');
		if (typeof attr !== typeof undefined && attr !== false) {
			exist = true;
		}
		return exist;
	},
	reviewConsent: function () {
		jQuery(document).on(
			'click',
			'.cli_manage_current_consent,.wt-cli-manage-consent-link',
			function () {
				CLI.displayHeader();
			}
		);
	},
	mayBeSetPreferenceCookie: function () {
		if (CLI.getParameterByName('cli_bypass') === "1") {
			CLI.generateConsent();
		}
	}
}
var cliBlocker =
{
	blockingStatus: true,
	scriptsLoaded: false,
	ccpaEnabled: false,
	ccpaRegionBased: false,
	ccpaApplicable: false,
	ccpaBarEnabled: false,
	cliShowBar: true,
	isBypassEnabled: CLI.getParameterByName('cli_bypass'),
	checkPluginStatus: function (callbackA, callbackB) {
		this.ccpaEnabled = Boolean(Cli_Data.ccpaEnabled);
		this.ccpaRegionBased = Boolean(Cli_Data.ccpaRegionBased);
		this.ccpaBarEnabled = Boolean(Cli_Data.ccpaBarEnabled);

		if (Boolean(Cli_Data.custom_integration) === true) {
			callbackA(false);
		} else {
			if (this.ccpaEnabled === true) {
				this.ccpaApplicable = true;
				if (Cli_Data.ccpaType === 'ccpa') {
					if (this.ccpaBarEnabled !== true) {
						this.cliShowBar = false;
						this.blockingStatus = false;
					}
				}
			} else {
				jQuery('.wt-cli-ccpa-opt-out,.wt-cli-ccpa-checkbox,.wt-cli-ccpa-element').remove();
			}
			if (cliBlocker.isBypassEnabled === "1") {
				cliBlocker.blockingStatus = false;
			}
			callbackA(this.cliShowBar);
			callbackB(this.blockingStatus);
		}

	},
	cookieBar: function (showbar) {
		showbar = typeof showbar !== 'undefined' ? showbar : true;
		cliBlocker.cliShowBar = showbar;
		if (cliBlocker.cliShowBar === false) {
			CLI.bar_elm.hide();
			CLI.showagain_elm.hide();
			CLI.settingsModal.removeClass('cli-blowup cli-out');
			CLI.hidePopupOverlay();
			jQuery(".cli-settings-overlay").removeClass("cli-show");
		} else {
			if (!CLI_Cookie.exists(CLI_ACCEPT_COOKIE_NAME)) {
				CLI.displayHeader();
			} else {
				CLI.hideHeader();
			}
		}
	},
	removeCookieByCategory: function () {

		if (cliBlocker.blockingStatus === true) {
			if (CLI_Cookie.read(CLI_ACCEPT_COOKIE_NAME) !== null) {
				var non_necessary_cookies = Cli_Data.non_necessary_cookies;
				for (var key in non_necessary_cookies) {
					currentCategory = key;
					if (CLI.allowedCategories.indexOf(currentCategory) === -1) {
						var nonNecessaryCookies = non_necessary_cookies[currentCategory];
						for (var i = 0; i < nonNecessaryCookies.length; i++) {
							if (CLI_Cookie.read(nonNecessaryCookies[i]) !== null) {
								CLI_Cookie.erase(nonNecessaryCookies[i]);
							}

						}
					}
				}
			}
		}
	},
	runScripts: function (blocking) {
		blocking = typeof blocking !== 'undefined' ? blocking : true;
		cliBlocker.blockingStatus = blocking;
		srcReplaceableElms = ['iframe', 'IFRAME', 'EMBED', 'embed', 'OBJECT', 'object', 'IMG', 'img'];
		var genericFuncs =
		{

			renderByElement: function (callback) {
				cliScriptFuncs.renderScripts();
				callback();
				cliBlocker.scriptsLoaded = true;
			},

		};
		var cliScriptFuncs =
		{
			// trigger DOMContentLoaded
			scriptsDone: function () {
				if (typeof Cli_Data.triggerDomRefresh !== 'undefined') {
					if (Boolean(Cli_Data.triggerDomRefresh) === true) {
						var DOMContentLoadedEvent = document.createEvent('Event')
						DOMContentLoadedEvent.initEvent('DOMContentLoaded', true, true)
						window.document.dispatchEvent(DOMContentLoadedEvent);
					}
				}
			},
			seq: function (arr, callback, index) {
				// first call, without an index
				if (typeof index === 'undefined') {
					index = 0
				}

				arr[index](
					function () {
						index++
						if (index === arr.length) {
							callback()
						} else {
							cliScriptFuncs.seq(arr, callback, index)
						}
					}
				)
			},
			/* script runner */
			insertScript: function ($script, callback) {
				var s = '';
				var scriptType = $script.getAttribute('data-cli-script-type');
				var elementPosition = $script.getAttribute('data-cli-element-position');
				var isBlock = $script.getAttribute('data-cli-block');
				var s = document.createElement('script');
				var ccpaOptedOut = cliBlocker.ccpaOptedOut();
				s.type = 'text/plain';
				if ($script.async) {
					s.async = $script.async;
				}
				if ($script.defer) {
					s.defer = $script.defer;
				}
				if ($script.src) {
					s.onload = callback
					s.onerror = callback
					s.src = $script.src
				} else {
					s.textContent = $script.innerText
				}
				var attrs = jQuery($script).prop("attributes");
				for (var ii = 0; ii < attrs.length; ++ii) {
					if (attrs[ii].nodeName !== 'id') {
						s.setAttribute(attrs[ii].nodeName, attrs[ii].value);
					}
				}
				if (cliBlocker.blockingStatus === true) {

					if ((CLI_Cookie.read(CLI_ACCEPT_COOKIE_NAME) == 'yes' && CLI.allowedCategories.indexOf(scriptType) !== -1)) {
						s.setAttribute('data-cli-consent', 'accepted');
						s.type = 'text/javascript';
					}
					if (cliBlocker.ccpaApplicable === true) {
						if (ccpaOptedOut === true || CLI_Cookie.read(CLI_ACCEPT_COOKIE_NAME) == null) {
							s.type = 'text/plain';
						}
					}
				} else {
					s.type = 'text/javascript';
				}

				if ($script.type != s.type) {
					if (elementPosition === 'head') {
						document.head.appendChild(s);
					} else {
						document.body.appendChild(s);
					}
					if (!$script.src) {
						callback()
					}
					$script.parentNode.removeChild($script);

				} else {

					callback();
				}
			},
			renderScripts: function () {
				var $scripts = document.querySelectorAll('script[data-cli-class="cli-blocker-script"]');
				if ($scripts.length > 0) {
					var runList = []
					var typeAttr
					Array.prototype.forEach.call(
						$scripts,
						function ($script) {
							// only run script tags without the type attribute
							// or with a javascript mime attribute value
							typeAttr = $script.getAttribute('type')
							runList.push(
								function (callback) {
									cliScriptFuncs.insertScript($script, callback)
								}
							)
						}
					)
					cliScriptFuncs.seq(runList, cliScriptFuncs.scriptsDone);
				}
			}
		};
		genericFuncs.renderByElement(cliBlocker.removeCookieByCategory);
	},
	ccpaOptedOut: function () {
		var ccpaOptedOut = false;
		var preferenceCookie = CLI_Cookie.read(CLI_PREFERNCE_COOKIE);
		if (preferenceCookie !== null) {
			cliConsent = window.atob(preferenceCookie);
			cliConsent = JSON.parse(cliConsent);
			if (typeof cliConsent.ccpaOptout !== 'undefined') {
				ccpaOptedOut = cliConsent.ccpaOptout;
			}
		}
		return ccpaOptedOut;
	}
}
jQuery(document).ready(
	function () {
		if (typeof cli_cookiebar_settings != 'undefined') {
			CLI.set(
				{
					settings: cli_cookiebar_settings
				}
			);
			if (CLI.js_blocking_enabled === true) {
				cliBlocker.checkPluginStatus(cliBlocker.cookieBar, cliBlocker.runScripts);
			}
		}
	}
);
