<?php
/**
 * Facebook Page Plugin widget
 *
 * @package Bttk
 */

// register Bttk_Facebook_Page_Widget widget.
function bttk_register_facebook_page_widget(){
    register_widget( 'Bttk_Facebook_Page_Widget' );
}
add_action('widgets_init', 'bttk_register_facebook_page_widget');
 
 /**
 * Adds Bttk_Facebook_Page_Widget widget.
 */
class Bttk_Facebook_Page_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
			'bttk_facebook_page_widget', // Base ID
			__( 'Blossom: Facebook Page', 'blossomthemes-toolkit' ), // Name
			array( 'description' => __( 'A widget that shows Facebook Page Box', 'blossomthemes-toolkit' ), ) // Args
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
        
        $tab                = '';
        $title              = ! empty( $instance['title'] ) ? $instance['title'] : '';		
        $page_url           = ! empty( $instance['page_url']) ? $instance['page_url'] : '';
        $height             = ! empty( $instance['height'] ) ? $instance['height'] : 400;
        $show_faces         = ! empty( $instance['show_faces'] ) ? $instance['show_faces'] : 'false';
        $small_header       = ! empty( $instance['small_header'] ) ? $instance['small_header'] : 'false';
        $hide_cover_photo   = ! empty( $instance['hide_cover_photo'] ) ? $instance['hide_cover_photo'] : 'false';
        $show_timeline_tab  = ! empty( $instance['show_timeline_tab'] ) ? $instance['show_timeline_tab'] : 'true';
        $show_event_tab     = ! empty( $instance['show_event_tab'] ) ? $instance['show_event_tab'] : '';
        $show_msg_tab       = ! empty( $instance['show_msg_tab'] ) ? $instance['show_msg_tab'] : '';
        
        if( $show_event_tab || $show_msg_tab || $show_timeline_tab ){
            $tab = 'data-tabs="';
            $tabs = array();
            
            if( $show_timeline_tab ) $tabs[] .= 'timeline';
            if( $show_event_tab )   $tabs[] .= 'events';
            if( $show_msg_tab )     $tabs[] .= 'messages';            
            
            $tab .= implode( ', ', $tabs );
            $tab .= '"';            
        }
        
        echo $args['before_widget'];
        ob_start(); ?>
        <div id="fb-root"></div>
        <script>
        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        
        <div class="bttk-facebook-page-box">
        <?php
        if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title']; ?>
        <div class="fb-page" data-href="<?php echo esc_url( $page_url ); ?>" data-height="<?php echo esc_attr( $height ); ?>" data-hide-cover="<?php echo esc_attr( $hide_cover_photo ); ?>" data-show-facepile="<?php echo esc_attr( $show_faces ); ?>" data-small-header="<?php echo esc_attr( $small_header );?>" <?php echo $tab; ?> ></div>
        </div>        
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'blossom_facebook_page_widget_filter', $html, $args, $instance ); 
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
        
        $title              = ! empty( $instance['title'] ) ? $instance['title'] : '';		
        $page_url           = ! empty( $instance['page_url']) ? $instance['page_url'] : '';
        $height             = ! empty( $instance['height'] ) ? $instance['height'] : 400;
        $show_faces         = ! empty( $instance['show_faces'] ) ? $instance['show_faces'] : '1';
        $small_header       = ! empty( $instance['small_header'] ) ? $instance['small_header'] : '1';
        $hide_cover_photo   = ! empty( $instance['hide_cover_photo'] ) ? $instance['hide_cover_photo'] : '';
        $show_timeline_tab  = ! empty( $instance['show_timeline_tab'] ) ? $instance['show_timeline_tab'] : '';
        $show_event_tab     = ! empty( $instance['show_event_tab'] ) ? $instance['show_event_tab'] : '';
        $show_msg_tab       = ! empty( $instance['show_msg_tab'] ) ? $instance['show_msg_tab'] : '';
        ?>
		
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />            
		</p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>"><?php esc_html_e( 'Facebook Page URL', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_url' ) ); ?>" type="text" value="<?php echo esc_url( $page_url ); ?>" />
		</p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="number" step="1" min="70" value="<?php echo esc_attr( $height ); ?>" />
		</p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_faces' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_faces' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_faces ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_faces' ) ); ?>"><?php esc_html_e( "Show Friend's Faces", 'blossomthemes-toolkit' ); ?></label>
		</p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'small_header' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'small_header' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $small_header ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'small_header' ) ); ?>"><?php esc_html_e( 'Use Small Header', 'blossomthemes-toolkit' ); ?></label>
		</p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'hide_cover_photo' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_cover_photo' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $hide_cover_photo ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'hide_cover_photo' ) ); ?>"><?php esc_html_e( 'Hide Cover Photo', 'blossomthemes-toolkit' ); ?></label>
		</p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_timeline_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_timeline_tab' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_timeline_tab ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_timeline_tab' ) ); ?>"><?php esc_html_e( 'Show Timeline Tab', 'blossomthemes-toolkit' ); ?></label>
		</p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_event_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_event_tab' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_event_tab ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_event_tab' ) ); ?>"><?php esc_html_e( 'Show Event Tab', 'blossomthemes-toolkit' ); ?></label>
		</p>
        
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'show_msg_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_msg_tab' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_msg_tab ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_msg_tab' ) ); ?>"><?php esc_html_e( 'Show Message Tab', 'blossomthemes-toolkit' ); ?></label>
		</p>
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
		
        $instance['title']              = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['page_url']           = ! empty( $new_instance['page_url']) ? esc_url_raw( $new_instance['page_url'] ) : '';
        $instance['height']             = ! empty( $new_instance['height'] ) ? absint( $new_instance['height'] ) : 400;
        $instance['show_faces']         = ! empty( $new_instance['show_faces'] ) ? esc_attr( $new_instance['show_faces']) : 'false';
        $instance['small_header']       = ! empty( $new_instance['small_header'] ) ? esc_attr( $new_instance['small_header']) : 'false';
        $instance['hide_cover_photo']   = ! empty( $new_instance['hide_cover_photo'] ) ? esc_attr( $new_instance['hide_cover_photo']) : 'false';
        $instance['show_timeline_tab']  = ! empty( $new_instance['show_timeline_tab'] ) ? esc_attr( $new_instance['show_timeline_tab'] ) : '';
        $instance['show_event_tab']     = ! empty( $new_instance['show_event_tab'] ) ? esc_attr( $new_instance['show_event_tab'] ) : '';
        $instance['show_msg_tab']       = ! empty( $new_instance['show_msg_tab'] ) ? esc_attr( $new_instance['show_msg_tab'] ) : '';
        
        return $instance;
	}

}  // class Bttk_Facebook_Page_Widget / class Bttk_Facebook_Page_Widget 