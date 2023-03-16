<?php
/**
 * Customizer Control: Note.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blossom_Fashion_Note_Control' ) ) {

	class Blossom_Fashion_Note_Control extends WP_Customize_Control {
		
		public function render_content(){ ?>
    	    <span class="customize-control-title">
    			<?php echo esc_html( $this->label ); ?>
    		</span>
    
    		<?php if( $this->description ){ ?>
    			<span class="description customize-control-description">
    			<?php echo wp_kses_post($this->description); ?>
    			</span>
    		<?php }
        }
	}
}