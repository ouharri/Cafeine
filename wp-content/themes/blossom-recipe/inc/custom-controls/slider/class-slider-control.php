<?php
/**
 * Blossom Recipe Customizer Slider Control.
 * 
 * @package Blossom_Recipe
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blossom_Recipe_Slider_Control' ) ){
	/**
	 * Slider control (range).
    */
	class Blossom_Recipe_Slider_Control extends WP_Customize_Control {

		public $type = 'blossom-recipe-slider';
        
        public $tooltip = '';
        
		public function to_json() {
			parent::to_json();
			
            if ( isset( $this->default ) ) {
				$this->json['default'] = $this->default;
			} else {
				$this->json['default'] = $this->setting->default;
			}
			
            $this->json['value']   = $this->value();
			$this->json['choices'] = $this->choices;
			$this->json['link']    = $this->get_link();            
            $this->json['tooltip'] = $this->tooltip;
						
            $this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
            
            $this->json['choices']['min']  = ( isset( $this->choices['min'] ) ) ? $this->choices['min'] : '0';
			$this->json['choices']['max']  = ( isset( $this->choices['max'] ) ) ? $this->choices['max'] : '100';
			$this->json['choices']['step'] = ( isset( $this->choices['step'] ) ) ? $this->choices['step'] : '1';
		}
        
        public function enqueue() {            
            wp_enqueue_style( 'blossom-recipe-slider', get_template_directory_uri() . '/inc/custom-controls/slider/slider.css', null );
            wp_enqueue_script( 'blossom-recipe-slider', get_template_directory_uri() . '/inc/custom-controls/slider/slider.js', array( 'jquery' ), false, true ); //for slider                
        }
        
		protected function content_template(){ ?>
			<# if ( data.tooltip ) { #>
				<a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
			<# } #>
			<label>
				<# if ( data.label ) { #>
					<span class="customize-control-title">{{{ data.label }}}</span>
				<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				<div class="wrapper">
					<input {{{ data.inputAttrs }}} type="range" min="{{ data.choices['min'] }}" max="{{ data.choices['max'] }}" step="{{ data.choices['step'] }}" value="{{ data.value }}" {{{ data.link }}} data-reset_value="{{ data.default }}" />
					<div class="range_value">
						<span class="value">{{ data.value }}</span>
						<# if ( data.choices['suffix'] ) { #>
							{{ data.choices['suffix'] }}
						<# } #>
					</div>
					<div class="slider-reset">
						<span class="dashicons dashicons-image-rotate"></span>
					</div>
				</div>
			</label>
			<?php
		}

		protected function render_content(){
		}
	}
}