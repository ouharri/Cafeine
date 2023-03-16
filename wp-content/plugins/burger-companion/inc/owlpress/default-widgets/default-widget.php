<?php
$theme = wp_get_theme(); // gets the current theme
if( 'Crowl' == $theme->name){
	$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/crowl/images/logo_2.png';
}else{
	$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/owlpress/images/logo_2.png';
}
$activate = array(
        'owlpress-sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'owlpress-footer-widget-area' => array(
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
                                    <a href="javascript:void(0);"><img src="'.$footer_logo.'" alt="image"></a>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adiping elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida.</p>
                                <aside class="widget widget_social_widget">
                                    <ul>
                                        <li><a href="javascript:void(0);"><i class="fa fa-facebook-f"></i><i class="fa fa-facebook-f"></i></a></li>
                                        <li><a href="javascript:void(0);"><i class="fa fa-instagram"></i><i class="fa fa-instagram"></i></a></li>
                                        <li><a href="javascript:void(0);"><i class="fa fa-twitter"></i><i class="fa fa-twitter"></i></a></li>
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
	$MediaId = get_option('owlpress_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );