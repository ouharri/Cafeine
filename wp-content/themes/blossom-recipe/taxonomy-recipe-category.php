<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header(); ?>

<div class="wrap">

	<?php
	//get the currently queried taxonomy term, for use later in the template file
	$tax_term = get_queried_object();
	$termId = $tax_term->term_id;
	$termSlug = $tax_term->slug;
	$taxonomyName = $tax_term->taxonomy;
	$postType = 'blossom-recipe';

	$options = get_option( 'br_recipe_settings', array() );
	
	$tax_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$parent_tax_post_args = array(
       	'post_type' => $postType, // Your Post type Name that You Registered
       	'posts_per_page' => -1,
       	'order' => 'ASC',
       	'tax_query' => array(
       		array(
                'taxonomy' => $taxonomyName,
                'field' => 'slug',
                'terms' => $termSlug,
                'include_children' => false
            )
       	),
       	'paged' => $tax_paged
    	);

	$parent_tax_post = new WP_Query( $parent_tax_post_args );

	if( $parent_tax_post->have_posts() ) : 
	 	?>

		<div id="primary" class="content-area" itemscope itemtype="http://schema.org/ItemList">
		<main id="main" class="site-main" role="main">
			<div class="parent-taxonomy-wrap">

			<?php
			while( $parent_tax_post->have_posts()) : $parent_tax_post->the_post();
			        	
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;
			echo '</div>';
			
	endif;
	wp_reset_postdata();

	$catChildren= get_term_children( $termId, $taxonomyName );
	
	foreach ($catChildren as $child)
	{
		$cterm = get_term_by( 'id', $child, $taxonomyName);
        $term_link = get_term_link( $cterm );
        $child_term_description = term_description( $cterm, $taxonomyName ); 
            
        $cpaged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $child_query = new WP_Query( array(
            'post_type' => $postType,
            'posts_per_page' =>-1, 
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomyName,
                    'field' => 'slug',
                    'terms' => $cterm->slug
                    )
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'paged'=> $cpaged

        ));

        if( $child_query->have_posts() ) : 
		?>
			<div class="child-taxonomy-wrap">
				<h2 class="child-title">
					<a href="<?php echo esc_url( $term_link );?>">
						<?php echo esc_html( $cterm->name );?>
					</a>
				</h2>
				<?php
	                if(!empty( $child_term_description ) ):
	                	?>
			        	<div class="child-description">
			        		<?php echo wp_kses_post( $child_term_description ); ?>
			        	</div>
			        	<?php
			        endif;

	            while ( $child_query->have_posts() ) : $child_query->the_post();

	            	do_action('br_recipe_archive_action');

				endwhile;
				echo '</div>';
		endif;

	}
	wp_reset_postdata();

	?>
	</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- .wrap -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
