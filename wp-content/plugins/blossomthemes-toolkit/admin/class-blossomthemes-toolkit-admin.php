<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/blossomthemes-toolkit/
 * @since      1.0.0
 *
 * @package    Blossomthemes_Toolkit
 * @subpackage Blossomthemes_Toolkit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossomthemes_Toolkit
 * @subpackage Blossomthemes_Toolkit/admin
 * @author     blossomthemes <info@blossomthemes.com>
 */
class Blossomthemes_Toolkit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = BTTK_PLUGIN_VERSION;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$screen = get_current_screen();

		$post_types = array( 'blossom-portfolio', 'post', 'page' );
		$page_ids   = array( 'widgets', 'customize' );

		if ( in_array( $screen->post_type, $post_types ) || in_array( $screen->id, $page_ids ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blossomthemes-toolkit-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'chosen', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_style('thickbox');
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();

		$post_types = array( 'blossom-portfolio', 'post', 'page' );
		$page_ids   = array( 'widgets', 'customize' );

		if ( in_array( $screen->post_type, $post_types ) || in_array( $screen->id, $page_ids ) ) {
			wp_enqueue_media();
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blossomthemes-toolkit-admin.js', array( 'jquery','wp-color-picker' ), $this->version, true );
			wp_localize_script( $this->plugin_name, 'bttk_theme_toolkit_pro_uploader', array(
				'upload' => __( 'Upload', 'blossomthemes-toolkit' ),
				'change' => __( 'Change', 'blossomthemes-toolkit' ),
				'msg'    => __( 'Please upload valid image file.', 'blossomthemes-toolkit' )
			));
			$confirming = array( 
						'msg'       => __( 'Are you sure?', 'blossomthemes-toolkit' ),
						'category'	=> __('Select Categories','blossomthemes-toolkit')
						);
			wp_localize_script( $this->plugin_name, 'sociconsmsg', $confirming );

			wp_localize_script( $this->plugin_name, 'sociconsmsg', array(
					'msg' => __( 'Are you sure you want to delete this Social Media?', 'blossomthemes-toolkit' )));

			wp_enqueue_script( 'chosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js', array( 'jquery' ), $this->version, true );

			wp_enqueue_script( 'font-awesome', plugin_dir_url( __FILE__ ) . 'js/fontawesome/all.js', array( 'jquery'), '6.1.1', true );
			wp_enqueue_script( 'v4-shims', plugin_dir_url( __FILE__ ) . 'js/fontawesome/v4-shims.js', array( 'jquery'), '6.1.1', true );
		}
	}

    public function bttk_icon_list_enqueue(){
		$obj = new BlossomThemes_Toolkit_Functions;
		$socicons = $obj->bttk_icon_list();
		echo '<div class="bttk-icons-wrap-template"><div class="bttk-icons-wrap"><ul class="bttk-icons-list">';
		foreach ($socicons as $socicon) {
			if($socicon == 'rss'){
				echo '<li><i class="fas fa-'.$socicon.'"></i></li>';
			}
			else{
				echo '<li><i class="fab fa-'.$socicon.'"></i></li>';
			}
			
		}
		echo'</ul></div></div>';
		echo '<style>
		.bttk-icons-wrap{
			display:none;
		}
		</style>';
	}

    /**
     * Portfolio template.
    */
    function bttk_get_portfolio_template( $template ) {
	    $post = get_post();
	    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
	    if( $page_template == 'templates/blossom-portfolio.php' ){
	        if ( $theme_file = locate_template( 'templates/blossom-portfolio.php' ) ) {
                return $theme_file;
            } else {
                return BTTK_BASE_PATH . '/includes/templates/blossom-portfolio.php';
            }
	    }
	    return $template;
	}

	/**
     * Portfolio template returned.
    */
	function bttk_filter_admin_page_templates( $templates ) {
	    $templates['templates/blossom-portfolio.php'] = __( 'Portfolio Template', 'blossomthemes-toolkit' );
	    return $templates;
	}

	/**
     * Portfolio template added.
    */
	function wpte_add_portfolio_templates() {

		// If REST_REQUEST is defined (by WordPress) and is a TRUE, then it's a REST API request.
		$is_rest_route = (defined('REST_REQUEST') && REST_REQUEST);
		if (
			(is_admin() && !$is_rest_route) || // admin and AJAX (via admin-ajax.php) requests
			(!is_admin() && $is_rest_route)    // REST requests only
		) {
			add_filter('theme_page_templates', array($this, 'bttk_filter_admin_page_templates'));
		} else {
			add_filter('page_template', array($this, 'bttk_get_portfolio_template'));
		}

	}

	/**
	 * Template over-ride for single trip.
	 *
	 * @since    1.0.0
	 */
	function bttk_include_template_function( $template_path ) {
	    if ( is_singular( 'blossom-portfolio' ) ) 
	    {
	            if ( $theme_file = locate_template( 'single-blossom-portfolio.php' ) ) {
	                $template_path = $theme_file;
	            } else {
	                $template_path = BTTK_BASE_PATH . '/includes/templates/single-blossom-portfolio.php';
	            }
	    }
	    return $template_path;
	} 
	/*
	  * Add a form field in the new category page
	  * @since 1.0.0
	 */
	 public function bttk_add_category_image ( $taxonomy ) { ?>
	   <div class="form-field term-group">
	     <label for="category-image-id"><?php _e('Image', 'blossomthemes-toolkit'); ?></label>
	     <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
	     <div id="category-image-wrapper"></div>
	     <p>
	       <input type="button" class="button button-secondary bttk_tax_media_button" id="bttk_tax_media_button" name="bttk_tax_media_button" value="<?php _e( 'Add Image', 'blossomthemes-toolkit' ); ?>" />
	       <input type="button" class="button button-secondary bttk_tax_media_remove" id="bttk_tax_media_remove" name="bttk_tax_media_remove" value="<?php _e( 'Remove Image', 'blossomthemes-toolkit' ); ?>" />
	    </p>
	   </div>
	 <?php
	 }
	 
	 /*
	  * Save the form field
	  * @since 1.0.0
	 */
	 public function bttk_save_category_image ( $term_id ) {
	    if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
	      $image = $_POST['category-image-id'];
	      add_term_meta( $term_id, 'category-image-id', $image, true );
	    }
	 }
	 
	 /*
	  * Edit the form field
	  * @since 1.0.0
	 */
	 public function bttk_update_category_image ( $term, $taxonomy='' ) { ?>
	   <tr class="form-field term-group-wrap">
	     <th scope="row">
	       <label for="category-image-id"><?php _e( 'Image', 'blossomthemes-toolkit' ); ?></label>
	     </th>
	     <td>
	       <?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
	       <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
	       <div id="category-image-wrapper">
	         <?php if ( isset( $image_id ) && $image_id!='' ) { ?>
	           <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
	         <?php } ?>
	       </div>
	       <p>
	         <input type="button" class="button button-secondary bttk_tax_media_button" id="bttk_tax_media_button" name="bttk_tax_media_button" value="<?php _e( 'Add Image', 'blossomthemes-toolkit' ); ?>" />
	         <input type="button" class="button button-secondary bttk_tax_media_remove" id="bttk_tax_media_remove" name="bttk_tax_media_remove" value="<?php _e( 'Remove Image', 'blossomthemes-toolkit' ); ?>" />
	       </p>
	     </td>
	   </tr>
	 <?php
	 }

	/*
	 * Update the form field value
	 * @since 1.0.0
	 */
	 public function bttk_updated_category_image ( $term_id ) {
	   if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
	     $image = $_POST['category-image-id'];
	     update_term_meta ( $term_id, 'category-image-id', $image );
	   } else {
	     update_term_meta ( $term_id, 'category-image-id', '' );
	   }
	 }

	/*
	 * Add script
	 * @since 1.0.0
	 */
	public function bttk_add_script() { ?>
	   <script>
	     jQuery(document).ready( function($) {
	       function ct_media_upload(button_class) {
	         var _custom_media = true,
	         _orig_send_attachment = wp.media.editor.send.attachment;
	         $('body').on('click', button_class, function(e) {
	           var button_id = '#'+$(this).attr('id');
	           var send_attachment_bkp = wp.media.editor.send.attachment;
	           var button = $(button_id);
	           _custom_media = true;
	           wp.media.editor.send.attachment = function(props, attachment){
	             if ( _custom_media ) {
	               $('#category-image-id').val(attachment.id);
	               $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
	               $('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
	             } else {
	               return _orig_send_attachment.apply( button_id, [props, attachment] );
	             }
	            }
	         wp.media.editor.open(button);
	         return false;
	       });
	     }
	     ct_media_upload('.bttk_tax_media_button.button'); 
	     $('body').on('click','.bttk_tax_media_remove',function(){
	       $('#category-image-id').val('');
	       $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
	     });
	     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
	     $(document).ajaxComplete(function(event, xhr, settings) {
	       var queryStringArr = settings.data.split('&');
	       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
	         var xml = xhr.responseXML;
	         $response = $(xml).find('term_id').text();
	         if($response!=""){
	           // Clear the thumb image
	           $('#category-image-wrapper').html('');
	         }
	       }
	     });
	   });
	 </script>
	 <?php 
	}

	function bttk_custom_column_header( $columns ){
	  $columns['header_name'] = 'Thumbnail'; 
	  return $columns;
	}


	// To show the column value
	function bttk_custom_column_content( $value, $column_name, $tax_id ){
	   	$img = get_term_meta( $tax_id, 'category-image-id', false );
	   	$ret = '';
	   	if(isset($img[0]) && $img[0]!='')
		{
			$url = wp_get_attachment_image_url($img[0],'thumbnail');
			$ret = '<img src="'.esc_url($url).'" class="tax-img">';
		}
	   	return $ret;
	}

	function bttk_client_logo_template()
	{ ?>
		<div class="bttk-client-logo-template">
			<div class="link-image-repeat"><span class="cross"><a href="#"><i class="fa fa-times"></i></a></span>
				<div class="widget-client-logo-repeater" id="widget-bttktheme_client_logo_widget-2-bttkthemecompanion-logo-repeater">
		            <div class="widget-upload">
		            	<label for="widget-bttktheme_client_logo_widget-2-image"><?php _e('Upload Image','blossomthemes-toolkit');?></label><br>
		            	<input id="widget-bttktheme_client_logo_widget-2-image" class="bttk-upload link" type="hidden" name="widget-bttktheme_client_logo_widget[2][image][]" value="" placeholder="No file chosen">
						<input id="upload-widget-bttktheme_client_logo_widget-2-image" class="bttk-upload-button button" type="button" value="Upload">
						<div class="bttk-screenshot" id="widget-bttktheme_client_logo_widget-2-image-image"></div>
					</div>
					<div class="widget-feat-link">
		                <label for="widget-bttktheme_client_logo_widget-2-link"><?php _e('Featured Link','blossomthemes-toolkit');?></label> 
		                <input class="widefat featured-link" id="widget-bttktheme_client_logo_widget-2-link" name="widget-bttktheme_client_logo_widget[2][link][]" type="text" value="">            
		            </div>
	        	</div>
        	</div>
	    </div>
	<?php
	echo '<style>.bttk-client-logo-template{display:none;}</style>';
	}

	function bttk_faq_template()
	{?> 
		<div class="bttk-faq-template">
			<div class="faqs-repeat" data-id=""><span class="fa fa-times cross"></span>
	            <label for="widget-raratheme_companion_faqs_widget-2-question-1"><?php _e('Question','blossomthemes-toolkit');?></label> 
	            <input class="widefat question" id="widget-raratheme_companion_faqs_widget-2-question-1" name="widget-raratheme_companion_faqs_widget[2][question][1]" type="text" value="">   
	            <label for="widget-raratheme_companion_faqs_widget-2-answer-1"><?php _e('Answer','blossomthemes-toolkit');?></label> 
	            <textarea class="answer" id="widget-raratheme_companion_faqs_widget-2-answer-1" name="widget-raratheme_companion_faqs_widget[2][answer][1]"></textarea>         
	        </div>
	    </div>
        <?php
		echo '<style>.bttk-faq-template{display:none;}</style>';
    }

    	  /**
	* Get post types for templates
	*
	* @return array of default settings
	*/
	public function bttk_get_posttype_array() {

		$posts = array(
			'blossom-portfolio' => array( 
				'label'		  => __( 'Portfolio', 'blossomthemes-toolkit' ),
				'description' => __( 'Portfolio Post Type', 'blossomthemes-toolkit' ),
				'labels'              => array(
					'name'                  => _x( 'Portfolios', 'Post Type General Name', 'blossomthemes-toolkit' ),
					'singular_name'         => _x( 'Portfolio', 'Post Type Singular Name', 'blossomthemes-toolkit' ),
					'menu_name'             => _x( 'Portfolios', 'admin menu', 'blossomthemes-toolkit' ),
					'name_admin_bar'        => _x( 'Portfolio', 'add new on admin bar', 'blossomthemes-toolkit' ),
					'add_new'               => _x( 'Add New', 'Portfolio', 'blossomthemes-toolkit' ),
					'add_new_item'          => __( 'Add New Portfolio', 'blossomthemes-toolkit' ),
					'archives'              => __( 'Portfolio Archives', 'blossomthemes-toolkit' ),
					'attributes'            => __( 'Portfolio Attributes', 'blossomthemes-toolkit' ),
					'parent_item_colon'     => __( 'Parent Portfolio:', 'blossomthemes-toolkit' ),
					'all_items'             => __( 'All Portfolios', 'blossomthemes-toolkit' ),
					'new_item'              => __( 'New Portfolio', 'blossomthemes-toolkit' ),
					'edit_item'             => __( 'Edit Portfolio', 'blossomthemes-toolkit' ),
					'update_item'           => __( 'Update Portfolio', 'blossomthemes-toolkit' ),
					'view_item'             => __( 'View Portfolio', 'blossomthemes-toolkit' ),
					'view_items'            => __( 'View Portfolios', 'blossomthemes-toolkit' ),
					'search_items'          => __( 'Search Portfolio', 'blossomthemes-toolkit' ),
					'not_found'             => __( 'Not found', 'blossomthemes-toolkit' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'blossomthemes-toolkit' ),
					'featured_image'        => __( 'Featured Image', 'blossomthemes-toolkit' ),
					'set_featured_image'    => __( 'Set featured image', 'blossomthemes-toolkit' ),
					'remove_featured_image' => __( 'Remove featured image', 'blossomthemes-toolkit' ),
					'use_featured_image'    => __( 'Use as featured image', 'blossomthemes-toolkit' ),
					'insert_into_item'      => __( 'Insert into Portfolio', 'blossomthemes-toolkit' ),
					'uploaded_to_this_item' => __( 'Uploaded to this Portfolio', 'blossomthemes-toolkit' ),
					'items_list'            => __( 'Portfolios list', 'blossomthemes-toolkit' ),
					'items_list_navigation' => __( 'Portfolios list navigation', 'blossomthemes-toolkit' ),
					'filter_items_list'     => __( 'Filter Portfolios list', 'blossomthemes-toolkit' ),
				),
				'dashicon'			  => 'dashicons-portfolio',
				'taxonomy'			  => 'blossom_portfolio_categories',
				'taxonomy_slug'		  => 'portfolio-category',
				'has_archive'         => false,		
				'exclude_from_search' => false,
				'show_in_nav_menus'	  => true,
				'show_in_rest'   	  => true,
				'supports' 			  => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
				'rewrite'             => array( 'slug' => 'portfolio' ),
				'tax_general_name' => _x( 'Portfolios Categories', 'taxonomy general name', 'blossomthemes-toolkit' ),
				'tax_singular_name' => _x( 'Portfolio Category', 'taxonomy singular name', 'blossomthemes-toolkit' ),
				'tax_menu_name'     => __( 'Portfolios Categories', 'blossomthemes-toolkit')
			),
		);
		$posts = apply_filters( 'bttk_get_posttype_array', $posts );
		return $posts;
	}

	/**
	 * Register post types.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function bttk_register_post_types() {
		$myarray = $this->bttk_get_posttype_array();
		foreach ($myarray as $key => $value) {
			
			$args = array(
				'label'                 => $value['label'],
				'description'           => $value['description'],
				'labels'                => $value['labels'],
				'supports'              => $value['supports'],
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'show_in_rest'          => $value['show_in_rest'],
				'menu_icon'             => $value['dashicon'],
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => $value['show_in_nav_menus'],
				'can_export'            => true,
				'has_archive'           => $value['has_archive'],		
				'exclude_from_search'   => $value['exclude_from_search'],
				'publicly_queryable'    => true,
				'capability_type'       => 'page',
				'rewrite'               => $value['rewrite'],
			);
			register_post_type( $key, $args );
			if ( 'yes' === get_option( 'bttk_queue_flush_rewrite_rules' ) ) {
				update_option( 'bttk_queue_flush_rewrite_rules', 'no' );
				flush_rewrite_rules();
			}
		}
	}

	/**
	 * Register a taxonomy, post_types_categories for the post types.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	function bttk_create_post_type_taxonomies() {
		// Add new taxonomy, make it hierarchical
		$myarray = $this->bttk_get_posttype_array();
		foreach ($myarray as $key => $value) {
			if(isset($value['taxonomy']))
			{
				$labels = array(
					'name'              => $value['tax_general_name'],
					'singular_name'     => $value['tax_singular_name'],
					'search_items'      => __( 'Search Categories', 'blossomthemes-toolkit' ),
					'all_items'         => __( 'All Categories', 'blossomthemes-toolkit' ),
					'parent_item'       => __( 'Parent Categories', 'blossomthemes-toolkit' ),
					'parent_item_colon' => __( 'Parent Categories:', 'blossomthemes-toolkit' ),
					'edit_item'         => __( 'Edit Categories', 'blossomthemes-toolkit' ),
					'update_item'       => __( 'Update Categories', 'blossomthemes-toolkit' ),
					'add_new_item'      => __( 'Add New Categories', 'blossomthemes-toolkit' ),
					'new_item_name'     => __( 'New Categories Name', 'blossomthemes-toolkit' ),
					'menu_name'         => $value['tax_menu_name'],
				);

				$args = array(
					'hierarchical'      => true,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'show_in_rest'      => true,
					'rewrite'           => array( 'slug' => $value['taxonomy_slug'], 'hierarchical' => true ),
				);
				register_taxonomy( $value['taxonomy'], array( $key ), $args );
			}
		}
	} 
}
	 