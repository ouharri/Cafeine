<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function fashion_icon_theme_setup(){
	/*
	    * Make child theme available for translation.
	    * Translations can be filed in the /languages/ directory.
	*/
	load_child_theme_textdomain( 'fashion-icon', get_stylesheet_directory() . '/languages' );

    add_image_size( 'fashion-icon-blog-archive', 330, 255, true);
    add_image_size( 'fashion-icon-author-bio-image', 300, 300, true );
}
add_action( 'after_setup_theme', 'fashion_icon_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
if( ! function_exists( 'fashion_icon_scripts' ) ):
	function fashion_icon_scripts() {
		$my_theme = wp_get_theme();
    	$version = $my_theme['Version'];

       if( blossom_fashion_is_woocommerce_activated() ){
            $dependencies = array( 'blossom-fashion-woocommerce', 'owl-carousel', 'animate', 'blossom-fashion-google-fonts' );  
        }else{
            $dependencies = array( 'owl-carousel', 'animate', 'blossom-fashion-google-fonts' );
        }

        wp_enqueue_style( 'fashion-icon-parent-style', get_template_directory_uri() . '/style.css', $dependencies );

		wp_enqueue_script( 'fashion-icon', get_stylesheet_directory_uri(). '/js/custom.js', array( 'jquery' ), $version, true );
	}
endif;
add_action( 'wp_enqueue_scripts', 'fashion_icon_scripts' );

if( ! function_exists( 'fashion_icon_author_image' ) ) :
/**
 * Author Bio Image Size Filter
 */
function fashion_icon_author_image(){
    return 'fashion-icon-author-bio-image';
}
endif;
add_filter( 'author_bio_img_size', 'fashion_icon_author_image' );

//Remove a function from the parent theme
function remove_parent_filters(){ //Have to do it after theme setup, because child theme functions are loaded first
    remove_action( 'customize_register', 'blossom_fashion_customizer_theme_info' );
    remove_action( 'wp_enqueue_scripts', 'blossom_fashion_dynamic_css', 99 );
}
add_action( 'init', 'remove_parent_filters' );

function blossom_fashion_body_classes( $classes ) {
	$home_layout_option = get_theme_mod( 'home_layout_option', 'two' );

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image custom-background';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color custom-background';
	}

    if ( is_single() || is_page() ) {
        $classes[] = 'underline';
    }
    
    $classes[] = blossom_fashion_sidebar_layout();

    if( $home_layout_option == 'two' ) {
		$classes[] = 'homepage-layout-two';
	}

	return $classes;
}

function fashion_icon_customize_register( $wp_customize ) {
	$wp_customize->add_section( 
        'theme_info', 
        array(
            'title'     => __( 'Demo & Documentation', 'fashion-icon' ),
            'priority'  => 6,
        )
    );

    /** Important Links */
    $wp_customize->add_setting(
        'theme_info_link',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );

    $theme_info = '<p>';
    $theme_info .= sprintf( __( '%1$sDemo Link:%2$s %3$sClick here.%4$s', 'fashion-icon' ), '<strong>', '</strong>' , '<a href="' . esc_url( 'https://blossomthemes.com/theme-demo/?theme=fashion-icon' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p><p>';
    $theme_info .= sprintf( __( '%1$sDocumentation Link:%2$s %3$sClick here.%4$s', 'fashion-icon' ), '<strong>', '</strong>' , '<a href="' . esc_url( 'https://docs.blossomthemes.com/docs/fashion-icon/' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p>';

    $wp_customize->add_control( new Blossom_Fashion_Note_Control( $wp_customize,
            'theme_info_link',
            array(
                'section'       => 'theme_info',
                'description'   => $theme_info,
            )
        )        
    );

    /** Site Identity */
    $wp_customize->add_setting( 
        'site_title_font', 
        array(
            'default' => array(                                         
                'font-family' => 'Marcellus',
                'variant'     => 'regular',
            ),
            'sanitize_callback' => array( 'Blossom_Fashion_Fonts', 'sanitize_typography' )
        ) 
    );

    $wp_customize->add_control( 
        new Blossom_Fashion_Typography_Control( 
            $wp_customize, 
            'site_title_font', 
            array(
                'label'       => __( 'Site Title Font', 'fashion-icon' ),
                'description' => __( 'Site title and tagline font.', 'fashion-icon' ),
                'section'     => 'title_tagline',
                'priority'    => 60, 
            ) 
        ) 
    );

     /** Site Title Font Size*/
    $wp_customize->add_setting( 
        'site_title_font_size', 
        array(
            'default'           => 40,
            'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Fashion_Slider_Control( 
            $wp_customize,
            'site_title_font_size',
            array(
                'section'     => 'title_tagline',
                'label'       => __( 'Site Title Font Size', 'fashion-icon' ),
                'description' => __( 'Change the font size of your site title.', 'fashion-icon' ),
                'priority'    => 65,
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 200,
                    'step'  => 1,
                )                 
            )
        )
    );

    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography', 'fashion-icon' ),
            'priority' => 10,
            'panel'    => 'appearance_settings',
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
        'primary_font',
        array(
            'default'           => 'Nunito Sans',
            'sanitize_callback' => 'blossom_fashion_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Fashion_Select_Control(
            $wp_customize,
            'primary_font',
            array(
                'label'       => __( 'Primary Font', 'fashion-icon' ),
                'description' => __( 'Primary font of the site.', 'fashion-icon' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_fashion_get_all_fonts(),  
            )
        )
    );
    
    /** Secondary Font */
    $wp_customize->add_setting(
        'secondary_font',
        array(
            'default'           => 'Marcellus',
            'sanitize_callback' => 'blossom_fashion_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Fashion_Select_Control(
            $wp_customize,
            'secondary_font',
            array(
                'label'       => __( 'Secondary Font', 'fashion-icon' ),
                'description' => __( 'Secondary font of the site.', 'fashion-icon' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_fashion_get_all_fonts(),  
            )
        )
    );

    /** Font Size*/
    $wp_customize->add_setting( 
        'font_size', 
        array(
            'default'           => 18,
            'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Fashion_Slider_Control( 
            $wp_customize,
            'font_size',
            array(
                'section'     => 'typography_settings',
                'label'       => __( 'Font Size', 'fashion-icon' ),
                'description' => __( 'Change the font size of your site.', 'fashion-icon' ),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 50,
                    'step'  => 1,
                )                 
            )
        )
    );

    $wp_customize->add_setting(
        'ed_localgoogle_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Fashion_Toggle_Control( 
            $wp_customize,
            'ed_localgoogle_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Load Google Fonts Locally', 'fashion-icon' ),
                'description'   => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'fashion-icon' )
            )
        )
    );   

    $wp_customize->add_setting(
        'ed_preload_local_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_fashion_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Fashion_Toggle_Control( 
            $wp_customize,
            'ed_preload_local_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Preload Local Fonts', 'fashion-icon' ),
                'description'   => __( 'Preloading Google fonts will speed up your website speed.', 'fashion-icon' ),
                'active_callback' => 'blossom_fashion_ed_localgoogle_fonts'
            )
        )
    );   

    ob_start(); ?>
        
        <span style="margin-bottom: 5px;display: block;"><?php esc_html_e( 'Click the button to reset the local fonts cache', 'fashion-icon' ); ?></span>
        
        <input type="button" class="button button-primary blossom-fashion-flush-local-fonts-button" name="blossom-fashion-flush-local-fonts-button" value="<?php esc_attr_e( 'Flush Local Font Files', 'fashion-icon' ); ?>" />
    <?php
    $fashion_icon_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __( 'Flush Local Fonts Cache', 'fashion-icon' ),
            'section'       => 'typography_settings',
            'description'   => $fashion_icon_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'blossom_fashion_ed_localgoogle_fonts'
        )
    );

    /** Primary Color*/
    $wp_customize->add_setting( 
        'primary_color', array(
            'default'           => '#ed5485',
            'sanitize_callback' => 'sanitize_hex_color'
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'primary_color', 
            array(
                'label'       => __( 'Primary Color', 'fashion-icon' ),
                'description' => __( 'Primary color of the theme.', 'fashion-icon' ),
                'section'     => 'colors',
                'priority'    => 5,                
            )
        )
    );

    /** Layout Settings */
    $wp_customize->add_panel(
        'layout_settings',
        array(
            'title'     => 'Layout Settings',
            'priority'  => 45,
        )
    );

    /** Header Layout **/
    $wp_customize->add_section(
        'header_layout_settings',
        array(
            'title'     => __( 'Header Layout', 'fashion-icon' ),
            'panel'     => 'layout_settings',
            'priority'  => 15,

        )
    );

    $wp_customize->add_setting(
        'header_layout_option',
        array(
            'default'           => 'two',
            'sanitize_callback' => 'esc_attr',
        )
    );

    $wp_customize->add_control(
        new Blossom_Fashion_Radio_Image_Control(
            $wp_customize,
            'header_layout_option',
            array(
                'section'       => 'header_layout_settings',
                'label'         => __( 'Header Layouts', 'fashion-icon' ),
                'description'   => __( 'This is the header layouts for your blog.', 'fashion-icon' ),
                'choices'       => array(
                    'one'   => get_stylesheet_directory_uri() . '/images/header/header-one.png',
                    'two'   => get_stylesheet_directory_uri() . '/images/header/header-two.png',
                )
            )
        )
    );

    /** Homepage Layout **/
    $wp_customize->add_section(
        'home_layout_settings',
        array(
            'title'     => __( 'Home Page Layout', 'fashion-icon' ),
            'panel'     => 'layout_settings',
            'priority'  => 15,

        )
    );

    $wp_customize->add_setting(
        'home_layout_option',
        array(
            'default'           => 'two',
            'sanitize_callback' => 'esc_attr',
        )
    );

    $wp_customize->add_control(
        new Blossom_Fashion_Radio_Image_Control(
            $wp_customize,
            'home_layout_option',
            array(
                'section'       => 'home_layout_settings',
                'label'         => __( 'Home Page Layouts', 'fashion-icon' ),
                'description'   => __( 'This is the home page layouts for your blog.', 'fashion-icon' ),
                'choices'       => array(
                    'one'   => get_stylesheet_directory_uri() . '/images/home/home-one.jpg',
                    'two'   => get_stylesheet_directory_uri() . '/images/home/home-two.jpg',
                )
            )
        )
    );

}
add_action( 'customize_register', 'fashion_icon_customize_register', 40 );

/** Header Layout **/
function blossom_fashion_header() {
	$ed_cart = get_theme_mod( 'ed_shopping_cart', true ); 
	$header_layout = get_theme_mod('header_layout_option', 'two'); ?>

	<header class="site-header <?php if($header_layout == 'two') echo 'header-two';?>" itemscope itemtype="http://schema.org/WPHeader">
		<?php if($header_layout == 'two') {
            fashion_icon_site_branding(); 
            fashion_icon_toggle_button();
			fashion_icon_primary_navigation();
			?>

			<div class="right">
				<div class="tools">					
					<?php
				        if( blossom_fashion_is_woocommerce_activated() && $ed_cart ) blossom_fashion_wc_cart_count();
				    ?>
				    <div class="form-section">
						<button aria-label="search form toggle" id="btn-search" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false"><i class="fas fa-search"></i></button>
                        <div class="form-holder search-modal cover-modal" data-modal-target-string=".search-modal">
                            <div class="header-search-inner-wrap">
                                <?php get_search_form(); ?>
                                <button class="btn-close-form" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
                                    <span></span>
                                </button><!-- .search-toggle -->
                            </div>
                        </div>
					</div>

				</div>
				<?php 

				if( ( ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ) && blossom_fashion_social_links( false ) ) echo '<span class="separator"></span>';

				if( blossom_fashion_social_links( false ) ){ ?>
				<div class="social-networks-holder">
					<?php blossom_fashion_social_links(); ?>
				</div>
				<?php } ?>
			</div>
		<?php 
		}
		if($header_layout == 'one' ){ ?>
			<div class="header-holder">
			<div class="header-t">
				<div class="container">
					<div class="row">
						<div class="col">
							<?php get_search_form(); ?>
						</div>
						<div class="col">
							<?php fashion_icon_site_branding();?>
						</div>
						<div class="col">
							<div class="tools">
								<?php 
                                	fashion_icon_header_tools();                                  
                                ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="nav-holder">
			<div class="container">
				<div class="overlay"></div>
				<?php 
                    fashion_icon_toggle_button();
                    fashion_icon_primary_navigation(); 
                ?>
                <div class="tools">
					<div class="form-section">
                        <button aria-label="search form toggle" id="btn-search" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false"><i class="fa fa-search"></i></button>	
                        <div class="form-holder search-modal cover-modal" data-modal-target-string=".search-modal">
							<div class="header-search-inner-wrap">
								<?php get_search_form(); ?>
								<button class="btn-close-form" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
									<span></span>
								</button><!-- .search-toggle -->
							</div>
						</div>					
					</div>
                    <?php 
                    	fashion_icon_header_tools();
                    ?>					
				</div>
			</div>
		</div>
		<?php } ?>
		
	</header>
<?php
}

function fashion_icon_site_branding() { 
	$header_layout = get_theme_mod('header_layout_option', 'two'); ?>

	<div class="<?php echo ($header_layout == 'two') ? 'site-branding' : 'text-logo'; ?>" itemscope itemtype="http://schema.org/Organization">
		<?php
		if (function_exists('has_custom_logo') && has_custom_logo()) {
			the_custom_logo();
		}?>
        <div class="site-title-wrap">
            <?php if( is_front_page() ){ ?>
                <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
                <?php 
            }else{ ?>
                <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
            <?php 
            } 
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) {?>
                <p class="site-description" itemprop="description"><?php echo $description; ?></p>
                <?php
            } ?>
        </div>
		
	</div>
<?php
}

function fashion_icon_header_tools() {
	$ed_cart = get_theme_mod( 'ed_shopping_cart', true ); 
	if( blossom_fashion_social_links( false ) || ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ){
	    if( blossom_fashion_is_woocommerce_activated() && $ed_cart ) blossom_fashion_wc_cart_count();
	    if( blossom_fashion_is_woocommerce_activated() && $ed_cart && blossom_fashion_social_links( false ) ) echo '<span class="separator"></span>';
	    blossom_fashion_social_links();
	}  
}

function fashion_icon_toggle_button() { ?> 
	<button aria-label="<?php esc_attr_e( 'primary menu toggle', 'fashion-icon' ); ?>" id="toggle-button" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".close-main-nav-toggle">
        <span></span>
    </button>	
<?php
}

function fashion_icon_primary_navigation() { ?>
	<nav id="site-navigation" class="main-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
        <div class="primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
            <button class="btn-close-menu close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal"><span></span></button>
            <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'fashion-icon' ); ?>">
                <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'main-menu-modal',
                        'fallback_cb'    => 'blossom_fashion_primary_menu_fallback',
                    ) );
                ?>
            </div>
        </div>
    </nav><!-- #site-navigation -->
<?php
}


/** Post Thumbnail **/
function blossom_fashion_post_thumbnail() {
	global $wp_query;
    $image_size     = 'thumbnail';
    $ed_featured    = get_theme_mod( 'ed_featured_image', true );
    $sidebar_layout = blossom_fashion_sidebar_layout();
    $home_layout = get_theme_mod( 'home_layout_option', 'two' );
    
    if( is_front_page() || is_home() ){        
        if( has_post_thumbnail() ){
        	echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
	        if( $home_layout == 'two' ) {
	        	$image_size = 'blossom-fashion-with-sidebar';
	        }else{
	        	if( $wp_query->current_post == 0 ){                
                $image_size = ( $sidebar_layout == 'full-width' ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-with-sidebar';
	            }else{
	                $image_size = 'blossom-fashion-blog-home';    
	            }  
	        }                      
            the_post_thumbnail( $image_size );  
            echo '</a>';  
        }else{
            $image_size = ( $wp_query->current_post == 0 ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-blog-home'; 
            if( !is_page() ) {
                blossom_fashion_get_fallback_svg( $image_size );
            }    
        }        
       
    }elseif( is_home() ){        
        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        if( has_post_thumbnail() ){                        
            the_post_thumbnail( 'blossom-fashion-blog-home' );    
        }else{ 
            blossom_fashion_get_fallback_svg( 'blossom-fashion-blog-home' );
        }        
        echo '</a>';
    }elseif( is_archive() || is_search() ){
        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        if( has_post_thumbnail() ){
            the_post_thumbnail( 'fashion-icon-blog-archive' );    
        }else{ 
            blossom_fashion_get_fallback_svg( 'fashion-icon-blog-archive' );
        }
        echo '</a>';
    }elseif( is_singular() ){
        echo '<div class="post-thumbnail">';
        $image_size = ( $sidebar_layout == 'full-width' ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-with-sidebar';
        if( is_single() ){
            if( $ed_featured ) the_post_thumbnail( $image_size );
        }else{
            the_post_thumbnail( $image_size );
        }
        echo '</div>';
    }
}

/** Category */
function blossom_fashion_category(){
	$ed_cat_single = get_theme_mod( 'ed_category', false );
    if ( 'post' === get_post_type() && !$ed_cat_single ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( ' ' );
		if ( $categories_list ) {
			echo '<span class="cat-links" itemprop="about">' . $categories_list . '</span>';
		}
	}
}

/** Blossom Fashion Fonts URL */
function blossom_fashion_fonts_url(){
    $fonts_url = '';
    
    $primary_font       = get_theme_mod( 'primary_font', 'Nunito Sans' );
    $ig_primary_font    = blossom_fashion_is_google_font( $primary_font );    
    $secondary_font     = get_theme_mod( 'secondary_font', 'Marcellus' );
    $ig_secondary_font  = blossom_fashion_is_google_font( $secondary_font );    
    $site_title_font    = get_theme_mod( 'site_title_font', array( 'font-family'=>'Marcellus', 'variant'=>'regular' ) );
    $ig_site_title_font = blossom_fashion_is_google_font( $site_title_font['font-family'] );
        
    /* Translators: If there are characters in your language that are not
    * supported by respective fonts, translate this to 'off'. Do not translate
    * into your own language.
    */
    $primary    = _x( 'on', 'Primary Font: on or off', 'fashion-icon' );
    $secondary  = _x( 'on', 'Secondary Font: on or off', 'fashion-icon' );
    $site_title = _x( 'on', 'Site Title Font: on or off', 'fashion-icon' );
    
    
    if ( 'off' !== $primary || 'off' !== $secondary || 'off' !== $site_title ) {
        
        $font_families = array();
     
        if ( 'off' !== $primary && $ig_primary_font ) {
            $primary_variant = blossom_fashion_check_varient( $primary_font, 'regular', true );
            if( $primary_variant ){
                $primary_var = ':' . $primary_variant;
            }else{
                $primary_var = '';    
            }            
            $font_families[] = $primary_font . $primary_var;
        }
         
        if ( 'off' !== $secondary && $ig_secondary_font ) {
            $secondary_variant = blossom_fashion_check_varient( $secondary_font, 'regular', true );
            if( $secondary_variant ){
                $secondary_var = ':' . $secondary_variant;    
            }else{
                $secondary_var = '';
            }
            $font_families[] = $secondary_font . $secondary_var;
        }
        
        if ( 'off' !== $site_title && $ig_site_title_font ) {
            
            if( ! empty( $site_title_font['variant'] ) ){
                $site_title_var = ':' . blossom_fashion_check_varient( $site_title_font['font-family'], $site_title_font['variant'] );    
            }else{
                $site_title_var = '';
            }
            $font_families[] = $site_title_font['font-family'] . $site_title_var;
        }
        
        $font_families = array_diff( array_unique( $font_families ), array('') );
        
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),            
        );
        
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    if( get_theme_mod( 'ed_localgoogle_fonts', false ) ) {
        $fonts_url = blossom_fashion_get_webfont_url( add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ) );
    }
     
    return esc_url_raw( $fonts_url );
}


/** Fashion Icon Dynamic CSS */
function fashion_icon_dynamic_css(){    
    $primary_font    = get_theme_mod( 'primary_font', 'Nunito Sans' );
    $primary_fonts   = blossom_fashion_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Marcellus' );
    $secondary_fonts = blossom_fashion_get_fonts( $secondary_font, 'regular' );
    $font_size       = get_theme_mod( 'font_size', 18 );
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Marcellus', 'variant'=>'regular' ) );
    $site_title_fonts     = blossom_fashion_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 40 );
    
    $primary_color = get_theme_mod( 'primary_color', '#ed5485' );
    
    $rgb = blossom_fashion_hex2rgb( blossom_fashion_sanitize_hex_color( $primary_color ) );
     
    $custom_css = '';
    $custom_css .= '
     
    .content-newsletter .blossomthemes-email-newsletter-wrapper.bg-img:after,
    .widget_blossomthemes_email_newsletter_widget .blossomthemes-email-newsletter-wrapper:after{
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.8);' . '
    }
    
    /*Typography*/

    body,
    button,
    input,
    select,
    optgroup,
    textarea{
        font-family : ' . wp_kses_post( $primary_fonts['font'] ) . ';
        font-size   : ' . absint( $font_size ) . 'px;        
    }

     .main-navigation ul{
        font-family : ' . wp_kses_post( $primary_fonts['font'] ) . ';
    }

    .header-two .site-title, .site-title, .site-header.header-three .site-title{
        font-size   : ' . absint( $site_title_font_size ) . 'px;
        font-family : ' . wp_kses_post( $site_title_fonts['font'] ) . ';
        font-weight : ' . esc_html( $site_title_fonts['weight'] ) . ';
        font-style  : ' . esc_html( $site_title_fonts['style'] ) . ';
    }
    
    /*Color Scheme*/
    a,
    .site-header .social-networks li a:hover,
    .site-title a:hover,
	.shop-section .shop-slider .item h3 a:hover,
	#primary .post .entry-footer .social-networks li a:hover,
	.widget ul li a:hover,
	.widget_bttk_author_bio .author-bio-socicons ul li a:hover,
	.widget_bttk_popular_post ul li .entry-header .entry-title a:hover,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-title a:hover,
	.widget_bttk_popular_post ul li .entry-header .entry-meta a:hover,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-meta a:hover,
	.bottom-shop-section .bottom-shop-slider .item .product-category a:hover,
	.bottom-shop-section .bottom-shop-slider .item h3 a:hover,
	.instagram-section .header .title a:hover,
	.site-footer .widget ul li a:hover,
	.site-footer .widget_bttk_popular_post ul li .entry-header .entry-title a:hover,
	.site-footer .widget_bttk_pro_recent_post ul li .entry-header .entry-title a:hover,
	.single .single-header .site-title:hover,
	.single .single-header .right .social-share .social-networks li a:hover,
	.comments-area .comment-body .fn a:hover,
	.comments-area .comment-body .comment-metadata a:hover,
	.page-template-contact .contact-details .contact-info-holder .col .icon-holder,
	.page-template-contact .contact-details .contact-info-holder .col .text-holder h3 a:hover,
	.page-template-contact .contact-details .contact-info-holder .col .social-networks li a:hover,
    #secondary .widget_bttk_description_widget .social-profile li a:hover,
    #secondary .widget_bttk_contact_social_links .social-networks li a:hover,
    .site-footer .widget_bttk_contact_social_links .social-networks li a:hover,
    .site-footer .widget_bttk_description_widget .social-profile li a:hover,
    .portfolio-sorting .button:hover,
    .portfolio-sorting .button.is-checked,
    .portfolio-item .portfolio-cat a:hover,
    .entry-header .portfolio-cat a:hover,
    .single-blossom-portfolio .post-navigation .nav-previous a:hover,
    .single-blossom-portfolio .post-navigation .nav-next a:hover,
    #primary .post .entry-header .entry-title a:hover, 
    .banner .text-holder .title a:hover,
    #primary .post .entry-header .entry-meta a:hover,
    .widget_bttk_posts_category_slider_widget .carousel-title .title a:hover,
    .error-holder .recent-posts .post .entry-header .cat-links a:hover,
    .error-holder .recent-posts .post .entry-header .entry-title a:hover, 
    .woocommerce-cart #primary .page .entry-content table.shop_table td.product-name a:hover, 
    .search #primary .search-post .entry-header .entry-title a:hover,
    .entry-content a:hover,
    .entry-summary a:hover,
    .page-content a:hover,
    .comment-content a:hover,
    .widget .textwidget a:hover{
		color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	.site-header .tools .cart .number,
	.shop-section .header .title:after,
	.header-two .header-t,
	.header-six .header-t,
	.header-eight .header-t,
	.shop-section .shop-slider .item .product-image .btn-add-to-cart:hover,
	.widget .widget-title:before,
	.widget .widget-title:after,
	.widget_calendar caption,
	.widget_bttk_popular_post .style-two li:after,
	.widget_bttk_popular_post .style-three li:after,
	.widget_bttk_pro_recent_post .style-two li:after,
	.widget_bttk_pro_recent_post .style-three li:after,
	.instagram-section .header .title:before,
	.instagram-section .header .title:after,
	#primary .post .entry-content .pull-left:after,
	#primary .page .entry-content .pull-left:after,
	#primary .post .entry-content .pull-right:after,
	#primary .page .entry-content .pull-right:after,
	.page-template-contact .contact-details .contact-info-holder h2:after,
    .widget_bttk_image_text_widget ul li .btn-readmore:hover,
    #secondary .widget_bttk_icon_text_widget .text-holder .btn-readmore:hover,
    #secondary .widget_blossomtheme_companion_cta_widget .btn-cta:hover,
    #secondary .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:hover,
    .banner .text-holder .cat-links a:hover,
    #primary .post .entry-header .cat-links a:hover, 
    .widget_bttk_popular_post .style-two li .entry-header .cat-links a:hover, 
    .widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a:hover, 
    .widget_bttk_popular_post .style-three li .entry-header .cat-links a:hover, 
    .widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a:hover, 
    .widget_bttk_posts_category_slider_widget .carousel-title .cat-links a:hover, 
    .portfolio-item .portfolio-cat a:hover, .entry-header .portfolio-cat a:hover,
    .featured-section .img-holder:hover .text-holder,
    #primary .post .btn-readmore:hover, 
    .widget_bttk_author_bio .text-holder .readmore:hover,
    .widget_tag_cloud .tagcloud a:hover,
    .widget_bttk_posts_category_slider_widget .owl-theme .owl-nav [class*="owl-"]:hover,
    .error-holder .text-holder .btn-home:hover, 
    .error-holder .recent-posts .post .entry-header .cat-links a:hover, 
    .single-post-layout-two .post-header-holder .entry-header .cat-links a:hover, 
    .single #primary .post .entry-footer .tags a:hover, 
    #primary .page .entry-footer .tags a:hover, 
    .woocommerce .woocommerce-message .button:hover, 
    .woocommerce div.product .entry-summary .variations_form .single_variation_wrap .button:hover, 
    .woocommerce-checkout .woocommerce form.woocommerce-form-login input.button:hover, 
    .woocommerce-checkout .woocommerce form.checkout_coupon input.button:hover, 
    .woocommerce form.lost_reset_password input.button:hover, 
    .woocommerce .return-to-shop .button:hover, 
    .woocommerce #payment #place_order:hover{
		background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}
    
    .banner .text-holder .cat-links a,
	#primary .post .entry-header .cat-links a,
	.widget_bttk_popular_post .style-two li .entry-header .cat-links a,
	.widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a,
	.widget_bttk_popular_post .style-three li .entry-header .cat-links a,
	.widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a,
	.page-header span,
	.page-template-contact .top-section .section-header span,
    .portfolio-item .portfolio-cat a,
    .entry-header .portfolio-cat a{
		border-bottom-color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	.banner .text-holder .title a,
	.header-four .main-navigation ul li a,
	.header-four .main-navigation ul ul li a,
	#primary .post .entry-header .entry-title a,
    .portfolio-item .portfolio-img-title a{
		background-image: linear-gradient(180deg, transparent 96%, ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ' 0);
	}

    @media screen and (max-width: 1024px) {
        .main-navigation ul li a {
            background-image: linear-gradient(180deg, transparent 93%, ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ' 0);
        }
    }

	.widget_bttk_social_links ul li a:hover{
		border-color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	button:hover,
	input[type="button"]:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover{
		background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
		border-color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

	#primary .post .btn-readmore:hover{
		background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
	}

    .banner .text-holder .cat-links a,
    #primary .post .entry-header .cat-links a,
    .widget_bttk_popular_post .style-two li .entry-header .cat-links a,
    .widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a,
    .widget_bttk_popular_post .style-three li .entry-header .cat-links a,
    .widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a,
    .page-header span,
    .page-template-contact .top-section .section-header span,
    .widget_bttk_posts_category_slider_widget .carousel-title .cat-links a,
    .portfolio-item .portfolio-cat a,
    .entry-header .portfolio-cat a, 
    .error-holder .recent-posts .post .entry-header .cat-links a, 
    .widget:not(.widget_bttk_author_bio) .widget-title:after, 
    .widget.widget_bttk_author_bio .widget-title::before,
    .widget.widget_bttk_author_bio .widget-title:after {
        ' . 'background-color: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.3);' . '
    }

    .single-post-layout-two .post-header-holder .entry-header .cat-links a,
    .single #primary .post .entry-footer .tags a, 
    #primary .page .entry-footer .tags a, 
    .widget_calendar table tbody td a {
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.3);' . '
    }

	@media only screen and (min-width: 1025px){
		.main-navigation ul li:after{
			background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
		}
	}
    
    /*Typography*/
	.banner .text-holder .title,
	.top-section .newsletter .blossomthemes-email-newsletter-wrapper .text-holder h3,
	.shop-section .header .title,
	#primary .post .entry-header .entry-title,
	#primary .post .post-shope-holder .header .title,
	.widget_bttk_author_bio .title-holder,
	.widget_bttk_popular_post ul li .entry-header .entry-title,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-title,
	.widget-area .widget_blossomthemes_email_newsletter_widget .text-holder h3,
	.bottom-shop-section .bottom-shop-slider .item h3,
	.page-title,
	#primary .post .entry-content blockquote,
	#primary .page .entry-content blockquote,
	#primary .post .entry-content .dropcap,
	#primary .page .entry-content .dropcap,
	#primary .post .entry-content .pull-left,
	#primary .page .entry-content .pull-left,
	#primary .post .entry-content .pull-right,
	#primary .page .entry-content .pull-right,
	.author-section .text-holder .title,
	.single .newsletter .blossomthemes-email-newsletter-wrapper .text-holder h3,
	.related-posts .title, .popular-posts .title,
	.comments-area .comments-title,
	.comments-area .comment-reply-title,
	.single .single-header .title-holder .post-title,
    .portfolio-text-holder .portfolio-img-title,
    .portfolio-holder .entry-header .entry-title,
    .related-portfolio-title,
    .archive #primary .post .entry-header .entry-title, 
    .search #primary .search-post .entry-header .entry-title, 
    .archive #primary .post-count, 
    .search #primary .post-count,
    .search .top-section .search-form input[type="search"],
    .header-two .form-holder .search-form input[type="search"],
    .archive.author .top-section .text-holder .author-title,
    .widget_bttk_posts_category_slider_widget .carousel-title .title, 
    .error-holder .text-holder h2,
    .error-holder .recent-posts .post .entry-header .entry-title,
    .error-holder .recent-posts .title{
		font-family: ' . wp_kses_post( $secondary_fonts['font'] ) . ';
	}';
    if( blossom_fashion_is_woocommerce_activated() ) {
        $custom_css .= '
        .woocommerce #secondary .widget_price_filter .ui-slider .ui-slider-range{
			background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
    	}
        
        .woocommerce #secondary .widget .product_list_widget li .product-title:hover,
    	.woocommerce #secondary .widget .product_list_widget li .product-title:focus,
    	.woocommerce div.product .entry-summary .product_meta .posted_in a:hover,
    	.woocommerce div.product .entry-summary .product_meta .posted_in a:focus,
    	.woocommerce div.product .entry-summary .product_meta .tagged_as a:hover,
    	.woocommerce div.product .entry-summary .product_meta .tagged_as a:focus{
			color: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
    	}
        
        .woocommerce-checkout .woocommerce .woocommerce-info,
        .woocommerce ul.products li.product .add_to_cart_button:hover,
        .woocommerce ul.products li.product .add_to_cart_button:focus,
        .woocommerce ul.products li.product .product_type_external:hover,
        .woocommerce ul.products li.product .product_type_external:focus,
        .woocommerce ul.products li.product .ajax_add_to_cart:hover,
        .woocommerce ul.products li.product .ajax_add_to_cart:focus,
        .woocommerce ul.products li.product .added_to_cart:hover,
        .woocommerce ul.products li.product .added_to_cart:focus,
        .woocommerce div.product form.cart .single_add_to_cart_button:hover,
        .woocommerce div.product form.cart .single_add_to_cart_button:focus,
        .woocommerce div.product .cart .single_add_to_cart_button.alt:hover,
        .woocommerce div.product .cart .single_add_to_cart_button.alt:focus,
        .woocommerce #secondary .widget_shopping_cart .buttons .button:hover,
        .woocommerce #secondary .widget_shopping_cart .buttons .button:focus,
        .woocommerce #secondary .widget_price_filter .price_slider_amount .button:hover,
        .woocommerce #secondary .widget_price_filter .price_slider_amount .button:focus,
        .woocommerce-cart #primary .page .entry-content table.shop_table td.actions .coupon input[type="submit"]:hover,
        .woocommerce-cart #primary .page .entry-content table.shop_table td.actions .coupon input[type="submit"]:focus,
        .woocommerce-cart #primary .page .entry-content .cart_totals .checkout-button:hover,
        .woocommerce-cart #primary .page .entry-content .cart_totals .checkout-button:focus{
			background: ' . blossom_fashion_sanitize_hex_color( $primary_color ) . ';
    	}

    	.woocommerce div.product .product_title,
    	.woocommerce div.product .woocommerce-tabs .panel h2{
			font-family: ' . wp_kses_post( $secondary_fonts['font'] ) . ';
    	}';    
    }
           
    wp_add_inline_style( 'blossom-fashion-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'fashion_icon_dynamic_css', 100 );


/** Fashion Icon Footer */
function blossom_fashion_footer_bottom(){ ?>
    <div class="footer-b">
		<div class="container">
			<div class="site-info">            
            <?php
                blossom_fashion_get_footer_copyright();
                esc_html_e( ' Fashion Icon | Developed By ', 'fashion-icon' );                                
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'fashion-icon' ) . '</a>.';                                
                printf( esc_html__( ' Powered by %s', 'fashion-icon' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'fashion-icon' ) ) .'" target="_blank">WordPress</a>.' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
		</div>
	</div>
    <?php
}