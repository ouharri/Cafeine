<?php
/**
	burger-companion Range
 */
  if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}
	class Burger_Customizer_Range_Control extends WP_Customize_Control {

		public $type = 'burger-companion-range-slider';

		public function to_json() {
			if ( ! empty( $this->setting->default ) ) {
				$this->json['default'] = $this->setting->default;
			} else {
				$this->json['default'] = false;
			}
			parent::to_json();
		}

		public function enqueue() {
			wp_enqueue_script( 'burger-companion-range-slider', BURGER_COMPANION_PLUGIN_URL . 'inc/custom-controls/range-validator/assets/js/range-control.js', array( 'jquery' ), '', true );
			wp_enqueue_style( 'burger-companion-range-slider', BURGER_COMPANION_PLUGIN_URL . 'inc/custom-controls/range-validator/assets/css/range-control.css' );
		}

		public function render_content() {
		?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
				<div id="<?php echo esc_attr( $this->id ); ?>">
					<div class="burger-companion-range-slider">
						<input class="burger-companion-range-slider-range" type="range" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->input_attrs(); $this->link(); ?> />
						<input class="burger-companion-range-slider-value" type="number" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->input_attrs(); $this->link(); ?> />
						<?php if ( ! empty( $this->setting->default ) ) : ?>
							<span class="burger-companion-range-reset-slider" title="<?php _e( 'Reset', 'burger-companion' ); ?>"><span class="dashicons dashicons-image-rotate"></span></span>
						<?php endif;?>
					</div>
				</div>
			</label>
		<?php }

	}
