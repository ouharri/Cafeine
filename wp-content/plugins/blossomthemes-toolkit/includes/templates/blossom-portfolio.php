<?php
/**
 * Portfolio Page Template
*/
get_header(); 
    /**
     * Action before portfolios
    */
    do_action( 'bttk_before_portfolios' ); ?>

<div class="portfolio-holder">	
    <?php 
    $args = apply_filters( 'bttk_taxonomy_args', array(
        'taxonomy' => 'blossom_portfolio_categories',
        'orderby'  => 'name', 
        'order'    => 'ASC',
    ) );                
    $terms = get_terms( $args );
    
    if( $terms ){ ?>
        <div class="portfolio-sorting">        
            <button data-sort-value="*" class="button is-checked"><?php echo esc_html_e( 'All', 'blossomthemes-toolkit' ); ?></button><!-- This is HACK for reducing space between inline block elements.
            --><?php
                foreach( $terms as $t ){                            
                    echo '<button class="button" data-sort-value=".' . esc_attr( $t->slug ) .  '">' . esc_html( $t->name ) . '</button>';
                } 
            ?>
        </div><!-- .portfolio-sorting -->            
        <?php
    }
    
    $arg = apply_filters( 'bttk_portfolio_args', array( 'post_type' => 'blossom-portfolio', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
    $portfolio_qry = new WP_Query( $arg );
    if( taxonomy_exists( 'blossom_portfolio_categories' ) && $portfolio_qry->have_posts() ){ ?>
    
	<div class="portfolio-img-holder">
		<?php
        while( $portfolio_qry->have_posts() ){
            $portfolio_qry->the_post();
            $terms = get_the_terms( get_the_ID(), 'blossom_portfolio_categories' );
            $s = '';
            $i = 0;
            if( $terms ){
                foreach( $terms as $t ){
                    $i++;
                    $s .= $t->slug;
                    if( count( $terms ) > $i ){
                        $s .= ' ';
                    }
                }
            }
            $term_list = get_the_term_list( get_the_ID(), 'blossom_portfolio_categories' );                    
            if( has_post_thumbnail() ){
                $image_size = apply_filters( 'bttk_portflio_image', 'full' ); ?>
                <div class="portfolio-item <?php echo esc_attr( $s );?>">
    				<div class="portfolio-item-inner">
    					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $image_size ); ?></a>
    					<div class="portfolio-text-holder">
    						<?php 
                                if( $term_list ) echo '<div class="portfolio-cat">' . $term_list . '</div>'; 
                            ?>
                            <div class="portfolio-img-title">
    							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    						</div>
    					</div>
    				</div>
    			</div>
    		    <?php
            }
        }
        wp_reset_postdata();
        ?>				
	</div><!-- .portfolio-img-holder -->
    <?php } ?>
</div><!-- .portfolio-holder -->
    
<?php
    /**
     * Action after portfolios
    */
    do_action( 'bttk_after_portfolios' );
    
get_footer();