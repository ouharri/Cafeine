<?php
/**
 * Elementor Widget class.
 *
 * @since 2.2.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

use Elementor\Plugin;
use Elementor\Widget_Button;
use Elementor\Controls_Manager;

/**
 * OptinMonster widget for Elementor page builder.
 *
 * @since 2.2.0
 */
class OMAPI_Elementor_ButtonWidget extends Widget_Button {

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		parent::register_controls();

		$campaigns = OMAPI::get_instance()->blocks->get_campaign_options( true );
		$campaigns = ! empty( $campaigns['other'] )
			? array_merge( array( '' => esc_html__( 'Select Campaign...', 'optin-monster-api' ) ), $campaigns['other'] )
			: array( '' => 'N/A' );

		$this->add_control(
			'om_button_campaign_id',
			array(
				'label'              => esc_html__( 'Click to Load Popup', 'optin-monster-api' ),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'label_block'        => true,
				'options'            => $campaigns,
				'default'            => '',
			),
			array(
				'position' => array(
					'type' => 'control',
					'of'   => 'link',
				),
			)
		);

		$link_control = $this->get_controls( 'link' );

		$link_control['condition'] = array(
			'om_button_campaign_id' => '',
		);

		$this->add_control(
			'link',
			$link_control,
			array(
				'overwrite' => true,
			)
		);
	}

	/**
	 * Render button widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		if ( settings.om_button_campaign_id ) {
			settings.link                   = settings.link || {}
			settings.link.url               = '<?php echo esc_url_raw( OPTINMONSTER_SHAREABLE_LINK ); ?>/c/' + settings.om_button_campaign_id + '/';
			settings.link.is_external       = 'on';
			settings.link.nofollow          = false;
			settings.link.custom_attributes = 'rel|noopener noreferrer';
		}
		#>
		<?php
		return parent::content_template();
	}

	/**
	 * Get active settings.
	 *
	 * Retrieve the settings from all the active controls.
	 *
	 * @since 1.4.0
	 * @since 2.1.0 Added the `controls` and the `settings` parameters.
	 * @access public
	 *
	 * @param array $settings Optional. Controls settings. Default is null.
	 * @param array $controls Optional. An array of controls. Default is null.
	 *
	 * @return array Active settings.
	 */
	public function get_active_settings( $settings = null, $controls = null ) {
		$settings = parent::get_active_settings( $settings, $controls );

		if ( ! empty( $settings['om_button_campaign_id'] ) ) {
			$settings['link'] = ! empty( $settings['link'] ) ? $settings['link'] : array();
			$settings['link'] = wp_parse_args(
				array(
					'url'               => OPTINMONSTER_SHAREABLE_LINK . '/c/' . sanitize_text_field( $settings['om_button_campaign_id'] ) . '/',
					'is_external'       => 'on',
					'nofollow'          => false,
					'custom_attributes' => 'rel|noopener noreferrer',
				),
				$settings['link']
			);
		}

		return $settings;
	}
}
