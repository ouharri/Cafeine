<?php

namespace WeglotWP\Actions\Front;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Flag_Type;
use WeglotWP\Models\Hooks_Interface_Weglot;
use WeglotWP\Services\Option_Service_Weglot;

/**
 * Enqueue CSS / JS on front
 *
 * @since 2.0
 */
class Front_Enqueue_Weglot implements Hooks_Interface_Weglot {
	/**
	 * @var Option_Service_Weglot
	 */
	private $option_services;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services = weglot_get_service( 'Option_Service_Weglot' );
	}

	/**
	 * @return void
	 * @since 2.0
	 * @see Hooks_Interface_Weglot
	 *
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'weglot_wp_enqueue_scripts' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'weglot_wp_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'weglot_pageviews_script' ) );
	}

	/**
	 *
	 * @return string
	 * @since 2.0
	 */
	public function weglot_pageviews_script() {
		$options = $this->option_services->get_options();
		if ( $options['page_views_enabled'] ) {
			?>
			<script>
				var request = new XMLHttpRequest();
				var url = 'ht' + 'tps:' + '//' + 'cdn-api.weglot.com/' + 'pageviews?api_key=' + '<?php echo esc_js( $options['api_key'] )?>';
				var data = JSON.stringify({
						url: location.protocol + '//' + location.host + location.pathname,
						language: document.getElementsByTagName('html')[0].getAttribute('lang'),
						browser_language: (navigator.language || navigator.userLanguage)
					}
				);
				request.open('POST', url , true);
				request.send(data);
			</script>
		<?php }
	}

	/**
	 * @return void
	 * @since 2.0
	 *
	 * @see wp_enqueue_scripts
	 */
	public function weglot_wp_enqueue_scripts() {
		// Add JS
		wp_register_script( 'wp-weglot-js', WEGLOT_URL_DIST . '/front-js.js', false, WEGLOT_VERSION, false );
		wp_enqueue_script( 'wp-weglot-js' );

		// Add CSS
		wp_register_style( 'weglot-css', WEGLOT_URL_DIST . '/css/front-css.css', false, WEGLOT_VERSION, false );
		wp_enqueue_style( 'weglot-css' );

		//display new flags
		if ( empty( $this->option_services->get_option( 'flag_css' ) )
		     && strpos( $this->option_services->get_css_custom_inline(), 'background-position' ) == false
		     && strpos( $this->option_services->get_css_custom_inline(), 'background-image' ) == false ) {
			Helper_Flag_Type::get_new_flags();
		}

		wp_add_inline_style( 'weglot-css', $this->option_services->get_flag_css() );
		wp_add_inline_style( 'weglot-css', $this->option_services->get_css_custom_inline() );
		wp_add_inline_style( 'weglot-css', $this->option_services->get_switcher_editor_css() );
	}
}
