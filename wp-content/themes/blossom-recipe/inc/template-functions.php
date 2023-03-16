<?php
/**
 * Blossom Recipe Template Functions which enhance the theme by hooking into WordPress
 *
 * @package Blossom_Recipe
 */

if( ! function_exists( 'blossom_recipe_doctype' ) ) :
/**
 * Doctype Declaration
*/
function blossom_recipe_doctype(){ ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'blossom_recipe_doctype', 'blossom_recipe_doctype' );

if( ! function_exists( 'blossom_recipe_head' ) ) :
/**
 * Before wp_head 
*/
function blossom_recipe_head(){ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'blossom_recipe_before_wp_head', 'blossom_recipe_head' );

if( ! function_exists( 'blossom_recipe_page_start' ) ) :
/**
 * Page Start
*/
function blossom_recipe_page_start(){ ?>
    <div id="page" class="site"><a aria-label="<?php esc_attr_e( 'skip to content', 'blossom-recipe' ); ?>" class="skip-link" href="#content"><?php esc_html_e( 'Skip to Content', 'blossom-recipe' ); ?></a>
    <?php
}
endif;
add_action( 'blossom_recipe_before_header', 'blossom_recipe_page_start', 20 );

if( ! function_exists( 'blossom_recipe_sticky_newsletter' ) ) :
/**
 * Page Start
*/
function blossom_recipe_sticky_newsletter(){
    $ed_newsletter = get_theme_mod( 'ed_header_newsletter', true );
    $newsletter    = get_theme_mod( 'header_newsletter_shortcode' );
    if( $ed_newsletter && $newsletter ){ ?>
        <div class="sticky-t-bar">
            <div class="sticky-bar-content">
                <div class="container">
                    <?php echo do_shortcode( $newsletter ); ?>
                </div>
            </div>
            <button aria-label="<?php esc_attr_e( 'sticky bar close', 'blossom-recipe' ); ?>" class="close"></button>
        </div>
        <?php
    }
}
endif;
add_action( 'blossom_recipe_before_header', 'blossom_recipe_sticky_newsletter', 30 );

if( ! function_exists( 'blossom_recipe_header' ) ) :
/**
 * Header Start
*/
function blossom_recipe_header(){ 

    $ed_cart   = get_theme_mod( 'ed_shopping_cart', true );
    $ed_search = get_theme_mod( 'ed_header_search', true ); ?>

    <header id="masthead" class="site-header header-one" itemscope itemtype="http://schema.org/WPHeader">
        <div class="main-header">
            <div class="container">
                <?php if( blossom_recipe_social_links( false ) ){
                    echo '<div class="header-social-icons">';
                    blossom_recipe_social_links();
                    echo '</div>';
                } ?>
                <?php if( ( blossom_recipe_is_woocommerce_activated() && $ed_cart ) || $ed_search ){ 
                    echo '<div class="search-wrap">';
                    if( $ed_search ) blossom_recipe_form_section();
                    if( blossom_recipe_is_woocommerce_activated() && $ed_cart ) blossom_recipe_wc_cart_count();
                    echo '</div>';
                } ?>
                <?php blossom_recipe_site_branding(); ?>
            </div>
        </div><!-- .main-header -->
        <div class="nav-wrap">
            <div class="container">
                <?php blossom_recipe_primary_nagivation(); ?>
            </div>
        </div>
    </header>
<?php
}
endif;
add_action( 'blossom_recipe_header', 'blossom_recipe_header', 20 );

if( ! function_exists( 'blossom_recipe_banner' ) ) :
/**
 * Banner Section 
*/
function blossom_recipe_banner(){
    if( is_front_page() || is_home() ) {
        $ed_banner          = get_theme_mod( 'ed_banner_section', 'slider_banner' );
        $slider_type        = get_theme_mod( 'slider_type', 'latest_posts' ); 
        $slider_cat         = get_theme_mod( 'slider_cat' );
        $posts_per_page     = get_theme_mod( 'no_of_slides', 4 );
        $banner_title       = get_theme_mod( 'banner_title', __( 'Relaxing Is Never Easy On Your Own', 'blossom-recipe' ) );
        $banner_subtitle    = get_theme_mod( 'banner_subtitle' , __( 'Come and discover your oasis. It has never been easier to take a break from stress and the harmful factors that surround you every day!', 'blossom-recipe' ) ) ;
        $banner_button      = get_theme_mod( 'banner_button', __( 'Read More', 'blossom-recipe' ) );
        $banner_url         = get_theme_mod( 'banner_url', '#' );

        $image_size = 'blossom-recipe-slider';
        
        if( $ed_banner == 'static_banner' && has_custom_header() ){ ?>
            <div class="site-banner static-banner<?php if( has_header_video() ) echo esc_attr( ' video-banner' ); ?>">
                <?php 
                the_custom_header_markup(); 
                if( $ed_banner == 'static_banner' && ( $banner_title || $banner_subtitle || ( $banner_button && $banner_url ) )){ ?>
                    <div class="banner-caption">
                        <div class="container">
                            <?php 
                            if( $banner_title ) echo '<h2 class="banner-title">' . esc_html( $banner_title ) . '</h2>';
                            if( $banner_subtitle ) echo '<div class="banner-desc">' . wp_kses_post( $banner_subtitle ) . '</div>';
                            if( $banner_button && $banner_url ) echo '<a href="'.esc_url( $banner_url ).'" class="btn btn-green"><span>'.esc_html( $banner_button ).'</span></a>';
                            ?>
                        </div>
                    </div> <?php 
                }
                ?>
            </div>
            <?php
        }elseif( $ed_banner == 'slider_banner' ){
            if( $slider_type == 'latest_posts' || $slider_type == 'cat' || ( blossom_recipe_is_brm_activated() && $slider_type == 'latest_recipes' ) || ( blossom_recipe_is_delicious_recipe_activated() && $slider_type == 'latest_dr_recipe' ) ){
            
                $args = array(
                    'post_status'         => 'publish',            
                    'ignore_sticky_posts' => true
                );
                if( blossom_recipe_is_delicious_recipe_activated() && $slider_type == 'latest_dr_recipe' ){
                    $args['post_type']      = DELICIOUS_RECIPE_POST_TYPE;
                    $args['posts_per_page'] = $posts_per_page;          
                }elseif( $slider_type == 'latest_recipes' ){
                    $args['post_type']      = 'blossom-recipe';
                    $args['posts_per_page'] = $posts_per_page;          
                }elseif( $slider_type === 'cat' && $slider_cat ){
                    $args['post_type']      = 'post';
                    $args['cat']            = $slider_cat; 
                    $args['posts_per_page'] = -1;  
                }else{
                    $args['post_type']      = 'post';
                    $args['posts_per_page'] = $posts_per_page;
                }
                    
                $qry = new WP_Query( $args );
            
                if( $qry->have_posts() ){ ?>
                <div class="site-banner slider-one">
                    <div class="container">
                        <div class="banner-slider owl-carousel">
                            <?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                            <div class="slider-item">
                                <a href="<?php the_permalink(); ?>">
                                <?php  
                                    if( has_post_thumbnail() ){
                                        the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
                                    }else{ 
                                        blossom_recipe_get_fallback_svg( $image_size );
                                    } ?>                        
                                    <div class="banner-caption">
                                        <?php the_title( '<h3 class="banner-title">', '</h3>' ); ?>
                                    </div>
                                </a>
                            </div>
                            <?php } ?>                        
                        </div>
                    </div>
                </div>
                <?php
                }
                wp_reset_postdata();
            }            
        }
    }  
}
endif;
add_action( 'blossom_recipe_after_header', 'blossom_recipe_banner', 15 );

if( ! function_exists( 'blossom_recipe_content_start' ) ) :
/**
 * Content Start
 *   
*/
function blossom_recipe_content_start(){

    if ( ! is_front_page() && ! is_home() ) blossom_recipe_breadcrumb();

    $background_image  = '';
    if( is_archive() ){
        $taxid             = get_queried_object_id();
        $dr_taxonomy_metas = get_term_meta( $taxid, 'dr_taxonomy_metas', true );
        $get_thumb_id      = isset( $dr_taxonomy_metas['taxonomy_image'] ) ? $dr_taxonomy_metas['taxonomy_image'] : false;
        $get_thumb_image   = wp_get_attachment_image_src( $get_thumb_id, 'full' );
        if( $get_thumb_image ) $background_image  = ' style="background-image: url( ' . esc_url( $get_thumb_image[0] ) . ' );"';
    }

    $template = array( 'templates/template-recipe-category.php', 'templates/template-recipe-cooking-method.php', 'templates/template-recipe-cuisine.php' );
    
    ?>
    <div id="content" class="site-content">
        <?php if( ! is_page_template( $template ) ){ ?>
            <header class="page-header<?php echo ( $background_image ) ? ' has-bg' : ''; ?>"<?php echo $background_image; ?>>
                <div class="container">
        			<?php
                        
                        if( is_archive() ) :
                            if( is_author() ){
                                $author_title = get_the_author_meta( 'display_name' ); ?>
                                <div class="container">
                                    <figure class="author-img"><?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?></figure>
                                    <div class="author-info-wrap">
                                        <?php 
                                            echo '<h1 class="name">' . esc_html__( 'All Posts By ','blossom-recipe' ) . '<span class="vcard">' . esc_html( $author_title ) . '</span></h1>';
                                        ?>      
                                    </div>
                                </div>    
                                <?php 
                            }else{
                                if( is_post_type_archive( 'recipe' ) ) {
                                    the_archive_title( '<h1 class="page-title">', '</h1>' );
                                }else{
                                    the_archive_title();
                                }
                            }
                            the_archive_description( '<div class="archive-description">', '</div>' );
                        endif;
                        
                        if( is_search() ){ 
                            echo '<h1 class="page-title">' . esc_html__( 'You Are Looking For', 'blossom-recipe' ) . '</h1>';
                            get_search_form();
                        }

                        if( is_page() ){
                            the_title( '<h1 class="page-title">', '</h1>' );
                        }
                    ?>
                </div>
    		</header>
        <?php } ?>
        <div class="container">
        <?php
}
endif;
add_action( 'blossom_recipe_content', 'blossom_recipe_content_start' );

if( ! function_exists( 'blossom_recipe_posts_per_page_count' ) ):
/**
*   Counts the Number of total posts in Archive, Search and Author
*/
function blossom_recipe_posts_per_page_count(){
    global $wp_query;
    if( is_archive() || is_search() && $wp_query->found_posts > 0 ) {
        printf( esc_html__( '%1$s Showing %2$s %3$s Result(s) %4$s', 'blossom-recipe' ), '<span class="showing-results">', '<span class="result-count">', esc_html( number_format_i18n( $wp_query->found_posts ) ), '</span></span>' );
    }
}
endif; 
add_action( 'blossom_recipe_before_posts_content' , 'blossom_recipe_posts_per_page_count', 10 );

if ( ! function_exists( 'blossom_recipe_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function blossom_recipe_post_thumbnail() {
    $image_size  = 'thumbnail';
    $ed_featured = get_theme_mod( 'ed_featured_image', true );
    $sidebar     = blossom_recipe_sidebar();
    
    if( is_home() ){
        $image_size = 'blossom-recipe-blog';        
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '">';
        if( has_post_thumbnail() ){                        
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        }else{
            blossom_recipe_get_fallback_svg( $image_size );    
        }        
        echo '</a>';
        echo '</figure>';
    }elseif( is_archive() || is_search() ){
        $image_size = 'blossom-recipe-blog'; 
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '">';
        if( has_post_thumbnail() ){
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        }else{
            blossom_recipe_get_fallback_svg( $image_size );
        }
        echo '</a>';
        echo '</figure>';
    }elseif( is_singular() ){
        $image_size = ( $sidebar ) ? 'blossom-recipe-blog' : 'blossom-recipe-blog-one';
        if( has_post_thumbnail() ) {
            if( is_single() ){
                if( $ed_featured ) {
                    echo '<figure class="post-thumbnail">';
                    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                    echo '</figure>';
                }
            }else{
                echo '<figure class="post-thumbnail">';
                the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                echo '</figure>';
            }
        }
    }
}
endif;
add_action( 'blossom_recipe_before_page_entry_content', 'blossom_recipe_post_thumbnail' );
add_action( 'blossom_recipe_before_post_entry_content', 'blossom_recipe_post_thumbnail', 15 );

if( ! function_exists( 'blossom_recipe_entry_header' ) ) :
/**
 * Entry Header
*/
function blossom_recipe_entry_header(){ ?>
    <header class="entry-header">
		<?php  
            if( blossom_recipe_is_delicious_recipe_activated() &&  DELICIOUS_RECIPE_POST_TYPE === get_post_type() ){
                blossom_recipe_recipe_category();
                the_title( '<h2 class="entry-title" itemprop="headline"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                echo '<div class="entry-meta">';
                blossom_recipe_posted_on();
                blossom_recipe_recipe_rating();
                echo '</div>';
            }else{        
                blossom_recipe_category();    
                
                if ( is_singular() ) :
        			the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );
        		else :
        			the_title( '<h2 class="entry-title" itemprop="headline"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        		endif; 
            
                if( 'post' === get_post_type() || ( blossom_recipe_is_brm_activated() && 'blossom-recipe' === get_post_type() ) ){
                    echo '<div class="entry-meta">';
                    if( is_single() ){
                        blossom_recipe_posted_by();
                        blossom_recipe_posted_on();
                    }else{
                        blossom_recipe_posted_on();
                    }
                    echo '</div>';
                }	
            }	
		?>
	</header>         
    <?php    
}
endif;
add_action( 'blossom_recipe_post_entry_content', 'blossom_recipe_entry_header', 10 );

if( ! function_exists( 'blossom_recipe_entry_content' ) ) :
/**
 * Entry Content
*/
function blossom_recipe_entry_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true ); ?>
    <div class="entry-content" itemprop="text">
		<?php
			if( is_singular() || ! $ed_excerpt || ( get_post_format() != false ) ){
                the_content();    
    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blossom-recipe' ),
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
add_action( 'blossom_recipe_page_entry_content', 'blossom_recipe_entry_content', 15 );
add_action( 'blossom_recipe_post_entry_content', 'blossom_recipe_entry_content', 15 );

if( ! function_exists( 'blossom_recipe_entry_footer' ) ) :
/**
 * Entry Footer
*/
function blossom_recipe_entry_footer(){ 
    $readmore = get_theme_mod( 'read_more_text', __( 'Read More', 'blossom-recipe' ) ); ?>
	<footer class="entry-footer">
		<?php
			if( is_single() ){
			    blossom_recipe_tag();
			}
            
            if( is_home() || is_archive() || is_search() ){
                echo '<a href="' . esc_url( get_the_permalink() ) . '" class="btn-link">' . esc_html( $readmore ) . '</a>';    
            }

            if( get_edit_post_link() ){
                edit_post_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Edit <span class="screen-reader-text">%s</span>', 'blossom-recipe' ),
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
add_action( 'blossom_recipe_page_entry_content', 'blossom_recipe_entry_footer', 20 );
add_action( 'blossom_recipe_post_entry_content', 'blossom_recipe_entry_footer', 20 );

if( ! function_exists( 'blossom_recipe_author' ) ) :
/**
 * Author Section
*/
function blossom_recipe_author(){ 
    $ed_author    = get_theme_mod( 'ed_post_author', false );
    $author_name = get_the_author_meta( 'display_name' );
    $author_description = get_the_author_meta( 'description' );
    $author_title = get_theme_mod( 'author_title', __( 'About', 'blossom-recipe' ) );
    if( ! $ed_author && $author_name && $author_description ) { ?>
        <div class="author-profile">
            <div class="author-img"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></div>
            <div class="author-content-wrap">
                <?php 
                    if( $author_name ) echo '<h2 class="author-name"><span class="author-title">' . esc_html( $author_title ) . '</span><span class="vcard">' . esc_html( $author_name ) . '</h2>';
                    if( $author_description ) echo '<div class="author-desc">' . wpautop( wp_kses_post( $author_description ) ) . '</div>';
                ?>      
            </div>
        </div>
    <?php
    }
}
endif;
add_action( 'blossom_recipe_after_post_content', 'blossom_recipe_author', 10 );

if( ! function_exists( 'blossom_recipe_newsletter' ) ) :
/**
 * Newsletter
*/
function blossom_recipe_newsletter(){ 
    if( is_active_sidebar( 'newsletter-section' ) ) {
        echo '<div class="newsletter-section"><div class="container">';
        dynamic_sidebar( 'newsletter-section' );   
        echo '</div></div>';            
    }
}
endif;
add_action( 'blossom_recipe_after_post_content', 'blossom_recipe_newsletter', 15 );

if( ! function_exists( 'blossom_recipe_navigation' ) ) :
/**
 * Navigation
*/
function blossom_recipe_navigation(){
    if( is_single() ){
        $next_post = get_next_post();
        $prev_post = get_previous_post(); 
        
        if( $prev_post || $next_post ){?>            
            <nav class="navigation post-navigation pagination" role="navigation">
    			<h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'blossom-recipe' ); ?></h2>
    			<div class="nav-links">
    				<?php if( $prev_post ){ ?>
                    <div class="nav-previous">
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
                            <span class="meta-nav"><i class="fas fa-chevron-left"></i></span>
                            <figure class="post-img">
                                <?php
                                $prev_img = get_post_thumbnail_id( $prev_post->ID );
                                if( $prev_img ){
                                    $prev_url = wp_get_attachment_image_url( $prev_img, 'thumbnail' );
                                    echo '<img src="' . esc_url( $prev_url ) . '" alt="' . the_title_attribute( 'echo=0', $prev_post ) . '">';                                        
                                }else{
                                    blossom_recipe_get_fallback_svg( 'thumbnail' );
                                }
                                ?>
                            </figure>
                            <span class="post-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if( $next_post ){ ?>
                    <div class="nav-next">
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
                            <span class="meta-nav"><i class="fas fa-chevron-right"></i></span>
                            <figure class="post-img">
                                <?php
                                $next_img = get_post_thumbnail_id( $next_post->ID );
                                if( $next_img ){
                                    $next_url = wp_get_attachment_image_url( $next_img, 'thumbnail' );
                                    echo '<img src="' . esc_url( $next_url ) . '" alt="' . the_title_attribute( 'echo=0', $next_post ) . '">';                                        
                                }else{
                                    blossom_recipe_get_fallback_svg( 'thumbnail' );
                                }
                                ?>
                            </figure>
                            <span class="post-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
                        </a>
                    </div>
                    <?php } ?>
    			</div>
    		</nav>        
            <?php
        }
    }else{                   
        the_posts_pagination( array(
            'prev_text'          => __( 'Previous', 'blossom-recipe' ),
            'next_text'          => __( 'Next', 'blossom-recipe' ),
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'blossom-recipe' ) . ' </span>',
        ) );            
    }
}
endif;
add_action( 'blossom_recipe_after_post_content', 'blossom_recipe_navigation', 20 );
add_action( 'blossom_recipe_after_posts_content', 'blossom_recipe_navigation' ); 

if( ! function_exists( 'blossom_recipe_related_posts' ) ) :
/**
 * Related Posts 
*/
function blossom_recipe_related_posts(){ 
    $ed_related_post = get_theme_mod( 'ed_related', true );
    
    if( $ed_related_post && !is_singular( 'recipe' ) ){
        blossom_recipe_get_posts_list( 'related' );    
    }
}
endif;                                                                               
add_action( 'blossom_recipe_after_post_content', 'blossom_recipe_related_posts', 30 );

if( ! function_exists( 'blossom_recipe_latest_posts' ) ) :
/**
 * Latest Posts
*/
function blossom_recipe_latest_posts(){ 
    blossom_recipe_get_posts_list( 'latest' );
}
endif;
add_action( 'blossom_recipe_latest_posts', 'blossom_recipe_latest_posts' );

if( ! function_exists( 'blossom_recipe_comment' ) ) :
/**
 * Comments Template 
*/
function blossom_recipe_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if( get_theme_mod( 'ed_comments', true ) && ( comments_open() || get_comments_number() ) ) :
		comments_template();
	endif;
}
endif;
add_action( 'blossom_recipe_after_post_content', 'blossom_recipe_comment', 35 );
add_action( 'blossom_recipe_after_page_content', 'blossom_recipe_comment' );

if( ! function_exists( 'blossom_recipe_content_end' ) ) :
/**
 * Content End
*/
function blossom_recipe_content_end(){ ?>            
        </div><!-- .container -->        
    </div><!-- .site-content -->
<?php
}
endif;
add_action( 'blossom_recipe_before_footer', 'blossom_recipe_content_end', 20 );

if( ! function_exists( 'blossom_recipe_newsletter_section' ) ) :
/**
 * Blossom Newsletter
*/
function blossom_recipe_newsletter_section(){
    $templates = array( 'templates/pages/recipe-courses.php', 'templates/pages/recipe-cuisines.php', 'templates/pages/recipe-cooking-methods.php', 'templates/pages/recipe-keys.php', 'templates/pages/recipe-tags.php' );
    if( is_active_sidebar( 'newsletter-section' ) && !is_single() && !is_page_template( $templates ) ) {
        echo '<div class="newsletter-section"><div class="container">';
        dynamic_sidebar( 'newsletter-section' );   
        echo '</div></div>';            
    }
}
endif;
add_action( 'blossom_recipe_before_footer_start', 'blossom_recipe_newsletter_section', 10 );

if( ! function_exists( 'blossom_recipe_instagram_section' ) ) :
/**
 * Blossom Instagram
*/
function blossom_recipe_instagram_section(){ 
    if( blossom_recipe_is_btif_activated() && ( is_front_page() || is_single() ) ){
        $ed_instagram = get_theme_mod( 'ed_instagram', false );        
        if( $ed_instagram ){
            echo '<div class="instagram-section">';
            echo do_shortcode( '[blossomthemes_instagram_feed]' );
            echo '</div>';    
        }
    }
}
endif;
add_action( 'blossom_recipe_before_footer_start', 'blossom_recipe_instagram_section', 20 );

if( ! function_exists( 'blossom_recipe_footer_start' ) ) :
/**
 * Footer Start
*/
function blossom_recipe_footer_start(){
    ?>
    <footer id="colophon" class="site-footer" itemscope itemtype="http://schema.org/WPFooter">
    <?php
}
endif;
add_action( 'blossom_recipe_footer', 'blossom_recipe_footer_start', 20 );

if( ! function_exists( 'blossom_recipe_footer_top' ) ) :
/**
 * Footer Top
*/
function blossom_recipe_footer_top(){    
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three' );
    $active_sidebars = array();
    $sidebar_count   = 0;
    
    foreach ( $footer_sidebars as $sidebar ) {
        if( is_active_sidebar( $sidebar ) ){
            array_push( $active_sidebars, $sidebar );
            $sidebar_count++ ;
        }
    }
                 
    if( $active_sidebars ){ ?>
        <div class="top-footer">
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
add_action( 'blossom_recipe_footer', 'blossom_recipe_footer_top', 30 );

if( ! function_exists( 'blossom_recipe_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function blossom_recipe_footer_bottom(){ ?>
    <div class="bottom-footer">
		<div class="container">
			<div class="copyright">            
            <?php
                blossom_recipe_get_footer_copyright();
                esc_html_e( ' Blossom Recipe | Developed By ', 'blossom-recipe' );
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'blossom-recipe' ) . '</a>.';
                
                printf( esc_html__( ' Powered by %s', 'blossom-recipe' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'blossom-recipe' ) ) .'" target="_blank">WordPress</a>. ' );
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
add_action( 'blossom_recipe_footer', 'blossom_recipe_footer_bottom', 40 );

if( ! function_exists( 'blossom_recipe_footer_end' ) ) :
/**
 * Footer End 
*/
function blossom_recipe_footer_end(){ ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'blossom_recipe_footer', 'blossom_recipe_footer_end', 50 );

if( ! function_exists( 'blossom_recipe_back_to_top' ) ) :
/**
 * Back to top
*/
function blossom_recipe_back_to_top(){ ?>
    <button aria-label="<?php esc_attr_e( 'got to top', 'blossom-recipe' ); ?>" id="back-to-top">
		<span><i class="fas fa-long-arrow-alt-up"></i></span>
	</button>
    <?php
}
endif;
add_action( 'blossom_recipe_after_footer', 'blossom_recipe_back_to_top', 15 );

if( ! function_exists( 'blossom_recipe_page_end' ) ) :
/**
 * Page End
*/
function blossom_recipe_page_end(){ ?>
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'blossom_recipe_after_footer', 'blossom_recipe_page_end', 20 );