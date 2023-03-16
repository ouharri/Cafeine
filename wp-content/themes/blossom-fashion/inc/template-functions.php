<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Blossom_Fashion
 */

if( ! function_exists( 'blossom_fashion_doctype' ) ) :
/**
 * Doctype Declaration
*/
function blossom_fashion_doctype(){ ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'blossom_fashion_doctype', 'blossom_fashion_doctype' );

if( ! function_exists( 'blossom_fashion_head' ) ) :
/**
 * Before wp_head 
*/
function blossom_fashion_head(){ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'blossom_fashion_before_wp_head', 'blossom_fashion_head' );

if( ! function_exists( 'blossom_fashion_page_start' ) ) :
/**
 * Page Start
*/
function blossom_fashion_page_start(){
    ?>
    <div id="page" class="site"><a aria-label="<?php esc_attr_e( 'skip to content', 'blossom-fashion' ); ?>" class="skip-link" href="#content"><?php esc_html_e( 'Skip to Content', 'blossom-fashion' ); ?></a>
    <?php
}
endif;
add_action( 'blossom_fashion_before_header', 'blossom_fashion_page_start', 20 );

if( ! function_exists( 'blossom_fashion_header' ) ) :
/**
 * Header Start
*/
function blossom_fashion_header(){ 
    $ed_cart = get_theme_mod( 'ed_shopping_cart', true ); ?>
    <header class="site-header" itemscope itemtype="http://schema.org/WPHeader" itemscope itemtype="http://schema.org/WPHeader">
		<div class="header-holder">
			<div class="header-t">
				<div class="container">
					<div class="row">
						<div class="col">
							<?php get_search_form(); ?>
						</div>
						<div class="col">
							<div class="text-logo" itemscope itemtype="http://schema.org/Organization">
								<?php 
                                    if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                                        the_custom_logo();
                                    }
                                    ?>

                                    <div class="site-title-wrap">
                                     <?php 
                                        if( is_front_page() ){ ?>
                                        <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
                                        <?php 
                                    }else{ ?>
                                        <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
                                    <?php 
                                    } 
                                 
                                    $description = get_bloginfo( 'description', 'display' );
                                    if ( $description || is_customize_preview() ){ ?>
                                        <p class="site-description"><?php echo $description; ?></p>
                                    <?php                
                                        }
                                    ?>
                                    </div>                                    
							</div>
						</div>
						<div class="col">
							<div class="tools">
								<?php 
                                if( blossom_fashion_social_links( false ) || ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ){
                                    if( blossom_fashion_is_woocommerce_activated() && $ed_cart ) blossom_fashion_wc_cart_count();
                                    if( blossom_fashion_is_woocommerce_activated() && $ed_cart && blossom_fashion_social_links( false ) ) echo '<span class="separator"></span>';
                                    blossom_fashion_social_links();
                                }                                    
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
    			<button aria-label="<?php esc_attr_e( 'primary menu toggle', 'blossom-fashion' ); ?>" id="toggle-button" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".close-main-nav-toggle">
    				<span></span>
    			</button>
				<nav id="site-navigation" class="main-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
                    <div class="primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
                        <button class="btn-close-menu close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal"><span></span></button>
                        <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'blossom-fashion' ); ?>">
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
                <div class="tools">
					<div class="form-section">
						<button aria-label="<?php esc_attr_e( 'search toggle', 'blossom-fashion' ); ?>" id="btn-search" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false"><i class="fa fa-search"></i></button>
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
                    if( blossom_fashion_social_links( false ) || ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ){
                        if( blossom_fashion_is_woocommerce_activated() && $ed_cart ) blossom_fashion_wc_cart_count();
                        if( blossom_fashion_is_woocommerce_activated() && $ed_cart && blossom_fashion_social_links( false ) ) echo '<span class="separator"></span>';
                        blossom_fashion_social_links();
                    }
                    ?>					
				</div>
			</div>
		</div>
	</header>
    <?php 
}
endif;
add_action( 'blossom_fashion_header', 'blossom_fashion_header', 20 );

if( ! function_exists( 'blossom_fashion_banner' ) ) :
/**
 * Banner Section 
*/
function blossom_fashion_banner(){
    $ed_banner      = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    $slider_type    = get_theme_mod( 'slider_type', 'latest_posts' ); 
    $slider_cat     = get_theme_mod( 'slider_cat' );
    $posts_per_page = get_theme_mod( 'no_of_slides', 3 );    
    
    if( is_front_page() || is_home() ){ 
        
        if( $ed_banner == 'static_banner' && has_custom_header() ){ ?>
            <div class="banner<?php if( has_header_video() ) echo esc_attr( ' video-banner' ); ?>">
                <?php the_custom_header_markup(); ?>
            </div>
            <?php
        }elseif( $ed_banner == 'slider_banner' ){
            $args = array(
                'post_type'           => 'post',
                'post_status'         => 'publish',            
                'ignore_sticky_posts' => true
            );
            
            if( $slider_type === 'cat' && $slider_cat ){
                $args['cat']            = $slider_cat; 
                $args['posts_per_page'] = -1;  
            }else{
                $args['posts_per_page'] = $posts_per_page;
            }
                
            $qry = new WP_Query( $args );
            
            if( $qry->have_posts() ){ ?>
            <div class="banner">
        		<div id="banner-slider" class="owl-carousel">
        			<?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                    <div class="item">
        				<?php 
                        if( has_post_thumbnail() ){
        				    the_post_thumbnail( 'blossom-fashion-slider' );    
        				}else{ 
                            blossom_fashion_get_fallback_svg( 'blossom-fashion-slider' );
                        }
                        ?>                        
        				<div class="banner-text">
        					<div class="container">
        						<div class="text-holder">
        							<?php
                                        blossom_fashion_category();
                                        the_title( '<h2 class="title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                                    ?>
        						</div>
        					</div>
        				</div>
        			</div>
        			<?php } ?>
                    
        		</div>
        	</div>
            <?php
            }
            wp_reset_postdata();
        } 
    }    
}
endif;
add_action( 'blossom_fashion_after_header', 'blossom_fashion_banner', 15 );

if( ! function_exists( 'blossom_fashion_top_section' ) ) :
/**
 * Top Section
*/
function blossom_fashion_top_section(){
    $ed_featured         = get_theme_mod( 'ed_featured_area', true );
    $featured_page_one   = get_theme_mod( 'featured_content_one' );
    $featured_page_two   = get_theme_mod( 'featured_content_two' );
    $featured_page_three = get_theme_mod( 'featured_content_three' );
    $featured_pages      = array( $featured_page_one, $featured_page_two, $featured_page_three );
    $featured_pages      = array_diff( array_unique( $featured_pages), array( '' ) );
    $ed_newsletter       = get_theme_mod( 'ed_newsletter', false );
    $newsletter          = get_theme_mod( 'newsletter_shortcode' );
    
    $args = array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__in'       => $featured_pages,
        'orderby'        => 'post__in'   
    );
    
    $qry = new WP_Query( $args );
                        
    if( is_front_page() && ( ( $ed_featured && $featured_pages && $qry->have_posts() ) || ( blossom_fashion_is_btnw_activated() && $ed_newsletter && has_shortcode( $newsletter, 'BTEN' ) ) ) ){ ?>
        <div class="top-section">
    		<div class="container">
    			<?php 
                    if( $ed_featured && $featured_pages && $qry->have_posts() ){ ?>
                        <div class="featured-section">
            				<div class="grid">
            					<?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                                <div class="grid-item">
            						<div class="img-holder">
            							<a href="<?php the_permalink(); ?>">
                                        <?php 
                                            if( has_post_thumbnail() ){
                                                the_post_thumbnail( 'blossom-fashion-featured' );
                                            }else{ 
                                                blossom_fashion_get_fallback_svg( 'blossom-fashion-featured' );
                                            }
                                        ?>
                                        </a>
            							<?php the_title( '<div class="text-holder">', '</div>' ); ?>
            						</div>
            					</div>
            					<?php } ?>
            				</div>
            			</div>
                        <?php
                    } 
                    wp_reset_postdata();                                   
                    
                    if( blossom_fashion_is_btnw_activated() && $ed_newsletter && has_shortcode( $newsletter, 'BTEN' ) ) blossom_fashion_newsletter();
                ?>
    		</div>
    	</div>
        <?php
    }    
}
endif;
add_action( 'blossom_fashion_after_header', 'blossom_fashion_top_section', 20 );

if( ! function_exists( 'blossom_fashion_shop_section' ) ) :
/**
 * Shop Section
*/
function blossom_fashion_shop_section(){ 
    $ed_shop_section = get_theme_mod( 'ed_top_shop_section', false );
    $section_title   = get_theme_mod( 'shop_section_title', __( 'Welcome to our Shop!', 'blossom-fashion' ) );
    $section_content = get_theme_mod( 'shop_section_content', __( 'This option can be change from Customize > General Settings > Shop settings.', 'blossom-fashion' ) );
    $number_of_posts = get_theme_mod( 'no_of_products', 8 );
    
    if( is_front_page() && blossom_fashion_is_woocommerce_activated() && $ed_shop_section ){ 
        
        
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $number_of_posts
        );
        
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() || $section_title || $section_content ){ ?>
        <div class="shop-section">
            <div class="container">
            <?php if( $section_title || $section_content ){ ?>
            <section class="header">
                <?php
                    if( $section_title ) echo '<h2 class="title">' . esc_html( $section_title ) . '</h2>';
                    if( $section_content ) echo '<div class="content">' . wpautop( wp_kses_post( $section_content ) ) . '</div>';
                ?>
            </section>
            <?php } ?>
            
            <?php
                if( $qry->have_posts() ){ ?> 
                    <div class="shop-slider owl-carousel">
                    <?php
                        while( $qry->have_posts() ){
                            $qry->the_post(); global $product; ?>
                            <div class="item">
                            <?php
                                $stock = get_post_meta( get_the_ID(), '_stock_status', true );
                                
                                if( $stock == 'outofstock' ){
                                    echo '<span class="outofstock">' . esc_html__( 'Sold Out', 'blossom-fashion' ) . '</span>';
                                }else{
                                    woocommerce_show_product_sale_flash();    
                                }
                                ?>
                                
                                <div class="product-image">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <?php 
                                            if( has_post_thumbnail() ){
                                                the_post_thumbnail( 'blossom-fashion-shop' );    
                                            }else{ 
                                                blossom_fashion_get_fallback_svg( 'blossom-fashion-shop' );
                                            }                                                                                      
                                        ?>
                                    </a>
                                    <?php
                                        if( $product->is_type( 'simple' ) && $stock == 'instock' ){ ?>
                                            <a href="javascript:void(0);" rel="bookmark" class="btn-add-to-cart btn-simple" id="<?php the_ID(); ?>"><?php esc_html_e( 'Add to Cart', 'blossom-fashion' ); ?></a> 
                                        <?php
                                        }else{ ?>
                                            <a href="<?php the_permalink(); ?>" rel="bookmark" class="btn-add-to-cart"><?php esc_html_e( 'View Details', 'blossom-fashion' ); ?></a>
                                        <?php
                                        }                                      
                                    ?>
                                </div>
                                
                                <?php
                                
                                the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); 
                                
                                woocommerce_template_single_price(); //price
                                woocommerce_template_single_rating(); //rating
                                
                            ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            ?>
            </div>
        </div>
        <?php
        }
    }
}
endif;
add_action( 'blossom_fashion_after_header', 'blossom_fashion_shop_section', 25 );

if( ! function_exists( 'blossom_fashion_top_author_section' ) ) :
/**
 * Top Section for Author Archive
*/
function blossom_fashion_top_author_section(){
    if( is_author() ){ ?>
        <div class="top-section">
    		<div class="container">
    			<div class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 160 ); ?></div>
    			<div class="text-holder">
    				<span><?php esc_html_e( 'All Posts by', 'blossom-fashion' ); ?></span>
    				<h1 class="author-title"><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></h1>
    			</div>
    		</div>
    	</div>
        <?php
    }    
}
endif;
add_action( 'blossom_fashion_after_header', 'blossom_fashion_top_author_section', 30 );

if( ! function_exists( 'blossom_fashion_top_search_section' ) ) :
/**
 * Top Section for Search Page
*/
function blossom_fashion_top_search_section(){
    if( is_search() ){ ?>
        <div class="top-section">
    		<div class="container">
    			<div class="text-holder">
    				<span><?php esc_html_e( 'You are looking for', 'blossom-fashion'); ?></span>
    				<?php get_search_form(); ?>
    			</div>
    		</div>
    	</div>
        <?php
    }    
}
endif;
add_action( 'blossom_fashion_after_header', 'blossom_fashion_top_search_section', 35 );

if( ! function_exists( 'blossom_fashion_top_bar' ) ) :
/**
 * Top bar for single page and post
*/
function blossom_fashion_top_bar(){
    if( ! is_front_page() && ! is_404() ){ ?>
        <div class="top-bar">
    		<div class="container">
            <?php
                //Breadcrumb
                blossom_fashion_breadcrumb();
            ?>
    		</div>
    	</div>   
        <?php 
    }    
}
endif;
add_action( 'blossom_fashion_after_header', 'blossom_fashion_top_bar', 40 );

if( ! function_exists( 'blossom_fashion_content_start' ) ) :
/**
 * Content Start
*/
function blossom_fashion_content_start(){ 
    echo is_404() ? '<div class="error-holder">' : '<div id="content" class="site-content">'; ?>
    <div class="container">
    <?php
    /**
     * Page Header for category archive & single page.        
    */                                                        
    if( ! is_front_page() && ( ( is_archive() && ! is_author() ) || is_page() ) ){ ?>
        <div class="page-header">
			<?php        
                if( is_archive() && ! is_author() ){ 
                    if( blossom_fashion_is_woocommerce_activated() && is_shop() ){
                        echo '<h1 class="page-title">' . esc_html ( get_the_title( get_option( 'woocommerce_shop_page_id' ) ) ) . '</h1>';
                        
                        $shop_archive_description = get_theme_mod( 'shop_archive_description', true );
                        if( $shop_archive_description ){
                            the_archive_description( '<div class="archive-description">', '</div>' );
                        }
                    }else{
                        the_archive_title();
                        the_archive_description( '<div class="archive-description">', '</div>' );
                    }             
                }
                
                if( is_page() ){
                    the_title( '<h1 class="page-title">', '</h1>' );
                }
            ?>
		</div>
        <?php
    }
        
    if( ! is_404() ) echo '<div class="row">';
}
endif;
add_action( 'blossom_fashion_content', 'blossom_fashion_content_start' );

if( ! function_exists( 'blossom_fashion_post_count' ) ) :
/**
 * Post counts in search and archive page.
*/
function blossom_fashion_post_count(){
    global $wp_query;
    printf( esc_html__( '%1$sShowing %2$s %3$s Result(s)%4$s', 'blossom-fashion' ), '<span class="post-count">', '<strong>', number_format_i18n( $wp_query->found_posts ), '</strong></span>' );        
}
endif;
add_action( 'blossom_fashion_before_posts_content', 'blossom_fashion_post_count' );

if( ! function_exists( 'blossom_fashion_entry_header' ) ) :
/**
 * Entry Header
*/
function blossom_fashion_entry_header(){ ?>
    <header class="entry-header">
		<?php 
            blossom_fashion_category();    
            
            if ( is_singular() ) :
    			the_title( '<h1 class="entry-title">', '</h1>' );
    		else :
    			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
    		endif; 
        
            if( 'post' === get_post_type() ){
                echo '<div class="entry-meta">';
                blossom_fashion_posted_by();
                blossom_fashion_posted_on();
                blossom_fashion_comment_count();
                echo '</div>';
            }		
		?>
	</header>         
    <?php    
}
endif;
add_action( 'blossom_fashion_before_post_entry_content', 'blossom_fashion_entry_header', 15 );
add_action( 'blossom_fashion_entry_content', 'blossom_fashion_entry_header', 10 );

if ( ! function_exists( 'blossom_fashion_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function blossom_fashion_post_thumbnail() {
	global $wp_query;
    $image_size     = 'thumbnail';
    $ed_featured    = get_theme_mod( 'ed_featured_image', true );
    $sidebar_layout = blossom_fashion_sidebar_layout();
    
    if( is_front_page() && is_home() ){
        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        if( has_post_thumbnail() ){
            if( $wp_query->current_post == 0 ){                
                $image_size = ( $sidebar_layout == 'full-width' ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-with-sidebar';
            }else{
                $image_size = 'blossom-fashion-blog-home';    
            }            
            the_post_thumbnail( $image_size );    
        }else{
            $image_size = ( $wp_query->current_post == 0 ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-blog-home';
            blossom_fashion_get_fallback_svg( $image_size );    
        }        
        echo '</a>';
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
            the_post_thumbnail( 'blossom-fashion-blog-archive' );    
        }else{ 
            blossom_fashion_get_fallback_svg( 'blossom-fashion-blog-archive' );
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
endif;
add_action( 'blossom_fashion_before_page_entry_content', 'blossom_fashion_post_thumbnail' );
add_action( 'blossom_fashion_before_post_entry_content', 'blossom_fashion_post_thumbnail', 20 );
add_action( 'blossom_fashion_before_entry_content', 'blossom_fashion_post_thumbnail' );

if( ! function_exists( 'blossom_fashion_entry_content' ) ) :
/**
 * Entry Content
*/
function blossom_fashion_entry_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true ); ?>
    <div class="entry-content" itemprop="text">
		<?php
			if( is_singular() || ! $ed_excerpt || ( get_post_format() != false ) ){
                the_content();
    
    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blossom-fashion' ),
    				'after'  => '</div>',
    			) );
            }else{
                the_excerpt();
            }
		?>
	</div><!-- .entry-content -->
    <?php
}
endif;
add_action( 'blossom_fashion_page_entry_content', 'blossom_fashion_entry_content', 15 );
add_action( 'blossom_fashion_post_entry_content', 'blossom_fashion_entry_content', 15 );
add_action( 'blossom_fashion_entry_content', 'blossom_fashion_entry_content', 15 );

if( ! function_exists( 'blossom_fashion_entry_footer' ) ) :
/**
 * Entry Footer
*/
function blossom_fashion_entry_footer(){ 
    $readmore = get_theme_mod( 'read_more_text', __( 'Continue Reading', 'blossom-fashion' ) );
    ?>
	<footer class="entry-footer">
		<?php
			if( is_single() ){
			    blossom_fashion_tag();
			}
            
            if( is_home() ){
                echo '<a href="' . esc_url( get_the_permalink() ) . '" class="btn-readmore">' . esc_html( $readmore ) . '</a>';    
            }
            
            if( get_edit_post_link() ){
                edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'blossom-fashion' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
            }
		?>
	</footer><!-- .entry-footer -->
	<?php 
}
endif;
add_action( 'blossom_fashion_page_entry_content', 'blossom_fashion_entry_footer', 20 );
add_action( 'blossom_fashion_post_entry_content', 'blossom_fashion_entry_footer', 20 );
add_action( 'blossom_fashion_entry_content', 'blossom_fashion_entry_footer', 20 );

if( ! function_exists( 'blossom_fashion_navigation' ) ) :
/**
 * Navigation
*/
function blossom_fashion_navigation(){
    if( is_single() ){
        $previous = get_previous_post_link(
    		'<div class="nav-previous nav-holder">%link</div>',
    		'<span class="meta-nav">' . esc_html__( 'Previous Article', 'blossom-fashion' ) . '</span><span class="post-title">%title</span>',
    		false,
    		'',
    		'category'
    	);
    
    	$next = get_next_post_link(
    		'<div class="nav-next nav-holder">%link</div>',
    		'<span class="meta-nav">' . esc_html__( 'Next Article', 'blossom-fashion' ) . '</span><span class="post-title">%title</span>',
    		false,
    		'',
    		'category'
    	); 
        
        if( $previous || $next ){?>            
            <nav class="navigation post-navigation" role="navigation">
    			<h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'blossom-fashion' ); ?></h2>
    			<div class="nav-links">
    				<?php
                        if( $previous ) echo $previous;
                        if( $next ) echo $next;
                    ?>
    			</div>
    		</nav>        
            <?php
        }
    }else{
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous', 'blossom-fashion' ),
            'next_text'          => __( 'Next', 'blossom-fashion' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'blossom-fashion' ) . ' </span>',
        ) );
    }
}
endif;
add_action( 'blossom_fashion_after_post_content', 'blossom_fashion_navigation', 15 );
add_action( 'blossom_fashion_after_posts_content', 'blossom_fashion_navigation' );

if( ! function_exists( 'blossom_fashion_author' ) ) :
/**
 * Author Section
*/
function blossom_fashion_author(){ 
    $ed_author    = get_theme_mod( 'ed_author' );
    $author_title = get_theme_mod( 'author_title', __( 'About Author', 'blossom-fashion' ) );
    if( ! $ed_author && get_the_author_meta( 'description' ) ){ ?>
    <div class="author-section">
		<div class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 95 ); ?></div>
		<div class="text-holder">
			<?php 
                if( $author_title ) echo '<h2 class="title">' . esc_html( $author_title ) . '</h2>';
                echo wpautop( wp_kses_post( get_the_author_meta( 'description' ) ) );
            ?>		
		</div>
	</div>
    <?php
    }
}
endif;
add_action( 'blossom_fashion_after_post_content', 'blossom_fashion_author', 20 );

if( ! function_exists( 'blossom_fashion_newsletter' ) ) :
/**
 * Newsletter
*/
function blossom_fashion_newsletter(){ 
    $ed_newsletter = get_theme_mod( 'ed_newsletter', false );
    $newsletter    = get_theme_mod( 'newsletter_shortcode' );
    if( blossom_fashion_is_btnw_activated() && $ed_newsletter && has_shortcode( $newsletter, 'BTEN' ) ){ ?>
        <div class="newsletter">
            <?php echo do_shortcode( $newsletter ); ?>
        </div>
        <?php
    }
}
endif;
add_action( 'blossom_fashion_after_post_content', 'blossom_fashion_newsletter', 25 );

if( ! function_exists( 'blossom_fashion_related_posts' ) ) :
/**
 * Related Posts 
*/
function blossom_fashion_related_posts(){ 
    global $post;
    $ed_related_post = get_theme_mod( 'ed_related', true );
    $related_title   = get_theme_mod( 'related_post_title', __( 'You may also like...', 'blossom-fashion' ) );
    if( $ed_related_post ){
        $args = array(
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'posts_per_page'        => 3,
            'ignore_sticky_posts'   => true,
            'post__not_in'          => array( $post->ID ),
            'orderby'               => 'rand'
        );
        $cats = get_the_category( $post->ID );
        if( $cats ){
            $c = array();
            foreach( $cats as $cat ){
                $c[] = $cat->term_id; 
            }
            $args['category__in'] = $c;
        }
        
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() ){ ?>
        <div class="related-posts">
    		<?php if( $related_title ) echo '<h2 class="title">' . esc_html( $related_title ) . '</h2>'; ?>
    		<div class="grid">
    			<?php 
                while( $qry->have_posts() ){ 
                    $qry->the_post(); ?>
                    <article class="post">
        				<a href="<?php the_permalink(); ?>" class="post-thumbnail">
                            <?php
                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( 'blossom-fashion-popular' );
                                }else{ 
                                    blossom_fashion_get_fallback_svg( 'blossom-fashion-popular' );
                                }
                            ?>
                        </a>
                        <header class="entry-header">
        					<?php
                                blossom_fashion_category();
                                the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); 
                            ?>
        				</header>
        			</article>
        			<?php 
                }
                ?>
    		</div>
    	</div>
        <?php
        }
        wp_reset_postdata();  
    }
}
endif;                                                                               
add_action( 'blossom_fashion_after_post_content', 'blossom_fashion_related_posts', 30 );

if( ! function_exists( 'blossom_fashion_popular_posts' ) ) :
/**
 * Popular Posts
*/
function blossom_fashion_popular_posts(){ 
    global $post;
    $ed_popular_post = get_theme_mod( 'ed_popular', true );
    $popular_title   = get_theme_mod( 'popular_post_title', __( 'Popular Posts', 'blossom-fashion' ) );
    if( $ed_popular_post ){ 
        $args = array(
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'posts_per_page'        => 6,
            'ignore_sticky_posts'   => true,
            'post__not_in'          => array( $post->ID ),
            'orderby'               => 'comment_count'
        );
        
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() ){ ?>
        <div class="popular-posts">
    		<?php if( $popular_title ) echo '<h2 class="title">' . esc_html( $popular_title ) . '</h2>'; ?>
            <div class="grid">
    			<?php 
                while( $qry->have_posts() ){
                    $qry->the_post(); ?>
                    <article class="post">
        				<a href="<?php the_permalink(); ?>" class="post-thumbnail">
                            <?php
                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( 'blossom-fashion-popular' );
                                }else{ 
                                    blossom_fashion_get_fallback_svg( 'blossom-fashion-popular' );
                                }
                            ?>
                        </a>
        				<header class="entry-header">
        					<?php
                                blossom_fashion_category();
                                the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); 
                            ?>
        				</header>
        			</article>
        			<?php 
                }
                ?>
    			
    		</div>
    	</div>
        <?php
        }
        wp_reset_postdata();  
    }
}
endif;
add_action( 'blossom_fashion_after_post_content', 'blossom_fashion_popular_posts', 35 );

if( ! function_exists( 'blossom_fashion_comment' ) ) :
/**
 * Comments Template 
*/
function blossom_fashion_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if ( !( get_theme_mod( 'ed_comments', false ) ) && comments_open() || get_comments_number() ) :
		comments_template();
	endif;
}
endif;
add_action( 'blossom_fashion_after_post_content', 'blossom_fashion_comment', 40 );
add_action( 'blossom_fashion_after_page_content', 'blossom_fashion_comment' );

if( ! function_exists( 'blossom_fashion_content_end' ) ) :
/**
 * Content End
*/
function blossom_fashion_content_end(){ 
        if( ! is_404() ) echo '</div><!-- .row -->'; ?>            
        </div><!-- .container/ -->        
    </div><!-- .error-holder/site-content -->
    <?php
}
endif;
add_action( 'blossom_fashion_before_footer', 'blossom_fashion_content_end', 20 );

if( ! function_exists( 'blossom_fashion_bottom_shop_section' ) ) :
/**
 * Bottom Shop Section
*/
function blossom_fashion_bottom_shop_section(){ 
    $ed_bottom_shop = get_theme_mod( 'ed_bottom_shop_section', false );
    $section_title  = get_theme_mod( 'bottom_shop_section_title', __( 'Shop My Closet', 'blossom-fashion' ) );
    $product_cat    = get_theme_mod( 'product_cat' );
    
    if( is_front_page() && blossom_fashion_is_woocommerce_activated() && $ed_bottom_shop ){
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'product_cat'    => $product_cat
        );
        
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() || $section_title ){ ?>
        <div class="bottom-shop-section">
            <div class="container">
            <?php if( $section_title ){ ?>
            <section class="header">
                <?php
                    if( $section_title ) echo '<h2 class="title">' . esc_html( $section_title ) . '</h2>';
                ?>
            </section>
            <?php } ?>
            
            <?php
                if( $qry->have_posts() ){ ?> 
                    <div class="bottom-shop-slider owl-carousel">
                    <?php
                        while( $qry->have_posts() ){
                            $qry->the_post(); 
                            $terms = get_the_terms( get_the_ID(), 'product_cat' );
                            $i = 0; ?>
                            <div class="item">
                                <?php
                                    echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
                                    the_post_thumbnail( 'full' );
                                    echo '</a>';
                                    
                                    echo '<div class="product-category">';
                                    foreach( $terms as $term ){
                                        $i++;
                                        echo '<a href="' . esc_url( get_term_link( $term->term_id ) ) . '">' . esc_html( $term->name ) . '</a>';
                                        if( $i < count( $terms ) ){
                                            esc_html_e( ', ', 'blossom-fashion' );
                                        }
                                    }
                                    echo '</div>';
                                    the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );                        
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            ?>
            </div>
        </div>
        <?php
        }
    }
}
endif;
add_action( 'blossom_fashion_footer', 'blossom_fashion_bottom_shop_section', 10 );

if( ! function_exists( 'blossom_fashion_instagram_section' ) ) :
/**
 * Bottom Shop Section
*/
function blossom_fashion_instagram_section(){ 
    if( is_front_page() && blossom_fashion_is_btif_activated() ){
        $ed_instagram = get_theme_mod( 'ed_instagram', false );
        
        if( $ed_instagram ){
            echo '<div class="instagram-section">';
            echo do_shortcode( '[blossomthemes_instagram_feed]' );
            echo '</div>';    
        }
    }
}
endif;
add_action( 'blossom_fashion_footer', 'blossom_fashion_instagram_section', 15 );

if( ! function_exists( 'blossom_fashion_footer_start' ) ) :
/**
 * Footer Start
*/
function blossom_fashion_footer_start(){
    ?>
    <footer id="colophon" class="site-footer" itemscope itemtype="http://schema.org/WPFooter">
    <?php
}
endif;
add_action( 'blossom_fashion_footer', 'blossom_fashion_footer_start', 20 );

if( ! function_exists( 'blossom_fashion_footer_top' ) ) :
/**
 * Footer Top
*/
function blossom_fashion_footer_top(){    
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three', 'footer-four' );
    $active_sidebars = array();
    $sidebar_count   = 0;
    
    foreach ( $footer_sidebars as $sidebar ) {
        if( is_active_sidebar( $sidebar ) ){
            array_push( $active_sidebars, $sidebar );
            $sidebar_count++ ;
        }
    }

    if( $active_sidebars ){ ?>
        <div class="footer-t">
            <div class="container">
                <div class="grid column-<?php echo esc_attr( $sidebar_count ); ?>">
                <?php foreach( $active_sidebars as $active ){ ?>
                    <div class="col">
                       <?php dynamic_sidebar( $active ); ?> 
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
        <?php 
    }   
}
endif;
add_action( 'blossom_fashion_footer', 'blossom_fashion_footer_top', 30 );

if( ! function_exists( 'blossom_fashion_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function blossom_fashion_footer_bottom(){ ?>
    <div class="footer-b">
		<div class="container">
			<div class="site-info">            
            <?php
                blossom_fashion_get_footer_copyright();
                esc_html_e( 'Blossom Fashion | Developed By', 'blossom-fashion' );
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( ' Blossom Themes', 'blossom-fashion' ) . '</a>.';                                
                printf( esc_html__( ' Powered by %s', 'blossom-fashion' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'blossom-fashion' ) ) .'" target="_blank">WordPress</a>.' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
		</div>
	</div>
    <?php
}
endif;
add_action( 'blossom_fashion_footer', 'blossom_fashion_footer_bottom', 40 );

if( ! function_exists( 'blossom_fashion_footer_end' ) ) :
/**
 * Footer End 
*/
function blossom_fashion_footer_end(){
    ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'blossom_fashion_footer', 'blossom_fashion_footer_end', 50 );

if( ! function_exists( 'blossom_fashion_page_end' ) ) :
/**
 * Page End
*/
function blossom_fashion_page_end(){
    ?>
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'blossom_fashion_after_footer', 'blossom_fashion_page_end', 20 );