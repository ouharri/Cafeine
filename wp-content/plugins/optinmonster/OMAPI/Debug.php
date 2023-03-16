<?php
/**
 * Output class.
 *
 * @since 2.6.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output class.
 *
 * @since 2.6.0
 */
class OMAPI_Debug {

	/**
	 * Check if rules debug can be output.
	 *
	 * @since  2.0.0
	 *
	 * @return bool
	 */
	public static function can_output_debug() {
		$rules_debug = ! empty( $_GET['omwpdebug'] ) ? wp_unslash( $_GET['omwpdebug'] ) : '';

		if ( $rules_debug ) {
			$omapi         = OMAPI::get_instance();
			$disable       = 'off' === $rules_debug;
			$decoded       = base64_decode( base64_decode( $rules_debug ) );
			$debug_enabled = $omapi->get_option( 'api', 'omwpdebug' );
			$creds         = $omapi->get_api_credentials();
			if (
				! empty( $creds['apikey'] )
				&& ( $decoded === $creds['apikey'] || $disable )
			) {

				$option = $omapi->get_option();

				if ( $disable ) {
					unset( $option['api']['omwpdebug'] );
					$debug_enabled = false;
				} else {
					$option['api']['omwpdebug'] = true;
					$debug_enabled              = true;
				}
				update_option( 'optin_monster_api', $option );
			}

			$rules_debug = $debug_enabled || is_user_logged_in() && $omapi->can_access( 'rules_debug' );
		}

		// If query var is set and user can manage OM, output debug data.
		return apply_filters( 'optin_monster_api_should_output_rules_debug', ! empty( $rules_debug ) );
	}

	/**
	 * Outputs general debug rule data.
	 *
	 * Borrowed heavily from Query Monitor plugin.
	 *
	 * @see https://github.com/johnbillion/query-monitor/blob/develop/collectors/conditionals.php#L25-L100
	 *
	 * @since 2.6.0
	 *
	 * @return void
	 */
	public static function output_general() {
		$results = array();

		$post_types = array_keys( get_post_types( array( 'public' => true ), 'names' ) );
		foreach ( $post_types as $post_type ) {
			$results[ is_singular( $post_type ) ? 'TRUE' : 'FALSE' ][] = "is_singular('{$post_type}')";
		}

		$conditionals = array(
			'is_404',
			'is_admin',
			'is_archive',
			'is_attachment',
			'is_author',
			'is_blog_admin',
			'is_category',
			'is_comment_feed',
			'is_customize_preview',
			'is_date',
			'is_day',
			'is_embed',
			'is_favicon',
			'is_feed',
			'is_front_page',
			'is_home',
			'is_main_network',
			'is_main_site',
			'is_month',
			'is_network_admin',
			'is_page',
			'is_page_template',
			'is_paged',
			'is_post_type_archive',
			'is_preview',
			'is_privacy_policy',
			'is_robots',
			'is_rtl',
			'is_search',
			'is_single',
			'is_singular',
			'is_ssl',
			'is_sticky',
			'is_tag',
			'is_tax',
			'is_time',
			'is_trackback',
			'is_user_admin',
			'is_year',
		);

		foreach ( $conditionals as $conditional ) {
			if ( ! function_exists( $conditional ) ) {
				$results['N/A'][] = $conditional;
				break;
			}

			// Special case for is_sticky to prevent PHP notices
			$id = null;
			if ( ( 'is_sticky' === $conditional ) && ! get_post( $id ) ) {
				$results['FALSE'][] = $conditional;
				break;
			}

			// Special case for multisite $conditionals to prevent them from
			// being annoying on single site installations
			if ( ! is_multisite() && in_array( $conditional, array( 'is_main_network', 'is_main_site' ), true ) ) {
				$results['N/A'][] = $conditional;
				break;
			}

			// Default case.
			$results[ call_user_func( $conditional ) ? 'TRUE' : 'FALSE' ][] = $conditional;
		}

		$results[ OMAPI_Utils::is_front_or_search() ? 'TRUE' : 'FALSE' ][] = 'is_front_or_search';

		sort( $results['FALSE'] );
		sort( $results['TRUE'] );

		?>
		<hr style="padding-top:15px;border-top:10px double red;"/>
		<div style="padding:20px;margin:20px;">
			<button type="button" onclick="javascript:this.parentElement.remove();document.querySelectorAll('._om-debugging').forEach(el => el.style.display = 'block')" class="button btn">
				Show Verbose Debugging Info
			</button>
		</div>
		<xmp class="_om-debugging _om-optin">$conditionals: <?php print_r( $results ); ?></xmp>
		<?php
	}

}
