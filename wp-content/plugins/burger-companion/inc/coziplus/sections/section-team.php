<?php 
	if ( ! function_exists( 'burger_cozipress_team' ) ) :
	function burger_cozipress_team() {
	$hs_team					= get_theme_mod('hs_team','1');			
	$team_title					= get_theme_mod('team_title','What We Are');
	$team_subtitle				= get_theme_mod('team_subtitle','Our <span class="text-primary">Team</span>');
	$team_description			= get_theme_mod('team_description','This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.');
	$team_contents				= get_theme_mod('team_contents',cozipress_get_team_default());
	if($hs_team=='1'){
?>
	<section id="team-section" class="team-home team-section st-pb-default shapes-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 mx-lg-auto mb-5 text-center">
                    <div class="heading-default wow fadeInUp">
                        <?php if ( ! empty( $team_title ) ) : ?>
							 <span class="badge ttl"><?php echo wp_kses_post($team_title); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $team_subtitle ) ) : ?>		
							<h2><?php echo wp_kses_post($team_subtitle); ?></h2>   							
						<?php endif; ?>	
						<?php if ( ! empty( $team_description ) ) : ?>		
							<p><?php echo wp_kses_post($team_description); ?></p>    
						<?php endif; ?>	
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-4 row-cols-md-2 g-4 wow fadeInUp team-data">
				<?php
					$team_contents = json_decode($team_contents);
					if( $team_contents!='' )
					{
					foreach($team_contents as $team_item){
					$image    = ! empty( $team_item->image_url ) ? apply_filters( 'cozipresss_translate_single_string', $team_item->image_url, 'Team section' ) : '';
					$cozipresss_team_title    = ! empty( $team_item->title ) ? apply_filters( 'cozipresss_translate_single_string', $team_item->title, 'Team section' ) : '';
					$subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'cozipresss_translate_single_string', $team_item->subtitle, 'Team section' ) : '';					
				?>
                <div class="col">
                    <div class="our-team">
                        <div class="team-img">
                            <?php if ( ! empty( $image ) ): ?>
								<img src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $cozipresss_team_title ) ) : ?> alt="<?php echo esc_attr( $cozipresss_team_title ); ?>" title="<?php echo esc_attr( $cozipresss_team_title ); ?>" <?php endif; ?> />
							<?php endif; ?>	
                        </div>
                        <div class="team-thumb-img">
                            <?php if ( ! empty( $image ) ): ?>
								<img src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $cozipresss_team_title ) ) : ?> alt="<?php echo esc_attr( $cozipresss_team_title ); ?>" title="<?php echo esc_attr( $cozipresss_team_title ); ?>" <?php endif; ?> />
							<?php endif; ?>	
                        </div>
                        <div class="team-info">
                            <div class="team-heading">
								<?php if ( ! empty( $cozipresss_team_title ) ) : ?>
									<h4><a href="javascript:void(0);"><?php echo esc_html( $cozipresss_team_title ); ?></a></h4>
								<?php endif; ?>
								<?php if ( ! empty( $subtitle ) ) : ?>
									<p class="text-primary mb-0"><?php echo esc_html( $subtitle ); ?></p>
								<?php endif; ?>
                            </div>
                            <div class="team-widget-wrap mt-4">
                                <aside class="widget widget_social_widget">
                                    <ul>
                                        <?php if ( ! empty( $team_item->social_repeater ) ) :
											$icons         = html_entity_decode( $team_item->social_repeater );
											$icons_decoded = json_decode( $icons, true );
											if ( ! empty( $icons_decoded ) ) : ?>
											<?php
												foreach ( $icons_decoded as $value ) {
													$social_icon = ! empty( $value['icon'] ) ? apply_filters( 'cozipresss_translate_single_string', $value['icon'], 'Team section' ) : '';
													$social_link = ! empty( $value['link'] ) ? apply_filters( 'cozipresss_translate_single_string', $value['link'], 'Team section' ) : '';
													if ( ! empty( $social_icon ) ) {
											?>	
												<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
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
        <div class="lg-shape17"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape17.png" alt="image"></div>
        <div class="lg-shape18"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape18.png" alt="image"></div>
        <div class="lg-shape19"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape19.png" alt="image"></div>
        <div class="lg-shape20"><img src="<?php echo esc_url(BURGER_COMPANION_PLUGIN_URL); ?>/inc/cozipress/images/clipArt/shape20.png" alt="image"></div>
    </section>
	
	
	<?php	
	}}
endif;
if ( function_exists( 'burger_cozipress_team' ) ) {
$section_priority = apply_filters( 'cozipress_section_priority', 13, 'burger_cozipress_team' );
add_action( 'cozipress_sections', 'burger_cozipress_team', absint( $section_priority ) );
}