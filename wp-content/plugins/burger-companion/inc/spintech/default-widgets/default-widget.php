<?php
$activate = array(
        'spintech-sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'spintech-footer-widget-area' => array(
			 'text-1',
            'categories-1',
            'archives-1',
			'search-1',
        )
    );
    /* the default titles will appear */
   update_option('widget_text', array(
        1 => array('title' => 'About Company',
        'text'=>'<div class="contact-area">
                                <div class="contact-icon">
                                    <div class="contact-corn"><i class="fa fa-phone"></i></div>
                                </div>
                                <div class="contact-info">
                                    <p class="text"><a href="javascript:void(0);">70 975 975 70</a></p>
                                </div>
                            </div>
                            <div class="contact-area">
                                <div class="contact-icon">
                                    <div class="contact-corn"><i class="fa fa-map-marker"></i></div>
                                </div>
                                <div class="contact-info">
                                    <p class="text"><a href="javascript:void(0);">2130 Fulton Street San Diego, CA 94117 - 1080 USA</a></p>
                                </div>
                            </div>
                            <div class="contact-area">
                                <div class="contact-icon">
                                    <div class="contact-corn"><i class="fa fa-envelope"></i></div>
                                </div>
                                <div class="contact-info">
                                    <p class="text"><a href="javascript:void(0);">Info@example.com</a></p>
                                </div>
                            </div>
                            <div class="contact-area">
                                <div class="contact-icon">
                                    <div class="contact-corn"><i class="fa fa-clock-o"></i></div>
                                </div>
                                <div class="contact-info">
                                    <p class="text"><a href="javascript:void(0);">Office Hours 8:00AM - 6:00PM</a></p>
                                </div>
                            </div>
		'),        
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
	$MediaId = get_option('spintech_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );