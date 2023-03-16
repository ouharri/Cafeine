<?php
$footer_logo = BURGER_COMPANION_PLUGIN_URL .'inc/decorme/images/logo.png';
$activate = array(
        'decorme-sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'decorme-footer-widget-area1' => array(
			 'text-1',
        ),
		'decorme-footer-widget-area2' => array(
			 'text-2',
        ),
		'decorme-footer-widget-area3' => array(
			 'text-3',
		)	 
    );
    /* the default titles will appear */
   update_option('widget_text', array(
        1 => array('title' => 'Quick Links',
        'text'=>'<ul id="menu-primar-menu" class="menu">
						<li class="menu-item"><a href="javascript:void(0);">Make Appointments</a></li>
						<li class="menu-item"><a href="javascript:void(0);">Before & After</a></li>
						<li class="menu-item"><a href="javascript:void(0);">Customer Treatments</a></li>
						<li class="menu-item"><a href="javascript:void(0);">Our Special Team</a></li>
						<li class="menu-item"><a href="javascript:void(0);">Departments Services</a></li>
						<li class="menu-item"><a href="javascript:void(0);">About our Firm</a></li>
						<li class="menu-item"><a href="javascript:void(0);">Contact Us</a></li>
					</ul>'),        
		2 => array(
        'text'=>'<aside class="widget widget_block">
                                <div class="textwidget">
                                    <div class="logo">
                                       <a href="javascript:void(0);"><img src="'.$footer_logo.'" alt=""></a>
                                    </div>
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout The point using Lorem Ipsum is that.</p>
                                    <div class="widget widget_mail">
                                        <form role="mail" method="get" class="mail-form" action="/">
                                            <label>
                                                <span class="screen-reader-text">Search for:</span>
                                                <input type="email" class="mail-field" placeholder="Subscribe Our Newsletter..." value="" name="e">
                                            </label>
                                            <button type="submit" class="submit">Subcribe</button>
                                        </form>
                                    </div>
                                    <p>Follow Us:</p>
                                </div>
                            </aside>
                            <aside class="widget widget_social">
                                <div class="circle"><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></div>
                                <div class="circle"><a href="javascript:void(0);"><i class="fa fa-twitter"></i></a></div>
                                <div class="circle"><a href="javascript:void(0);"><i class="fa fa-skype"></i></a></div>
                                <div class="circle"><a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a></div>
                                <div class="circle"><a href="javascript:void(0);"><i class="fa fa-instagram"></i></a></div>
                            </aside>'),
		3 => array(
        'text'=>'<aside class="widget widget_opening">
                                <h4 class="widget-title">Opening Hours</h4>
                                <div class="opening-hours">
                                    <dl class="st-grid-dl">
                                        <dt>Monday</dt>
                                        <dd>10:00 AM – 07:00 PM</dd>
                                        <dt>Tuesday</dt>
                                        <dd>10:00 AM – 07:00 PM</dd>
                                        <dt>Wednesday</dt>
                                        <dd>10:00 AM – 07:00 PM</dd>
                                        <dt>Thursday</dt>
                                        <dd>10:00 AM – 07:00 PM</dd>
                                        <dt>Friday</dt>
                                        <dd>10:00 AM – 07:00 PM</dd>
                                        <dt>Saturday</dt>
                                        <dd>10:00 AM – 07:00 PM</dd>
                                        <dt>Sunday</dt>
                                        <dd>10:00 AM – 07:00 PM</dd>
                                    </dl>
                                </div>
                            </aside>'),			
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
	$MediaId = get_option('decorme_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );