<?php
$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/setto/images/logo.png';
$activate = array(
        'setto-sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'setto-footer-widget-area' => array(
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
		<a href="javascript:void(0);"><img src="'.$footer_logo.'" alt="setto"></a>
	</div>
	<p>setto we talk destination we shine across your organization to fully understand.</p>
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
	$MediaId = get_option('setto_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );