<?php  
if ( ! function_exists( 'burger_appetizer_recepies' ) ) :
	function burger_appetizer_recepies() {
	$hs_recepies 			= get_theme_mod('hs_recepies','1');		
	$recepies_title 		= get_theme_mod('recepies_title','Top Recipes');
	$recepies_description	= get_theme_mod('recepies_description','Our Talented Chefs'); 
	$recepies_display_num	= get_theme_mod('recepies_display_num','6');
	$recepies_list			= get_theme_mod('recepies_list','<div class="top-list-heading">
							<h3>Super Delicious</h3>
							<h2 class="text-primary">Chicken Burger</h2>
						</div>
						<div class="top-list-footer">
							<h5>Call Us Now:</h5>   
							<h3 class="text-primary">+123 456 7890</h3>
						</div>
						<img src="'.esc_url(BURGER_COMPANION_PLUGIN_URL .'inc/rasam/images/toprecipes/toprecipes-list.png').'">');
	if($hs_recepies=='1'):					
?>		
<section id="toprecipes-section" class="toprecipes-section toprecipes-home st-py-default">
	<div class="container">
		<?php if(!empty($recepies_title) || !empty($recepies_description)): ?>
			<div class="row">
				<div class="col-lg-6 col-12 mx-lg-auto mb-5 text-center">
					<div class="heading-default wow fadeInUp">
						<?php if(!empty($recepies_title)): ?>
							<h2><?php echo wp_kses_post($recepies_title); ?></h2>
						<?php endif; ?>
						<?php do_action('appetizer_section_seprator'); ?>
						<?php if(!empty($recepies_description)): ?>
							<p><?php echo wp_kses_post($recepies_description); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row g-4">
			<?php if(empty($recepies_list)): ?>
			<div class="col-lg-12 col-md-12 col-12 wow fadeInLeft">
			<?php else: ?>
			<div class="col-lg-7 col-md-12 col-12 wow fadeInLeft">
			<?php endif; ?>
				<div class="row g-4">
					<?php
					if ( class_exists( 'woocommerce' ) ) {
						$args                   = array(
							'post_type' => 'product',
							'posts_per_page' => $recepies_display_num
						);
						
						$loop = new WP_Query( $args );
						while ( $loop->have_posts() ) : $loop->the_post(); global $product; 
					?>
					<div class="col-lg-6 col-md-6 col-12">
						<div class="product">
							<div class="product-inner">
								<div class="product-img">
									<a href="<?php echo esc_url(the_permalink()); ?>">
										<?php the_post_thumbnail(); ?>
									</a>
								</div>
								<div class="product-content">
									<h5><a href="<?php echo esc_url(the_permalink()); ?>"><?php echo the_title(); ?></a></h5>
									<!--p>Burgers</p-->
									<div class="price">
										<?php echo $product->get_price_html(); ?>
									</div>
									<div class="product-action">
										<?php woocommerce_template_loop_add_to_cart(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php  wp_reset_postdata(); ?>
					<?php } ?>
				</div>
			</div>
			<?php if(!empty($recepies_list)): ?>
				<div class="col-lg-4 col-md-12 col-12 ml-lg-auto wow fadeInRight">
					<div class="tilter">
						<div class="top-list tilter__figure">
							<?php echo do_shortcode($recepies_list); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
endif;	
	}
endif;
if ( function_exists( 'burger_appetizer_recepies' ) ) {
$section_priority = apply_filters( 'appetizer_section_priority', 13, 'burger_appetizer_recepies' );
add_action( 'appetizer_sections', 'burger_appetizer_recepies', absint( $section_priority ) );
}