<?php
/**
 * Stat Counter Widget
 *
 * @package Rttk_Pro
 */

// register BlossomThemes_Toolkit_Stat_Counter_Widget widget
function bttktheme_stat_counter_widget(){
    register_widget( 'BlossomThemes_Toolkit_Stat_Counter_Widget' );
}
add_action('widgets_init', 'bttktheme_stat_counter_widget');
 
 /**
 * Adds BlossomThemes_Toolkit_Stat_Counter_Widget widget.
 */
class BlossomThemes_Toolkit_Stat_Counter_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
            'blossomthemes_stat_counter_widget', // Base ID
            __( 'Blossom: Stat Counter Widget', 'blossomthemes-toolkit' ), // Name
            array( 'description' => __( 'Widget for stat counter.', 'blossomthemes-toolkit' ), ) // Args
        );
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

        if ( is_active_widget( false, false, $this->id_base, true ) || class_exists( 'Elementor\\Plugin' ) ) {

            wp_enqueue_script( 'odometer', BTTK_FILE_URL . '/public/js/odometer.min.js', array( 'jquery' ), '0.4.6', false );
            wp_enqueue_script( 'waypoint', BTTK_FILE_URL . '/public/js/waypoint.min.js', array( 'jquery' ), '2.0.3', false );
        }  
        
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : '' ;        
        $counter    = ! empty( $instance['counter'] ) ? $instance['counter'] : '';
        $show_comma = ! empty( $instance['show_comma'] ) ? $instance['show_comma'] : '';
        $icon       = ! empty( $instance['icon'] ) ? $instance['icon'] : '';

        echo $args['before_widget'];
        ob_start(); 
        $ran = rand(1,1000); $ran++;
        ?>
        <div class="col">
            <div class="blossomthemes-sc-holder">
                <?php 
                if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title']; 
                if( $icon ){ ?>
                    <div class="icon-holder">
                        <?php do_action( 'widget_blossomthemes_stat_counter_before_icon' ); ?>
                        <i class="<?php echo esc_attr( $icon ); ?>"></i><?php do_action('widget_stat_counter_after_icon')?>
                    </div>
                    <?php 
                } 
                
                if( $counter ) { 
                    $delay = ($ran/1000)*100; ?>
                    <div class="hs-counter<?php echo esc_attr( absint( $ran ) );?> hs-counter wow fadeInDown" data-wow-duration="<?php echo $delay/100; echo 's';?>">
                        <div class="hs-counter-count<?php echo esc_attr( absint( $ran ) );?> odometer odometer<?php echo esc_attr( absint( $ran ) );?>" data-count="<?php echo abs(intval($counter)); ?>">0</div>
                    </div>
                    <?php 
                } 
                ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'bttk_sc', $html, $args, $title, $icon, $counter, $ran );  
         
        echo '<script>
        jQuery( document ).ready(function($) {
            $(".odometer'.esc_attr( absint( $ran ) ).'").waypoint(function() {
               setTimeout(function() {
                  $(".odometer'.esc_attr( absint( $ran ) ).'").html($(".odometer'.esc_attr( absint( $ran ) ).'").data("count"));
                }, 500);
              }, {
                offset: 800,
                triggerOnce: true
            });
        });</script>';

        if( ! $show_comma ){
            echo '<style>
            .widget_blossomthemes_stat_counter_widget .blossomthemes-sc-holder .hs-counter-count' . esc_attr( absint( $ran ) ) . ' span.odometer-formatting-mark{
                display:none;
            }
            </style>';
        }
        $html = ob_get_clean();
        echo apply_filters( 'blossom_stat_counter_widget_filter', $html, $args, $instance );
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        
        $obj     = new BlossomThemes_Toolkit_Functions();
        $default = array( 
            'title'      => '', 
            'counter'    => '', 
            'show_comma' => '', 
            'icon'       => '',
        );
        $instance = wp_parse_args( (array) $instance, $default );
        
        $title      = $instance['title'];        
        $counter    = $instance['counter'];
        $show_comma = $instance['show_comma'];
        $icon       = $instance['icon'];
        ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'counter' ) ); ?>"><?php esc_html_e( 'Counter', 'blossomthemes-toolkit' ); ?></label>
            <input name="<?php echo esc_attr( $this->get_field_name( 'counter' ) ); ?>" type="text" id="<?php echo esc_attr( $this->get_field_id( 'counter' ) ); ?>" value="<?php echo absint( $counter ); ?>" class="small-text" />         
        </p>
        
        <p>
            <input class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_comma' ) ); ?>" value="1" <?php checked( '1', $show_comma ); ?> name="<?php echo esc_attr( $this->get_field_name( 'show_comma' ) ); ?>" type="checkbox" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_comma' ) ); ?>"><?php esc_html_e( 'Show Comma', 'blossomthemes-toolkit' ); ?></label>
        </p>
        
        <p style="position: relative;">
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icons', 'blossomthemes-toolkit' ); ?></label><br />
            <?php
            $class = "";
            if( isset($icon) && $icon!='' )
            {
                $class = "yes";
            }
            ?>
            <span class="icon-receiver <?php echo $class;?>"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
            <input class="hidden-icon-input" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" value="<?php echo esc_attr( $icon ); ?>" />            
        </p>
        <input class="bttk-sc-icons" type="text" value="" placeholder="<?php _e('Search Icons Here...','blossomthemes-toolkit'); ?>" />
        
        <?php $obj->bttk_get_icon_list(); ?>
                        
        <?php
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
        
        $instance['title']      = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' ;
        $instance['counter']    = ! empty( $new_instance['counter'] ) ? absint( $new_instance['counter'] ) : '';
        $instance['icon']       = ! empty( $new_instance['icon'] ) ? esc_attr( $new_instance['icon'] ) : '';
        $instance['show_comma'] = ! empty( $new_instance['show_comma'] ) ? absint( $new_instance['show_comma'] ) : '';
       
        return $instance;
    }
    
}  // class BlossomThemes_Toolkit_Stat_Counter_Widget