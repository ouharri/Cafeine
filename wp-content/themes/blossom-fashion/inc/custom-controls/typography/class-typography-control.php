<?php
/**
 * Customizer Typography Control
 *
 * Taken from Kirki.
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'Blossom_Fashion_Typography_Control' ) ) {
    
    class Blossom_Fashion_Typography_Control extends WP_Customize_Control {
    
    	public $tooltip = '';
    	public $js_vars = array();
    	public $output = array();
    	public $option_type = 'theme_mod';
    	public $type = 'blossom-fashion-typography';
    
    	/**
    	 * Refresh the parameters passed to the JavaScript via JSON.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function to_json() {
    		parent::to_json();
    
    		if ( isset( $this->default ) ) {
    			$this->json['default'] = $this->default;
    		} else {
    			$this->json['default'] = $this->setting->default;
    		}
    		$this->json['js_vars'] = $this->js_vars;
    		$this->json['output']  = $this->output;
    		$this->json['value']   = $this->value();
    		$this->json['choices'] = $this->choices;
    		$this->json['link']    = $this->get_link();
    		$this->json['tooltip'] = $this->tooltip;
    		$this->json['id']      = $this->id;
    		$this->json['l10n']    = apply_filters( 'blossom-fashion-typography-control/il8n/strings', array(
    			'on'                 => esc_attr__( 'ON', 'blossom-fashion' ),
    			'off'                => esc_attr__( 'OFF', 'blossom-fashion' ),
    			'all'                => esc_attr__( 'All', 'blossom-fashion' ),
    			'cyrillic'           => esc_attr__( 'Cyrillic', 'blossom-fashion' ),
    			'cyrillic-ext'       => esc_attr__( 'Cyrillic Extended', 'blossom-fashion' ),
    			'devanagari'         => esc_attr__( 'Devanagari', 'blossom-fashion' ),
    			'greek'              => esc_attr__( 'Greek', 'blossom-fashion' ),
    			'greek-ext'          => esc_attr__( 'Greek Extended', 'blossom-fashion' ),
    			'khmer'              => esc_attr__( 'Khmer', 'blossom-fashion' ),
    			'latin'              => esc_attr__( 'Latin', 'blossom-fashion' ),
    			'latin-ext'          => esc_attr__( 'Latin Extended', 'blossom-fashion' ),
    			'vietnamese'         => esc_attr__( 'Vietnamese', 'blossom-fashion' ),
    			'hebrew'             => esc_attr__( 'Hebrew', 'blossom-fashion' ),
    			'arabic'             => esc_attr__( 'Arabic', 'blossom-fashion' ),
    			'bengali'            => esc_attr__( 'Bengali', 'blossom-fashion' ),
    			'gujarati'           => esc_attr__( 'Gujarati', 'blossom-fashion' ),
    			'tamil'              => esc_attr__( 'Tamil', 'blossom-fashion' ),
    			'telugu'             => esc_attr__( 'Telugu', 'blossom-fashion' ),
    			'thai'               => esc_attr__( 'Thai', 'blossom-fashion' ),
    			'serif'              => _x( 'Serif', 'font style', 'blossom-fashion' ),
    			'sans-serif'         => _x( 'Sans Serif', 'font style', 'blossom-fashion' ),
    			'monospace'          => _x( 'Monospace', 'font style', 'blossom-fashion' ),
    			'font-family'        => esc_attr__( 'Font Family', 'blossom-fashion' ),
    			'font-size'          => esc_attr__( 'Font Size', 'blossom-fashion' ),
    			'font-weight'        => esc_attr__( 'Font Weight', 'blossom-fashion' ),
    			'line-height'        => esc_attr__( 'Line Height', 'blossom-fashion' ),
    			'font-style'         => esc_attr__( 'Font Style', 'blossom-fashion' ),
    			'letter-spacing'     => esc_attr__( 'Letter Spacing', 'blossom-fashion' ),
    			'text-align'         => esc_attr__( 'Text Align', 'blossom-fashion' ),
    			'text-transform'     => esc_attr__( 'Text Transform', 'blossom-fashion' ),
    			'none'               => esc_attr__( 'None', 'blossom-fashion' ),
    			'uppercase'          => esc_attr__( 'Uppercase', 'blossom-fashion' ),
    			'lowercase'          => esc_attr__( 'Lowercase', 'blossom-fashion' ),
    			'top'                => esc_attr__( 'Top', 'blossom-fashion' ),
    			'bottom'             => esc_attr__( 'Bottom', 'blossom-fashion' ),
    			'left'               => esc_attr__( 'Left', 'blossom-fashion' ),
    			'right'              => esc_attr__( 'Right', 'blossom-fashion' ),
    			'center'             => esc_attr__( 'Center', 'blossom-fashion' ),
    			'justify'            => esc_attr__( 'Justify', 'blossom-fashion' ),
    			'color'              => esc_attr__( 'Color', 'blossom-fashion' ),
    			'select-font-family' => esc_attr__( 'Select a font-family', 'blossom-fashion' ),
    			'variant'            => esc_attr__( 'Variant', 'blossom-fashion' ),
    			'style'              => esc_attr__( 'Style', 'blossom-fashion' ),
    			'size'               => esc_attr__( 'Size', 'blossom-fashion' ),
    			'height'             => esc_attr__( 'Height', 'blossom-fashion' ),
    			'spacing'            => esc_attr__( 'Spacing', 'blossom-fashion' ),
    			'ultra-light'        => esc_attr__( 'Ultra-Light 100', 'blossom-fashion' ),
    			'ultra-light-italic' => esc_attr__( 'Ultra-Light 100 Italic', 'blossom-fashion' ),
    			'light'              => esc_attr__( 'Light 200', 'blossom-fashion' ),
    			'light-italic'       => esc_attr__( 'Light 200 Italic', 'blossom-fashion' ),
    			'book'               => esc_attr__( 'Book 300', 'blossom-fashion' ),
    			'book-italic'        => esc_attr__( 'Book 300 Italic', 'blossom-fashion' ),
    			'regular'            => esc_attr__( 'Normal 400', 'blossom-fashion' ),
    			'italic'             => esc_attr__( 'Normal 400 Italic', 'blossom-fashion' ),
    			'medium'             => esc_attr__( 'Medium 500', 'blossom-fashion' ),
    			'medium-italic'      => esc_attr__( 'Medium 500 Italic', 'blossom-fashion' ),
    			'semi-bold'          => esc_attr__( 'Semi-Bold 600', 'blossom-fashion' ),
    			'semi-bold-italic'   => esc_attr__( 'Semi-Bold 600 Italic', 'blossom-fashion' ),
    			'bold'               => esc_attr__( 'Bold 700', 'blossom-fashion' ),
    			'bold-italic'        => esc_attr__( 'Bold 700 Italic', 'blossom-fashion' ),
    			'extra-bold'         => esc_attr__( 'Extra-Bold 800', 'blossom-fashion' ),
    			'extra-bold-italic'  => esc_attr__( 'Extra-Bold 800 Italic', 'blossom-fashion' ),
    			'ultra-bold'         => esc_attr__( 'Ultra-Bold 900', 'blossom-fashion' ),
    			'ultra-bold-italic'  => esc_attr__( 'Ultra-Bold 900 Italic', 'blossom-fashion' ),
    			'invalid-value'      => esc_attr__( 'Invalid Value', 'blossom-fashion' ),
    		) );
    
    		$defaults = array( 'font-family'=> false );
    
    		$this->json['default'] = wp_parse_args( $this->json['default'], $defaults );
    	}
    
    	/**
    	 * Enqueue scripts and styles.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function enqueue() {
    		wp_enqueue_style( 'blossom-fashion-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.css', null );
            /*
    		 * JavaScript
    		 */
            wp_enqueue_script( 'jquery-ui-core' );
    		wp_enqueue_script( 'jquery-ui-tooltip' );
    		wp_enqueue_script( 'jquery-stepper-min-js' );
    		
    		// Selectize
    		wp_enqueue_script( 'selectize', get_template_directory_uri() . '/inc/js/selectize.js', array( 'jquery' ), false, true );
    
    		// Typography
    		wp_enqueue_script( 'blossom-fashion-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.js', array(
    			'jquery',
    			'selectize'
    		), false, true );
    
    		$google_fonts   = Blossom_Fashion_Fonts::get_google_fonts();
    		$standard_fonts = Blossom_Fashion_Fonts::get_standard_fonts();
    		$all_variants   = Blossom_Fashion_Fonts::get_all_variants();
    
    		$standard_fonts_final = array();
    		foreach ( $standard_fonts as $key => $value ) {
    			$standard_fonts_final[] = array(
    				'family'      => $value['stack'],
    				'label'       => $value['label'],
    				'is_standard' => true,
    				'variants'    => array(
    					array(
    						'id'    => 'regular',
    						'label' => $all_variants['regular'],
    					),
    					array(
    						'id'    => 'italic',
    						'label' => $all_variants['italic'],
    					),
    					array(
    						'id'    => '700',
    						'label' => $all_variants['700'],
    					),
    					array(
    						'id'    => '700italic',
    						'label' => $all_variants['700italic'],
    					),
    				),
    			);
    		}
    
    		$google_fonts_final = array();
    
    		if ( is_array( $google_fonts ) ) {
    			foreach ( $google_fonts as $family => $args ) {
    				$label    = ( isset( $args['label'] ) ) ? $args['label'] : $family;
    				$variants = ( isset( $args['variants'] ) ) ? $args['variants'] : array( 'regular', '700' );
    
    				$available_variants = array();
    				foreach ( $variants as $variant ) {
    					if ( array_key_exists( $variant, $all_variants ) ) {
    						$available_variants[] = array( 'id' => $variant, 'label' => $all_variants[ $variant ] );
    					}
    				}
    
    				$google_fonts_final[] = array(
    					'family'   => $family,
    					'label'    => $label,
    					'variants' => $available_variants
    				);
    			}
    		}
    
    		$final = array_merge( $standard_fonts_final, $google_fonts_final );
    		wp_localize_script( 'blossom-fashion-typography', 'all_fonts', $final );
    	}
    
    	/**
    	 * An Underscore (JS) template for this control's content (but not its container).
    	 *
    	 * Class variables for this control class are available in the `data` JS object;
    	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
    	 *
    	 * I put this in a separate file because PhpStorm didn't like it and it fucked with my formatting.
    	 *
    	 * @see    WP_Customize_Control::print_template()
    	 *
    	 * @access protected
    	 * @return void
    	 */
    	protected function content_template() { ?>
    		<# if ( data.tooltip ) { #>
                <a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
            <# } #>
            
            <label class="customizer-text">
                <# if ( data.label ) { #>
                    <span class="customize-control-title">{{{ data.label }}}</span>
                <# } #>
                <# if ( data.description ) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>
            </label>
            
            <div class="wrapper">
                <# if ( data.default['font-family'] ) { #>
                    <# if ( '' == data.value['font-family'] ) { data.value['font-family'] = data.default['font-family']; } #>
                    <# if ( data.choices['fonts'] ) { data.fonts = data.choices['fonts']; } #>
                    <div class="font-family">
                        <h5>{{ data.l10n['font-family'] }}</h5>
                        <select id="blossom-typography-font-family-{{{ data.id }}}" placeholder="{{ data.l10n['select-font-family'] }}"></select>
                    </div>
                    <div class="variant blossom-variant-wrapper">
                        <h5>{{ data.l10n['style'] }}</h5>
                        <select class="variant" id="blossom-typography-variant-{{{ data.id }}}"></select>
                    </div>
                <# } #>   
                
            </div>
            <?php
    	}

        protected function render_content(){
        }
    
    }
}