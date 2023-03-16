<?php  
if ( ! function_exists( 'burger_seokart_team' ) ) :
	function burger_seokart_team() {
	$hs_team 			= get_theme_mod('hs_team','1');
	$team_title 		= get_theme_mod('team_title','Our Team');
	$team_subtitle		= get_theme_mod('team_subtitle','Our Awesome Team <i>Members</i>'); 
	$team_description	= get_theme_mod('team_description','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed eiusm tempor incididunt ut labore et dolore magna aliqua.');
	$team_contents		= get_theme_mod('team_contents',seokart_get_team_default());
	if($hs_team=='1'){
?>	 
<section id="team-section" class="team-area team-home">
	<div class="container">
		<?php if(!empty($team_title) || !empty($team_subtitle) || !empty($team_description)): ?>
			<div class="title">
				<?php if(!empty($team_title)): ?>
					<h6><?php echo wp_kses_post($team_title); ?></h6>
				<?php endif; ?>
				
				<?php if(!empty($team_subtitle)): ?>
					<h2><?php echo wp_kses_post($team_subtitle); ?></h2>
					<span class="shap"></span>
				<?php endif; ?>
				
				<?php if(!empty($team_description)): ?>
					<p><?php echo wp_kses_post($team_description); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?> 
		<div class="row hm-team-content">
			<?php
				if ( ! empty( $team_contents ) ) {
				$team_contents = json_decode( $team_contents );
				foreach ( $team_contents as $team_item ) {
					$title = ! empty( $team_item->title ) ? apply_filters( 'seokart_translate_single_string', $team_item->title, 'Team section' ) : '';
					$subtitle = ! empty( $team_item->subtitle ) ? apply_filters( 'seokart_translate_single_string', $team_item->subtitle, 'Team section' ) : '';
					$image = ! empty( $team_item->image_url ) ? apply_filters( 'seokart_translate_single_string', $team_item->image_url, 'Team section' ) : '';
			?>
				<div class="col-lg-4 col-md-6">
					<div class="team-item media">
						<?php if ( ! empty( $image ) ) : ?>	
							<div class="icon"><img src="<?php echo esc_url($image); ?>" /></div>
						<?php endif; ?>	
						<div class="media-body">
							<?php if ( ! empty( $title ) ) : ?>	
								<a href="javascript:void(0);"><?php echo esc_html($title); ?></a>
							<?php endif; ?>	
							
							<?php if ( ! empty( $subtitle ) ) : ?>	
								<h6><?php echo esc_html($subtitle); ?></h6>
							<?php endif; ?>	
						</div>
					</div>
				</div>
			<?php } } ?>
		</div>
	</div>
</section>
<?php	
	}}
endif;
if ( function_exists( 'burger_seokart_team' ) ) {
$section_priority = apply_filters( 'seokart_section_priority', 13, 'burger_seokart_team' );
add_action( 'seokart_sections', 'burger_seokart_team', absint( $section_priority ) );
}	