<?php
// Register and load the widget
function bttk_posts_category_slider_load_widget() {
    register_widget( 'bttk_posts_category_slider_widget' );
}
add_action( 'widgets_init', 'bttk_posts_category_slider_load_widget' );
 
// Creating the widget 
class bttk_posts_category_slider_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
	 
		// Base ID of your widget
		'bttk_posts_category_slider_widget', 
		 
		// Widget name will appear in UI
		__('Blossom: Posts Category Slider', 'blossomthemes-toolkit'), 
		 
		// Widget description
		array( 'description' => __( 'Simple posts slider from category.', 'blossomthemes-toolkit' ), ) 
		);
	}
 
	// Creating widget front-end
	public function widget( $args, $instance ) {

		if ( is_active_widget( false, false, $this->id_base, true ) || class_exists( 'Elementor\\Plugin' ) ) {

            wp_enqueue_style( 'owl-carousel', BTTK_FILE_URL . '/public/css/owl.carousel.min.css', array(), '2.2.1', 'all' );
			wp_enqueue_style( 'owl-theme-default', BTTK_FILE_URL . '/public/css/owl.theme.default.min.css', array(), '2.2.1', 'all' );
			wp_enqueue_script( 'owl-carousel', BTTK_FILE_URL . '/public/js/owl.carousel.min.js', array( 'jquery' ), '2.2.1', false );

        }

		$title  =  ! empty( $instance['title'] ) ? $instance['title'] : '';		
		$target = ' target="_self"';
        
        if( isset($instance['target']) && $instance['target']!='' ){
            $target = ' rel="noopener noexternal" target="_blank"';
        }
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		ob_start();
		
        if ( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
		
        $slides          = ! empty( $instance['slides'] ) ? $instance['slides'] : '1';
		$category        = ! empty( $instance['category'] ) ? $instance['category'] : '1';
		$show_arrow      = '0';
		$show_pagination = '0';
		$direction       = '0';

		if( isset( $instance['show_arrow'] ) && $instance['show_arrow'] != '' ) {
			$show_arrow = $instance['show_arrow'];
		}
		if( isset( $instance['show_pagination'] ) && $instance['show_pagination'] != '' ){
			$show_pagination = $instance['show_pagination'];
		}
		if( isset( $instance['direction'] ) && $instance['direction'] != '' ){
			$direction = $instance['direction'];
		}                                                          
        
		$ran = rand(1,100); $ran++;
		if( $direction == '1' ){
			$direction = 'true';
		}else{
			$direction = 'false';
		}
		$obj = new BlossomThemes_Toolkit_Functions;
		// This is where you run the code and display the output
		
        echo '<div id="sync1-'. esc_attr( absint( $ran ) ) . '" class="owl-carousel owl-theme">';
            $catquery = new WP_Query( 'cat='.$category.'&posts_per_page='.$slides );
            while( $catquery->have_posts() ) : $catquery->the_post(); 
                $category_img_size = apply_filters( 'bttk_category_img_size', 'post-category-slider-size' ); ?>
				<div class="item">
					<a href="<?php the_permalink();?>" class="post-thumbnail"<?php echo $target;?>>
						<?php 
                            if( has_post_thumbnail() ){
                                the_post_thumbnail( $category_img_size, array( 'itemprop' => 'image' ) );
                            }else{
                                //fallback svg
                                $obj->bttk_get_fallback_svg( $category_img_size );
                            }
                        ?>
					</a>
					<div class="carousel-title">
                        <?php
                            $category_detail = get_the_category( get_the_ID() );
                            echo '<span class="cat-links">';
                            foreach( $category_detail as $cd ){
                                echo '<a href="' . esc_url( get_category_link( $cd->term_id ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'blossomthemes-toolkit' ), $cd->name ) ) . '"'.$target.'>' . esc_html( $cd->name ) . '</a>';
                        }
                            echo '</span>';
                        ?>
						<h3 class="title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
					</div>
                </div>
				<?php 
            endwhile;
            wp_reset_postdata();
		echo '</div>';
        
		echo $obj->bttk_minify_css('<style>
		#sync1-'.esc_attr( absint( $ran ) ).' {
		  .item {
		    background: #0c83e7;
		    padding: 80px 0px;
		    margin: 5px;
		    color: #FFF;
		    -webkit-border-radius: 3px;
		    -moz-border-radius: 3px;
		    border-radius: 3px;
		    text-align: center;
		  }
		}
		.owl-theme {
			.owl-nav {
			    /*default owl-theme theme reset .disabled:hover links */
			    [class*="owl-"] {
			      transition: all .3s ease;
			      &.disabled:hover {
			       background-color: #D6D6D6;
			      }   
			    }
			    
			  }
			}

			//arrows on first carousel
			#sync1-'.esc_attr( absint( $ran ) ).'.owl-theme {
			  position: relative;
			  .owl-next, .owl-prev {
			    width: 22px;
			    height: 40px;
			    margin-top: -20px;
			    position: absolute;
			    top: 50%;
			  }
			  .owl-prev {
			    left: 10px;
			  }
			  .owl-next {
			    right: 10px;
			  }
			}
		</style>');
		echo '<script>
			jQuery(document).ready(function($) {
			  var sync1 = $("#sync1-'.esc_attr( absint( $ran ) ).'");
			  var slidesPerPage = 1;
			  var syncedSecondary = true;
			  sync1.owlCarousel({
			    items : 1,
			    slideSpeed : '.apply_filters( 'posts_category_slider_speed', '5000' ).',
			    nav: '.$show_arrow.',
			    dots: '.$show_pagination.',
			    rtl : '.$direction.',
			    autoplay: true,
			    loop: true,
			    responsiveRefreshRate : 200,
			  }); });</script>';
		$html = ob_get_clean();
		echo apply_filters( 'blossom_posts_category_slider_widget_filter', $html, $args, $instance );
		echo $args['after_widget'];
	}
         
// Widget Backend 
	public function form( $instance ) {
        $target    = ! empty( $instance['target'] ) ? $instance['target'] : '';

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'blossomthemes-toolkit' );
		}
		if ( isset( $instance[ 'category' ] ) ) {
			$category = $instance[ 'category' ];
		}
		else {
			$category = '1';
		}
		if ( isset( $instance[ 'show_arrow' ] ) ) {
			$show_arrow = $instance[ 'show_arrow' ];
		}
		else {
			$show_arrow = '';
		}
		if ( isset( $instance[ 'show_pagination' ] ) ) {
			$show_pagination = $instance[ 'show_pagination' ];
		}
		else {
			$show_pagination = '';
		}
		if ( isset( $instance[ 'slides' ] ) ) {
			$slides = $instance[ 'slides' ];
		}
		else {
			$slides = '1';
		}
		if ( isset( $instance[ 'direction' ] ) ) {
			$direction = $instance[ 'direction' ];
		}
		else {
			$direction = '';
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'blossomthemes-toolkit' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'blossomthemes-toolkit' ); ?></label> 
	        <select id="<?php echo esc_attr( $this->get_field_id('category') ); ?>" name="<?php echo esc_attr( $this->get_field_name('category') ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( get_terms( 'category', 'parent=0&hide_empty=0' ) as $term ){ ?>
	            <option <?php selected( $category, $term->term_id ); ?> value="<?php echo esc_attr( $term->term_id ); ?>"><?php echo esc_attr( $term->name ); ?></option>
	            <?php } ?>      
	        </select>
	    </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'slides' ) ); ?>"><?php esc_html_e( 'Number of Slides:', 'blossomthemes-toolkit' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'slides' ) ); ?>" min="1" name="<?php echo esc_attr( $this->get_field_name( 'slides' ) ); ?>" type="number" max="100" value="<?php echo esc_attr( $slides ); ?>"/>
            <div class="example-text"><?php esc_html_e( 'Total number of posts available in the selected category will be the maximum number of slides.', 'blossomthemes-toolkit' );?></div>
        </p>

	    <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_arrow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_arrow' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_arrow ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_arrow' ) ); ?>"><?php esc_html_e( 'Show Slider Arrows', 'blossomthemes-toolkit' ); ?></label>
        </p>

       	<p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_pagination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_pagination' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_pagination ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_pagination' ) ); ?>"><?php esc_html_e( 'Show Slider Pagination', 'blossomthemes-toolkit' ); ?></label>
        </p>

        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'direction' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $direction ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>"><?php esc_html_e( 'Change Direction', 'blossomthemes-toolkit' ); ?></label>
            <div class="example-text"><?php esc_html_e( 'Enabling this will change slider direction from \'right to left\' to \'left to right\'.', 'blossomthemes-toolkit' );?></div>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked( $target, 1 ); ?> /><?php esc_html_e( 'Open in New Tab', 'blossomthemes-toolkit' ); ?></label>
        </p>
		<?php 
	}
     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']           = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['category']        = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
		$instance['show_arrow']      = ( ! empty( $new_instance['show_arrow'] ) ) ? esc_attr( $new_instance['show_arrow'] ) : '';
		$instance['show_pagination'] = ( ! empty( $new_instance['show_pagination'] ) ) ? esc_attr( $new_instance['show_pagination'] ) : '';
		$instance['slides']          = ( ! empty( $new_instance['slides'] ) ) ? esc_attr( $new_instance['slides'] ) : '1';
		$instance['direction']       = ( ! empty( $new_instance['direction'] ) ) ? esc_attr( $new_instance['direction'] ) : '';
        $instance['target']          = ( ! empty( $new_instance['target'] ) ) ? esc_attr( $new_instance['target'] ) : '';

		return $instance;
	}
} // Class bttk_posts_category_slider_widget ends here