 <!--===// Start: Slider
    =================================--> 
<?php  
	if ( ! function_exists( 'burger_storebiz_slider' ) ) :
	function burger_storebiz_slider() {
	$slider 						= get_theme_mod('slider',storebiz_get_slider_default());
	$hs_slider_content_left			= get_theme_mod('hs_slider_content_left','1'); 
	$hs_slider_content_right		= get_theme_mod('hs_slider_content_right','1'); 
	$slider_right_info 				= get_theme_mod('slider_right_info',storebiz_get_slider_info_default());
?>

<div id="first-section" class="first-section mt-lg-2-9 mt-0 slider-two">
	<div class="container">
		<div class="row g-3">
        	<div class="col-lg-3 col-12">
				<?php if($hs_slider_content_left =='1'){ ?>
					<div class="browse-cat vertical-is-active">
						<button type="button" class="browse-btn"><span><i class="fa fa-list-ul first"></i> <?php esc_html_e('Browse Category', 'storebiz-pro'); ?></span></button>
						<div class="browse-menus">
							<div class="browse-menu">
								<?php if (class_exists('WooCommerce')) { ?>
									<ul class="main-menu">
									<?php
										$categories = array(
											  'taxonomy' => 'product_cat',
											  'hide_empty' => false,
											  'parent'   => 0
										  );
										$product_cat = get_terms( $categories );
										foreach ($product_cat as $parent_product_cat) {
											$child_args = array(
												'taxonomy' => 'product_cat',
												'hide_empty' => false,
												'parent'   => $parent_product_cat->term_id
											);
											$thumbnail_id = get_term_meta( $parent_product_cat->term_id, 'thumbnail_id', true );
											$image = wp_get_attachment_url( $thumbnail_id );
											$child_product_cats = get_terms( $child_args );
											if ( ! empty($child_product_cats) ) {
												echo '<li class="menu-item menu-item-has-children"><a href="'.get_term_link($parent_product_cat->term_id).'" class="nav-link">'.(!empty($image) ? "<img src='{$image}' alt='' width='20' height='20' />":''); echo $parent_product_cat->name.'</a>';
											} else {
												echo '<li class="menu-item"><a href="'.get_term_link($parent_product_cat->term_id).'" class="nav-link">'.(!empty($image) ? "<img src='{$image}' alt='' width='20' height='20' />":''); echo $parent_product_cat->name.'</a>';
											}
											if ( ! empty($child_product_cats) ) {
												echo '<ul class="dropdown-menu">';
												foreach ($child_product_cats as $child_product_cat) {
												echo '<li class="menu-item"><a href="'.get_term_link($child_product_cat->term_id).'" class="dropdown-item">'.$child_product_cat->name.'</a></li>';
												} echo '</ul>';
											} echo '</li>';
										} ?>
									</ul>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="col-lg-6 col-12">
				<div class="slider-area">
					<div id="slider-section" class="slider-section">
				        <div class="home-slider owl-carousel owl-theme">
							<?php
								if ( ! empty( $slider ) ) {
								$slider = json_decode( $slider );
								foreach ( $slider as $slide_item ) {
									$storebiz_slide_title = ! empty( $slide_item->title ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->title, 'slider section' ) : '';
									$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->subtitle, 'slider section' ) : '';
									$text = ! empty( $slide_item->text ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->text, 'slider section' ) : '';
									$button = ! empty( $slide_item->text2) ? apply_filters( 'storebiz_translate_single_string', $slide_item->text2,'slider section' ) : '';
									$storebiz_slide_link = ! empty( $slide_item->link ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->link, 'slider section' ) : '';
									$icon = ! empty( $slide_item->icon_value ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->icon_value, 'slider section' ) : '';
									$image = ! empty( $slide_item->image_url ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->image_url, 'slider section' ) : '';
									$open_new_tab = ! empty( $slide_item->open_new_tab ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->open_new_tab, 'slider section' ) : '';
									//$align = $slide_item->slide_align;
									$align = ! empty( $slide_item->slide_align ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->slide_align, 'slider section' ) : '';
							?>
				        	<div class="item">
				                <?php if ( ! empty( $image ) ) : ?>
									<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $storebiz_slide_title ) ) : ?> alt="<?php echo esc_attr( $storebiz_slide_title ); ?>" title="<?php echo esc_attr( $storebiz_slide_title ); ?>" <?php endif; ?> />
								<?php endif; ?>
				                <div class="main-slider">
				                    <div class="main-table">
				                        <div class="main-table-cell">
				                            <div class="container">                                
				                                <div class="main-content text-<?php echo esc_attr($align); ?>">
													<?php if ( ! empty( $storebiz_slide_title ) ) : ?>
														<h6 data-animation="fadeInUp" data-delay="150ms"> <?php echo esc_html($storebiz_slide_title); ?></h6>
													<?php endif; ?>	
													
													<?php if ( ! empty( $subtitle ) ) : ?>
														<h2 data-animation="fadeInUp" data-delay="200ms"><?php echo wp_kses_post($subtitle); ?></h2>
													<?php endif; ?>	
													
													<?php if ( ! empty( $text ) ) : ?>
														<h2 data-animation="fadeInUp" data-delay="500ms"><?php echo wp_kses_post($text); ?></h2>
													<?php endif; ?>	
													
													<?php if ( ! empty( $button ) ) : ?>
														<a data-animation="fadeInUp" data-delay="800ms" href="<?php echo esc_url($storebiz_slide_link); ?>" <?php if($open_new_tab== 'yes' || $open_new_tab== '1') { echo "target='_blank'"; } ?> class="btn btn-primary btn-like-icon"><?php echo wp_kses_post($button); ?></a>
													<?php endif; ?>		
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                </div>
				            </div>
							<?php } } ?>
				        </div>
				    </div>
				</div>
			</div>
			<div class="col-lg-3 col-12">
				<div class="row">
					<div class="col-12">
						<?php if(($hs_slider_content_right =='1')): ?>
							<div class="slider-info-slider owl-carousel owl-theme">
								<?php
									if ( ! empty( $slider_right_info ) ) {
									$slider_right_info = json_decode( $slider_right_info );
									foreach ( $slider_right_info as $slide_item ) {
										$storebiz_slide_info_title = ! empty( $slide_item->title ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->title, 'slider Right  section' ) : '';
										$subtitle = ! empty( $slide_item->subtitle ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->subtitle, 'slider Right section' ) : '';
										$text = ! empty( $slide_item->text ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->text, 'slider Right section' ) : '';
										$button = ! empty( $slide_item->text2) ? apply_filters( 'storebiz_translate_single_string', $slide_item->text2,'slider Right section' ) : '';
										$storebiz_slide_info_link = ! empty( $slide_item->link ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->link, 'slider Right section' ) : '';
										$image = ! empty( $slide_item->image_url ) ? apply_filters( 'storebiz_translate_single_string', $slide_item->image_url, 'slider Right section' ) : '';
								?>
								<aside class="slider-info">
									<?php if ( ! empty( $image ) ) : ?>
										<img src="<?php echo esc_url( $image ); ?>" data-img-url="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $storebiz_slide_info_title ) ) : ?> alt="<?php echo esc_attr( $storebiz_slide_info_title ); ?>" title="<?php echo esc_attr( $storebiz_slide_info_title ); ?>" <?php endif; ?> />
									<?php endif; ?>
									<div class="slider-area">
										<div class="slider-info-content">
											<?php if(!empty( $storebiz_slide_info_title ) ) : ?>
												<div class="slider-title">
													<h4><?php echo esc_html($storebiz_slide_info_title); ?></h4>
												</div>
											<?php endif; ?>
											<div class="slider-info-bottom">
												<?php if(!empty( $subtitle )): ?>
													<h6><?php echo esc_html($subtitle); ?></h6>
												<?php endif; ?>
												<?php if(!empty( $text )): ?>
													<div class="slider-bottom-title">
														<h4><a href="<?php echo esc_url($storebiz_slide_info_link); ?>"><?php echo esc_html($storebiz_slide_info_title); ?></a></h4>
														<p><?php echo esc_html($text); ?></p>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</aside>
								<?php } } ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}
endif;
if ( function_exists( 'burger_storebiz_slider' ) ) {
$section_priority = apply_filters( 'stortebiz_section_priority', 11, 'burger_storebiz_slider' );
add_action( 'storebiz_sections', 'burger_storebiz_slider', absint( $section_priority ) );
}
