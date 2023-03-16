<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Pizza Lite
 */

	$fb_link = get_theme_mod('fb_link'); 
	$twitt_link = get_theme_mod('twitt_link');
	$insta_link = get_theme_mod('insta_link');
	$linked_link = get_theme_mod('linked_link');
?>  
<div id="footer-wrapper">
		<div class="footerarea">
    	<div class="container footer">
        	<div class="rowfooter">
               <div class="footercols1"><h4><?php bloginfo('name'); ?></h4></div>
               <div class="clear"></div>
				<div class="footer-social">
                	<div class="social-icons">
						<?php 
                        if (!empty($fb_link)) { ?>
                        <a title="<?php esc_attr__('facebook','pizza-lite'); ?>" class="fb" target="_blank" href="<?php echo esc_url($fb_link); ?>"></a> 
                        <?php } ?>       
                        <?php
                        if (!empty($twitt_link)) { ?>
                        <a title="<?php esc_attr__('twitter','pizza-lite'); ?>" class="tw" target="_blank" href="<?php echo esc_url($twitt_link); ?>"></a>
                        <?php } ?>     
                        <?php
                        if (!empty($insta_link)) { ?>
                        <a title="<?php esc_attr__('instagram','pizza-lite'); ?>" class="gp" target="_blank" href="<?php echo esc_url($insta_link); ?>"></a>
                        <?php } ?>        
                        <?php
                         if (!empty($linked_link)) { ?> 
                        <a title="<?php esc_attr__('linkedin','pizza-lite'); ?>" class="in" target="_blank" href="<?php echo esc_url($linked_link); ?>"></a>
                        <?php } ?>                   
                      </div>
                </div>
            </div>
        </div>
        </div>
         <div class="copyright-wrapper">
        	<div class="container">
            	 <div class="design-by"><?php echo esc_html('SKT Pizza Lite'); ?></div>
                 <div class="clear"></div>
            </div>           
        </div>
    </div><!--end .footer-wrapper-->
<?php wp_footer(); ?>
</body>
</html>