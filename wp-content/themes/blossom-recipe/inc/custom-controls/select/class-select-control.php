<?php
/**
 * Blossom Recipe Customizer Select Control.
 * 
 * @package Blossom_Recipe
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blossom_Recipe_Select_Control' ) ){
	/**
	 * Select control.
    */
	class Blossom_Recipe_Select_Control extends WP_Customize_Control {

		public $type = 'blossom-recipe-select';

		public $multiple = 1;
        
        public $tooltip = '';
        
		public function to_json() {
			parent::to_json();
			
            if ( isset( $this->default ) ) {
				$this->json['default'] = $this->default;
			} else {
				$this->json['default'] = $this->setting->default;
			}
			
            $this->json['multiple'] = $this->multiple;
            $this->json['value']    = $this->value();
			$this->json['choices']  = $this->choices;
			$this->json['link']     = $this->get_link();
            $this->json['tooltip']  = $this->tooltip;
						
            $this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
		}        
        
        public function enqueue() {
            wp_enqueue_style( 'blossom-recipe-select', get_template_directory_uri() . '/inc/custom-controls/select/select.css', null );
            wp_enqueue_script( 'blossom-recipe-selectize', get_template_directory_uri() . '/inc/js/selectize.js', array( 'jquery' ) ); //for multi select
            wp_enqueue_script( 'blossom-recipe-select', get_template_directory_uri() . '/inc/custom-controls/select/select.js', array( 'jquery', 'blossom-recipe-selectize' ), false, true ); //for multi select    
        }
		
		protected function content_template() {
			?>
			<# if ( ! data.choices ) return; #>
            <# if ( data.tooltip ) { #>
				<a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
			<# } #>			
			<label>
				<# if ( data.label ) { #>
					<span class="customize-control-title">{{ data.label }}</span>
				<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				<select {{{ data.inputAttrs }}} {{{ data.link }}} data-multiple="{{ data.multiple }}"<# if ( 1 < data.multiple ) { #> multiple<# } #>>
					<# if ( 1 < data.multiple && data.value ) { #>
						<# for ( key in data.value ) { #>
							<option value="{{ data.value[ key ] }}" selected>{{ data.choices[ data.value[ key ] ] }}</option>
						<# } #>
						<# for ( key in data.choices ) { #>
							<# if ( data.value[ key ] in data.value ) { #>
							<# } else { #>
								<option value="{{ key }}">{{ data.choices[ key ] }}</option>
							<# } #>
						<# } #>
					<# } else { #>
						<# for ( key in data.choices ) { #>
							<option value="{{ key }}"<# if ( key === data.value ) { #>selected<# } #>>{{ data.choices[ key ] }}</option>
						<# } #>
					<# } #>
				</select>
			</label>
			<?php
		}

		protected function render_content(){
		}
	}
}