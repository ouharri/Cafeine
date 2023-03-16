<?php
/**
 * Widget Featured
 *
 * @package Rttk
 */
 
// register BlossomTheme_Featured_Page_Widget widget
function blossom_register_featured_page_widget() {
    register_widget( 'BlossomTheme_Featured_Page_Widget' );
}
add_action( 'widgets_init', 'blossom_register_featured_page_widget' );
 
 /**
 * Adds BlossomTheme_Featured_Page_Widget widget.
 */
class BlossomTheme_Featured_Page_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'blossomtheme_featured_page_widget', // Base ID
            __( 'Blossom: Featured Page Widget', 'blossomthemes-toolkit' ), // Name
            array( 'description' => __( 'A Featured Page Widget', 'blossomthemes-toolkit' ), ) // Args
        );
    }

    function BlossomTheme_Featured_Page_Image_Alignment()
    {
        $array = apply_filters('bttk_img_alignment',array(
            'right'     => __('Right','blossomthemes-toolkit'),
            'left'      => __('Left','blossomthemes-toolkit'),
            'centered'  => __('Centered','blossomthemes-toolkit')
        ));
        return $array;
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $read_more         = !empty( $instance['readmore'] ) ? $instance['readmore'] : __( 'Read More', 'blossomthemes-toolkit' );      
        $show_feat_img     = !empty( $instance['show_feat_img'] ) ? $instance['show_feat_img'] : '' ;  
        $show_page_content = !empty( $instance['show_page_content'] ) ? $instance['show_page_content'] : '' ;        
        $show_readmore     = !empty( $instance['show_readmore'] ) ? $instance['show_readmore'] : '' ;        
        $page_list         = !empty( $instance['page_list'] ) ? $instance['page_list'] : 1 ;
        $image_alignment   = !empty( $instance['image_alignment'] ) ? $instance['image_alignment'] : 'right' ;
        if( !isset( $page_list ) || $page_list == '' ) return;
        
        $post_no = get_post($page_list); 
        
        $target = 'rel="noopener noexternal" target="_blank"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_self"';
        }
        
        if( $post_no ){
            setup_postdata( $post_no );
            echo $args['before_widget'];
            ob_start();
                ?>
                <div class="widget-featured-holder <?php echo esc_attr($image_alignment);?>">
                    <?php
                        echo is_page_template( 'templates/about.php' ) ? '<h1 class="widget-title">' : $args['before_title']; //Done for SEO
                        echo esc_html( $post_no->post_title );
                        echo is_page_template( 'templates/about.php' ) ? '</h1>' : $args['after_title'];
                    ?>
                    <?php if( has_post_thumbnail( $post_no ) && $show_feat_img ){ ?>
                    <div class="img-holder">
                        <a <?php echo $target;?> href="<?php the_permalink( $post_no ); ?>">
                            <?php 
                            $featured_img_size = apply_filters( 'featured_img_size', 'full' );
                            echo get_the_post_thumbnail( $post_no, $featured_img_size ); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="text-holder">
                        <div class="featured_page_content">
                            <?php 
                            if( isset( $show_page_content ) && $show_page_content != '' ){
                                echo do_shortcode( $post_no->post_content );                                
                            }else{
                                echo apply_filters( 'the_excerpt', get_the_excerpt( $post_no ) );                                
                            }
                            
                            if( isset( $show_readmore ) && $show_readmore != '' && ! $show_page_content ){ ?>
                                <a href="<?php the_permalink( $post_no ); ?>" <?php echo $target;?> class="btn-readmore"><?php echo esc_html( $read_more );?></a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>                    
                </div>        
            <?php   
            $html = ob_get_clean();
            echo apply_filters( 'blossom_featured_page_widget_filter', $html, $args, $instance );
            wp_reset_postdata();
            echo $args['after_widget'];   
        }
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $postlist[0] = array(
            'value' => 0,
            'label' => __('--Choose--', 'blossomthemes-toolkit'),
        );
        $arg = array( 'posts_per_page' => -1, 'post_type' => array( 'page' ) );
        $posts = get_posts($arg); 
        
        foreach( $posts as $p ){ 
            $postlist[$p->ID] = array(
                'value' => $p->ID,
                'label' => $p->post_title
            );
        }
        
        $read_more         = !empty( $instance['readmore'] ) ? $instance['readmore'] : __( 'Read More', 'blossomthemes-toolkit' );
        $target    = ! empty( $instance['target'] ) ? $instance['target'] : '';      
        $show_feat_img     = !empty( $instance['show_feat_img'] ) ? $instance['show_feat_img'] : '' ;  
        $show_page_title   = !empty( $instance['show_page_title'] ) ? $instance['show_page_title'] : '' ;        
        $show_page_content = !empty( $instance['show_page_content'] ) ? $instance['show_page_content'] : '' ;        
        $show_readmore     = !empty( $instance['show_readmore'] ) ? $instance['show_readmore'] : '' ;        
        $page_list         = !empty( $instance['page_list'] ) ? $instance['page_list'] : 1 ;
        $image_alignment   = !empty( $instance['image_alignment'] ) ? $instance['image_alignment'] : 'right' ;
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'page_list' ) ); ?>"><?php esc_html_e( 'Page:', 'blossomthemes-toolkit' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'page_list' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'page_list' ) ); ?>" class="widefat">
                <?php
                foreach ( $postlist as $single_post ) { ?>
                    <option value="<?php echo $single_post['value']; ?>" id="<?php echo esc_attr( $this->get_field_id( $single_post['label'] ) ); ?>" <?php selected( $single_post['value'], $page_list ); ?>><?php echo $single_post['label']; ?></option>
                <?php } ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_page_content' ) ); ?>" class="check-btn-wrap">
                <input class="full-content" id="<?php echo esc_attr( $this->get_field_id( 'show_page_content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_page_content' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_page_content ); ?>/>
                <?php esc_html_e( 'Show Page Full Content', 'blossomthemes-toolkit' ); ?>
            </label>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_feat_img' ) ); ?>" class="check-btn-wrap">
                <input id="<?php echo esc_attr( $this->get_field_id( 'show_feat_img' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_feat_img' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_feat_img ); ?>/>
                <?php esc_html_e( 'Show Featured Image', 'blossomthemes-toolkit' ); ?>
            </label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_alignment' ) ); ?>"><?php esc_html_e( 'Image Alignment:', 'blossomthemes-toolkit' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'image_alignment' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_alignment' ) ); ?>" class="widefat">
                <?php
                $align_options = $this->BlossomTheme_Featured_Page_Image_Alignment();
                foreach ( $align_options as $key=>$val ) { ?>
                    <option value="<?php echo $key; ?>" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" <?php selected( $key, $image_alignment ); ?>><?php echo $val; ?></option>
                <?php } ?>
            </select>
        </p>

        
        <div class="read-more" <?php echo isset($show_page_content) && ($show_page_content =='1') ? "style='display:none;'" : "style='display:block;'" ;?>> 
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_readmore' ) ); ?>" class="check-btn-wrap">
                    <input id="<?php echo esc_attr( $this->get_field_id( 'show_readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_readmore' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_readmore ); ?>/>
                    <?php esc_html_e( 'Show Read More', 'blossomthemes-toolkit' ); ?>
                </label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in Same Tab', 'blossomthemes-toolkit' ); ?> </label>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>"><?php esc_html_e( 'Read More Text', 'blossomthemes-toolkit' ); ?></label> 
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore' ) ); ?>" type="text" value="<?php echo esc_attr( $read_more ); ?>" />
            </p>
        </div>
        <?php
        echo 
        '<script>
        jQuery(document).ready(function($){
            $(".full-content").on("change", function(e) {
                var checked = $(this).is(":checked");
                if( checked )
                {
                    $(this).parent().parent().siblings(".read-more").hide();
                }
                else{
                    $(this).parent().parent().siblings(".read-more").show();
                }
            });
        });
        </script>'; 
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title']             = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['show_page_title']   = ! empty( $new_instance['show_page_title'] ) ? absint( $new_instance['show_page_title'] ) : '' ;
        $instance['show_page_content'] = ! empty( $new_instance['show_page_content'] ) ? absint( $new_instance['show_page_content'] ) : '' ;
        $instance['show_readmore']     = ! empty( $new_instance['show_readmore'] ) ? absint( $new_instance['show_readmore'] ) : '' ;
        $instance['image_alignment']   = ! empty( $new_instance['image_alignment'] ) ? sanitize_text_field( $new_instance['image_alignment'] ) : 'right' ;
        $instance['readmore']          = ! empty( $new_instance['readmore'] ) ? sanitize_text_field( $new_instance['readmore'] ) : __( 'Read More', 'blossomthemes-toolkit' );
        $instance['page_list']         = ! empty( $new_instance['page_list'] ) ? absint( $new_instance['page_list'] ) : 1;
        $instance['show_feat_img']     = ! empty( $new_instance['show_feat_img'] ) ? absint( $new_instance['show_feat_img'] ) : '';
        $instance['target']         = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        return $instance;
    }

} // class BlossomTheme_Featured_Page_Widget
