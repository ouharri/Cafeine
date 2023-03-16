<?php  
	if ( ! function_exists( 'burger_appetizer_product' ) ) :
	function burger_appetizer_product() {
	$hs_product 			= get_theme_mod('hs_product','1');	
	$product_title 			= get_theme_mod('product_title','Special Dishes Today');
	$product_description	= get_theme_mod('product_description','Lets Discover Food'); 
	$product_display_num	= get_theme_mod('product_display_num','4');
	if($hs_product=='1'):	
?>		
<section id="product-section" class="product-section product-home st-py-default woo-shop">
	<div class="container">
		<?php if(!empty($product_title) || !empty($product_description)): ?>
			<div class="row">
				<div class="col-lg-6 col-12 mx-lg-auto mb-5 text-center">
					<div class="heading-default heading-white wow fadeInUp">
						<?php if(!empty($product_title)): ?>
							<h2><?php echo wp_kses_post($product_title); ?></h2>
						<?php endif; ?>
						<?php do_action('appetizer_section_seprator'); ?>
						<?php if(!empty($product_description)): ?>
							<p><?php echo wp_kses_post($product_description); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row g-4">
			<?php
				if ( class_exists( 'woocommerce' ) ) {
					$args                   = array(
						'post_type' => 'product',
						'posts_per_page' => $product_display_num
					);
					
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); global $product; 
				?>
					<div class="col-lg-3 col-md-6 col-12">
						<div class="product">
							<div class="product-inner">
								<div class="product-img">
									<a href="<?php echo esc_url(the_permalink()); ?>">
										<?php the_post_thumbnail(); ?>
									</a>
									<?php if ( $product->is_on_sale() ) : ?>
										<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="badge">' . esc_html__( 'Sale', 'appetizer' ) . '</span>', $post, $product ); ?>
									<?php endif; ?>
								</div>
								<div class="product-content">
									<div class="price">
										<?php echo $product->get_price_html(); ?>
									</div>
									<!--div class="price">
										<del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>72.00</bdi></span></del> 
										<ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>69.00</bdi></span></ins>
									</div-->
									<h5><a href="<?php echo esc_url(the_permalink()); ?>"><?php echo the_title(); ?></a></h5>
									<p><?php the_excerpt(); ?></p>
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
</section>
<?php
endif;	
	}
endif;
if ( function_exists( 'burger_appetizer_product' ) ) {
$section_priority = apply_filters( 'appetizer_section_priority', 13, 'burger_appetizer_product' );
add_action( 'appetizer_sections', 'burger_appetizer_product', absint( $section_priority ) );
}