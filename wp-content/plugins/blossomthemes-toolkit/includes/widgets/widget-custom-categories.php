<?php
function bttk_custom_categories_load_widget() {
    register_widget( 'Bttk_Custom_Categories' );
}
add_action( 'widgets_init', 'bttk_custom_categories_load_widget' );
 
// Creating the widget 
class Bttk_Custom_Categories extends WP_Widget {
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'Bttk_Custom_Categories', 
		 
		// Widget name will appear in UI
		__('Blossom: Custom Categories', 'blossomthemes-toolkit'), 
		 
		// Widget description
		array( 'description' => __( 'Widget to display categories with Image and Posts Count', 'blossomthemes-toolkit' ), ) 
		);
	}
		 
	// Creating widget front-end
		 
	public function widget( $args, $instance ) {

        $title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $obj = new BlossomThemes_Toolkit_Functions;
		 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		ob_start();

		$target = 'target="_self"';
        if( isset( $instance['target'] ) && $instance['target'] !='' ){
            $target = 'rel="noopener noexternal" target="_blank"';
        }

		if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
        
		echo '<div class="blossomthemes-custom-categories-wrap">';
		echo '<ul class="blossomthemes-custom-categories-meta-wrap">';

		$cats[] = '1';
		if( isset( $instance['categories'] ) &&  $instance['categories'] !='' ){
			$cats[] = '';
			$cats = $instance['categories'];
		}
		$ccw_img_size = apply_filters('bttk_ccw_img_size','post-slider-thumb-size');
		foreach ( $cats as $key => $value ) 
		{
			$img = get_term_meta( $value, 'category-image-id', false );
			$category = get_category( $value );
			if( $category )
			{
				$count = $category->category_count;

				if( isset( $img ) && is_array( $img ) && isset( $img[0] ) && $img[0] !='' )
				{
					$url1 = wp_get_attachment_image_url( $img[0], $ccw_img_size );

	                echo '<li style="background: url('.$url1.') no-repeat">';
					echo '<a '.$target.' href="'. esc_url( get_category_link( $value ) ) .'"><span class="cat-title">'.get_cat_name( $value ).'</span>';
					if( $count > 0 ) {
						echo '<span class="post-count">'.esc_html( $count ).__(' Post(s)','blossomthemes-toolkit').'</span>';
					}
					echo '</a></li>';
				}
				else
				{
					$image_size = $obj->bttk_get_image_sizes( $ccw_img_size );
					$svg_fill   = apply_filters('bttk_background_svg_fill', 'fill:%23f2f2f2;');
					if( $image_size ){ 
						$url1 = ("<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 ".$image_size['width']." ".$image_size['height']."' preserveAspectRatio='none'><rect width='".$image_size['width']."' height='".$image_size['height']."' style='".$svg_fill."'></rect></svg>");
						$url1 = "data:image/svg+xml; utf-8, $url1";
				    }
					echo '<li class="category-fallback-svg">';
					echo '<a '.$target.' href="'. esc_url( get_category_link( $value ) ) .'"><span class="cat-title">'.get_cat_name( $value ).'</span>';
					if( $count > 0 ) {
						echo '<span class="post-count">'.esc_html( $count ).__(' Post(s)','blossomthemes-toolkit').'</span>';
					}
					echo '</a></li>';
					echo '<style>
					.category-fallback-svg{
						background-image: url("'.$url1.'")
					}
					</style>';
				}
			}
		}
		echo '</ul></div>';
		// This is where you run the code and display the output
		$html = ob_get_clean();
        echo apply_filters( 'blossom_custom_categories_widget_filter', $html, $args, $instance );
		echo $args['after_widget'];
	}
		         
		// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'blossomthemes-toolkit' );
		}
		$categories[] = '';
		if ( isset( $instance[ 'categories' ] ) && $instance[ 'categories' ]!='' ) {
			$categories = $instance[ 'categories' ];
		}
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';

		// Widget admin form
		$ran = rand(1,1000); $ran++;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'blossomthemes-toolkit' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in new Tab', 'blossomthemes-toolkit' ); ?> </label>
        </p>
		<?php
		echo
			'<script>
			jQuery(document).ready(function($){
				$(".bttk-categories-select-'.esc_attr( absint( $ran ) ).'").chosen({
                change: _.throttle( function() { // For Customizer
                $(this).trigger( "chosen:updated" );
                }, 3000 ),
                clear: _.throttle( function() { // For Customizer
                $(this).trigger( "chosen:updated" );
                }, 4000 )
                });
				$(".bttk-categories-select-'.esc_attr( absint( $ran ) ).'").val('.json_encode($categories).').trigger("chosen:updated");
				if( $( ".bttk-categories-select-'.esc_attr( absint( $ran ) ).'" ).siblings( ".chosen-container" ).length > 1 )
				{
				 	$(".bttk-categories-select-'.esc_attr( absint( $ran ) ).'").siblings(".chosen-container").eq( 2 ).css( "display", "none" );
				}
			});
			</script>';
		?>
		<style>
		.bttk-custom-cats .chosen-container{
			width: 100% !important;
			margin-bottom: 10px;
		}
		.bttk-custom-cats .chosen-container:nth-of-type(2) {
    		display: none;
		}
		</style>
		<div class="bttk-custom-cats">
			<select name="<?php echo $this->get_field_name( 'categories[]' );?>" class="bttk-categories-select-<?php echo esc_attr( absint( $ran ) );?>" id="bttk-categories-select-<?php echo esc_attr( absint( $ran ) );?>" multiple style="width:350px;" tabindex="4">
			  	<?php
			  	$categories = get_categories();
			  	$categories = get_categories( array(
				    'orderby' => 'name',
				) );
				 
				foreach ( $categories as $category ) {
				    printf( '<option value="%1$s">%2$s</option>',
				        esc_html( $category->term_id ),
				        esc_html( $category->name )
				    );
				}
			  	?>
			</select>
		</div>
		<span class="bttk-option-side-note" class="example-text"><?php $bold = '<b>'; $boldclose = '</b>'; echo sprintf( __('To set thumbnail for categories, go to %1$sPosts > Categories%2$s and %3$sEdit%4$s the categories.','blossomthemes-toolkit'), $bold, $boldclose, $bold, $boldclose);?></span>
		<?php 
	}
		     
		// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['target'] = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';

		$instance['categories'] = '';
		if( isset( $new_instance['categories'] ) && $new_instance['categories']!='' )
		{
			$instance['categories'] = $new_instance['categories'];
		}
		return $instance;
	}
}