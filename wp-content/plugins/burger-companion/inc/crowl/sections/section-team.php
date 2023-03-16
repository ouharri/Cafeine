<?php  
if ( ! function_exists( 'burger_owlpress_team' ) ) :
	function burger_owlpress_team() {
	$hs_team 			= get_theme_mod('hs_team','1');
	$team_title 		= get_theme_mod('team_title','What We Are');
	$team_subtitle		= get_theme_mod('team_subtitle','Our <span class="text-primary">Team</span>'); 
	$team_description	= get_theme_mod('team_description','Lorem Ipsum. Proin Gravida Nibh Vel Velit Auctor Aliquet');
	$team_contents		= get_theme_mod('team_contents',owlpress_get_team_default());
	if($hs_team=='1'):
?>	
<section id="team-section" class="team-section team-home st-py-default shapes-section bg-primary-light">
	<div class="container">
		<?php if(!empty($team_title) || !empty($team_subtitle) || !empty($team_description)): ?>
			<div class="row">
				<div class="col-lg-6 col-12 mx-lg-auto text-center">
					<div class="heading-default wow fadeInUp">
						<?php if(!empty($team_title)): ?>
							<h6><?php echo wp_kses_post($team_title); ?></h6>
						<?php endif; ?>	
						
						<?php if(!empty($team_subtitle)): ?>
							<h4><?php echo wp_kses_post($team_subtitle); ?></h4>
							<?php do_action('owlpress_section_seprator'); ?>
						<?php endif; ?>	
						
						<?php if(!empty($team_description)): ?>
							<p><?php echo wp_kses_post($team_description); ?></p>
						<?php endif; ?>	
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row g-5 wow fadeIn teams-contents">
			<div class="col-lg-12 col-md-12 col-12">
				<div id="item01" class="item-row item-col-4">
					<?php
						$team_contents = json_decode($team_contents);
						if( $team_contents!='' )
						{
						foreach($team_contents as $team_item){
						$image    = ! empty( $team_item->image_url ) ? apply_filters( 'owlpress_translate_single_string', $team_item->image_url, 'Team section' ) : '';
						$title    = ! empty( $team_item->title ) ? apply_filters( 'owlpress_translate_single_string', $team_item->title, 'Team section' ) : '';
						$subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'owlpress_translate_single_string', $team_item->subtitle, 'Team section' ) : '';	
						$text = ! empty( $team_item->text ) ? apply_filters( 'owlpress_translate_single_string', $team_item->text, 'Team section' ) : '';						
					?>
					<div class="item">
						<div class="our-team">
							<?php if(!empty($image)): ?>
								<div class="team-img">
									<img src="<?php echo esc_url($image); ?>"  <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>"  <?php endif; ?>>
								</div>
							<?php endif; ?>
							<div class="team-info">
								<div class="team-heading">
									<?php if(!empty($title)): ?>
										<h5><a href="javascript:void(0);"><?php echo esc_html( $title ); ?></a></h5>
									<?php endif; ?>
									
									<?php if(!empty($subtitle)): ?>
										<span><?php echo esc_html( $subtitle ); ?></span>
									<?php endif; ?>	
								</div>
								<div class="team-content">
									<?php if(!empty($text)): ?>
										<p><?php echo esc_html( $text ); ?></p>
									<?php endif; ?>	
									<aside class="widget widget_social_widget">
										<ul>
											<?php if ( ! empty( $team_item->social_repeater ) ) :
											$icons         = html_entity_decode( $team_item->social_repeater );
											$icons_decoded = json_decode( $icons, true );
											if ( ! empty( $icons_decoded ) ) : ?>
												<?php
													foreach ( $icons_decoded as $value ) {
														$social_icon = ! empty( $value['icon'] ) ? apply_filters( 'owlpress_translate_single_string', $value['icon'], 'Team section' ) : '';
														$social_link = ! empty( $value['link'] ) ? apply_filters( 'owlpress_translate_single_string', $value['link'], 'Team section' ) : '';
														if ( ! empty( $social_icon ) ) {
												?>	
													<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
												<?php
														}
													}
												endif;
											endif;
											?>	
										</ul>
									</aside>
								</div>
							</div>
						</div>
					</div>
					<?php }} ?>
				</div>
			</div>
		</div>
	</div>
	<div class="lg-shape17 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/team/shape17.png" alt="image"></div>
	<div class="lg-shape18 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/team/shape18.png" alt="image"></div>
	<div class="lg-shape19 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/team/shape19.png" alt="image"></div>
	<div class="lg-shape20 cliparts"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL)?>inc/owlpress/images/clipArt/team/shape20.png" alt="image"></div>
</section>
<?php
endif;
}
endif;
if ( function_exists( 'burger_owlpress_team' ) ) {
$section_priority = apply_filters( 'owlpress_section_priority', 13, 'burger_owlpress_team' );
add_action( 'owlpress_sections', 'burger_owlpress_team', absint( $section_priority ) );
}