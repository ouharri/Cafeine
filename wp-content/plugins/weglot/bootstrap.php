<?php // phpcs:ignore

if (!defined('ABSPATH')) {
	exit;
}

use WeglotWP\Bootstrap_Weglot;


/**
 * Only use for get one context
 *
 * @since 2.0
 */
abstract class Context_Weglot
{

	/**
	 * @static
	 * @since 2.0
	 * @var Bootstrap_Weglot|null
	 */
	protected static $context;

	/**
	 * Create context if not exist
	 *
	 * @static
	 * @return void
	 * @since 2.0
	 */
	public static function weglot_get_context()
	{
		if (null !== self::$context) {
			return self::$context;
		}

		self::$context = new Bootstrap_Weglot();

		// If PHP > 5.6, it will be possible to autoload the classes without listing them
		$services = array(
			'\WeglotWP\Services\Button_Service_Weglot',
			'\WeglotWP\Services\Request_Url_Service_Weglot',
			'\WeglotWP\Services\Option_Service_Weglot',
			'\WeglotWP\Services\Redirect_Service_Weglot',
			'\WeglotWP\Services\Language_Service_Weglot',
			'\WeglotWP\Services\Replace_Url_Service_Weglot',
			'\WeglotWP\Services\Multisite_Service_Weglot',
			'\WeglotWP\Services\Replace_Link_Service_Weglot',
			'\WeglotWP\Services\Parser_Service_Weglot',
			'\WeglotWP\Services\User_Api_Service_Weglot',
			'\WeglotWP\Services\Dom_Checkers_Service_Weglot',
			'\WeglotWP\Services\Regex_Checkers_Service_Weglot',
			'\WeglotWP\Services\Generate_Switcher_Service_Weglot',
			'\WeglotWP\Services\Email_Translate_Service_Weglot',
			'\WeglotWP\Services\Pdf_Translate_Service_Weglot',
			'\WeglotWP\Services\Translate_Service_Weglot',
			'\WeglotWP\Services\Private_Language_Service_Weglot',
			'\WeglotWP\Services\Href_Lang_Service_Weglot',
			'\WeglotWP\Services\Menu_Options_Service_Weglot',

			'\WeglotWP\Third\Amp\Amp_Service_Weglot',
			'\WeglotWP\Third\Calderaforms\Caldera_Active',
			'\WeglotWP\Third\Edd\Edd_Active',
			'\WeglotWP\Third\Gravityforms\Gf_Active',
			'\WeglotWP\Third\NinjaForms\Ninja_Active',
			'\WeglotWP\Third\Woocommerce\Wc_Active',
			'\WeglotWP\Third\Woocommercepdf\Wcpdf_Active',
			'\WeglotWP\Third\WPForms\Wpforms_Active',
			'\WeglotWP\Third\UnderConstructionPage\Ucp_Active',
			'\WeglotWP\Third\Maintenance\Maintenance_Active',
			'\WeglotWP\Third\TheEventsCalendar\Theeventscalendar_Active',
			'\WeglotWP\Third\MailOptin\Mailoptin_Active',
			'\WeglotWP\Third\ContactForm7\Contactform7_Active',
			'\WeglotWP\Third\WpOptimize\Wp_Optimize_Active',
			'\WeglotWP\Third\CacheEnabler\Cache_Enabler_Active',
			'\WeglotWP\Third\Wprocket\Wprocket_Active',
		);

		self::$context->set_services($services);

		// If PHP > 5.6, it will be possible to autoload the classes without listing them
		$actions = array(
			'\WeglotWP\Actions\Email_Translate_Weglot',
			'\WeglotWP\Actions\Register_Widget_Weglot',
			'\WeglotWP\Actions\Admin\Pages_Weglot',
			'\WeglotWP\Actions\Admin\Plugin_Links_Weglot',
			'\WeglotWP\Actions\Admin\Options_Weglot',
			'\WeglotWP\Actions\Admin\Admin_Enqueue_Weglot',
			'\WeglotWP\Actions\Admin\Customize_Menu_Weglot',
			'\WeglotWP\Actions\Admin\Permalink_Weglot',
			'\WeglotWP\Actions\Admin\Metabox_Url_Translate_Weglot',
			'\WeglotWP\Actions\Front\Translate_Page_Weglot',
			'\WeglotWP\Actions\Front\Front_Enqueue_Weglot',
			'\WeglotWP\Actions\Front\Shortcode_Weglot',
			'\WeglotWP\Actions\Front\Redirect_Log_User_Weglot',
			'\WeglotWP\Actions\Migration_Weglot',
			'\WeglotWP\Actions\Front\Front_Menu_Weglot',
			'\WeglotWP\Actions\Front\Search_Weglot',
			'\WeglotWP\Actions\Front\Redirect_Comment',
			'\WeglotWP\Actions\Admin\Ajax_User_Info',
			'\WeglotWP\Actions\Front\Clean_Options',

			'\WeglotWP\Third\Amp\Amp_Enqueue_Weglot',
			'\WeglotWP\Third\Calderaforms\Caldera_I18n_Inline',
			'\WeglotWP\Third\Edd\Edd_Filter_Urls',
			'\WeglotWP\Third\Gravityforms\GF_Filter_Urls',
			'\WeglotWP\Third\Woocommerce\WC_Filter_Urls_Weglot',
			'\WeglotWP\Third\Woocommerce\WC_Cart_Reload_Weglot',
			'\WeglotWP\Third\Woocommerce\WC_Mail_Weglot',
			'\WeglotWP\Third\Woocommerce\WC_Mail_Weglot',
			'\WeglotWP\Third\Woocommercepdf\WCPDF_Weglot',
			'\WeglotWP\Third\UnderConstructionPage\Ucp_Tracking',
			'\WeglotWP\Third\Maintenance\Maintenance_Tracking',
			'\WeglotWP\Third\TheEventsCalendar\Theeventscalendar_Words',
			'\WeglotWP\Third\Contactform7\Contactform7_Json_Keys',
			'\WeglotWP\Third\WpOptimize\Wp_Optimize_Cache',
			'\WeglotWP\Third\CacheEnabler\Cache_Enabler_Cache',
			'\WeglotWP\Third\Wprocket\Wprocket_Cache',
		);

		self::$context->set_actions($actions);

		return self::$context;
	}
}


/**
 * Init plugin
 * @return void
 * @version 2.0.1
 * @since 2.0
 */
function weglot_init()
{
	//add filter to prevent load weglot if not needed
	$cancel_init = apply_filters( 'weglot_cancel_init', false );

	if( $cancel_init ){
		return;
	}

	if (!function_exists('curl_version') || !function_exists('curl_exec')) {
		add_action('admin_notices', array('\WeglotWP\Notices\Curl_Weglot', 'admin_notice'));
	}

	if (!function_exists('json_last_error')) {
		add_action('admin_notices', array('\WeglotWP\Notices\Json_Function_Weglot', 'admin_notice'));
	}

	load_plugin_textdomain('weglot', false, WEGLOT_DIR_LANGUAGES);

	Context_Weglot::weglot_get_context()->init_plugin();
}
