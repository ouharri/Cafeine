<?php  
if ( ! function_exists( 'burger_decorme_cta' ) ) :
	function burger_decorme_cta() {
	$cta_hs 				= get_theme_mod('cta_hs','1');	
	$cta_contact_icon		= get_theme_mod('cta_contact_icon','fa-phone'); 
	$cta_contact_ttl		= get_theme_mod('cta_contact_ttl','+1-202-555-0170 '); 
	$cta_title				= get_theme_mod('cta_title','Contact Us For Your Dreams Home design'); 
	$cta_description 		= get_theme_mod('cta_description','There are many variations of passages of Lorem Ipsum available but of the majority have suffered alteration in some form.');
	$cta_contact_btn_lbl	= get_theme_mod('cta_contact_btn_lbl','Contact Us'); 
	$cta_contact_btn_url	= get_theme_mod('cta_contact_btn_url');
if($cta_hs=='1'):	
?>		
<section id="cta-section" class="cta-section home-cta st-py-default">
	<div class="container">
		<div class="row wow fadeInUp">
			<div class="col-12">
				<div class="cta-wrapper">
					<div class="cta-content">
						<?php if(!empty($cta_contact_icon) || !empty($cta_contact_ttl)): ?>
							<div class="cta-icon-wrap">
								<?php if(!empty($cta_contact_icon)): ?>
									<div class="cta-icon"><i class="fa <?php echo esc_attr($cta_contact_icon); ?>"></i></div>
								<?php endif; ?>	
								
								<?php if(!empty($cta_contact_ttl)): ?>
									<div class="cta-number"><?php echo wp_kses_post($cta_contact_ttl); ?></div>
								<?php endif; ?>	
							</div>
						<?php endif; ?>	
						
						<?php if(!empty($cta_title) || !empty($cta_description)): ?>
							<div class="cta-info">
								<?php if(!empty($cta_title)): ?>
									<h3><?php echo wp_kses_post($cta_title); ?></h3>
								<?php endif; ?>	
								
								<?php if(!empty($cta_description)): ?>
									<p class="mb-0"><?php echo wp_kses_post($cta_description); ?></p>
								<?php endif; ?>		
							</div>
						<?php endif; ?>	
					</div>
					
					<?php if(!empty($cta_contact_btn_lbl)): ?>
						<div class="cta-btn-wrap mt-lg-0 mt-4">
							<div class="cta-btns">
								<a href="<?php echo esc_url($cta_contact_btn_url); ?>" class="btn btn-primary"><?php echo wp_kses_post($cta_contact_btn_lbl); ?></a>
							</div>
						</div>
					<?php endif; ?>		
				</div>
			</div>
		</div>
	</div>
</section>
<?php	
endif;	}
endif;
if ( function_exists( 'burger_decorme_cta' ) ) {
$section_priority = apply_filters( 'decorme_section_priority', 14, 'burger_decorme_cta' );
add_action( 'decorme_sections', 'burger_decorme_cta', absint( $section_priority ) );
}
	