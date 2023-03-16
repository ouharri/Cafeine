<?php
/**
 * The About Page for SKT Templates.
 *
 * @link       https://www.sktthemes.org
 * @since      1.0.0
 *
 * @package    Skt_Templates
 * @subpackage Skt_Templates/app/views
 * @codeCoverageIgnore
 */
?>
<div class="sktb-wrapper sktb-header">
	<div class="sktb-header-content">
		<img src="<?php echo esc_url( SKTB_URL ); ?>/images/logo.png" title="<?php echo esc_html( 'SKT Templates'); ?>" class="sktb-logo"/>
		<h1><?php esc_attr_e( 'SKT Templates', 'skt-templates' ); ?></h1><span class="powered"> <?php echo esc_html( 'by'); ?> <a
					href="<?php echo esc_url('https://www.sktthemes.org/');?>" target="_blank"><b><?php echo esc_html( 'SKT Themes'); ?></b></a></span>
	</div>
</div>
<div class="sktb-full-page-container">
	<div class="sktb-wrapper" id="sktb-modules-wrapper">
    <h2><center><?php esc_attr_e( 'How to use SKT Templates?' ,'skt-templates') ?></center></h2>
    <p><center><iframe width="50%" height="460" src="https://www.youtube.com/embed/2QVEhff55d4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></center></p>
    <p><?php esc_attr_e( 'Ready to import WordPress websites all made up using Elementor page builder. Be ready to import those Elementor templates into your existing website or fresh installs. Use the template blocks with your favourite themes whereas if you want to take the full juice out of those templates try SKT free themes. SKT Themes houses more than 300+ exciting designs and have been doling out updates to existing themes as well as fresh designs each year from 2013 and has a customer base from all over the world.', 'skt-templates' ); ?></p>
	<p><?php esc_attr_e( 'Check out our premium themes.', 'skt-templates' ); ?></p>	
    <p class="sktb-banner-center"><a href="<?php echo esc_url('https://www.sktthemes.org/themes/');?>" target="_blank"><img src="<?php echo esc_url( SKTB_URL ); ?>/images/skt-template-banner.jpg" title="<?php echo esc_html( 'SKT Themes'); ?>"/></a></p>
	</div>
</div>