<?php
/**
 * Blossom Recipe Standalone Functions.
 *
 * @package Blossom_Recipe
 */

if ( ! function_exists( 'blossom_recipe_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function blossom_recipe_posted_on() {
    $ed_post_date   = get_theme_mod( 'ed_post_date', false );
    if( $ed_post_date ) return false;

	$ed_updated_post_date = get_theme_mod( 'ed_post_update_date', true );
    $on = __( 'on ', 'blossom-recipe' );

    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		if( $ed_updated_post_date ){
            $time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
            $on = __( 'updated on ', 'blossom-recipe' );		  
		}else{
            $time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';  
		}        
	}else{
	   $time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';   
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
    
    if( is_single() ) {
        $posted_on = sprintf( '%1$s %2$s', esc_html( $on ), '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="33" height="31" viewBox="0 0 33 31"><defs><filter id="Rectangle_1344" x="0" y="0" width="33" height="31" filterUnits="userSpaceOnUse"><feOffset dy="3" input="SourceAlpha"></feOffset><feGaussianBlur stdDeviation="3" result="blur"></feGaussianBlur><feFlood flood-color="#E84E3B" flood-opacity="0.102"></feFlood><feComposite operator="in" in2="blur"></feComposite><feComposite in="SourceGraphic"></feComposite></filter></defs><g id="Group_5559" data-name="Group 5559" transform="translate(-534.481 -811)"><g transform="matrix(1, 0, 0, 1, 534.48, 811)" filter="url(#Rectangle_1344)"><rect id="Rectangle_1344-2" data-name="Rectangle 1344" width="15" height="13" transform="translate(9 6)" fill="#fff"></rect></g><path id="Path_30675" data-name="Path 30675" d="M5.84,23.3a2.279,2.279,0,0,1-2.277-2.277V10.1A2.279,2.279,0,0,1,5.84,7.821H7.206V6.455a.455.455,0,0,1,.911,0V7.821h6.375V6.455a.455.455,0,0,1,.911,0V7.821h1.366A2.28,2.28,0,0,1,19.044,10.1V21.026A2.279,2.279,0,0,1,16.767,23.3ZM4.474,21.026A1.367,1.367,0,0,0,5.84,22.392H16.767a1.368,1.368,0,0,0,1.366-1.366V12.374H4.474ZM5.84,8.732A1.367,1.367,0,0,0,4.474,10.1v1.366h13.66V10.1a1.368,1.368,0,0,0-1.366-1.366Z" transform="translate(539.437 808)" fill="#ABADB4"></path><g id="Group_5542" data-name="Group 5542" transform="translate(547.149 822.506)"><path id="Path_30676" data-name="Path 30676" d="M1036.473-439.908a.828.828,0,0,1,.831.814.832.832,0,0,1-.833.838.831.831,0,0,1-.825-.822A.826.826,0,0,1,1036.473-439.908Z" transform="translate(-1035.646 439.908)" fill="#374757"></path><path id="Path_30677" data-name="Path 30677" d="M1105.926-439.908a.826.826,0,0,1,.831.826.832.832,0,0,1-.821.826.831.831,0,0,1-.836-.823A.827.827,0,0,1,1105.926-439.908Z" transform="translate(-1099.534 439.908)" fill="#374757"></path><path id="Path_30678" data-name="Path 30678" d="M1071.255-439.909a.821.821,0,0,1,.81.844.825.825,0,0,1-.847.809.825.825,0,0,1-.8-.851A.821.821,0,0,1,1071.255-439.909Z" transform="translate(-1067.628 439.909)" fill="#374757"></path><path id="Path_30679" data-name="Path 30679" d="M1036.473-439.908a.828.828,0,0,1,.831.814.832.832,0,0,1-.833.838.831.831,0,0,1-.825-.822A.826.826,0,0,1,1036.473-439.908Z" transform="translate(-1035.646 443.397)" fill="#374757"></path><path id="Path_30680" data-name="Path 30680" d="M1105.926-439.908a.826.826,0,0,1,.831.826.832.832,0,0,1-.821.826.831.831,0,0,1-.836-.823A.827.827,0,0,1,1105.926-439.908Z" transform="translate(-1099.534 443.397)" fill="#374757"></path><path id="Path_30681" data-name="Path 30681" d="M1071.255-439.909a.821.821,0,0,1,.81.844.825.825,0,0,1-.847.809.825.825,0,0,1-.8-.851A.821.821,0,0,1,1071.255-439.909Z" transform="translate(-1067.628 443.397)" fill="#374757"></path></g></g></svg><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' );
    }else{
        $posted_on = sprintf( '%1$s', '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="33" height="31" viewBox="0 0 33 31"><defs><filter id="Rectangle_1344" x="0" y="0" width="33" height="31" filterUnits="userSpaceOnUse"><feOffset dy="3" input="SourceAlpha"></feOffset><feGaussianBlur stdDeviation="3" result="blur"></feGaussianBlur><feFlood flood-color="#E84E3B" flood-opacity="0.102"></feFlood><feComposite operator="in" in2="blur"></feComposite><feComposite in="SourceGraphic"></feComposite></filter></defs><g id="Group_5559" data-name="Group 5559" transform="translate(-534.481 -811)"><g transform="matrix(1, 0, 0, 1, 534.48, 811)" filter="url(#Rectangle_1344)"><rect id="Rectangle_1344-2" data-name="Rectangle 1344" width="15" height="13" transform="translate(9 6)" fill="#fff"></rect></g><path id="Path_30675" data-name="Path 30675" d="M5.84,23.3a2.279,2.279,0,0,1-2.277-2.277V10.1A2.279,2.279,0,0,1,5.84,7.821H7.206V6.455a.455.455,0,0,1,.911,0V7.821h6.375V6.455a.455.455,0,0,1,.911,0V7.821h1.366A2.28,2.28,0,0,1,19.044,10.1V21.026A2.279,2.279,0,0,1,16.767,23.3ZM4.474,21.026A1.367,1.367,0,0,0,5.84,22.392H16.767a1.368,1.368,0,0,0,1.366-1.366V12.374H4.474ZM5.84,8.732A1.367,1.367,0,0,0,4.474,10.1v1.366h13.66V10.1a1.368,1.368,0,0,0-1.366-1.366Z" transform="translate(539.437 808)" fill="#ABADB4"></path><g id="Group_5542" data-name="Group 5542" transform="translate(547.149 822.506)"><path id="Path_30676" data-name="Path 30676" d="M1036.473-439.908a.828.828,0,0,1,.831.814.832.832,0,0,1-.833.838.831.831,0,0,1-.825-.822A.826.826,0,0,1,1036.473-439.908Z" transform="translate(-1035.646 439.908)" fill="#374757"></path><path id="Path_30677" data-name="Path 30677" d="M1105.926-439.908a.826.826,0,0,1,.831.826.832.832,0,0,1-.821.826.831.831,0,0,1-.836-.823A.827.827,0,0,1,1105.926-439.908Z" transform="translate(-1099.534 439.908)" fill="#374757"></path><path id="Path_30678" data-name="Path 30678" d="M1071.255-439.909a.821.821,0,0,1,.81.844.825.825,0,0,1-.847.809.825.825,0,0,1-.8-.851A.821.821,0,0,1,1071.255-439.909Z" transform="translate(-1067.628 439.909)" fill="#374757"></path><path id="Path_30679" data-name="Path 30679" d="M1036.473-439.908a.828.828,0,0,1,.831.814.832.832,0,0,1-.833.838.831.831,0,0,1-.825-.822A.826.826,0,0,1,1036.473-439.908Z" transform="translate(-1035.646 443.397)" fill="#374757"></path><path id="Path_30680" data-name="Path 30680" d="M1105.926-439.908a.826.826,0,0,1,.831.826.832.832,0,0,1-.821.826.831.831,0,0,1-.836-.823A.827.827,0,0,1,1105.926-439.908Z" transform="translate(-1099.534 443.397)" fill="#374757"></path><path id="Path_30681" data-name="Path 30681" d="M1071.255-439.909a.821.821,0,0,1,.81.844.825.825,0,0,1-.847.809.825.825,0,0,1-.8-.851A.821.821,0,0,1,1071.255-439.909Z" transform="translate(-1067.628 443.397)" fill="#374757"></path></g></g></svg><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' );
    }
	
	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'blossom_recipe_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author.
 */
function blossom_recipe_posted_by() {
	$ed_post_author   = get_theme_mod( 'ed_post_author', false );
    if( $ed_post_author ) return false;

    $byline = sprintf( '%s',
		'<span itemprop="name"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" itemprop="url">' . esc_html( get_the_author() ) . '</a></span>' 
    );
	echo '<span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person">' . $byline . '</span>';
}
endif;

if( ! function_exists( 'blossom_recipe_comment_count' ) ) :
/**
 * Comment Count
*/
function blossom_recipe_comment_count(){
    $ed_comments = get_theme_mod( 'ed_comments', true );
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) && $ed_comments ) {
		echo '<span class="comments"><i class="far fa-comment"></i>';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'blossom-recipe' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}    
}
endif;

if ( ! function_exists( 'blossom_recipe_category' ) ) :
/**
 * Prints categories
 */
function blossom_recipe_category(){
	$ed_cat_single  = get_theme_mod( 'ed_category', false );
    // Hide category and tag text for pages.
	if ( 'post' === get_post_type() && !$ed_cat_single ) {
		$categories_list = get_the_category_list( ' ' );
		if ( $categories_list ) {
			echo '<span class="category" itemprop="about">' . $categories_list . '</span>';
		}
	}elseif( blossom_recipe_is_brm_activated() && 'blossom-recipe' === get_post_type() ){
        $categories_list = get_the_term_list( '', 'recipe-category' );
        if ( $categories_list ) {
            echo '<span class="category" itemprop="about">' . $categories_list . '</span>';
        }
    }
}
endif;

if ( ! function_exists( 'blossom_recipe_tag' ) ) :
/**
 * Prints tags
 */
function blossom_recipe_tag(){
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		$tags_list = get_the_tag_list( ' ' );
		if ( $tags_list ) {
			/* translators: 1: list of tags. */
			printf( '<div class="tags" itemprop="about">' . esc_html__( '%1$sTags:%2$s %3$s', 'blossom-recipe' ) . '</div>', '<span>', '</span>', $tags_list );
		}
	}
}
endif;

if( ! function_exists( 'blossom_recipe_get_posts_list' ) ) :
/**
 * Returns Latest, Related & Popular Posts
*/
function blossom_recipe_get_posts_list( $status ){
    global $post;
    $sidebar = blossom_recipe_sidebar( true );
    $args = array(
        'posts_status'        => 'publish',
        'ignore_sticky_posts' => true
    );
    
    switch( $status ){
        case 'latest':        
        $args['post_type']      = 'post';
        $args['posts_per_page'] = 4;
        $title                  = __( 'Latest Posts', 'blossom-recipe' );
        $class                  = 'latest';
        $image_size             = 'blossom-recipe-slider';
        break;
        
        case 'related':
        $args['post_type']      = 'post';
        $args['posts_per_page'] = ( $sidebar == 'full-width' ) ? 4 : 6;
        $args['post__not_in']   = array( $post->ID );
        $args['orderby']        = 'rand';
        $title                  = get_theme_mod( 'related_post_title', __( 'You may also like...', 'blossom-recipe' ) );
        $class                  = 'related';
        $image_size             = 'blossom-recipe-slider';
        $cats                   = get_the_category( $post->ID );       
        if( $cats ){
            $c = array();
            foreach( $cats as $cat ){
                $c[] = $cat->term_id; 
            }
            $args['category__in'] = $c;
        }
        break;        
    }

    $qry = new WP_Query( $args );
    
    if( $qry->have_posts() ){ ?>    
        <div class="<?php echo esc_attr( $class ); ?>-articles">
    		<?php 
            if( $title ) echo '<h3 class="' . esc_attr( $class ) . '-title">' . esc_html( $title ) . '</h3>'; ?>
            <div class="block-wrap">
    			<?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                <div class="article-block">
    				<figure class="post-thumbnail">
                        <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                            <?php
                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                                }else{ 
                                    blossom_recipe_get_fallback_svg( $image_size );
                                }
                            ?>
                        </a>
                    </figure>    
    				<header class="entry-header">
    					<?php
                            the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
                        ?>                        
    				</header>
    			</div>
                <?php } ?>
            </div>                
    	</div>
        <?php
    }
    wp_reset_postdata();
}
endif;

if( ! function_exists( 'blossom_recipe_site_branding' ) ) :
/**
 * Site Branding
*/
function blossom_recipe_site_branding(){ 
    $site_title       = get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    $header_text      = get_theme_mod( 'header_text', 1 );
    if( has_custom_logo() || $site_title || $site_description || $header_text ) :
        if( has_custom_logo() && ( $site_title || $site_description ) && $header_text ) {
            $branding_class = ' has-logo-text';
        }else{
            $branding_class = '';
        } ?>
        <div class="site-branding<?php echo esc_attr( $branding_class ); ?>" itemscope itemtype="http://schema.org/Organization">
    		<?php 
            if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
                the_custom_logo();
            } 
            if( $site_title || $site_description ) :
                echo '<div class="site-title-wrap">';
                if( is_front_page() ){ ?>
                    <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
            		<?php 
                }else{ ?>
                    <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
                <?php
                }
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ){ ?>
                    <p class="site-description" itemprop="description"><?php echo $description; ?></p>
                <?php
                }
                echo '</div>';
            endif;
            ?>
    	</div>    
    <?php
    endif;
}
endif;

if( ! function_exists( 'blossom_recipe_social_links' ) ) :
/**
 * Social Links 
*/
function blossom_recipe_social_links( $echo = true ){ 
    $social_links = get_theme_mod( 'social_links' );
    $ed_social    = get_theme_mod( 'ed_social_links', false ); 
    
    if( $ed_social && $social_links && $echo ){ ?>
    <ul class="social-icon-list">
        <?php 
        foreach( $social_links as $link ){
           if( $link['link'] ){ ?>
            <li>
                <a href="<?php echo esc_url( $link['link'] ); ?>" target="_blank" rel="nofollow noopener">
                    <i class="<?php echo esc_attr( $link['font'] ); ?>"></i>
                </a>
            </li>          
            <?php
            } 
        } 
        ?>
    </ul>
    <?php    
    }elseif( $ed_social && $social_links ){
        return true;
    }else{
        return false;
    }
    ?>
    <?php                                
}
endif;

if( ! function_exists( 'blossom_recipe_form_section' ) ) :
/**
 * Form Icon
*/
function blossom_recipe_form_section(){ ?>
    <div class="header-search">
        <button aria-label="<?php esc_attr_e( 'search form open', 'blossom-recipe' ); ?>" class="search-btn" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false"><span><?php esc_html_e( 'Search', 'blossom-recipe' ); ?></span><i class="fas fa-search"></i></button>
        <?php blossom_recipe_header_search(); ?>
    </div>
    <?php
}
endif;

if( ! function_exists( 'blossom_recipe_header_search' ) ) :
/**
 * Page Start
*/
function blossom_recipe_header_search(){ ?>
    <div class="header-search-form search-modal cover-modal" data-modal-target-string=".search-modal">
        <div class="header-search-inner-wrap">
            <?php get_search_form();?> 
            <button aria-label="<?php esc_attr_e( 'search form close', 'blossom-recipe' ); ?>" class="close" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false"></button>
        </div>
    </div>
    <?php
}
endif;

if( ! function_exists( 'blossom_recipe_primary_nagivation' ) ) :
/**
 * Primary Navigation.
*/
function blossom_recipe_primary_nagivation(){ ?>
	<nav id="site-navigation" class="main-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
		<button class="toggle-button" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".close-main-nav-toggle">
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
        </button>
        <div class="primary-menu-list main-menu-modal cover-modal" data-modal-target-string=".main-menu-modal">
            <button class="close close-main-nav-toggle" data-toggle-target=".main-menu-modal" data-toggle-body-class="showing-main-menu-modal" aria-expanded="false" data-set-focus=".main-menu-modal"></button>
            <div class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'blossom-recipe' ); ?>">
                <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu main-menu-modal',
                        'fallback_cb'    => 'blossom_recipe_primary_menu_fallback',
                    ) );
                ?>
            </div>
        </div>
	</nav><!-- #site-navigation -->
    <?php
}
endif;

if( ! function_exists( 'blossom_recipe_primary_menu_fallback' ) ) :
/**
 * Fallback for primary menu
*/
function blossom_recipe_primary_menu_fallback(){
    if( current_user_can( 'manage_options' ) ){
        echo '<ul id="primary-menu" class="nav-menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'blossom-recipe' ) . '</a></li>';
        echo '</ul>';
    }
}
endif;

if( ! function_exists( 'blossom_recipe_theme_comment' ) ) :
/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function blossom_recipe_theme_comment( $comment, $args, $depth ){
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" itemscope itemtype="http://schema.org/UserComments">
	<?php endif; ?>
    	<article class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
            	   <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                   <?php printf( __( '<b class="fn" itemprop="creator" itemscope itemtype="https://schema.org/Person">%s</b> <span class="says">says:</span>', 'blossom-recipe' ), get_comment_author_link() ); ?>
            	</div><!-- .comment-author vcard -->
                <div class="comment-metadata commentmetadata">
                    <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
                        <time itemprop="commentTime" datetime="<?php echo esc_attr( get_gmt_from_date( get_comment_date() . get_comment_time(), 'Y-m-d H:i:s' ) ); ?>"><?php printf( esc_html__( '%1$s at %2$s', 'blossom-recipe' ), get_comment_date(),  get_comment_time() ); ?></time>
                    </a>
                </div>
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'blossom-recipe' ); ?></p>
                    <br />
                <?php endif; ?>
                <div class="reply">
                    <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div>
            </footer>            
            <div class="comment-content" itemprop="commentText"><?php comment_text(); ?></div>    
        </article>
        
	<?php if ( 'div' != $args['style'] ) : ?>
    </div><!-- .comment-body -->
	<?php endif; ?>
<?php
}
endif;

if( ! function_exists( 'blossom_recipe_sidebar' ) ) :
/**
 * Return sidebar layouts for pages/posts
*/
function blossom_recipe_sidebar( $class = false ){
    global $post;
    $return = false;
    $page_layout = get_theme_mod( 'page_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Pages
    $post_layout = get_theme_mod( 'post_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Posts
    $layout      = get_theme_mod( 'layout_style', 'right-sidebar' ); //Default Layout Style for Styling Settings
    
    if( is_singular( array( 'page', 'post' ) ) ){         
        if( get_post_meta( $post->ID, '_blossom_recipe_sidebar_layout', true ) ){
            $sidebar_layout = get_post_meta( $post->ID, '_blossom_recipe_sidebar_layout', true );
        }else{
            $sidebar_layout = 'default-sidebar';
        }
        
        if( is_page() ){
            $template = array( 'templates/template-recipe-category.php', 'templates/template-recipe-cooking-method.php', 'templates/template-recipe-cuisine.php', 'templates/blossom-portfolio.php' );
            $dr_template = array( 'templates/pages/recipe-courses.php', 'templates/pages/recipe-cuisines.php', 'templates/pages/recipe-cooking-methods.php', 'templates/pages/recipe-keys.php', 'templates/pages/recipe-tags.php' );
            if( is_page_template( $template ) ){
                $return = $class ? 'full-width' : false;
            }elseif( is_page_template( $dr_template ) ){
                if( $page_layout == 'no-sidebar' ){
                    $return = $class ? 'full-width' : false;
                }elseif( $page_layout == 'centered' ){
                    $return = $class ? 'full-width centered' : false;
                }elseif( is_active_sidebar( 'delicious-recipe-sidebar' ) ){            
                    if( $class ){
                        if( $page_layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                        if( $page_layout == 'left-sidebar' ) $return = 'leftsidebar';
                    }else{
                        $return = 'delicious-recipe-sidebar';    
                    }                         
                }else{
                    $return = $class ? 'full-width' : false;
                } 
            }elseif( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'centered' ) ){
                    $return = $class ? 'full-width centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }elseif( is_single() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'centered' ) ){
                    $return = $class ? 'full-width centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }
    }elseif( blossom_recipe_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || get_post_type() == 'product' ) ){
        if( $layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false;
        }elseif( is_active_sidebar( 'shop-sidebar' ) ){            
            if( $class ){
                if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
            }         
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }elseif( blossom_recipe_is_delicious_recipe_activated() && ( is_post_type_archive( 'recipe' ) || is_tax( 'recipe-course' ) || is_tax( 'recipe-cuisine' ) || is_tax( 'recipe-cooking-method' ) || is_tax( 'recipe-key' ) || is_tax( 'recipe-tag' ) ) ){            
            if( $layout == 'no-sidebar' ){
                $return = $class ? 'full-width' : false; //Fullwidth
            }elseif( is_active_sidebar( 'delicious-recipe-sidebar' ) ){
                if( $class ){
                    if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                    if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
                }else{
                    $return = 'delicious-recipe-sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false; //Fullwidth
            } 
    }elseif( blossom_recipe_is_delicious_recipe_activated() && is_singular( DELICIOUS_RECIPE_POST_TYPE ) ){ 
        if( $post_layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false; //Fullwidth
        }elseif( $post_layout == 'centered' ){
            $return = $class ? 'full-width centered' : false; //Fullwidth
        }elseif( is_active_sidebar( 'delicious-recipe-sidebar' ) ){
            if( $class ){
                if( $post_layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $post_layout == 'left-sidebar' ) $return = 'leftsidebar';
            }else{
                $return = 'delicious-recipe-sidebar';    
            } 
        }else{
            $return = $class ? 'full-width' : false; //Fullwidth
        }
    }elseif( is_404() ) {
        $return = $class ? 'full-width' : false;
    }else{
        if( $layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false;
        }elseif( is_active_sidebar( 'sidebar' ) ){            
            if( $class ){
                if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
            }else{
                $return = 'sidebar';    
            }                         
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }    
    return $return; 
}
endif;

if( ! function_exists( 'blossom_recipe_get_categories' ) ) :
/**
 * Function to list post categories in customizer options
*/
function blossom_recipe_get_categories( $select = true, $taxonomy = 'category', $slug = false ){    
    /* Option list of all categories */
    $categories = array();
    
    $args = array( 
        'hide_empty' => false,
        'taxonomy'   => $taxonomy 
    );
    
    $catlists = get_terms( $args );
    if( $select ) $categories[''] = __( 'Choose Category', 'blossom-recipe' );
    foreach( $catlists as $category ){
        if( $slug ){
            $categories[$category->slug] = $category->name;
        }else{
            $categories[$category->term_id] = $category->name;    
        }        
    }
    
    return $categories;
}
endif;

if( ! function_exists( 'blossom_recipe_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function blossom_recipe_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

if( ! function_exists( 'blossom_recipe_get_image_sizes' ) ) :
/**
 * Get information about available image sizes
 */
function blossom_recipe_get_image_sizes( $size = '' ) {
 
    global $_wp_additional_image_sizes;
 
    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();
 
    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size ) {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array( 
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    } 
    // Get only 1 size if found
    if ( $size ) {
        if( isset( $sizes[ $size ] ) ) {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }
    return $sizes;
}
endif;

if ( ! function_exists( 'blossom_recipe_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function blossom_recipe_get_fallback_svg( $post_thumbnail ) {
    if( ! $post_thumbnail ){
        return;
    }
    
    $image_size = blossom_recipe_get_image_sizes( $post_thumbnail );
     
    if( $image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#f2f2f2;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

/**
 * Is Blossom Theme Toolkit active or not
*/
function blossom_recipe_is_bttk_activated(){
    return class_exists( 'Blossomthemes_Toolkit' ) ? true : false;
}

/**
 * Is BlossomThemes Email Newsletters active or not
*/
function blossom_recipe_is_btnw_activated(){
    return class_exists( 'Blossomthemes_Email_Newsletter' ) ? true : false;        
}

/**
 * Is BlossomThemes Social Feed active or not
*/
function blossom_recipe_is_btif_activated(){
    return class_exists( 'Blossomthemes_Instagram_Feed' ) ? true : false;
}

/**
 * Query WooCommerce activation
 */
function blossom_recipe_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

/**
 * Check if Delicious Recipe Plugin is installed
*/
function blossom_recipe_is_delicious_recipe_activated(){
    return class_exists( 'WP_Delicious\DeliciousRecipes' ) ? true : false;
}

/**
 * Check if Blossom Recipe Maker Plugin is installed
*/
function blossom_recipe_is_brm_activated(){
    return class_exists( 'Blossom_Recipe_Maker' ) ? true : false;
}

if ( ! function_exists( 'blossom_recipe_fonts_url' ) ) :
/**
 * Register Google fonts
 *
 * @return string Google fonts URL for the theme.
 */
function blossom_recipe_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    /* translators: If there are characters in your language that are not supported by Nunito Sans, translate this to 'off'. Do not translate into your own language. */
    if ( 'off' !== _x( 'on', 'Nunito Sans: on or off', 'blossom-recipe' ) ) {
        $fonts[] = 'Nunito Sans:300,300i,400,400i,600,600i,700,700i,800,800i';
    }

    /* translators: If there are characters in your language that are not supported by Marcellus, translate this to 'off'. Do not translate into your own language. */
    if ( 'off' !== _x( 'on', 'Marcellus: on or off', 'blossom-recipe' ) ) {
        $fonts[] = 'Marcellus:';
    }

    $query_args = array(
        'family' => urlencode( implode( '|', $fonts ) ),
        'subset' => urlencode( $subsets ),
    );

    if ( $fonts ) {
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    if( get_theme_mod( 'ed_localgoogle_fonts', false ) ) {
        $fonts_url = blossom_recipe_get_webfont_url( add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ) );
    }
    
    return esc_url_raw( $fonts_url );
}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         *
         */
        do_action( 'wp_body_open' );
    }
endif;

if( ! function_exists( 'blossom_recipe_breadcrumb' ) ) :
/**
 * Breadcrumbs
*/
function blossom_recipe_breadcrumb() {
    global $post;
    $post_page  = get_option( 'page_for_posts' ); //The ID of the page that displays posts.
    $show_front = get_option( 'show_on_front' ); //What to show on the front page
    $home       = get_theme_mod( 'home_text', __( 'Home', 'blossom-recipe' ) ); // text for the 'Home' link
    $delimiter  = '<i class="fa fa-angle-right"></i>';
    $before     = '<span class="current" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">'; // tag before the current crumb
    $after      = '</span>'; // tag after the current crumb

    if( get_theme_mod( 'ed_breadcrumb', true ) ){
            
        $depth = 1;
        echo '<div class="breadcrumb-wrapper"><div class="container" >
                <div id="crumbs" itemscope itemtype="http://schema.org/BreadcrumbList"> 
                    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemprop="item" href="' . esc_url( home_url() ) . '"><span itemprop="name">' . esc_html( $home ) . '</span></a>
                        <meta itemprop="position" content="'. absint( $depth ).'" />
                        <span class="separator">' .  $delimiter  . '</span>
                    </span>';
        if( is_home() ){
            $depth = 2;
            echo $before . '<a itemprop="item" href="'. esc_url( get_the_permalink() ) .'"><span itemprop="name">' . esc_html( single_post_title( '', false ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" /> '. $after;
            
        }elseif( is_category() ){
            
            $depth = 2;
            $thisCat = get_category( get_query_var( 'cat' ), false );

            if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                $p = get_post( $post_page );
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $post_page ) ) . '"><span itemprop="name">' . esc_html( $p->post_title ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                $depth ++;  
            }

            if ( $thisCat->parent != 0 ) {
                $parent_categories = get_category_parents( $thisCat->parent, false, ',' );
                $parent_categories = explode( ',', $parent_categories );

                foreach ( $parent_categories as $parent_term ) {
                    $parent_obj = get_term_by( 'name', $parent_term, 'category' );
                    if( is_object( $parent_obj ) ){
                        $term_url    = get_term_link( $parent_obj->term_id );
                        $term_name   = $parent_obj->name;
                        echo ' <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span> ';
                        $depth ++;
                    }
                }
            }
            echo $before . ' <a itemprop="item" href="' . esc_url( get_term_link( $thisCat->term_id) ) . '"><span itemprop="name">' .  esc_html( single_cat_title( '', false ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /> ' . $after;
        
        }elseif( blossom_recipe_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ //For Woocommerce archive page
        
            $depth = 2;
            $current_term = $GLOBALS['wp_query']->get_queried_object();
            
            if ( wc_get_page_id( 'shop' ) ) { //Displaying Shop link in woocommerce archive page
                $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                $shop_url = wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0  ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
                if ( ! $_name ) {
                    $product_post_type = get_post_type_object( 'product' );
                    $_name = $product_post_type->labels->singular_name;
                }
                echo ' <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $shop_url ) . '"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /> <span class="separator">' . $delimiter . '</span></span> ';
                $depth++;
            }

            if( is_product_category() ){
                $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                $ancestors = array_reverse( $ancestors );
                foreach ( $ancestors as $ancestor ) {
                    $ancestor = get_term( $ancestor, 'product_cat' );    
                    if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                        echo ' <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $ancestor ) ) . '"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /> <span class="separator">' . $delimiter . '</span></span> ';
                        $depth++;
                    }
                }
            }           
            echo $before .'<a itemprop="item" href="' . esc_url( get_term_link( $current_term->term_id ) ) . '"><span itemprop="name">'. esc_html( $current_term->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            
        }elseif( blossom_recipe_is_woocommerce_activated() && is_shop() ){ //Shop Archive page

            $depth = 2;
            if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
                return;
            }
            $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
            $shop_url = wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0  ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );

            if ( ! $_name ) {
                $product_post_type = get_post_type_object( 'product' );
                $_name = $product_post_type->labels->singular_name;
            }
            echo $before .'<a itemprop="item" href="' . esc_url( $shop_url ) . '"><span itemprop="name">'. esc_html( $_name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after; 

        }elseif( is_tax( 'blossom_portfolio_categories' ) ){
            $depth = 2;
            $queried_object = get_queried_object();
            $taxonomy = 'blossom_portfolio_categories';
            $ancestors = get_ancestors( $queried_object->term_id, $taxonomy );
            if( !empty( $ancestors ) ){
            $termz = get_term( $ancestors[0], $taxonomy );
            $ancestors_title = !empty( $termz->name ) ? esc_html( $termz->name ) : ''; 
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $termz->term_id ) ) . '"><span itemprop="name">' . $ancestors_title . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';

                $depth++;
            }
            
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        }elseif( is_tax( 'recipe-cuisine' ) ){
            $depth = 2;
            $queried_object = get_queried_object();
            $taxonomy = 'recipe-cuisine';
            $ancestors = get_ancestors( $queried_object->term_id, $taxonomy );
            if( !empty( $ancestors ) ){
            $termz = get_term( $ancestors[0], $taxonomy );
            $ancestors_title = !empty( $termz->name ) ? esc_html( $termz->name ) : ''; 
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $termz->term_id ) ) . '"><span itemprop="name">' . $ancestors_title . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';

                $depth++;
            }
            
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        }elseif( is_tax( 'recipe-category' ) ){
            $depth = 2;
            $queried_object = get_queried_object();
            $taxonomy = 'recipe-category';
            $ancestors = get_ancestors( $queried_object->term_id, $taxonomy );
            if( !empty( $ancestors ) ){
            $termz = get_term( $ancestors[0], $taxonomy );
            $ancestors_title = !empty( $termz->name ) ? esc_html( $termz->name ) : ''; 
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $termz->term_id ) ) . '"><span itemprop="name">' . $ancestors_title . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';

                $depth++;
            }
            
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        }elseif( is_tax( 'recipe-course' ) ){
            $depth = 2;
            $queried_object = get_queried_object();
            $taxonomy = 'recipe-course';
            $ancestors = get_ancestors( $queried_object->term_id, $taxonomy );
            if( !empty( $ancestors ) ){
            $termz = get_term( $ancestors[0], $taxonomy );
            $ancestors_title = !empty( $termz->name ) ? esc_html( $termz->name ) : ''; 
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $termz->term_id ) ) . '"><span itemprop="name">' . $ancestors_title . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';

                $depth++;
            }
            
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        }elseif( is_tax( 'recipe-cooking-method' ) ){
            $depth = 2;
            $queried_object = get_queried_object();
            $taxonomy = 'recipe-cooking-method';
            $ancestors = get_ancestors( $queried_object->term_id, $taxonomy );
            if( !empty( $ancestors ) ){
            $termz = get_term( $ancestors[0], $taxonomy );
            $ancestors_title = !empty( $termz->name ) ? esc_html( $termz->name ) : ''; 
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $termz->term_id ) ) . '"><span itemprop="name">' . $ancestors_title . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';

                $depth++;
            }
            
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        }elseif( is_tax( 'recipe-tag' ) ){
            $depth = 2;
            $queried_object = get_queried_object();
            $taxonomy = 'recipe-tag';
            $ancestors = get_ancestors( $queried_object->term_id, $taxonomy );
            if( !empty( $ancestors ) ){
            $termz = get_term( $ancestors[0], $taxonomy );
            $ancestors_title = !empty( $termz->name ) ? esc_html( $termz->name ) : ''; 
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $termz->term_id ) ) . '"><span itemprop="name">' . $ancestors_title . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';

                $depth++;
            }
            
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        }elseif( is_tax( 'recipe-key' ) ){
            $depth = 2;
            $queried_object = get_queried_object();
            $taxonomy = 'recipe-key';
            $ancestors = get_ancestors( $queried_object->term_id, $taxonomy );
            if( !empty( $ancestors ) ){
            $termz = get_term( $ancestors[0], $taxonomy );
            $ancestors_title = !empty( $termz->name ) ? esc_html( $termz->name ) : ''; 
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $termz->term_id ) ) . '"><span itemprop="name">' . $ancestors_title . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';

                $depth++;
            }
            
            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( $queried_object->name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        }elseif( is_tag() ){
            
            $queried_object = get_queried_object();
            $depth = 2;

            echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( single_tag_title( '', false ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
     
        }elseif( is_author() ){
            
            $depth = 2;
            global $author;

            $userdata = get_userdata( $author );
            echo $before . '<a itemprop="item" href="' . esc_url( get_author_posts_url( $author ) ) . '"><span itemprop="name">' . esc_html( $userdata->display_name ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
     
        }elseif( is_search() ){
            
            $depth = 2;
            $request_uri = $_SERVER['REQUEST_URI'];
            echo $before .'<a itemprop="item" href="'. esc_url( $request_uri ) .'"><span itemprop="name">'. esc_html__( 'Search Results for "', 'blossom-recipe' ) . esc_html( get_search_query() ) . esc_html__( '"', 'blossom-recipe' ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_day() ){
            
            $depth = 2;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'blossom-recipe' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'blossom-recipe' ) ) ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'"/><span class="separator">' . $delimiter . '</span></span> ';
            $depth ++;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'blossom-recipe' ) ), get_the_time( __( 'm', 'blossom-recipe' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'blossom-recipe' ) ) ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span> ';
            $depth ++;
            echo $before .'<a itemprop="item" href="' . esc_url( get_day_link( get_the_time( __( 'Y', 'blossom-recipe' ) ), get_the_time( __( 'm', 'blossom-recipe' ) ), get_the_time( __( 'd', 'blossom-recipe' ) ) ) ) . '"><span itemprop="name">'. esc_html( get_the_time( __( 'd', 'blossom-recipe' ) ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_month() ){
            
            $depth = 2;
            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'blossom-recipe' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'blossom-recipe' ) ) ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span> ';
            $depth++;
            echo $before .'<a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'blossom-recipe' ) ), get_the_time( __( 'm', 'blossom-recipe' ) ) ) ) . '"><span itemprop="name">'. esc_html( get_the_time( __( 'F', 'blossom-recipe' ) ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_year() ){
            
            $depth = 2;
            echo $before .'<a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'blossom-recipe' ) ) ) ) . '"><span itemprop="name">'. esc_html( get_the_time( __( 'Y', 'blossom-recipe' ) ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;

        }elseif( is_single() && !is_attachment() ){
            
            if( blossom_recipe_is_woocommerce_activated() && 'product' === get_post_type() ){ //For Woocommerce single product
                
                $depth = 2;
                if ( wc_get_page_id( 'shop' ) ) { //Displaying Shop link in woocommerce archive page
                    $_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
                    $shop_url = wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0  ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
                    if ( ! $_name ) {
                        $product_post_type = get_post_type_object( 'product' );
                        $_name = $product_post_type->labels->singular_name;
                    }
                    echo ' <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $shop_url ) . '"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /> <span class="separator">' . $delimiter . '</span></span> ';
                    $depth++;
                }
            
                if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                    $main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
                    $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );    
                        if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                            echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                            $depth++;
                        }
                    }
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_term_link( $main_term ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $main_term->name ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span> ';
                    $depth ++;
                }
                
                echo $before .'<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                
            }elseif( get_post_type() != 'post' ){
                $depth = 2;
                $post_type = get_post_type_object( get_post_type() );
                
                if( $post_type->has_archive == true ){// For CPT Archive Link
                   
                   // Add support for a non-standard label of 'archive_title' (special use case).
                   $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                   printf( '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a><meta itemprop="position" content="%3$s" />', esc_url( get_post_type_archive_link( get_post_type() ) ), $label, $depth );
                   echo '<meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                   $depth ++;    
                }

                if( get_post_type() =='blossom-portfolio' ){
                    // Add support for a non-standard label of 'archive_title' (special use case).
                   $label = !empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
                   $portfolio_link = blossom_recipe_get_page_template_url( 'templates/blossom-portfolio.php' );
                   echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="'.esc_url( $portfolio_link) .'" itemprop="item"><span itemprop="name">'.esc_html($label).'</span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span>';
                   $depth ++;    
                }

                echo $before .'<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
                
            }else{ //For Post
                
                $cat_object       = get_the_category();
                $potential_parent = 0;
                $depth            = 2;
                
                if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                    $p = get_post( $post_page );
                    echo ' <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span> ';  
                    $depth++;
                }
                
                if( is_array( $cat_object ) ){ //Getting category hierarchy if any
        
                    //Now try to find the deepest term of those that we know of
                    $use_term = key( $cat_object );
                    foreach( $cat_object as $key => $object )
                    {
                        //Can't use the next($cat_object) trick since order is unknown
                        if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
                            $use_term = $key;
                            $potential_parent = $object->term_id;
                        }
                    }
                    
                    $cat = $cat_object[$use_term];
              
                    $cats = get_category_parents( $cat, false, ',' );
                    $cats = explode( ',', $cats );

                    foreach ( $cats as $cat ) {
                        $cat_obj = get_term_by( 'name', $cat, 'category' );
                        if( is_object( $cat_obj ) ){
                            $term_url    = get_term_link( $cat_obj->term_id );
                            $term_name   = $cat_obj->name;
                            echo ' <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . ' </span></a><meta itemprop="position" content="'. absint( $depth ).'" /><span class="separator">' . $delimiter . '</span></span> ';
                            $depth ++;
                        }
                    }
                }

                 echo $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;     
                
            }
        
        }elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ){
            
            $depth = 2;
            $post_type = get_post_type_object(get_post_type());
            if( get_query_var('paged') ){
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" />';
                echo ' <span class="separator">' . $delimiter . '</span></span> ' . $before . sprintf( __('Page %s', 'blossom-recipe'), get_query_var('paged') ) . $after;
            }elseif( is_archive() ){
                echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( post_type_archive_title( '', false ) ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            }else{
                echo $before .'<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">'. esc_html( $post_type->label ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
            }

        }elseif( is_attachment() ){
            
            $depth = 2;
            $parent = get_post( $post->post_parent );
            $cat = get_the_category( $parent->ID ); 
            if( $cat ){
                $cat = $cat[0];
                echo get_category_parents( $cat, TRUE, ' <span class="separator">' . $delimiter . '</span> ');
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $parent ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $parent->post_title ) . '<span></a><meta itemprop="position" content="'. absint( $depth ).'" />' . ' <span class="separator">' . $delimiter . '</span></span>';
            }
            echo  $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;
        
        }elseif( is_page() && !$post->post_parent ){
            
           $depth = 2;
            echo $before .'<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" />'. $after;

        }elseif( is_page() && $post->post_parent ){
            
            global $post;
            $depth = 2;
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            
            while( $parent_id ){
                $current_page = get_post( $parent_id );
                $breadcrumbs[] = $current_page->ID;
                $parent_id  = $current_page->post_parent;
            }

            $breadcrumbs = array_reverse( $breadcrumbs );

            for ( $i = 0; $i < count( $breadcrumbs); $i++ ){
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . esc_url( get_permalink( $breadcrumbs[$i] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $breadcrumbs[$i] ) ) . '</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></span>';
                if ( $i != count( $breadcrumbs ) - 1 ) echo ' <span class="separator">' . $delimiter . '</span> ';
                $depth++;
            }

            echo ' <span class="separator">' .  $delimiter . '</span> ' . $before .'<a href="' . get_permalink() . '" itemprop="item"><span itemprop="name">'. esc_html( get_the_title() ) .'</span></a><meta itemprop="position" content="'. absint( $depth ).'" /></span>'. $after;
        
        }elseif( is_404() ){
            echo $before . esc_html__( '404 Error - Page Not Found', 'blossom-recipe' ) . $after;
        }
        
        if( get_query_var('paged') ) echo __( ' (Page', 'blossom-recipe' ) . ' ' . get_query_var('paged') . __( ')', 'blossom-recipe' );
        
        echo '</div></div></div><!-- .breadcrumb-wrapper -->';
        
    }  
}
endif;

if( ! function_exists( 'blossom_recipe_get_page_template_url' ) ) :
/**
 * Returns page template url if not found returns home page url
*/
function blossom_recipe_get_page_template_url( $page_template ){
    $args = array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => $page_template,
        'post_type'  => 'page',
        'fields'     => 'ids',
    );
    
    $posts_array = get_posts( $args );
    
    $url = ( $posts_array ) ? get_permalink( $posts_array[0] ) : get_permalink( get_option( 'page_on_front' ) );
    return $url;    
}
endif;