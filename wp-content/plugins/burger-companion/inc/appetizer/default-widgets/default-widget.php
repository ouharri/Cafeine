<?php
$theme = wp_get_theme(); // gets the current theme
if( 'Rasam' == $theme->name){
	$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/rasam/images/logo.png';
}else{
	$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/appetizer/images/logo.png';
}
$activate = array(
        'appetizer-sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'appetizer-footer-widget-area' => array(
			 'text-1',
            'categories-1',
            'archives-1',
			'search-1',
        )
    );
    /* the default titles will appear */
   update_option('widget_text', array(
        1 => array('title' => 'About Company',
        'text'=>'<div class="textwidget">
					<div class="logo">
						<img src="'.$footer_logo.'" alt="">
					</div>
					<p>A reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal w use.</p>
					<aside class="widget widget_social_widget">
						<ul>
							<li><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-twitter"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-instagram"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-google-plus"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-pinterest"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-dribbble"></i></a></li>
						</ul>
					</aside>
				</div>'),        
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
	$MediaId = get_option('appetizer_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );