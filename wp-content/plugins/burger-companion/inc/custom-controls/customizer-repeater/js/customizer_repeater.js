/* global jQuery */
/* global wp */
function burger_companion_media_upload(button_class) {
    'use strict';
    jQuery('body').on('click', button_class, function () {
        var button_id = '#' + jQuery(this).attr('id');
        var display_field = jQuery(this).parent().children('input:text');
        var _custom_media = true;

        wp.media.editor.send.attachment = function (props, attachment) {

            if (_custom_media) {
                if (typeof display_field !== 'undefined') {
                    switch (props.size) {
                        case 'full':
                            display_field.val(attachment.sizes.full.url);
                            display_field.trigger('change');
                            break;
                        case 'medium':
                            display_field.val(attachment.sizes.medium.url);
                            display_field.trigger('change');
                            break;
                        case 'thumbnail':
                            display_field.val(attachment.sizes.thumbnail.url);
                            display_field.trigger('change');
                            break;
                        default:
                            display_field.val(attachment.url);
                            display_field.trigger('change');
                    }
                }
                _custom_media = false;
            } else {
                return wp.media.editor.send.attachment(button_id, [props, attachment]);
            }
        };
        wp.media.editor.open(button_class);
        window.send_to_editor = function (html) {

        };
        return false;
    });
}

/********************************************
 *** Generate unique id ***
 *********************************************/
function burger_companion_customizer_repeater_uniqid(prefix, more_entropy) {
    'use strict';
    if (typeof prefix === 'undefined') {
        prefix = '';
    }

    var retId;
    var php_js;
    var formatSeed = function (seed, reqWidth) {
        seed = parseInt(seed, 10)
            .toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) { // so short we pad
            return new Array(1 + (reqWidth - seed.length))
                .join('0') + seed;
        }
        return seed;
    };

    // BEGIN REDUNDANT
    if (!php_js) {
        php_js = {};
    }
    // END REDUNDANT
    if (!php_js.uniqidSeed) { // init seed with big random int
        php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    php_js.uniqidSeed++;

    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date()
        .getTime() / 1000, 10), 8);
    retId += formatSeed(php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10)
            .toFixed(8)
            .toString();
    }

    return retId;
}


/********************************************
 *** General Repeater ***
 *********************************************/
function burger_companion_customizer_repeater_refresh_social_icons(th) {
    'use strict';
    var icons_repeater_values = [];
    th.find('.customizer-repeater-social-repeater-container').each(function () {
        var icon = jQuery(this).find('.icp').val();
        var link = jQuery(this).find('.customizer-repeater-social-repeater-link').val();
        var id = jQuery(this).find('.customizer-repeater-social-repeater-id').val();

        if (!id) {
            id = 'customizer-repeater-social-repeater-' + burger_companion_customizer_repeater_uniqid();
            jQuery(this).find('.customizer-repeater-social-repeater-id').val(id);
        }

        if (icon !== '' && link !== '') {
            icons_repeater_values.push({
                'icon': icon,
                'link': link,
                'id': id
            });
        }
    });

    th.find('.social-repeater-socials-repeater-colector').val(JSON.stringify(icons_repeater_values));
    burger_companion_customizer_repeater_refresh_general_control_values();
}


function burger_companion_customizer_repeater_refresh_general_control_values() {
    'use strict';
    jQuery('.customizer-repeater-general-control-repeater').each(function () {
        var values = [];
        var th = jQuery(this);
        th.find('.customizer-repeater-general-control-repeater-container').each(function () {

            var icon_value = jQuery(this).find('.icp').val();
            var text = jQuery(this).find('.customizer-repeater-text-control').val();
            var link = jQuery(this).find('.customizer-repeater-link-control').val();
            var text2 = jQuery(this).find('.customizer-repeater-text2-control').val();
            var link2 = jQuery(this).find('.customizer-repeater-link2-control').val();
            var color = jQuery(this).find('input.customizer-repeater-color-control').val();
            var color2 = jQuery(this).find('input.customizer-repeater-color2-control').val();
            var image_url = jQuery(this).find('.custom-media-url').val();
            var choice = jQuery(this).find('.customizer-repeater-image-choice').val();
            var title = jQuery(this).find('.customizer-repeater-title-control').val();
            var subtitle = jQuery(this).find('.customizer-repeater-subtitle-control').val();
			 var designation = jQuery(this).find('.customizer-repeater-designation-control').val();
			var slide_align = jQuery(this).find('.customizer-repeater-slide-align').val();
            var id = jQuery(this).find('.social-repeater-box-id').val();
            if (!id) {
                id = 'social-repeater-' + burger_companion_customizer_repeater_uniqid();
                jQuery(this).find('.social-repeater-box-id').val(id);
            }
            var social_repeater = jQuery(this).find('.social-repeater-socials-repeater-colector').val();
            var shortcode = jQuery(this).find('.customizer-repeater-shortcode-control').val();

            if (text !== '' || image_url !== '' || title !== '' || subtitle !== '' || icon_value !== '' || link !== '' || choice !== '' || social_repeater !== '' || shortcode !== '' || slide_align !== '' || color !== '') {
                values.push({
                    'icon_value': (choice === 'customizer_repeater_none' ? '' : icon_value),
                    'color': color,
                    'color2': color2,
                    'text': burger_companionescapeHtml(text),
                    'link': link,
                    'text2': burger_companionescapeHtml(text2),
                    'link2': link2,
                    'image_url': (choice === 'customizer_repeater_none' ? '' : image_url),
                    'choice': choice,
                    'title': burger_companionescapeHtml(title),
                    'subtitle': burger_companionescapeHtml(subtitle),
					'designation': burger_companionescapeHtml(designation),
					'slide_align': burger_companionescapeHtml(slide_align),
                    'social_repeater': burger_companionescapeHtml(social_repeater),
                    'id': id,
                    'shortcode': burger_companionescapeHtml(shortcode)
                });
            }

        });
        th.find('.customizer-repeater-colector').val(JSON.stringify(values));
        th.find('.customizer-repeater-colector').trigger('change');
    });
}


jQuery(document).ready(function () {
    'use strict';
    var burger_companion_theme_controls = jQuery('#customize-theme-controls');
    burger_companion_theme_controls.on('click', '.customizer-repeater-customize-control-title', function () {
        jQuery(this).next().slideToggle('medium', function () {
            if (jQuery(this).is(':visible')){
                jQuery(this).prev().addClass('repeater-expanded');
                jQuery(this).css('display', 'block');
            } else {
                jQuery(this).prev().removeClass('repeater-expanded');
            }
        });
    });

    burger_companion_theme_controls.on('change', '.icp',function(){
        burger_companion_customizer_repeater_refresh_general_control_values();
        return false;
    });
	
	burger_companion_theme_controls.on('change','.customizer-repeater-slide-align', function(){
		burger_companion_customizer_repeater_refresh_general_control_values();
        return false;
		
	});
	
    burger_companion_theme_controls.on('change', '.customizer-repeater-image-choice', function () {
        if (jQuery(this).val() === 'customizer_repeater_image') {
            jQuery(this).parent().parent().find('.social-repeater-general-control-icon').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-image-control').show();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').prev().prev().hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').hide();

        }
        if (jQuery(this).val() === 'customizer_repeater_icon') {
            jQuery(this).parent().parent().find('.social-repeater-general-control-icon').show();
            jQuery(this).parent().parent().find('.customizer-repeater-image-control').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').prev().prev().show();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').show();
        }
        if (jQuery(this).val() === 'customizer_repeater_none') {
            jQuery(this).parent().parent().find('.social-repeater-general-control-icon').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-image-control').hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').prev().prev().hide();
            jQuery(this).parent().parent().find('.customizer-repeater-color-control').hide();
        }

        burger_companion_customizer_repeater_refresh_general_control_values();
        return false;
    });
    burger_companion_media_upload('.customizer-repeater-custom-media-button');
    jQuery('.custom-media-url').on('change', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
        return false;
    });

    var color_options = {
        change: function(event, ui){
            burger_companion_customizer_repeater_refresh_general_control_values();
        }
    };

    /**
     * This adds a new box to repeater
     *
     */
    burger_companion_theme_controls.on('click', '.customizer-repeater-new-field', function () {
        var split_add_more_button=jQuery(this).text();
        var split_add_more_button_split=split_add_more_button.substr(4, 12);
        var th = jQuery(this).parent();
        var id = 'customizer-repeater-' + burger_companion_customizer_repeater_uniqid();
		
		if(split_add_more_button_split=="Add New Slid")
        {
        if(jQuery('#exist_burger_companion_Spintech_Slider,#exist_burger_companion_ITpress_Slider,#exist_burger_companion_Burgertech_Slider,#exist_burger_companion_KitePress_Slider,#exist_burger_companion_SpinSoft_Slider').val()>=3)
        {
        jQuery(".customizer_Spintech_slider_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_Spintech_Slider,#exist_burger_companion_ITpress_Slider,#exist_burger_companion_Burgertech_Slider,#exist_burger_companion_KitePress_Slider,#exist_burger_companion_SpinSoft_Slider').val()<3)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Spintech_Slider,#exist_burger_companion_ITpress_Slider,#exist_burger_companion_Burgertech_Slider,#exist_burger_companion_KitePress_Slider,#exist_burger_companion_SpinSoft_Slider').val())+1;
         jQuery('#exist_burger_companion_Spintech_Slider,#exist_burger_companion_ITpress_Slider,#exist_burger_companion_Burgertech_Slider,#exist_burger_companion_KitePress_Slider,#exist_burger_companion_SpinSoft_Slider').val(new_service_add_val);  
        }
        }
		
		 if(split_add_more_button_split=="Add New Serv")
        {
        if(jQuery('#exist_burger_companion_Spintech_Service,#exist_burger_companion_ITpress_Service,#exist_burger_companion_Burgertech_Service,#exist_burger_companion_KitePress_Service,#exist_burger_companion_SpinSoft_Service').val()>=3)
        {
        jQuery(".customizer_spintech_service_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_Spintech_Service,#exist_burger_companion_ITpress_Service,#exist_burger_companion_Burgertech_Service,#exist_burger_companion_KitePress_Service,#exist_burger_companion_SpinSoft_Service').val()<3)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Spintech_Service,#exist_burger_companion_ITpress_Service,#exist_burger_companion_Burgertech_Service,#exist_burger_companion_KitePress_Service,#exist_burger_companion_SpinSoft_Service').val())+1;
         jQuery('#exist_burger_companion_Spintech_Service,#exist_burger_companion_ITpress_Service,#exist_burger_companion_Burgertech_Service,#exist_burger_companion_KitePress_Service,#exist_burger_companion_SpinSoft_Service').val(new_service_add_val);  
        }
        }
         if(split_add_more_button_split=="Add New Info")
        {
        if(jQuery('#exist_burger_companion_Spintech_Information,#exist_burger_companion_ITpress_Information,#exist_burger_companion_KitePress_Information,#exist_burger_companion_SpinSoft_Information').val()>=4)
        {
        jQuery(".customizer_spintech_info_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_Spintech_Information,#exist_burger_companion_ITpress_Information,#exist_burger_companion_KitePress_Information,#exist_burger_companion_SpinSoft_Information').val()<4)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Spintech_Information,#exist_burger_companion_ITpress_Information,#exist_burger_companion_KitePress_Information,#exist_burger_companion_SpinSoft_Information').val())+1;
         jQuery('#exist_burger_companion_Spintech_Information,#exist_burger_companion_ITpress_Information,#exist_burger_companion_KitePress_Information,#exist_burger_companion_SpinSoft_Information').val(new_service_add_val);  
        }
        }
		
		if(split_add_more_button_split=="Add New Funf")
        {
        if(jQuery('#exist_burger_companion_SpinSoft_Funfact').val()>=4)
        {
        jQuery(".customizer_spintech_funfact_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_SpinSoft_Funfact').val()<4)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpinSoft_Funfact').val())+1;
         jQuery('#exist_burger_companion_SpinSoft_Funfact').val(new_service_add_val);  
        }
        }
		
		if(split_add_more_button_split=="Add New Test")
        {
        if(jQuery('#exist_burger_companion_SpinSoft_Testimonial').val()>=3)
        {
        jQuery(".customizer_spintech_testimonial_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_SpinSoft_Testimonial').val()<3)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpinSoft_Testimonial').val())+1;
         jQuery('#exist_burger_companion_SpinSoft_Testimonial').val(new_service_add_val);  
        }
        }
		
		
		if(split_add_more_button_split=="Add New Soci")
        {
        if(jQuery('#exist_burger_companion_Spintech_Social,#exist_burger_companion_ITpress_Social,#exist_burger_companion_Burgertech_Social,#exist_burger_companion_KitePress_Social,#exist_burger_companion_SpinSoft_Social').val()>=3)
        {
        jQuery(".customizer_spintech_social_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_Spintech_Social,#exist_burger_companion_ITpress_Social,#exist_burger_companion_Burgertech_Social,#exist_burger_companion_KitePress_Social,#exist_burger_companion_SpinSoft_Social').val()<3)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Spintech_Social,#exist_burger_companion_ITpress_Social,#exist_burger_companion_Burgertech_Social,#exist_burger_companion_KitePress_Social,#exist_burger_companion_SpinSoft_Social').val())+1;
         jQuery('#exist_burger_companion_Spintech_Social,#exist_burger_companion_ITpress_Social,#exist_burger_companion_Burgertech_Social,#exist_burger_companion_KitePress_Social,#exist_burger_companion_SpinSoft_Social').val(new_service_add_val);  
        }
        }
		
		if(split_add_more_button_split=="Add New Desi")
        {
        if(jQuery('#exist_burger_companion_Spintech_Design,#exist_burger_companion_ITpress_Design,#exist_burger_companion_Burgertech_Design,#exist_burger_companion_KitePress_Design').val()>=8)
        {
        jQuery(".customizer_spintech_design_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_Spintech_Design,#exist_burger_companion_ITpress_Design,#exist_burger_companion_Burgertech_Design,#exist_burger_companion_KitePress_Design').val()<8)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Spintech_Design,#exist_burger_companion_ITpress_Design,#exist_burger_companion_Burgertech_Design,#exist_burger_companion_KitePress_Design').val())+1;
         jQuery('#exist_burger_companion_Spintech_Design,#exist_burger_companion_ITpress_Design,#exist_burger_companion_Burgertech_Design,#exist_burger_companion_KitePress_Design').val(new_service_add_val);  
        }
        }
		
		//Cozipress
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_CoziPress_Slider,#exist_burger_companion_Sipri_Slider,#exist_burger_companion_Anexa_Slider,#exist_burger_companion_CoziWeb_Slider,#exist_burger_companion_CoziPlus_Slider,#exist_burger_companion_CoziBee_Slider').val()>=3)
				{
					jQuery(".customizer_CoziPress_slider_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_CoziPress_Slider,#exist_burger_companion_Sipri_Slider,#exist_burger_companion_Anexa_Slider,#exist_burger_companion_CoziWeb_Slider,#exist_burger_companion_CoziPlus_Slider,#exist_burger_companion_CoziBee_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziPress_Slider,#exist_burger_companion_Sipri_Slider,#exist_burger_companion_Anexa_Slider,#exist_burger_companion_CoziWeb_Slider,#exist_burger_companion_CoziPlus_Slider,#exist_burger_companion_CoziBee_Slider').val())+1;
					jQuery('#exist_burger_companion_CoziPress_Slider,#exist_burger_companion_Sipri_Slider,#exist_burger_companion_Anexa_Slider,#exist_burger_companion_CoziWeb_Slider,#exist_burger_companion_CoziPlus_Slider,#exist_burger_companion_CoziBee_Slider').val(new_service_add_val);  
				}
        }
		
		 if(split_add_more_button_split=="Add New Info")
        {
			if(jQuery('#exist_burger_companion_CoziPress_Information,#exist_burger_companion_CoziWeb_Information').val()>=4)
				{
					jQuery(".customizer_CoziPress_info_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_CoziPress_Information,#exist_burger_companion_CoziWeb_Information').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziPress_Information,#exist_burger_companion_CoziWeb_Information').val())+1;
					jQuery('#exist_burger_companion_CoziPress_Information,#exist_burger_companion_CoziWeb_Information').val(new_service_add_val);  
				}
        }
		
		 if(split_add_more_button_split=="Add New Serv")
        {
			if(jQuery('#exist_burger_companion_CoziPress_Service,#exist_burger_companion_Sipri_Service,#exist_burger_companion_Anexa_Service,#exist_burger_companion_CoziWeb_Service,#exist_burger_companion_CoziPlus_Service,#exist_burger_companion_CoziBee_Service').val()>=3)
				{
					jQuery(".customizer_CoziPress_service_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_CoziPress_Service,#exist_burger_companion_Sipri_Service,#exist_burger_companion_Anexa_Service,#exist_burger_companion_CoziWeb_Service,#exist_burger_companion_CoziPlus_Service,#exist_burger_companion_CoziBee_Service').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziPress_Service,#exist_burger_companion_Sipri_Service,#exist_burger_companion_Anexa_Service,#exist_burger_companion_CoziWeb_Service,#exist_burger_companion_CoziPlus_Service,#exist_burger_companion_CoziBee_Service').val())+1;
					jQuery('#exist_burger_companion_CoziPress_Service,#exist_burger_companion_Sipri_Service,#exist_burger_companion_Anexa_Service,#exist_burger_companion_CoziWeb_Service,#exist_burger_companion_CoziPlus_Service,#exist_burger_companion_CoziBee_Service').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Test")
        {
			if(jQuery('#exist_burger_companion_CoziPress_Testimonial,#exist_burger_companion_Sipri_Testimonial,#exist_burger_companion_Anexa_Testimonial,#exist_burger_companion_CoziWeb_Testimonial,#exist_burger_companion_CoziPlus_Testimonial,#exist_burger_companion_CoziBee_Testimonial').val()>=3)
				{
					jQuery(".customizer_CoziPress_testimonial_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_CoziPress_Testimonial,#exist_burger_companion_Sipri_Testimonial,#exist_burger_companion_Anexa_Testimonial,#exist_burger_companion_CoziWeb_Testimonial,#exist_burger_companion_CoziPlus_Testimonial,#exist_burger_companion_CoziBee_Testimonial').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziPress_Testimonial,#exist_burger_companion_Sipri_Testimonial,#exist_burger_companion_Anexa_Testimonial,#exist_burger_companion_CoziWeb_Testimonial,#exist_burger_companion_CoziPlus_Testimonial,#exist_burger_companion_CoziBee_Testimonial').val())+1;
					jQuery('#exist_burger_companion_CoziPress_Testimonial,#exist_burger_companion_Sipri_Testimonial,#exist_burger_companion_Anexa_Testimonial,#exist_burger_companion_CoziWeb_Testimonial,#exist_burger_companion_CoziPlus_Testimonial,#exist_burger_companion_CoziBee_Testimonial').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Funf")
        {
			if(jQuery('#exist_burger_companion_CoziBee_Funfact').val()>=4)
				{
					jQuery(".customizer_CoziPress_funfact_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_CoziBee_Funfact').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziBee_Funfact').val())+1;
					jQuery('#exist_burger_companion_CoziBee_Funfact').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_CoziPress_Social,#exist_burger_companion_Sipri_Social,#exist_burger_companion_Anexa_Social,#exist_burger_companion_CoziWeb_Social,#exist_burger_companion_CoziPlus_Social,#exist_burger_companion_CoziBee_Social').val()>=3)
				{
					jQuery(".customizer_CoziPress_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_CoziPress_Social,#exist_burger_companion_Sipri_Social,#exist_burger_companion_Anexa_Social,#exist_burger_companion_CoziWeb_Social,#exist_burger_companion_CoziPlus_Social,#exist_burger_companion_CoziBee_Social').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziPress_Social,#exist_burger_companion_Sipri_Social,#exist_burger_companion_Anexa_Social,#exist_burger_companion_CoziWeb_Social,#exist_burger_companion_CoziPlus_Social,#exist_burger_companion_CoziBee_Social').val())+1;
					jQuery('#exist_burger_companion_CoziPress_Social,#exist_burger_companion_Sipri_Social,#exist_burger_companion_Anexa_Social,#exist_burger_companion_CoziWeb_Social,#exist_burger_companion_CoziPlus_Social,#exist_burger_companion_CoziBee_Social').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Desi")
        {
			if(jQuery('#exist_burger_companion_CoziWeb_Design,#exist_burger_companion_CoziPlus_Design').val()>=4)
				{
					jQuery(".customizer_CoziPress_design_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_CoziWeb_Design,#exist_burger_companion_CoziPlus_Design').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziWeb_Design,#exist_burger_companion_CoziPlus_Design').val())+1;
					jQuery('#exist_burger_companion_CoziWeb_Design,#exist_burger_companion_CoziPlus_Design').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Team")
        {
			if(jQuery('#exist_burger_companion_CoziPlus_Teams').val()>=4)
				{
					jQuery(".customizer_CoziPress_team_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_CoziPlus_Teams').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_CoziPlus_Teams').val())+1;
					jQuery('#exist_burger_companion_CoziPlus_Teams').val(new_service_add_val);  
				}
        }
		
		
		//Storebiz
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_StoreBiz_Slider,#exist_burger_companion_ShopMax_Slider,#exist_burger_companion_StoreWise_Slider').val()>=3)
				{
					jQuery(".customizer_StoreBiz_slider_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_StoreBiz_Slider,#exist_burger_companion_ShopMax_Slider,#exist_burger_companion_StoreWise_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_StoreBiz_Slider,#exist_burger_companion_ShopMax_Slider,#exist_burger_companion_StoreWise_Slider').val())+1;
					jQuery('#exist_burger_companion_StoreBiz_Slider,#exist_burger_companion_ShopMax_Slider,#exist_burger_companion_StoreWise_Slider').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Test")
        {
			if(jQuery('#exist_burger_companion_StoreBiz_Testimonial,#exist_burger_companion_ShopMax_Testimonial,#exist_burger_companion_StoreWise_Testimonial').val()>=3)
				{
					jQuery(".customizer_StoreBiz_testimonial_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_StoreBiz_Testimonial,#exist_burger_companion_ShopMax_Testimonial,#exist_burger_companion_StoreWise_Testimonial').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_StoreBiz_Testimonial,#exist_burger_companion_ShopMax_Testimonial,#exist_burger_companion_StoreWise_Testimonial').val())+1;
					jQuery('#exist_burger_companion_StoreBiz_Testimonial,#exist_burger_companion_ShopMax_Testimonial,#exist_burger_companion_StoreWise_Testimonial').val(new_service_add_val);  
				}
        }
		
		 if(split_add_more_button_split=="Add New Info")
        {
        if(jQuery('#exist_burger_companion_StoreBiz_Information,#exist_burger_companion_ShopMax_Information').val()>=2)
        {
        jQuery(".customizer_storebiz_info_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_StoreBiz_Information,#exist_burger_companion_ShopMax_Information').val()<2)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_StoreBiz_Information,#exist_burger_companion_ShopMax_Information').val())+1;
         jQuery('#exist_burger_companion_StoreBiz_Information,#exist_burger_companion_ShopMax_Information').val(new_service_add_val);  
        }
        }
		
		 if(split_add_more_button_split=="Add New Offe")
        {
        if(jQuery('#exist_burger_companion_StoreBiz_Offer,#exist_burger_companion_ShopMax_Offer,#exist_burger_companion_StoreWise_Offer').val()>=1)
        {
        jQuery(".customizer_storebiz_offer_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_StoreBiz_Offer,#exist_burger_companion_ShopMax_Offer,#exist_burger_companion_StoreWise_Offer').val()<1)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_StoreBiz_Offer,#exist_burger_companion_ShopMax_Offer,#exist_burger_companion_StoreWise_Offer').val())+1;
         jQuery('#exist_burger_companion_StoreBiz_Offer,#exist_burger_companion_ShopMax_Offer,#exist_burger_companion_StoreWise_Offer').val(new_service_add_val);  
        }
        }
		
		if(split_add_more_button_split=="Add New Info")
        {
        if(jQuery('#exist_burger_companion_ShopMax_Informations,#exist_burger_companion_StoreWise_Informations').val()>=3)
        {
        jQuery(".customizer_storebiz_infos_upgrade_section").show();
        return false;   
        }
         if(jQuery('#exist_burger_companion_ShopMax_Informations,#exist_burger_companion_StoreWise_Informations').val()<3)
        {
         var new_service_add_val=parseInt(jQuery('#exist_burger_companion_ShopMax_Informations,#exist_burger_companion_StoreWise_Informations').val())+1;
         jQuery('#exist_burger_companion_ShopMax_Informations,#exist_burger_companion_StoreWise_Informations').val(new_service_add_val);  
        }
        }
		
		
		
		//SeoKart
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_SeoKart_Slider,#exist_burger_companion_DigiPress_Slider').val()>=3)
				{
					jQuery(".customizer_SeoKart_slider_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_SeoKart_Slider,#exist_burger_companion_DigiPress_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SeoKart_Slider,#exist_burger_companion_DigiPress_Slider').val())+1;
					jQuery('#exist_burger_companion_SeoKart_Slider,#exist_burger_companion_DigiPress_Slider').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Feat")
        {
			if(jQuery('#exist_burger_companion_SeoKart_Features, #exist_burger_companion_DigiPress_Features').val()>=6)
				{
					jQuery(".customizer_SeoKart_features_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_SeoKart_Features, #exist_burger_companion_DigiPress_Features').val()<6)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SeoKart_Features, #exist_burger_companion_DigiPress_Features').val())+1;
					jQuery('#exist_burger_companion_SeoKart_Features, #exist_burger_companion_DigiPress_Features').val(new_service_add_val);  
				}
        }
		
		
		
		if(split_add_more_button_split=="Add New Team")
        {
			if(jQuery('#exist_burger_companion_SeoKart_Teams,#exist_burger_companion_DigiPress_Teams').val()>=6)
				{
					jQuery(".customizer_SeoKart_team_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SeoKart_Teams,#exist_burger_companion_DigiPress_Teams').val()<6)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SeoKart_Teams,#exist_burger_companion_DigiPress_Teams').val())+1;
					jQuery('#exist_burger_companion_SeoKart_Teams,#exist_burger_companion_DigiPress_Teams').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Info")
        {
			if(jQuery('#exist_burger_companion_SeoKart_Infos,#exist_burger_companion_DigiPress_Infos').val()>=3)
				{
					jQuery(".customizer_SeoKart_footer_info_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SeoKart_Infos,#exist_burger_companion_DigiPress_Infos').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SeoKart_Infos,#exist_burger_companion_DigiPress_Infos').val())+1;
					jQuery('#exist_burger_companion_SeoKart_Infos,#exist_burger_companion_DigiPress_Infos').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_SeoKart_Social,#exist_burger_companion_DigiPress_Social').val()>=3)
				{
					jQuery(".customizer_SeoKart_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SeoKart_Social,#exist_burger_companion_DigiPress_Social').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SeoKart_Social,#exist_burger_companion_DigiPress_Social').val())+1;
					jQuery('#exist_burger_companion_SeoKart_Social,#exist_burger_companion_DigiPress_Social').val(new_service_add_val);  
				}
        }
		
		
		//Appetizer
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_Appetizer_Slider,#exist_burger_companion_Rasam_Slider').val()>=3)
				{
					jQuery(".customizer_Appetizer_slider_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_Appetizer_Slider,#exist_burger_companion_Rasam_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Appetizer_Slider,#exist_burger_companion_Rasam_Slider').val())+1;
					jQuery('#exist_burger_companion_Appetizer_Slider,#exist_burger_companion_Rasam_Slider').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Serv")
        {
			if(jQuery('#exist_burger_companion_Appetizer_Service,#exist_burger_companion_Rasam_Service').val()>=4)
				{
					jQuery(".customizer_Appetizer_service_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_Appetizer_Service,#exist_burger_companion_Rasam_Service').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Appetizer_Service,#exist_burger_companion_Rasam_Service').val())+1;
					jQuery('#exist_burger_companion_Appetizer_Service,#exist_burger_companion_Rasam_Service').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Info")
        {
			if(jQuery('#exist_burger_companion_Appetizer_Information,#exist_burger_companion_Rasam_Information').val()>=4)
				{
					jQuery(".customizer_Appetizer_information_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_Appetizer_Information,#exist_burger_companion_Rasam_Information').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Appetizer_Information,#exist_burger_companion_Rasam_Information').val())+1;
					jQuery('#exist_burger_companion_Appetizer_Information,#exist_burger_companion_Rasam_Information').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_Appetizer_Social,#exist_burger_companion_Rasam_Social').val()>=4)
				{
					jQuery(".customizer_Appetizer_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_Appetizer_Social,#exist_burger_companion_Rasam_Social').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Appetizer_Social,#exist_burger_companion_Rasam_Social').val())+1;
					jQuery('#exist_burger_companion_Appetizer_Social,#exist_burger_companion_Rasam_Social').val(new_service_add_val);  
				}
        }
		
		
		
		//OwlPress
		
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_OwlPress_Social,#exist_burger_companion_Crowl_Social').val()>=3)
				{
					jQuery(".customizer_OwlPress_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_OwlPress_Social,#exist_burger_companion_Crowl_Social').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_OwlPress_Social,#exist_burger_companion_Crowl_Social').val())+1;
					jQuery('#exist_burger_companion_OwlPress_Social,#exist_burger_companion_Crowl_Social').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Info")
        {
			if(jQuery('#exist_burger_companion_OwlPress_Information,#exist_burger_companion_Crowl_Information').val()>=4)
				{
					jQuery(".customizer_OwlPress_hdr_info_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_OwlPress_Information,#exist_burger_companion_Crowl_Information').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_OwlPress_Information,#exist_burger_companion_Crowl_Information').val())+1;
					jQuery('#exist_burger_companion_OwlPress_Information,#exist_burger_companion_Crowl_Information').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_OwlPress_Slider,#exist_burger_companion_Crowl_Slider').val()>=3)
				{
					jQuery(".customizer_OwlPress_slider_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_OwlPress_Slider,#exist_burger_companion_Crowl_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_OwlPress_Slider,#exist_burger_companion_Crowl_Slider').val())+1;
					jQuery('#exist_burger_companion_OwlPress_Slider,#exist_burger_companion_Crowl_Slider').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Serv")
        {
			if(jQuery('#exist_burger_companion_OwlPress_Service,#exist_burger_companion_Crowl_Service').val()>=4)
				{
					jQuery(".customizer_OwlPress_service_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_OwlPress_Service,#exist_burger_companion_Crowl_Service').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_OwlPress_Service,#exist_burger_companion_Crowl_Service').val())+1;
					jQuery('#exist_burger_companion_OwlPress_Service,#exist_burger_companion_Crowl_Service').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Feat")
        {
			if(jQuery('#exist_burger_companion_OwlPress_Features,#exist_burger_companion_Crowl_Features').val()>=5)
				{
					jQuery(".customizer_OwlPress_features_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_OwlPress_Features,#exist_burger_companion_Crowl_Features').val()<5)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_OwlPress_Features,#exist_burger_companion_Crowl_Features').val())+1;
					jQuery('#exist_burger_companion_OwlPress_Features,#exist_burger_companion_Crowl_Features').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Cont")
        {
			if(jQuery('#exist_burger_companion_OwlPress_Contact,#exist_burger_companion_Crowl_Contact').val()>=2)
				{
					jQuery(".customizer_OwlPress_footer_abv_ct_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_OwlPress_Contact,#exist_burger_companion_Crowl_Contact').val()<2)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_OwlPress_Contact,#exist_burger_companion_Crowl_Contact').val())+1;
					jQuery('#exist_burger_companion_OwlPress_Contact,#exist_burger_companion_Crowl_Contact').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Team")
        {
			if(jQuery('#exist_burger_companion_Crowl_Teams').val()>=4)
				{
					jQuery(".customizer_OwlPress_team_upgrade_section").show();
					return false;   
				}
            if(jQuery('#exist_burger_companion_Crowl_Teams').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Crowl_Teams').val())+1;
					jQuery('#exist_burger_companion_Crowl_Teams').val(new_service_add_val);  
				}
        }
		
		
		// Setto
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_Setto_Social, #exist_burger_companion_Setto_Lifestyle_Social').val()>=4)
				{
					jQuery(".customizer_Setto_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_Setto_Social, #exist_burger_companion_Setto_Lifestyle_Social').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Setto_Social, #exist_burger_companion_Setto_Lifestyle_Social').val())+1;
					jQuery('#exist_burger_companion_Setto_Social, #exist_burger_companion_Setto_Lifestyle_Social').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_Setto_Slider, #exist_burger_companion_Setto_Lifestyle_Slider').val()>=3)
				{
					jQuery(".customizer_Setto_slider_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_Setto_Slider, #exist_burger_companion_Setto_Lifestyle_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Setto_Slider, #exist_burger_companion_Setto_Lifestyle_Slider').val())+1;
					jQuery('#exist_burger_companion_Setto_Slider, #exist_burger_companion_Setto_Lifestyle_Slider').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Cont")
        {
			if(jQuery('#exist_burger_companion_Setto_Contact, #exist_burger_companion_Setto_Lifestyle_Contact').val()>=3)
				{
					jQuery(".customizer_Setto_footer_contact_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_Setto_Contact, #exist_burger_companion_Setto_Lifestyle_Contact').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_Setto_Contact, #exist_burger_companion_Setto_Lifestyle_Contact').val())+1;
					jQuery('#exist_burger_companion_Setto_Contact, #exist_burger_companion_Setto_Lifestyle_Contact').val(new_service_add_val);  
				}
        }
		
		
		
		
		// DecorMe
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_DecorMe_Social').val()>=4)
				{
					jQuery(".customizer_DecorMe_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_DecorMe_Social').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_DecorMe_Social').val())+1;
					jQuery('#exist_burger_companion_DecorMe_Social').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_DecorMe_Slider').val()>=3)
				{
					jQuery(".customizer_DecorMe_slider_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_DecorMe_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_DecorMe_Slider').val())+1;
					jQuery('#exist_burger_companion_DecorMe_Slider').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Info")
        {
			if(jQuery('#exist_burger_companion_DecorMe_Information').val()>=6)
				{
					jQuery(".customizer_DecorMe_info_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_DecorMe_Information').val()<6)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_DecorMe_Information').val())+1;
					jQuery('#exist_burger_companion_DecorMe_Information').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Serv")
        {
			if(jQuery('#exist_burger_companion_DecorMe_Service').val()>=3)
				{
					jQuery(".customizer_DecorMe_service_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_DecorMe_Service').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_DecorMe_Service').val())+1;
					jQuery('#exist_burger_companion_DecorMe_Service').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Paym")
        {
			if(jQuery('#exist_burger_companion_DecorMe_Payment').val()>=4)
				{
					jQuery(".customizer_DecorMe_payment_icon_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_DecorMe_Payment').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_DecorMe_Payment').val())+1;
					jQuery('#exist_burger_companion_DecorMe_Payment').val(new_service_add_val);  
				}
        }
		
		// SpaBiz
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_SpaBiz_Social').val()>=4)
				{
					jQuery(".customizer_SpaBiz_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaBiz_Social').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaBiz_Social').val())+1;
					jQuery('#exist_burger_companion_SpaBiz_Social').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Cont")
        {
			if(jQuery('#exist_burger_companion_SpaBiz_Contact').val()>=3)
				{
					jQuery(".customizer_SpaBiz_contact_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaBiz_Contact').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaBiz_Contact').val())+1;
					jQuery('#exist_burger_companion_SpaBiz_Contact').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_SpaBiz_Slider').val()>=3)
				{
					jQuery(".customizer_SpaBiz_slider_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaBiz_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaBiz_Slider').val())+1;
					jQuery('#exist_burger_companion_SpaBiz_Slider').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Info")
        {
			if(jQuery('#exist_burger_companion_SpaBiz_Information').val()>=6)
				{
					jQuery(".customizer_SpaBiz_information_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaBiz_Information').val()<6)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaBiz_Information').val())+1;
					jQuery('#exist_burger_companion_SpaBiz_Information').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Serv")
        {
			if(jQuery('#exist_burger_companion_SpaBiz_Service').val()>=4)
				{
					jQuery(".customizer_SpaBiz_service_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaBiz_Service').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaBiz_Service').val())+1;
					jQuery('#exist_burger_companion_SpaBiz_Service').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Funf")
        {
			if(jQuery('#exist_burger_companion_SpaBiz_Funfact').val()>=4)
				{
					jQuery(".customizer_SpaBiz_funfact_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaBiz_Funfact').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaBiz_Funfact').val())+1;
					jQuery('#exist_burger_companion_SpaBiz_Funfact').val(new_service_add_val);  
				}
        }
		
		// SpaCare
		if(split_add_more_button_split=="Add New Soci")
        {
			if(jQuery('#exist_burger_companion_SpaCare_Social').val()>=4)
				{
					jQuery(".customizer_SpaCare_social_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaCare_Social').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaCare_Social').val())+1;
					jQuery('#exist_burger_companion_SpaCare_Social').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Cont")
        {
			if(jQuery('#exist_burger_companion_SpaCare_Contact').val()>=3)
				{
					jQuery(".customizer_SpaCare_contact_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaCare_Contact').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaCare_Contact').val())+1;
					jQuery('#exist_burger_companion_SpaCare_Contact').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Slid")
        {
			if(jQuery('#exist_burger_companion_SpaCare_Slider').val()>=3)
				{
					jQuery(".customizer_SpaCare_slider_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaCare_Slider').val()<3)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaCare_Slider').val())+1;
					jQuery('#exist_burger_companion_SpaCare_Slider').val(new_service_add_val);  
				}
        }
		
		
		if(split_add_more_button_split=="Add New Info")
        {
			if(jQuery('#exist_burger_companion_SpaCare_Information').val()>=6)
				{
					jQuery(".customizer_SpaCare_information_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaCare_Information').val()<6)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaCare_Information').val())+1;
					jQuery('#exist_burger_companion_SpaCare_Information').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Serv")
        {
			if(jQuery('#exist_burger_companion_SpaCare_Service').val()>=4)
				{
					jQuery(".customizer_SpaCare_service_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaCare_Service').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaCare_Service').val())+1;
					jQuery('#exist_burger_companion_SpaCare_Service').val(new_service_add_val);  
				}
        }
		
		if(split_add_more_button_split=="Add New Funf")
        {
			if(jQuery('#exist_burger_companion_SpaCare_Funfact').val()>=4)
				{
					jQuery(".customizer_SpaCare_funfact_upgrade_section").show();
					return false;   
				}
			 if(jQuery('#exist_burger_companion_SpaCare_Funfact').val()<4)
				{
					var new_service_add_val=parseInt(jQuery('#exist_burger_companion_SpaCare_Funfact').val())+1;
					jQuery('#exist_burger_companion_SpaCare_Funfact').val(new_service_add_val);  
				}
        }
		
		
        if (typeof th !== 'undefined') {
            /* Clone the first box*/
            var field = th.find('.customizer-repeater-general-control-repeater-container:first').clone( true, true );

            if (typeof field !== 'undefined') {
                /*Set the default value for choice between image and icon to icon*/
                field.find('.customizer-repeater-image-choice').val('customizer_repeater_icon');

                /*Show icon selector*/
                field.find('.social-repeater-general-control-icon').show();

                /*Hide image selector*/
                if (field.find('.social-repeater-general-control-icon').length > 0) {
                    field.find('.customizer-repeater-image-control').hide();
                }

                /*Show delete box button because it's not the first box*/
                field.find('.social-repeater-general-control-remove-field').show();

                /* Empty control for icon */
                field.find('.input-group-addon').find('.fa').attr('class', 'fa');


                /*Remove all repeater fields except first one*/

                field.find('.customizer-repeater-social-repeater').find('.customizer-repeater-social-repeater-container').not(':first').remove();
                field.find('.customizer-repeater-social-repeater-link').val('');
                field.find('.social-repeater-socials-repeater-colector').val('');

                /*Remove value from icon field*/
                field.find('.icp').val('');

                /*Remove value from text field*/
                field.find('.customizer-repeater-text-control').val('');

                /*Remove value from link field*/
                field.find('.customizer-repeater-link-control').val('');

                /*Remove value from text field*/
                field.find('.customizer-repeater-text2-control').val('');

                /*Remove value from link field*/
                field.find('.customizer-repeater-link2-control').val('');
				
				/*Set the default value in slide align*/
                field.find('.customizer-repeater-slide-align').val('left');
				
                /*Set box id*/
                field.find('.social-repeater-box-id').val(id);

                /*Remove value from media field*/
                field.find('.custom-media-url').val('');

                /*Remove value from title field*/
                field.find('.customizer-repeater-title-control').val('');


                /*Remove value from color field*/
                field.find('div.customizer-repeater-color-control .wp-picker-container').replaceWith('<input type="text" class="customizer-repeater-color-control ' + id + '">');
                field.find('input.customizer-repeater-color-control').wpColorPicker(color_options);


                field.find('div.customizer-repeater-color2-control .wp-picker-container').replaceWith('<input type="text" class="customizer-repeater-color2-control ' + id + '">');
                field.find('input.customizer-repeater-color2-control').wpColorPicker(color_options);

                // field.find('.customize-control-notifications-container').remove();


                /*Remove value from subtitle field*/
                field.find('.customizer-repeater-subtitle-control').val('');
				
				/*Remove value from Designation field*/
                field.find('.customizer-repeater-designation-control').val('');

                /*Remove value from shortcode field*/
                field.find('.customizer-repeater-shortcode-control').val('');

                /*Append new box*/
                th.find('.customizer-repeater-general-control-repeater-container:first').parent().append(field);

                /*Refresh values*/
                burger_companion_customizer_repeater_refresh_general_control_values();
            }

        }
        return false;
    });


    burger_companion_theme_controls.on('click', '.social-repeater-general-control-remove-field', function () {
		var split_delete_button=jQuery(this).text();
        var split_delete_button_split=split_delete_button.substr(8, 12);
        if (typeof    jQuery(this).parent() !== 'undefined') {
            jQuery(this).parent().hide(500, function(){
                jQuery(this).parent().remove();
                burger_companion_customizer_repeater_refresh_general_control_values();
				
				if(split_delete_button_split=="Delete Slide")
                {
                jQuery('#exist_burger_companion_Spintech_Slider,#exist_burger_companion_ITpress_Slider,#exist_burger_companion_Burgertech_Slider,#exist_burger_companion_KitePress_Slider,#exist_burger_companion_SpinSoft_Slider').val(parseInt(jQuery('#exist_burger_companion_Spintech_Slider,#exist_burger_companion_ITpress_Slider,#exist_burger_companion_Burgertech_Slider,#exist_burger_companion_KitePress_Slider,#exist_burger_companion_SpinSoft_Slider').val())-1);  
                }
				if(split_delete_button_split=="Delete Servi")
                {
                jQuery('#exist_burger_companion_Spintech_Service,#exist_burger_companion_ITpress_Service,#exist_burger_companion_Burgertech_Service,#exist_burger_companion_KitePress_Service,#exist_burger_companion_SpinSoft_Service').val(parseInt(jQuery('#exist_burger_companion_Spintech_Service,#exist_burger_companion_ITpress_Service,#exist_burger_companion_Burgertech_Service,#exist_burger_companion_KitePress_Service,#exist_burger_companion_SpinSoft_Service').val())-1);  
                }
				if(split_delete_button_split=="Delete Infor")
                {
                jQuery('#exist_burger_companion_Spintech_Information,#exist_burger_companion_ITpress_Information,#exist_burger_companion_KitePress_Information,#exist_burger_companion_SpinSoft_Information').val(parseInt(jQuery('#exist_burger_companion_Spintech_Information,#exist_burger_companion_ITpress_Information,#exist_burger_companion_KitePress_Information,#exist_burger_companion_SpinSoft_Information').val())-1); 
                }
				
				if(split_delete_button_split=="Delete Funfa")
                {
                jQuery('#exist_burger_companion_SpinSoft_Funfact').val(parseInt(jQuery('#exist_burger_companion_SpinSoft_Funfact').val())-1); 
                }
				
				if(split_delete_button_split=="Delete Testi")
                {
                jQuery('#exist_burger_companion_SpinSoft_Testimonial').val(parseInt(jQuery('#exist_burger_companion_SpinSoft_Testimonial').val())-1); 
                }
				
				if(split_delete_button_split=="Delete Socia")
                {
                jQuery('#exist_burger_companion_Spintech_Social,#exist_burger_companion_ITpress_Social,#exist_burger_companion_Burgertech_Social,#exist_burger_companion_KitePress_Social,#exist_burger_companion_SpinSoft_Social').val(parseInt(jQuery('#exist_burger_companion_Spintech_Social,#exist_burger_companion_ITpress_Social,#exist_burger_companion_Burgertech_Social,#exist_burger_companion_KitePress_Social,#exist_burger_companion_SpinSoft_Social').val())-1); 
                }
				if(split_delete_button_split=="Delete Desig")
                {
                jQuery('#exist_burger_companion_Spintech_Design,#exist_burger_companion_ITpress_Design,#exist_burger_companion_Burgertech_Design,#exist_burger_companion_KitePress_Design').val(parseInt(jQuery('#exist_burger_companion_Spintech_Design,#exist_burger_companion_ITpress_Design,#exist_burger_companion_Burgertech_Design,#exist_burger_companion_KitePress_Design').val())-1); 
                }
				
				
				//Cozipress
				if(split_delete_button_split=="Delete Slide")
                {
                jQuery('#exist_burger_companion_CoziPress_Slider,#exist_burger_companion_Sipri_Slider,#exist_burger_companion_Anexa_Slider,#exist_burger_companion_CoziWeb_Slider,#exist_burger_companion_CoziPlus_Slider,#exist_burger_companion_CoziBee_Slider').val(parseInt(jQuery('#exist_burger_companion_CoziPress_Slider,#exist_burger_companion_Sipri_Slider,#exist_burger_companion_Anexa_Slider,#exist_burger_companion_CoziWeb_Slider,#exist_burger_companion_CoziPlus_Slider,#exist_burger_companion_CoziBee_Slider').val())-1);  
                }
				
				if(split_delete_button_split=="Delete Infor")
                {
                jQuery('#exist_burger_companion_CoziPress_Information,#exist_burger_companion_CoziWeb_Information').val(parseInt(jQuery('#exist_burger_companion_CoziPress_Information,#exist_burger_companion_CoziWeb_Information').val())-1); 
                }
				
				
				if(split_delete_button_split=="Delete Servi")
                {
                jQuery('#exist_burger_companion_CoziPress_Service,#exist_burger_companion_Sipri_Service,#exist_burger_companion_Anexa_Service,#exist_burger_companion_CoziWeb_Service,#exist_burger_companion_CoziPlus_Service,#exist_burger_companion_CoziBee_Service').val(parseInt(jQuery('#exist_burger_companion_CoziPress_Service,#exist_burger_companion_Sipri_Service,#exist_burger_companion_Anexa_Service,#exist_burger_companion_CoziWeb_Service,#exist_burger_companion_CoziPlus_Service,#exist_burger_companion_CoziBee_Service').val())-1); 
                }
				
				
				if(split_delete_button_split=="Delete Testi")
                {
                jQuery('#exist_burger_companion_CoziPress_Testimonial,#exist_burger_companion_Sipri_Testimonial,#exist_burger_companion_Anexa_Testimonial,#exist_burger_companion_CoziWeb_Testimonial,#exist_burger_companion_CoziPlus_Testimonial,#exist_burger_companion_CoziBee_Testimonial').val(parseInt(jQuery('#exist_burger_companion_CoziPress_Testimonial,#exist_burger_companion_Sipri_Testimonial,#exist_burger_companion_Anexa_Testimonial,#exist_burger_companion_CoziWeb_Testimonial,#exist_burger_companion_CoziPlus_Testimonial,#exist_burger_companion_CoziBee_Testimonial').val())-1); 
                }
				
				if(split_delete_button_split=="Delete Funfa")
                {
                jQuery('#exist_burger_companion_CoziBee_Funfact').val(parseInt(jQuery('#exist_burger_companion_CoziBee_Funfact').val())-1); 
                }
				
				
				if(split_delete_button_split=="Delete Desig")
                {
                jQuery('#exist_burger_companion_CoziWeb_Design,#exist_burger_companion_CoziPlus_Design').val(parseInt(jQuery('#exist_burger_companion_CoziWeb_Design,#exist_burger_companion_CoziPlus_Design').val())-1); 
                }
				
				
				if(split_delete_button_split=="Delete Teams")
                {
                jQuery('#exist_burger_companion_CoziPlus_Teams').val(parseInt(jQuery('#exist_burger_companion_CoziPlus_Teams').val())-1); 
                }
				
				
				if(split_delete_button_split=="Delete Socia")
                {
                jQuery('#exist_burger_companion_CoziPress_Social,#exist_burger_companion_Sipri_Social,#exist_burger_companion_Anexa_Social,#exist_burger_companion_CoziWeb_Social,#exist_burger_companion_CoziPlus_Social,#exist_burger_companion_CoziBee_Social').val(parseInt(jQuery('#exist_burger_companion_CoziPress_Social,#exist_burger_companion_Sipri_Social,#exist_burger_companion_Anexa_Social,#exist_burger_companion_CoziWeb_Social,#exist_burger_companion_CoziPlus_Social,#exist_burger_companion_CoziBee_Social').val())-1); 
                }
				
				
				//Storebiz
				if(split_delete_button_split=="Delete Slide")
                {
                jQuery('#exist_burger_companion_StoreBiz_Slider,#exist_burger_companion_ShopMax_Slider,#exist_burger_companion_StoreWise_Slider').val(parseInt(jQuery('#exist_burger_companion_StoreBiz_Slider,#exist_burger_companion_ShopMax_Slider,#exist_burger_companion_StoreWise_Slider').val())-1);  
                }
				
				if(split_delete_button_split=="Delete Testi")
                {
                jQuery('#exist_burger_companion_StoreBiz_Testimonial,#exist_burger_companion_ShopMax_Testimonial,#exist_burger_companion_StoreWise_Testimonial').val(parseInt(jQuery('#exist_burger_companion_StoreBiz_Testimonial,#exist_burger_companion_ShopMax_Testimonial,#exist_burger_companion_StoreWise_Testimonial').val())-1); 
                }
				
				if(split_delete_button_split=="Delete Infor")
                {
                jQuery('#exist_burger_companion_StoreBiz_Information,#exist_burger_companion_ShopMax_Information').val(parseInt(jQuery('#exist_burger_companion_StoreBiz_Information,#exist_burger_companion_ShopMax_Information').val())-1); 
                }
				
				if(split_delete_button_split=="Delete Offer")
                {
                jQuery('#exist_burger_companion_StoreBiz_Offer,#exist_burger_companion_ShopMax_Offer,#exist_burger_companion_StoreWise_Offer').val(parseInt(jQuery('#exist_burger_companion_StoreBiz_Offer,#exist_burger_companion_ShopMax_Offer,#exist_burger_companion_StoreWise_Offer').val())-1); 
                }
				
				if(split_delete_button_split=="Delete Infor")
                {
                jQuery('#exist_burger_companion_ShopMax_Informations,#exist_burger_companion_StoreWise_Informations').val(parseInt(jQuery('#exist_burger_companion_ShopMax_Informations,#exist_burger_companion_StoreWise_Informations').val())-1); 
                }
				
				
				//SeoKart
				if(split_delete_button_split=="Delete Slide")
                {
               console.log( jQuery('#exist_burger_companion_SeoKart_Slider,#exist_burger_companion_DigiPress_Slider').val(parseInt(jQuery('#exist_burger_companion_SeoKart_Slider,#exist_burger_companion_DigiPress_Slider').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Featu")
                {
                jQuery('#exist_burger_companion_SeoKart_Features, #exist_burger_companion_DigiPress_Features').val(parseInt(jQuery('#exist_burger_companion_SeoKart_Features, #exist_burger_companion_DigiPress_Features').val())-1);  
                }
				
				
				if(split_delete_button_split=="Delete Teams")
                {
                jQuery('#exist_burger_companion_SeoKart_Teams,#exist_burger_companion_DigiPress_Teams').val(parseInt(jQuery('#exist_burger_companion_SeoKart_Teams,#exist_burger_companion_DigiPress_Teams').val())-1);  
                }
				
				if(split_delete_button_split=="Delete Infos")
                {
                jQuery('#exist_burger_companion_SeoKart_Infos,#exist_burger_companion_DigiPress_Infos').val(parseInt(jQuery('#exist_burger_companion_SeoKart_Infos,#exist_burger_companion_DigiPress_Infos').val())-1);  
                }
				
				
				if(split_delete_button_split=="Delete Socia")
                {
                jQuery('#exist_burger_companion_SeoKart_Social,#exist_burger_companion_DigiPress_Social').val(parseInt(jQuery('#exist_burger_companion_SeoKart_Social,#exist_burger_companion_DigiPress_Social').val())-1);  
                }
				
				
				//Appetizer
				if(split_delete_button_split=="Delete Slide")
                {
               console.log( jQuery('#exist_burger_companion_Appetizer_Slider,#exist_burger_companion_Rasam_Slider').val(parseInt(jQuery('#exist_burger_companion_Appetizer_Slider,#exist_burger_companion_Rasam_Slider').val())-1));  
                }
				
				
				if(split_delete_button_split=="Delete Servi")
                {
               console.log( jQuery('#exist_burger_companion_Appetizer_Service,#exist_burger_companion_Rasam_Service').val(parseInt(jQuery('#exist_burger_companion_Appetizer_Service,#exist_burger_companion_Rasam_Service').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Infor")
                {
               console.log( jQuery('#exist_burger_companion_Appetizer_Information,#exist_burger_companion_Rasam_Information').val(parseInt(jQuery('#exist_burger_companion_Appetizer_Information,#exist_burger_companion_Rasam_Information').val())-1));  
                }
				
				
				if(split_delete_button_split=="Delete Socia")
                {
               console.log( jQuery('#exist_burger_companion_Appetizer_Social,#exist_burger_companion_Rasam_Social').val(parseInt(jQuery('#exist_burger_companion_Appetizer_Social,#exist_burger_companion_Rasam_Social').val())-1));  
                }
				
				
				// OwlPress
				if(split_delete_button_split=="Delete Socia")
                {
               console.log( jQuery('#exist_burger_companion_OwlPress_Social,#exist_burger_companion_Crowl_Social').val(parseInt(jQuery('#exist_burger_companion_OwlPress_Social,#exist_burger_companion_Crowl_Social').val())-1));  
                }
				
				
				if(split_delete_button_split=="Delete Infor")
                {
               console.log( jQuery('#exist_burger_companion_OwlPress_Information,#exist_burger_companion_Crowl_Information').val(parseInt(jQuery('#exist_burger_companion_OwlPress_Information,#exist_burger_companion_Crowl_Information').val())-1));  
                }
				
				
				if(split_delete_button_split=="Delete Slide")
                {
				console.log( jQuery('#exist_burger_companion_OwlPress_Slider,#exist_burger_companion_Crowl_Slider').val(parseInt(jQuery('#exist_burger_companion_OwlPress_Slider,#exist_burger_companion_Crowl_Slider').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Servi")
                {
				console.log( jQuery('#exist_burger_companion_OwlPress_Service,#exist_burger_companion_Crowl_Service').val(parseInt(jQuery('#exist_burger_companion_OwlPress_Service,#exist_burger_companion_Crowl_Service').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Featu")
                {
				console.log( jQuery('#exist_burger_companion_OwlPress_Features,#exist_burger_companion_Crowl_Features').val(parseInt(jQuery('#exist_burger_companion_OwlPress_Features,#exist_burger_companion_Crowl_Features').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Conta")
                {
				console.log( jQuery('#exist_burger_companion_OwlPress_Contact,#exist_burger_companion_Crowl_Contact').val(parseInt(jQuery('#exist_burger_companion_OwlPress_Contact,#exist_burger_companion_Crowl_Contact').val())-1));  
                }
				
				
				if(split_delete_button_split=="Delete Teams")
                {
				console.log( jQuery('#exist_burger_companion_Crowl_Teams').val(parseInt(jQuery('#exist_burger_companion_Crowl_Teams').val())-1));  
                }
				
				// Setto
				if(split_delete_button_split=="Delete Socia")
                {
               console.log( jQuery('#exist_burger_companion_Setto_Social, #exist_burger_companion_Setto_Lifestyle_Social').val(parseInt(jQuery('#exist_burger_companion_Setto_Social, #exist_burger_companion_Setto_Lifestyle_Social').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Slide")
                {
               console.log( jQuery('#exist_burger_companion_Setto_Slider, #exist_burger_companion_Setto_Lifestyle_Slider').val(parseInt(jQuery('#exist_burger_companion_Setto_Slider, #exist_burger_companion_Setto_Lifestyle_Slider').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Conta")
                {
               console.log( jQuery('#exist_burger_companion_Setto_Contact, #exist_burger_companion_Setto_Lifestyle_Contact').val(parseInt(jQuery('#exist_burger_companion_Setto_Contact, #exist_burger_companion_Setto_Lifestyle_Contact').val())-1));  
                }
				
				// DecorMe
				if(split_delete_button_split=="Delete Socia")
                {
				console.log( jQuery('#exist_burger_companion_DecorMe_Social').val(parseInt(jQuery('#exist_burger_companion_DecorMe_Social').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Slide")
                {
				console.log( jQuery('#exist_burger_companion_DecorMe_Slider').val(parseInt(jQuery('#exist_burger_companion_DecorMe_Slider').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Infor")
                {
				console.log( jQuery('#exist_burger_companion_DecorMe_Information').val(parseInt(jQuery('#exist_burger_companion_DecorMe_Information').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Servi")
                {
				console.log( jQuery('#exist_burger_companion_DecorMe_Service').val(parseInt(jQuery('#exist_burger_companion_DecorMe_Service').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Payme")
                {
				console.log( jQuery('#exist_burger_companion_DecorMe_Payment').val(parseInt(jQuery('#exist_burger_companion_DecorMe_Payment').val())-1));  
                }
				
				// SpaBiz
				if(split_delete_button_split=="Delete Socia")
                {
				console.log( jQuery('#exist_burger_companion_SpaBiz_Social').val(parseInt(jQuery('#exist_burger_companion_SpaBiz_Social').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Conta")
                {
				console.log( jQuery('#exist_burger_companion_SpaBiz_Contact').val(parseInt(jQuery('#exist_burger_companion_SpaBiz_Contact').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Slide")
                {
				console.log( jQuery('#exist_burger_companion_SpaBiz_Slider').val(parseInt(jQuery('#exist_burger_companion_SpaBiz_Slider').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Infor")
                {
				console.log( jQuery('#exist_burger_companion_SpaBiz_Information').val(parseInt(jQuery('#exist_burger_companion_SpaBiz_Information').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Servi")
                {
				console.log( jQuery('#exist_burger_companion_SpaBiz_Service').val(parseInt(jQuery('#exist_burger_companion_SpaBiz_Service').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Funfa")
                {
				console.log( jQuery('#exist_burger_companion_SpaBiz_Funfact').val(parseInt(jQuery('#exist_burger_companion_SpaBiz_Funfact').val())-1));  
                }
				
				// SpaCare
				if(split_delete_button_split=="Delete Socia")
                {
				console.log( jQuery('#exist_burger_companion_SpaCare_Social').val(parseInt(jQuery('#exist_burger_companion_SpaCare_Social').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Conta")
                {
				console.log( jQuery('#exist_burger_companion_SpaCare_Contact').val(parseInt(jQuery('#exist_burger_companion_SpaCare_Contact').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Slide")
                {
				console.log( jQuery('#exist_burger_companion_SpaCare_Slider').val(parseInt(jQuery('#exist_burger_companion_SpaCare_Slider').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Infor")
                {
				console.log( jQuery('#exist_burger_companion_SpaCare_Information').val(parseInt(jQuery('#exist_burger_companion_SpaCare_Information').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Servi")
                {
				console.log( jQuery('#exist_burger_companion_SpaCare_Service').val(parseInt(jQuery('#exist_burger_companion_SpaCare_Service').val())-1));  
                }
				
				if(split_delete_button_split=="Delete Funfa")
                {
				console.log( jQuery('#exist_burger_companion_SpaCare_Funfact').val(parseInt(jQuery('#exist_burger_companion_SpaCare_Funfact').val())-1));  
                }
				
            });
        }
        return false;
    });


    burger_companion_theme_controls.on('keyup', '.customizer-repeater-title-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });

    jQuery('input.customizer-repeater-color-control').wpColorPicker(color_options);
    jQuery('input.customizer-repeater-color2-control').wpColorPicker(color_options);

    burger_companion_theme_controls.on('keyup', '.customizer-repeater-subtitle-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });
	
	burger_companion_theme_controls.on('keyup', '.customizer-repeater-designation-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });
	
    burger_companion_theme_controls.on('keyup', '.customizer-repeater-shortcode-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });

    burger_companion_theme_controls.on('keyup', '.customizer-repeater-text-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });

    burger_companion_theme_controls.on('keyup', '.customizer-repeater-link-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });

    burger_companion_theme_controls.on('keyup', '.customizer-repeater-text2-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });

    burger_companion_theme_controls.on('keyup', '.customizer-repeater-link2-control', function () {
        burger_companion_customizer_repeater_refresh_general_control_values();
    });

    /*Drag and drop to change icons order*/

    jQuery('.customizer-repeater-general-control-droppable').sortable({
        axis: 'y',
        update: function () {
            burger_companion_customizer_repeater_refresh_general_control_values();
        }
    });


    /*----------------- Socials Repeater ---------------------*/
    burger_companion_theme_controls.on('click', '.social-repeater-add-social-item', function (event) {
        event.preventDefault();
        var th = jQuery(this).parent();
        var id = 'customizer-repeater-social-repeater-' + burger_companion_customizer_repeater_uniqid();
        if (typeof th !== 'undefined') {
            var field = th.find('.customizer-repeater-social-repeater-container:first').clone( true, true );
            if (typeof field !== 'undefined') {
                field.find( '.icp' ).val('');
                field.find( '.input-group-addon' ).find('.fa').attr('class','fa');
                field.find('.social-repeater-remove-social-item').show();
                field.find('.customizer-repeater-social-repeater-link').val('');
                field.find('.customizer-repeater-social-repeater-id').val(id);
                th.find('.customizer-repeater-social-repeater-container:first').parent().append(field);
            }
        }
        return false;
    });

    burger_companion_theme_controls.on('click', '.social-repeater-remove-social-item', function (event) {
        event.preventDefault();
        var th = jQuery(this).parent();
        var repeater = jQuery(this).parent().parent();
        th.remove();
        burger_companion_customizer_repeater_refresh_social_icons(repeater);
        return false;
    });

    burger_companion_theme_controls.on('keyup', '.customizer-repeater-social-repeater-link', function (event) {
        event.preventDefault();
        var repeater = jQuery(this).parent().parent();
        burger_companion_customizer_repeater_refresh_social_icons(repeater);
        return false;
    });

    burger_companion_theme_controls.on('change', '.customizer-repeater-social-repeater-container .icp', function (event) {
        event.preventDefault();
        var repeater = jQuery(this).parent().parent().parent();
        burger_companion_customizer_repeater_refresh_social_icons(repeater);
        return false;
    });

});

var burger_companionentityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    '\'': '&#39;',
    '/': '&#x2F;'
};

function burger_companionescapeHtml(string) {
    'use strict';
    //noinspection JSUnresolvedFunction
    string = String(string).replace(new RegExp('\r?\n', 'g'), '<br />');
    string = String(string).replace(/\\/g, '&#92;');
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return burger_companionentityMap[s];
    });

}