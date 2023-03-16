<?php
/**
 * Team Member Widget
 *
 * @package BlossomThemes_Toolkit
 */

// register BlossomThemes_Toolkit_Team_Member_Widget widget
function bttk_register_team_member_widget(){
    register_widget( 'BlossomThemes_Toolkit_Team_Member_Widget' );
}
add_action('widgets_init', 'bttk_register_team_member_widget');
 
 /**
 * Adds BlossomThemes_Toolkit_Team_Member_Widget widget.
 */
class BlossomThemes_Toolkit_Team_Member_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'bttk_description_widget', // Base ID
            __( 'Blossom: Team Member', 'blossomthemes-toolkit' ), // Name
            array( 'description' => __( 'A Team Member Widget.', 'blossomthemes-toolkit' ), ) // Args
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
        
        $obj         = new BlossomThemes_Toolkit_Functions();
        $name        = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
        $designation = ! empty( $instance['designation'] ) ? $instance['designation'] : '' ;        
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $linkedin    = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $twitter     = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $facebook    = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $instagram   = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $youtube     = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $dribbble    = ! empty( $instance['dribbble'] ) ? $instance['dribbble'] : '';
        $behance     = ! empty( $instance['behance'] ) ? $instance['behance'] : '';
        $image       = ! empty( $instance['image'] ) ? $instance['image'] : '';

        $target = 'rel="noopener noexternal" target="_blank"';
        if( isset($instance['target']) && $instance['target']!='' ){
            $target = 'target="_self"';
        }

        echo $args['before_widget']; 
        ob_start();
        ?>
            <div class="bttk-team-holder">
                <div class="bttk-team-inner-holder">
                    <?php
                    if( $image ){
                        /** Added to work for demo content compatible */
                        $attachment_id = $image;
                        if ( !filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
                            $attachment_id = $obj->bttk_get_attachment_id( $image );
                        }
                        $icon_img_size = apply_filters( 'bttk_team_member_icon_img_size', 'thumbnail' );
                        ?>
                        <div class="image-holder">
                            <?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, array( 'alt' => esc_attr( $name )));?>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="text-holder">
                    <?php 
                        if( $name ) { echo '<span class="name">' . esc_html( $name ) . '</span>'; }
                        if( isset( $designation ) && $designation!='' ){
                            echo '<span class="designation">' . esc_html( $designation ) .  '</span>';
                        }
                        if( $description ) echo '<div class="description">' . wpautop( wp_kses_post( $description ) ) . '</div>';
                    ?>                              
                    </div>
                    <ul class="social-profile">
                        <?php if( isset( $linkedin ) && $linkedin!='' ) { echo '<li><a '.$target.' href="'.esc_url($linkedin).'"><i class="fab fa-linkedin"></i></a></li>'; }?>
                        <?php if( isset( $twitter ) && $twitter!='' ) { echo '<li><a '.$target.' href="'.esc_url($twitter).'"><i class="fab fa-twitter"></i></a></li>'; }?>
                        <?php if( isset( $facebook ) && $facebook!='' ) { echo '<li><a '.$target.' href="'.esc_url($facebook).'"><i class="fab fa-facebook"></i></a></li>'; }?>
                        <?php if( isset( $instagram ) && $instagram!='' ) { echo '<li><a '.$target.' href="'.esc_url($instagram).'"><i class="fab fa-instagram"></i></a></li>'; }?>
                        <?php if( isset( $youtube ) && $youtube!='' ) { echo '<li><a '.$target.' href="'.esc_url($youtube).'"><i class="fab fa-youtube"></i></a></li>'; }?>
                        <?php if( isset( $dribbble ) && $dribbble!='' ) { echo '<li><a '.$target.' href="'.esc_url($dribbble).'"><i class="fab fa-dribbble"></i></a></li>'; }?>
                        <?php if( isset( $behance ) && $behance!='' ) { echo '<li><a '.$target.' href="'.esc_url($behance).'"><i class="fab fa-behance"></i></a></li>'; }?>
                    </ul>
                </div>
            </div>

            <div class="bttk-team-holder-modal">
                <div class="bttk-team-inner-holder-modal">
                    <?php if( $image ){ ?>
                        <div class="image-holder">
                            <?php echo wp_get_attachment_image( $attachment_id, $icon_img_size, false, array( 'alt' => esc_attr( $name )));?>
                        </div>
                    <?php } ?>

                    <div class="text-holder">
                    <?php 
                        if( $name ) { echo '<span class="name"> ' . esc_html( $name ) . '</span>'; }
                        if( isset( $designation ) && $designation!='' ){
                            echo '<span class="designation">' . esc_html( $designation ) . '</span>';
                        }
                        if( $description ) echo '<div class="description">' . wpautop( wp_kses_post( $description ) ) . '</div>';
                    ?>                              
                    </div>
                    <ul class="social-profile">
                        <?php if( isset( $linkedin ) && $linkedin!='' ) { echo '<li><a '.$target.' href="'.esc_url($linkedin).'"><i class="fab fa-linkedin"></i></a></li>'; }?>
                        <?php if( isset( $twitter ) && $twitter!='' ) { echo '<li><a '.$target.' href="'.esc_url($twitter).'"><i class="fab fa-twitter"></i></a></li>'; }?>
                        <?php if( isset( $facebook ) && $facebook!='' ) { echo '<li><a '.$target.' href="'.esc_url($facebook).'"><i class="fab fa-facebook"></i></a></li>'; }?>
                        <?php if( isset( $instagram ) && $instagram!='' ) { echo '<li><a '.$target.' href="'.esc_url($instagram).'"><i class="fab fa-instagram"></i></a></li>'; }?>
                        <?php if( isset( $youtube ) && $youtube!='' ) { echo '<li><a '.$target.' href="'.esc_url($youtube).'"><i class="fab fa-youtube"></i></a></li>'; }?>
                        <?php if( isset( $dribbble ) && $dribbble!='' ) { echo '<li><a '.$target.' href="'.esc_url($dribbble).'"><i class="fab fa-dribbble"></i></a></li>'; }?>
                        <?php if( isset( $behance ) && $behance!='' ) { echo '<li><a '.$target.' href="'.esc_url($behance).'"><i class="fab fa-behance"></i></a></li>'; }?>
                    </ul>
                </div>
                <a href="javascript:void(0);" class="close_popup"></a>
            </div>
        <?php
        echo 
        "
        <style>
            .bttk-team-holder-modal{
                display: none;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
              $('.bttk-team-holder').on('click', function(){
                $(this).siblings('.bttk-team-holder-modal').addClass('show');
                $(this).siblings('.bttk-team-holder-modal').css('display', 'block');
              });

              $('.close_popup').on('click',function(){
                $(this).parent('.bttk-team-holder-modal').removeClass('show');
                $(this).parent().css('display', 'none');
              }); 
            });
        </script>";
        $html = ob_get_clean();
        echo apply_filters( 'blossom_team_member_widget_filter', $html, $args, $instance );    
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
        
        $obj = new BlossomThemes_Toolkit_Functions();
        $name        = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $linkedin    = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $twitter     = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $facebook    = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $instagram   = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $youtube     = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $dribbble    = ! empty( $instance['dribbble'] ) ? $instance['dribbble'] : '';
        $behance     = ! empty( $instance['behance'] ) ? $instance['behance'] : '';
        $designation = ! empty( $instance['designation'] ) ? $instance['designation'] : '';
        $image       = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $target      = ! empty( $instance['target'] ) ? $instance['target'] : '';
        ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>"><?php esc_html_e( 'Designation', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'designation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'designation' ) ); ?>" type="text" value="<?php echo esc_attr( $designation ); ?>" />            
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'blossomthemes-toolkit' ); ?></label>
            <textarea name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php print $description; ?></textarea>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in Same Tab', 'blossomthemes-toolkit' ); ?> </label>
        </p>
        
        <?php $obj->bttk_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), $image, __( 'Upload Photo', 'blossomthemes-toolkit' ) ); ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn Profile', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="text" value="<?php echo esc_url( $linkedin ); ?>" />            
        </p>
        
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter Profile', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="text" value="<?php echo esc_url( $twitter ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook Profile', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_url( $facebook ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram Profile', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_url( $instagram ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube Profile', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="text" value="<?php echo esc_url( $youtube ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>"><?php esc_html_e( 'Dribbble Profile', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dribbble' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dribbble' ) ); ?>" type="text" value="<?php echo esc_url( $dribbble ); ?>" />            
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'behance' ) ); ?>"><?php esc_html_e( 'Behance Profile', 'blossomthemes-toolkit' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'behance' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'behance' ) ); ?>" type="text" value="<?php echo esc_url( $behance ); ?>" />            
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
        
        $instance['name']        = ! empty( $new_instance['name'] ) ? sanitize_text_field( $new_instance['name'] ) : '' ;
        $instance['description'] = ! empty( $new_instance['description'] ) ? wp_kses_post( $new_instance['description'] ) : '';
        $instance['designation'] = ! empty( $new_instance['designation'] ) ? esc_attr( $new_instance['designation'] ) : '';
        $instance['target']      = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
        $instance['linkedin']    = ! empty( $new_instance['linkedin'] ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
        $instance['twitter']     = ! empty( $new_instance['twitter'] ) ? esc_url_raw( $new_instance['twitter'] ) : '';
        $instance['facebook']    = ! empty( $new_instance['facebook'] ) ? esc_url_raw( $new_instance['facebook'] ) : '';
        $instance['instagram']   = ! empty( $new_instance['instagram'] ) ? esc_url_raw( $new_instance['instagram'] ) : '';
        $instance['youtube']     = ! empty( $new_instance['youtube'] ) ? esc_url_raw( $new_instance['youtube'] ) : '';
        $instance['dribbble']    = ! empty( $new_instance['dribbble'] ) ? esc_url_raw( $new_instance['dribbble'] ) : '';
        $instance['behance']     = ! empty( $new_instance['behance'] ) ? esc_url_raw( $new_instance['behance'] ) : '';
        $instance['image']       = ! empty( $new_instance['image'] ) ? esc_attr( $new_instance['image'] ) : '';

        return $instance;
    }
    
}  // class BlossomThemes_Toolkit_Team_Member_Widget / class BlossomThemes_Toolkit_Team_Member_Widget 