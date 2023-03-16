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
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * OptinMonster widget for Elementor page builder.
 *
 * @since 2.2.0
 */
class OMAPI_Elementor_Widget extends Widget_Base {

	/**
	 * Widget constructor.
	 *
	 * Initializing the widget class.
	 *
	 * @see https://code.elementor.com/methods/elementor-widget_base-__construct/
	 *
	 * @since 2.2.0
	 *
	 * @throws \Exception If arguments are missing when initializing a full widget
	 *                   instance.
	 *
	 * @param array      $data Widget data. Default is an empty array.
	 * @param array|null $args Optional. Widget default arguments. Default is null.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		// Load the base class object.
		$this->base = OMAPI::get_instance();
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve shortcode widget name.
	 *
	 * @see https://code.elementor.com/methods/elementor-controls_stack-get_name/
	 *
	 * @since 2.2.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'optinmonster';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @see https://code.elementor.com/methods/elementor-element_base-get_title/
	 *
	 * @since 2.2.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'OptinMonster', 'optin-monster-api' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @see https://code.elementor.com/methods/elementor-widget_base-get_icon/
	 *
	 * @since 2.2.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'icon-optinmonster';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @see https://code.elementor.com/methods/elementor-widget_base-get_keywords/
	 *
	 * @since 2.2.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array(
			'popup',
			'form',
			'forms',
			'campaign',
			'email',
			'conversion',
			'contact form',
		);
	}

	/**
	 * Get widget categories.
	 *
	 * @see https://code.elementor.com/methods/elementor-widget_base-get_categories/
	 *
	 * @since 2.2.0
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array(
			'basic',
		);
	}

	/**
	 * Handle registering elementor editor JS assets.
	 *
	 * @see https://code.elementor.com/methods/elementor-element_base-get_script_depends/
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */
	public function get_script_depends() {
		if ( ! Plugin::instance()->preview->is_preview_mode() ) {
			return array();
		}

		$script_id = $this->base->plugin_slug . '-elementor';
		wp_register_script(
			$script_id,
			$this->base->url . 'assets/dist/js/elementor.min.js',
			array( 'elementor-frontend', 'jquery' ),
			$this->base->asset_version(),
			true
		);
		OMAPI_Utils::add_inline_script( $script_id, 'OMAPI', $this->base->blocks->get_data_for_js() );

		return array( $script_id );
	}

	/**
	 * Handle registering elementor editor CSS assets.
	 *
	 * @see https://code.elementor.com/methods/elementor-element_base-get_style_depends/
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */
	public function get_style_depends() {
		$css_handle = $this->base->plugin_slug . '-elementor-frontend';
		wp_register_style(
			$css_handle,
			$this->base->url . 'assets/dist/css/elementor-frontend.min.css',
			array(),
			$this->base->asset_version()
		);

		return array( $css_handle );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @see https://code.elementor.com/methods/elementor-controls_stack-_register_controls/
	 *
	 * @since 2.2.0
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_om_campaign',
			array(
				'label' => esc_html__( 'OptinMonster Campaign', 'optin-monster-api' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->base->blocks->has_sites() ) {
			$this->no_sites_controls();

		} elseif ( ! $this->has_inline_campaigns() ) {
			$this->no_campaign_controls();

		} else {
			$this->campaign_controls();
		}

		$this->end_controls_section();
	}

	/**
	 * Register no-site controls.
	 *
	 * @since 2.2.0
	 */
	protected function no_sites_controls() {
		$i18n = $this->base->blocks->get_data_for_js( 'i18n' );

		$this->add_control(
			'add_om_campaign_notice',
			array(
				'show_label'      => false,
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '
				<p class="om-elementor-editor-no_sites-help">
					<strong>' . esc_html__( 'You Have Not Connected to OptinMonster', 'optin-monster-api' ) . '</strong>
					<br>
					' . esc_html__( 'Please create a Free Account or Connect an Existing Account', 'optin-monster-api' ) . '
				</p>
				',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'om_create_account',
			array(
				'show_label'  => false,
				'label_block' => false,
				'type'        => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text'        => $i18n['no_sites_button_create_account'],
				'event'       => 'elementorOMAPICreateAccount',
			)
		);

		$this->add_control(
			'om_connect_account',
			array(
				'show_label'  => false,
				'label_block' => false,
				'type'        => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'separator'   => 'after',
				'text'        => $i18n['no_sites_button_connect_account'],
				'event'       => 'elementorOMAPIConnectAccount',
			)
		);
	}

	/**
	 * Register no-campaign controls.
	 *
	 * @since 2.2.0
	 */
	protected function no_campaign_controls() {
		$this->add_control(
			'add_om_campaign_notice',
			array(
				'show_label'      => false,
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => wp_kses(
					'<b>' . __( 'No inline campaigns available!', 'optin-monster-api' ) . '</b>',
					array(
						'b' => array(),
					)
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'add_campaign_btn',
			array(
				'show_label'  => false,
				'label_block' => false,
				'type'        => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'separator'   => 'after',
				'text'        => '<b>+</b>' . esc_html__( 'Create New Inline Campaign', 'optin-monster-api' ),
				'event'       => 'elementorOMAPIAddInlineBtnClick',
			)
		);
	}

	/**
	 * Register campaign controls.
	 *
	 * @since 2.2.0
	 */
	protected function campaign_controls() {
		$campaigns = $this->base->blocks->get_campaign_options( true );
		$campaigns = array_merge( array( '' => esc_html__( 'Select Campaign...', 'optin-monster-api' ) ), $campaigns['inline'] );

		$this->add_control(
			'campaign_id',
			array(
				'label'              => esc_html__( 'Select Campaign', 'optin-monster-api' ),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'label_block'        => true,
				'options'            => $campaigns,
				'default'            => '',
			)
		);

		$this->add_control(
			'followrules',
			array(
				'label'        => esc_html__( 'Use Output Settings', 'optin-monster-api' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'optin-monster-api' ),
				'label_off'    => esc_html__( 'No', 'optin-monster-api' ),
				'return_value' => 'yes',
				'condition'    => array(
					'campaign_id!' => '0',
				),
			)
		);

		$this->add_control(
			'edit_campaign',
			array(
				'show_label' => false,
				'type'       => Controls_Manager::RAW_HTML,
				'raw'        => sprintf(
					wp_kses(
						/* translators: %s - OptinMonster edit link. */
						__( 'Need to make changes? <a href="%1s" class="skip-om-trigger" target="_blank" rel="noopener">Edit the selected campaign.</a>', 'optin-monster-api' ),
						array(
							'a' => array(
								'href'   => array(),
								'class'  => array(),
								'target' => array(),
								'rel'    => array(),
							),
						)
					),
					esc_url( $this->base->blocks->get_data_for_js( 'editUrl' ) )
				),
				'condition'  => array(
					'campaign_id!' => '0',
				),
			)
		);

		$this->add_control(
			'add_campaign_btn',
			array(
				'show_label'  => false,
				'label_block' => false,
				'type'        => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'separator'   => 'before',
				'text'        => '<b>+</b>' . esc_html__( 'Create New Inline Campaign', 'optin-monster-api' ),
				'event'       => 'elementorOMAPIAddInlineBtnClick',
			)
		);
	}

	/**
	 * Render widget output.
	 *
	 * @see https://code.elementor.com/methods/elementor-element_base-render/
	 *
	 * @since 2.2.0
	 */
	protected function render() {
		if ( Plugin::instance()->editor->is_edit_mode() ) {
			$this->render_edit_mode();
		} else {
			$this->render_frontend();
		}
	}

	/**
	 * Get the editing-block render format.
	 *
	 * @since 2.2.0
	 *
	 * @return string Format html string.
	 */
	protected function get_render_format() {
		return '
		<div class="om-elementor-editor" data-slug="%1$s">
			%2$s
			<div class="om-elementor-holder">
				%3$s
			</div>
			<div class="om-errors" style="display:none;">
				<strong>' . esc_html__( 'OptinMonster Campaign Error:', 'optin-monster-api' ) . '</strong><br><span class="om-error-description"></span>
			</div>
		</div>
		';
	}

	/**
	 * Get the campaign-selector html.
	 *
	 * @since 2.2.0
	 *
	 * @param  bool $icon Whether to include Archie icon.
	 *
	 * @return string Html string.
	 */
	protected function get_campaign_select_html( $icon = true ) {

		$data = $this->base->blocks->get_data_for_js();

		if ( ! $this->base->blocks->has_sites() ) {
			$guts = '
			<div class="om-elementor-editor-no_sites">
				' . ( $icon ? '<img src="' . $this->base->url . 'assets/css/images/icons/archie-color-icon.svg">' : '' ) . '
				<p class="om-elementor-editor-no_sites-help">
					<strong>' . esc_html__( 'You Have Not Connected to OptinMonster', 'optin-monster-api' ) . '</strong>
					<br>
					' . esc_html__( 'Please create a Free Account or Connect an Existing Account', 'optin-monster-api' ) . '
				</p>
				<p class="om-elementor-editor-no_sites-button">
					<a class="om-help-button skip-om-trigger components-button is-primary" href="' . $data['wizardUri'] . '" target="_blank" rel="noopener">
						' . $data['i18n']['no_sites_button_create_account'] . '
					</a>
					<span>or</span>
					<a class="om-help-button skip-om-trigger components-button is-secondary" href="' . $data['settingsUri'] . '" target="_blank" rel="noopener">
						' . $data['i18n']['no_sites_button_connect_account'] . '
					</a>
				</p>
			</div>
			';
		} elseif ( ! $this->has_inline_campaigns() ) {
			$guts = '
			<div class="om-elementor-editor-no_campaigns">
				' . ( $icon ? '<img src="' . $this->base->url . 'assets/css/images/icons/archie-color-icon.svg">' : '' ) . '
				<p class="om-elementor-editor-no_campaigns-help">
					<strong>' . $data['i18n']['no_campaigns'] . '</strong>
					<br>
					' . $data['i18n']['no_campaigns_help'] . '
				</p>
				<p class="om-elementor-editor-no_campaigns-button">
					<a class="om-help-button skip-om-trigger components-button om-green omapi-link-arrow-after" href="' . $data['templatesUri'] . '&type=inline" target="_blank" rel="noopener">
						' . $data['i18n']['create_inline_campaign'] . '
					</a>
				</p>
				<p class="om-elementor-editor-no_campaigns-button-help">
					<a class="om-help-button skip-om-trigger components-button is-secondary" href="https://optinmonster.com/docs/getting-started-optinmonster-wordpress-checklist/?utm_source=plugin&utm_medium=link&utm_campaign=gutenbergblock" target="_blank" rel="noopener">
						' . esc_html__( 'Need some help? Check out our comprehensive guide.', 'optin-monster-api' ) . '
					</a>
				</p>
			</div>
			';
		} else {
			$guts = '
			<div class="om-elementor-editor-select-label">
				' . ( $icon ? '<img src="' . $this->base->url . 'assets/css/images/icons/archie-icon.svg">' : '' ) . '
				OptinMonster
			</div>
			<p>' . esc_html__( 'Select and display your email marketing form or smart call-to-action campaigns from OptinMonster.', 'optin-monster-api' ) . '</p>
			<div class="om-elementor-editor-select-controls">
				<select></select>
				<div class="om-elementor-editor-select-controls-button">
					<a class="om-help-button skip-om-trigger components-button is-secondary" href="' . $data['templatesUri'] . '&type=inline" target="_blank" rel="noopener">
					' . esc_html__( 'Create a New Inline Campaign', 'optin-monster-api' ) . '
					</a>
					<a class="om-help-button skip-om-trigger components-button is-secondary" href="' . $data['templatesUri'] . '&type=popup" target="_blank" rel="noopener">
						' . esc_html__( 'Create a New Popup Campaign', 'optin-monster-api' ) . '
					</a>
				</div>
			</div>
			';
		}

		return '<div class="om-elementor-editor-select">' . $guts . '</div>';
	}

	/**
	 * Get the campaign holder html.
	 *
	 * @since 2.2.0
	 *
	 * @param  string $campaign_id Campaign Id string.
	 *
	 * @return string              Html.
	 */
	public function get_campaign_holder( $campaign_id ) {
		return sprintf(
			'
			<div id="om-%1$s-holder">
				<div class="om-elementor-editor-holder-loading om-elementor-editor-select-label">
					<img src="' . $this->base->url . 'assets/css/images/icons/archie-icon.svg">
					' . esc_html__( 'Loading Campaign...', 'optin-monster-api' ) . '
				</div>
			</div>
			',
			$campaign_id
		);
	}

	/**
	 * Render widget output in edit mode.
	 *
	 * @since 2.2.0
	 */
	protected function render_edit_mode() {
		$campaign_id = esc_attr( $this->get_settings_for_display( 'campaign_id' ) );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		printf(
			$this->get_render_format(),
			$campaign_id,
			! $campaign_id ? $this->get_campaign_select_html() : '',
			$campaign_id ? $this->get_campaign_holder( $campaign_id ) : ''
		);
		// phpcs:enable
	}

	/**
	 * This method is used by the parent methods to output the backbone/underscore template.
	 *
	 * @see https://code.elementor.com/methods/elementor-element_base-_content_template/
	 *
	 * @since 2.2.0
	 */
	protected function content_template() {
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		printf(
			$this->get_render_format(),
			'{{{ settings.campaign_id }}}',
			'<# if ( ! settings.campaign_id ) { #>' . $this->get_campaign_select_html() . '<# } #>',
			'<# if ( settings.campaign_id ) { #>' . $this->get_campaign_holder( '{{{ settings.campaign_id }}}' ) . '<# } #>'
		);
		// phpcs:enable
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 2.2.0
	 */
	protected function render_frontend() {
		echo do_shortcode( $this->get_shortcode_output() );
	}

	/**
	 * Render widget as plain content.
	 *
	 * @see https://code.elementor.com/methods/elementor-widget_base-render_plain_content/
	 *
	 * @since 2.2.0
	 */
	public function render_plain_content() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->get_shortcode_output();
	}

	/**
	 * Render shortcode.
	 *
	 * @since 2.2.0
	 *
	 * @return string Shortcode
	 */
	protected function get_shortcode_output() {
		return sprintf(
			'[optin-monster slug="%1$s"%2$s]',
			sanitize_text_field( $this->get_settings_for_display( 'campaign_id' ) ),
			$this->get_settings_for_display( 'followrules' ) === 'yes' ? ' followrules="true"' : ''
		);
	}

	/**
	 * Does the user have any inline campaigns created?
	 *
	 * @since 2.2.0
	 *
	 * @return boolean
	 */
	protected function has_inline_campaigns() {
		$campaigns = $this->base->blocks->get_campaign_options();

		return ! empty( $campaigns['inline'] );
	}

}
