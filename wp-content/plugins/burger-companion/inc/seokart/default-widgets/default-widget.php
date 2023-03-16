<?php
$theme = wp_get_theme(); // gets the current theme
if( 'DigiPress' == $theme->name){
$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/digipress/images/footer-logo.png';
}else{
$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/seokart/images/footer-logo.png';	
}
$activate = array(
        'seokart-sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'seokart-footer-widget-area' => array(
			 'text-1',
            'categories-1',
            'archives-1',
			'search-1',
        )
    );
    /* the default titles will appear */
   update_option('widget_text', array(
        1 => array('title' => 'About Company',
        'text'=>'<div class="textwidget footer-logo">
<a href="index.html"><img src="'.$footer_logo.'" alt=""></a>
<p>Get In Touch With Highly Skilled <br> Digital Marketing Team.</p>
<aside class="widget widget_social_widget order-md-last">
<ul>
<li><a href="#"><i class="fa fa-facebook"></i><i class="fa fa-facebook"></i></a></li>
<li><a href="#"><i class="fa fa-twitter"></i><i class="fa fa-twitter"></i></a></li>
<li><a href="#"><i class="fa fa-instagram"></i><i class="fa fa-instagram"></i></a></li>
<li><a href="#"><i class="fa fa-pinterest"></i><i class="fa fa-pinterest"></i></a></li>
<li><a href="#"><i class="fa fa-linkedin"></i><i class="fa fa-linkedin"></i></a></li>
</ul>
</aside></div>'),        
		2 => array('title' => 'Recent Posts'),
		3 => array('title' => 'Categories'), 
        ));
		 update_option('widget_categories', array(
			1 => array('title' => 'Categories'), 
			2 => array('title' => 'Categories')));

		update_option('widget_archives', array(
			1 => array('title' => 'Archives'), 
			2 => array('title' => 'Archives')));
			
		update_option('widget_search', array(
			1 => array('title' => 'Search'), 
			2 => array('title' => 'Search')));	
		
    update_option('sidebars_widgets',  $activate);
	$MediaId = get_option('seokart_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );