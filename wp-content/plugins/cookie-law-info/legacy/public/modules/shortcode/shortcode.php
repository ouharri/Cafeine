<?php
/**
 * If this file is called directly, abort.
 * */
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*
	===============================================================================

	Copyright 2018 @ WebToffee

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
class Cookie_Law_Info_Shortcode {

	public $plugin_name;

	public $cookie_options;

	public function __construct( $parent_obj ) {
		$this->plugin_name = 'cookie-law-info';
		// Shortcodes.
		add_shortcode( 'delete_cookies', array( $this, 'cookielawinfo_delete_cookies_shortcode' ) ); // a shortcode [delete_cookies (text="Delete Cookies")]
		add_shortcode( 'cookie_audit', array( $this, 'cookielawinfo_table_shortcode' ) );           // a shortcode [cookie_audit style="winter"]
		add_shortcode( 'cookie_accept', array( $this, 'cookielawinfo_shortcode_accept_button' ) );      // a shortcode [cookie_accept (colour="red")]
		add_shortcode( 'cookie_reject', array( $this, 'cookielawinfo_shortcode_reject_button' ) );      // a shortcode [cookie_reject (colour="red")]
		add_shortcode( 'cookie_settings', array( $this, 'cookielawinfo_shortcode_settings_button' ) );      // a shortcode [cookie_settings]
		add_shortcode( 'cookie_link', array( $this, 'cookielawinfo_shortcode_more_link' ) );            // a shortcode [cookie_link]
		add_shortcode( 'cookie_button', array( $this, 'cookielawinfo_shortcode_main_button' ) );        // a shortcode [cookie_button]
		add_shortcode( 'cookie_after_accept', array( $this, 'cookie_after_accept_shortcode' ) );
		add_shortcode( 'user_consent_state', array( $this, 'user_consent_state_shortcode' ) );
		add_shortcode( 'webtoffee_powered_by', array( $this, 'wf_powered_by' ) );
		add_shortcode( 'cookie_close', array( $this, 'cookielawinfo_shortcode_close_button' ) );        // a shortcode [close_button]
		add_shortcode( 'wt_cli_manage_consent', array( $this, 'manage_consent' ) );
		add_shortcode( 'cookie_accept_all', array( $this, 'accept_all_button' ) );      // a shortcode [cookie_button]

	}

	/*
	*   Powered by WebToffe
	*   @since 1.7.4
	*/
	public function wf_powered_by() {
		return '<p class="powered_by_p" style="width:100% !important; display:block !important; color:#333; clear:both; font-style:italic !important; font-size:12px !important; margin-top:15px !important;">Powered By <a href="https://www.webtoffee.com/" class="powered_by_a" style="color:#333; font-weight:600 !important; font-size:12px !important;">WebToffee</a></p>';
	}

	/*
	*   User can manage his current consent. This function is used in [user_consent_state] shortcode
	*   @since 1.7.4
	*/
	public function manage_user_consent_jsblock() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.cli_manage_current_consent').click(function(){
					jQuery('#cookie-law-info-again').click();
					setTimeout(function(){
						if( jQuery('#cookie-law-info-bar').length > 0) {
							jQuery('html, body').animate({
								scrollTop: jQuery("#cookie-law-info-bar").offset().top
							}, 300);
						}
					},100);
				});
			});
		</script>
		<?php
	}

	/*
	*   Show current user's consent state
	*   @since 1.7.4
	*/
	public function user_consent_state_shortcode( $atts = array() ) {
		add_action( 'wp_footer', array( $this, 'manage_user_consent_jsblock' ), 15 );

		$html = '<div class="cli_user_consent_state">' . __( 'Your current state:', 'cookie-law-info' );
		if ( isset( $_COOKIE['viewed_cookie_policy'] ) ) {
			if ( $_COOKIE['viewed_cookie_policy'] == 'yes' ) {
				$html .= ' ' . __( 'Consent accepted.', 'cookie-law-info' );
			} else {
				$html .= ' ' . __( 'Consent rejected.', 'cookie-law-info' );
			}
		} else // no conset given
		{
			$html .= ' ' . __( 'No consent given.', 'cookie-law-info' );
		}
		$html .= ' <a class="cli_manage_current_consent" style="cursor:pointer;text-decoration:underline;">' . __( 'Manage your consent.', 'cookie-law-info' ) . '</a> </div>';
		return $html;
	}

	/*
	*   Add content after accepting the cookie notice.
	*   Usage :
	*			Inside post editor
	*			[cookie_after_accept] ...Your content goes here...  [/cookie_after_accept]
	*			Inside template
	*			<?php echo do_shortcode('...shortcode goes here...'); ?>
	*/
	public function cookie_after_accept_shortcode( $atts = array(), $content = '' ) {
		if ( isset( $_COOKIE['viewed_cookie_policy'] ) && $_COOKIE['viewed_cookie_policy'] == 'yes' ) {
			return $content;
		} else {
			return '';
		}
	}


	/*
	A shortcode that outputs a link which will delete the cookie used to track
	whether or not a vistor has dismissed the header message (i.e. so it doesn't
	keep on showing on all pages)

	Usage: [delete_cookies]
			[delete_cookies linktext="delete cookies"]

	N.B. This shortcut does not block cookies, or delete any other cookies!
	 */
	public function cookielawinfo_delete_cookies_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'text' => __( 'Delete Cookies', 'cookie-law-info' ),
			),
			$atts,
			'delete_cookies'
		);
		return '<a href="" class="cookielawinfo-cookie-delete">' . esc_attr( $atts['text'] ) . '</a>';
	}


	/**
	 * A nice shortcode to output a table of cookies you have saved, output in ascending
	 * alphabetical order. If there are no cookie records found a single empty row is shown.
	 * You can customise the 'not shown' message (see commented code below)
	 * N.B. This only shows the information you entered on the "cookie" admin page, it
	 * does not necessarily mean you comply with the cookie law. It is up to you, or
	 * the website owner, to make sure you have conducted an appropriate cookie audit
	 * and are informing website visitors of the actual cookies that are being stored.
	 *
	 * Usage:                 [cookie_audit]
	 *                   [cookie_audit style="winter"]
	 *                   [cookie_audit not_shown_message="No records found"]
	 *                   [cookie_audit style="winter" not_shown_message="Not found"]
	 *
	 *   Styles included:       simple, classic, modern, rounded, elegant, winter.
	 *                       Default style applied: classic.
	 *
	 *   *Additional styles:     You can customise the CSS by editing the CSS file itself,
	 *                   included with plugin.
	 */
	public function cookielawinfo_table_shortcode( $atts ) {

		/** RICHARDASHBY EDIT: only add CSS if table is being used */
		wp_enqueue_style( $this->plugin_name . '-table' );
		/** END EDIT */
		$atts    = shortcode_atts(
			array(
				'style'             => 'classic',
				'not_shown_message' => '',
				'columns'           => 'cookie,type,duration,description',
				'heading'           => '',
				'category'          => '',
			),
			$atts,
			'cookie_audit'
		);
		$columns = array_filter( array_map( 'trim', explode( ',', $atts['columns'] ) ) );
		$posts   = array();
		$args    = array(
			'post_type'      => CLI_POST_TYPE,
			/** 28/05/2013: Changing from 10 to 50 to allow longer tables of cookie data */
			'posts_per_page' => 50,
			'tax_query'      => array(),
			'order'          => 'ASC',
			'orderby'        => 'title',
		);
		global $sitepress;
		$is_wpml_enabled = false;
		if ( function_exists( 'icl_object_id' ) && $sitepress ) {
			$args['suppress_filters'] = false;
			$is_wpml_enabled          = true;
		}
		$category = isset( $atts['category'] ) ? $atts['category'] : '';
		if ( isset( $category ) && $category != '' ) {
			$wpml_default_lang = 'en';
			$wpml_current_lang = 'en';
			$term              = false;
			if ( $is_wpml_enabled ) {
				$wpml_default_lang = $sitepress->get_default_language();
				$wpml_current_lang = ICL_LANGUAGE_CODE;
				if ( $wpml_default_lang != $wpml_current_lang ) {
					$sitepress->switch_lang( $wpml_default_lang ); // switching to default lang
					$term = get_term_by( 'slug', $category, 'cookielawinfo-category' ); // original term
					$sitepress->switch_lang( $wpml_current_lang ); // revert back to current lang
					if ( ! $term ) {
						$term = get_term_by( 'slug', $category, 'cookielawinfo-category' ); // current lang term
					}
				} else {
					$term = get_term_by( 'slug', $category, 'cookielawinfo-category' );
				}
			} else {
				$term = get_term_by( 'slug', $category, 'cookielawinfo-category' );
			}
			if ( $term ) {
				$args['tax_query'][] = array(
					'taxonomy'         => 'cookielawinfo-category',
					'terms'            => $term->term_id,
					'include_children' => false,
				);
				$posts               = get_posts( $args ); // only return posts if term available
			}
		} else {
			$posts = get_posts( $args );
		}
		$ret = '<table class="cookielawinfo-row-cat-table cookielawinfo-' . esc_attr( $atts['style'] ) . '"><thead><tr>';
		if ( in_array( 'cookie', $columns ) ) {
			$ret .= '<th class="cookielawinfo-column-1">' . __( 'Cookie', 'cookie-law-info' ) . '</th>';
		}
		if ( in_array( 'type', $columns ) ) {
			$ret .= '<th class="cookielawinfo-column-2">' . __( 'Type', 'cookie-law-info' ) . '</th>';
		}
		if ( in_array( 'duration', $columns ) ) {
			$ret .= '<th class="cookielawinfo-column-3">' . __( 'Duration', 'cookie-law-info' ) . '</th>';
		}
		if ( in_array( 'description', $columns ) ) {
			$ret .= '<th class="cookielawinfo-column-4">' . __( 'Description', 'cookie-law-info' ) . '</th>';
		}
		$ret  = apply_filters( 'cli_new_columns_to_audit_table', $ret );
		$ret .= '</tr>';
		$ret .= '</thead><tbody>';

		if ( ! $posts ) {
			$ret .= '<tr class="cookielawinfo-row"><td colspan="4" class="cookielawinfo-column-empty">' . esc_html( $atts['not_shown_message'] ) . '</td></tr>';
		}

		// Get custom fields:
		if ( $posts ) {
			foreach ( $posts as $post ) {
				$custom          = get_post_custom( $post->ID );
				$cookie_type     = ( isset( $custom['_cli_cookie_type'][0] ) ) ? esc_html( sanitize_text_field( $custom['_cli_cookie_type'][0] ) ) : '';
				$cookie_duration = ( isset( $custom['_cli_cookie_duration'][0] ) ) ? esc_html( sanitize_text_field( $custom['_cli_cookie_duration'][0] ) ) : '';
				$ret            .= '<tr class="cookielawinfo-row">';
				if ( in_array( 'cookie', $columns ) ) {
					$ret .= '<td class="cookielawinfo-column-1">' . esc_html( sanitize_text_field( $post->post_title ) ) . '</td>';
				}
				if ( in_array( 'type', $columns ) ) {
					$ret .= '<td class="cookielawinfo-column-2">' . esc_html( $cookie_type ) . '</td>';
				}
				if ( in_array( 'duration', $columns ) ) {
					$ret .= '<td class="cookielawinfo-column-3">' . esc_html( $cookie_duration ) . '</td>';
				}
				if ( in_array( 'description', $columns ) ) {
					$ret .= '<td class="cookielawinfo-column-4">' . wp_kses_post( $post->post_content ) . '</td>';
				}
				$ret  = apply_filters( 'cli_new_column_values_to_audit_table', $ret, $custom );
				$ret .= '</tr>';
			}
		}
		$ret .= '</tbody></table>';
		if ( count( $posts ) > 0 ) {
			if ( $atts['heading'] != '' ) {
				$ret = '<p>' . esc_html( __( $atts['heading'], 'cookie-law-info' ) ) . '</p>' . $ret;
			}
		}
		if ( '' === $atts['not_shown_message'] && empty( $posts ) ) {
			$ret = '';
		}
		return $ret;
	}

	/**
	 *   Returns HTML for a standard (green, medium sized) 'Accept' button
	 */
	public function cookielawinfo_shortcode_accept_button( $atts ) {
		$atts          = shortcode_atts(
			array(
				'colour' => 'green',
				'margin' => '',
			),
			$atts,
			'cookie_accept'
		);
		$defaults      = Cookie_Law_Info::get_default_settings( 'button_1_text' );
		$settings      = wp_parse_args( Cookie_Law_Info::get_settings(), $defaults );
		$button_1_text = __( $settings['button_1_text'], 'cookie-law-info' );
		$margin_style  = $atts['margin'] != '' ? ' style="margin:' . esc_attr( $atts['margin'] ) . ';" ' : '';
		return '<a role="button" tabindex="0" class="cli_action_button cli-accept-button medium cli-plugin-button ' . esc_attr( $atts['colour'] ) . '" data-cli_action="accept"' . $margin_style . '>' . esc_html( stripslashes( $button_1_text ) ) . '</a>';
	}

	/** Returns HTML for a standard (green, medium sized) 'Reject' button */
	public function cookielawinfo_shortcode_reject_button( $atts ) {
		$atts = shortcode_atts(
			array(
				'margin' => '',
			),
			$atts,
			'cookie_reject'
		);

		$margin_style = $atts['margin'] != '' ? ' style="margin:' . esc_attr( $atts['margin'] ) . ';" ' : '';

		$defaults = Cookie_Law_Info::get_default_settings();
		$settings = wp_parse_args( Cookie_Law_Info::get_settings(), $defaults );

		$classr = '';
		if ( $settings['button_3_as_button'] ) {
			$classr = ' class="' . esc_attr( $settings['button_3_button_size'] ) . ' cli-plugin-button cli-plugin-main-button cookie_action_close_header_reject cli_action_button wt-cli-reject-btn"';
		} else {
			$classr = ' class="cookie_action_close_header_reject cli_action_button wt-cli-reject-btn" ';
		}
		$url_reject = ( $settings['button_3_action'] == 'CONSTANT_OPEN_URL' && $settings['button_3_url'] != '#' ) ? 'href="' . esc_url( $settings['button_3_url'] ) . '"' : "role='button' tabindex='0'";
		$link_tag   = '';
		$link_tag  .= '<a ' . $url_reject . ' id="' . esc_attr( Cookie_Law_Info_Public::cookielawinfo_remove_hash( $settings['button_3_action'] ) ) . '" ';
		$link_tag  .= ( $settings['button_3_new_win'] ) ? 'target="_blank" ' : '';
		$link_tag  .= $classr . '  data-cli_action="reject"' . $margin_style . '>' . esc_html( stripslashes( __( $settings['button_3_text'], 'cookie-law-info' ) ) ) . '</a>';
		return $link_tag;
	}
	/*
	*   Cookie Settings Button Shortcode
	*   @since 1.7.7
	*/
	public function cookielawinfo_shortcode_settings_button( $atts ) {
		$atts                         = shortcode_atts(
			array(
				'margin' => '',
			),
			$atts,
			'cookie_settings'
		);
		$margin_style                 = $atts['margin'] != '' ? ' style="margin:' . esc_attr( $atts['margin'] ) . ';" ' : '';
		$defaults                     = Cookie_Law_Info::get_default_settings();
		$settings                     = wp_parse_args( Cookie_Law_Info::get_settings(), $defaults );
		$settings['button_4_url']     = '#';
		$settings['button_4_action']  = '#cookie_action_settings';
		$settings['button_4_new_win'] = false;
		$classr                       = '';
		if ( $settings['button_4_as_button'] ) {
			$classr = ' class="' . esc_attr( $settings['button_4_button_size'] ) . ' cli-plugin-button cli-plugin-main-button cli_settings_button"';
		} else {
			$classr = ' class="cli_settings_button"';
		}

		// adding custom style
		$url_s     = ( $settings['button_4_action'] == 'CONSTANT_OPEN_URL' && $settings['button_4_url'] != '#' ) ? 'href="' . esc_url( $settings['button_4_url'] ) . '"' : "role='button' tabindex='0'";
		$link_tag  = '';
		$link_tag .= '<a ' . $url_s;
		$link_tag .= ( $settings['button_4_new_win'] ) ? ' target="_blank" ' : '';
		$link_tag .= $classr . '' . $margin_style . '>' . esc_html( stripslashes( $settings['button_4_text'] ) ) . '</a>';
		return $link_tag;
	}
	/** Returns HTML for a generic button */
	public function cookielawinfo_shortcode_more_link( $atts ) {
		return $this->cookielawinfo_shortcode_button_DRY_code( 'button_2', $atts );
	}


	/** Returns HTML for a generic button */
	public function cookielawinfo_shortcode_main_button( $atts ) {
		$atts         = shortcode_atts(
			array(
				'margin' => '',
			),
			$atts,
			'cookie_button'
		);
		$margin_style = $atts['margin'] != '' ? ' margin:' . esc_attr( $atts['margin'] ) . '; ' : '';

		$defaults = Cookie_Law_Info::get_default_settings();
		$settings = wp_parse_args( Cookie_Law_Info::get_settings(), $defaults );
		$class    = '';
		if ( $settings['button_1_as_button'] ) {
			$class = ' class="' . esc_attr( $settings['button_1_button_size'] ) . ' cli-plugin-button cli-plugin-main-button cookie_action_close_header cli_action_button wt-cli-accept-btn"';
		} else {
			$class = ' class="cli-plugin-main-button cookie_action_close_header cli_action_button wt-cli-accept-btn" ';
		}

		// If is action not URL then don't use URL!
		$url       = ( $settings['button_1_action'] == 'CONSTANT_OPEN_URL' && $settings['button_1_url'] != '#' ) ? 'href="' . esc_url( $settings['button_1_url'] ) . '"' : "role='button' tabindex='0'";
		$link_tag  = '<a ' . $url . ' data-cli_action="accept" id="' . esc_attr( Cookie_Law_Info_Public::cookielawinfo_remove_hash( $settings['button_1_action'] ) ) . '" ';
		$link_tag .= ( $settings['button_1_new_win'] ) ? 'target="_blank" ' : '';
		$link_tag .= $class . ' style="display:inline-block; ' . $margin_style . '">' . esc_html( stripslashes( __( $settings['button_1_text'], 'cookie-law-info' ) ) ) . '</a>';

		return $link_tag;
	}


	/** Returns HTML for a generic button */
	public function cookielawinfo_shortcode_button_DRY_code( $name, $atts = array() ) {
		$atts         = shortcode_atts(
			array(
				'margin' => '',
			),
			$atts,
			'cookie_link'
		);
		$margin_style = $atts['margin'] != '' ? ' margin:' . esc_attr( $atts['margin'] ) . '; ' : '';

		$arr        = Cookie_Law_Info::get_settings();
		$settings   = array();
		$class_name = '';

		if ( $name == 'button_1' ) {
			$settings   = array(
				'button_x_text'          => stripslashes( $arr['button_1_text'] ),
				'button_x_url'           => $arr['button_1_url'],
				'button_x_action'        => $arr['button_1_action'],

				'button_x_link_colour'   => $arr['button_1_link_colour'],
				'button_x_new_win'       => $arr['button_1_new_win'],
				'button_x_as_button'     => $arr['button_1_as_button'],
				'button_x_button_colour' => $arr['button_1_button_colour'],
				'button_x_button_size'   => $arr['button_1_button_size'],
			);
			$class_name = 'cli-plugin-main-button';
		} elseif ( $name == 'button_2' ) {
			$settings   = array(
				'button_x_text'          => stripslashes( $arr['button_2_text'] ),
				'button_x_action'        => $arr['button_2_action'],

				'button_x_link_colour'   => $arr['button_2_link_colour'],
				'button_x_new_win'       => $arr['button_2_new_win'],
				'button_x_as_button'     => $arr['button_2_as_button'],
				'button_x_button_colour' => $arr['button_2_button_colour'],
				'button_x_button_size'   => $arr['button_2_button_size'],
			);
			$class_name = 'cli-plugin-main-link';
			if ( $arr['button_2_url_type'] == 'url' ) {
				$settings['button_x_url'] = $arr['button_2_url'];

				/*
				* @since 1.7.4
				* Checks if user enabled minify bar in the current page
				*/
				if ( $arr['button_2_hidebar'] === true ) {
					global $wp;
					$current_url = home_url( add_query_arg( array(), $wp->request ) );
					$btn2_url    = $current_url[ strlen( $current_url ) - 1 ] == '/' ? substr( $current_url, 0, -1 ) : $current_url;
					$btn2_url    = $arr['button_2_url'][ strlen( $arr['button_2_url'] ) - 1 ] == '/' ? substr( $arr['button_2_url'], 0, -1 ) : $arr['button_2_url'];
					if ( strpos( $btn2_url, $current_url ) !== false ) {
						if ( $btn2_url != $current_url ) {
							$qry_var_arr  = explode( '?', $current_url );
							$hash_var_arr = explode( '#', $current_url );
							if ( $qry_var_arr[0] == $btn2_url || $hash_var_arr[0] == $btn2_url ) {
								$class_name .= ' cli-minimize-bar';
							}
						} else {
							 $class_name .= ' cli-minimize-bar';
						}
					}
				}
			} else {
				$privacy_page_exists = 0;
				if ( $arr['button_2_page'] > 0 ) {
					$privacy_policy_page = get_post( $arr['button_2_page'] );
					if ( $privacy_policy_page instanceof WP_Post ) {
						if ( $privacy_policy_page->post_status === 'publish' ) {
							$privacy_page_exists      = 1;
							$settings['button_x_url'] = get_page_link( $privacy_policy_page );

							/*
							* @since 1.7.4
							* Checks if user enabled minify bar in the current page
							*/
							if ( $arr['button_2_hidebar'] === true ) {
								if ( is_page( $arr['button_2_page'] ) ) {
									$class_name .= ' cli-minimize-bar';
								}
							}
						}
					}
				}
				if ( $privacy_page_exists == 0 ) {
					return '';
				}
			}
		}

		$settings = apply_filters( 'wt_readmore_link_settings', $settings );
		$class    = '';
		if ( $settings['button_x_as_button'] ) {
			$class .= ' class="' . esc_attr( $settings['button_x_button_size'] ) . ' cli-plugin-button ' . esc_attr( $class_name ) . '"';
		} else {
			$class .= ' class="' . esc_attr( $class_name ) . '" ';
		}

		// If is action not URL then don't use URL!
		$url       = ( $settings['button_x_action'] == 'CONSTANT_OPEN_URL' && $settings['button_x_url'] != '#' ) ? 'href="' . esc_url( $settings['button_x_url'] ) . '"' : "role='button' tabindex='0'";
		$link_tag  = '<a ' . $url . ' id="' . esc_attr( Cookie_Law_Info_Public::cookielawinfo_remove_hash( $settings['button_x_action'] ) ) . '" ';
		$link_tag .= ( $settings['button_x_new_win'] ) ? 'target="_blank" ' : '';
		$link_tag .= $class . ' style="display:inline-block;' . $margin_style . '" >' . esc_html( $settings['button_x_text'] ) . '</a>';
		return $link_tag;
	}
	/**
	 * Shortcode for adding close button
	 *
	 * @since  1.8.9
	 * @return string
	 */
	public function cookielawinfo_shortcode_close_button() {
		$styles = '';
		return '<a style="' . esc_attr( $styles ) . '" aria-label="' . __( 'Close the cookie bar', 'cookie-law-info' ) . '" data-cli_action="accept" class="wt-cli-element cli_cookie_close_button" title="' . __( 'Close and Accept', 'cookie-law-info' ) . '">×</a>';
	}
	/**
	 * Add a link that allows the user the revisit their consent
	 *
	 * @since  1.9.4
	 * @access public
	 * @return string
	 */
	public function manage_consent() {
		if ( ! $this->cookie_options ) {
			$this->cookie_options = Cookie_Law_Info::get_settings();
		}
		$manage_consent_link = '';
		$manage_consent_text = ( isset( $this->cookie_options['showagain_text'] ) ? $this->cookie_options['showagain_text'] : '' );
		$manage_consent_link = '<a class="wt-cli-manage-consent-link">' . esc_html( $manage_consent_text ) . '</a>';

		return $manage_consent_link;
	}
	/**
	 * Creates accept all button
	 *
	 * @return void
	 */
	public function accept_all_button() {

		$defaults = Cookie_Law_Info::get_default_settings();
		$settings = wp_parse_args( Cookie_Law_Info::get_settings(), $defaults );
		$class    = '';
		if ( $settings['button_7_as_button'] ) {
			$class = ' class="wt-cli-element' . ' ' . esc_attr( $settings['button_7_button_size'] ) . ' cli-plugin-button wt-cli-accept-all-btn cookie_action_close_header cli_action_button"';
		} else {
			$class = ' class="wt-cli-element cli-plugin-main-button wt-cli-accept-all-btn cookie_action_close_header cli_action_button" ';
		}
		$url = ( $settings['button_7_action'] == 'CONSTANT_OPEN_URL' && $settings['button_7_url'] != '#' ) ? 'href="' . esc_url( $settings['button_7_url'] ) . '"' : "role='button'";

		$link_tag  = '<a id="wt-cli-accept-all-btn" tabindex="0" ' . $url . ' data-cli_action="accept_all" ';
		$link_tag .= ( $settings['button_7_new_win'] ) ? ' target="_blank" ' : '';
		$link_tag .= $class . ' >' . esc_html( stripslashes( $settings['button_7_text'] ) ) . '</a>';
		return $link_tag;
	}

}
new Cookie_Law_Info_Shortcode( $this );
