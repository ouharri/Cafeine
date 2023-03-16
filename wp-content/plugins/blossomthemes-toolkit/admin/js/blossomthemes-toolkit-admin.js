jQuery(document).ready(function ($) {
    $("body").on("click", "#add-faq:visible", function (e) {
        e.preventDefault();
        da = $(this).siblings(".widget-client-faq-repeater").attr("id");
        if ($("body").hasClass("elementor-editor-active")) {
            suffix = "REPLACE_TO_ID";
        } else {
            suffix = da.match(/\d+/);
        }
        len = 0;
        $(".faqs-repeat:visible").each(function () {
            var value = $(this).attr("data-id");
            if (!isNaN(value)) {
                value = parseInt(value);
                len = value > len ? value : len;
            }
        });
        var newinput = $(".bttk-faq-template").clone();
        len++;
        // newinput.html(function(i, oldHTML) {
        // });
        newinput.find(".faqs-repeat").attr("data-id", len);
        newinput
            .find(".question")
            .attr(
                "name",
                "widget-bttk_faqs_widget[" + suffix + "][question][" + len + "]"
            );
        newinput
            .find(".answer")
            .attr(
                "name",
                "widget-bttk_faqs_widget[" + suffix + "][answer][" + len + "]"
            );
        // newinput.html(function(i, oldHTML) {
        //     return oldHTML.replace(/{{indexes}}/g, len);
        // });
        $(".cl-faq-holder").before(newinput.html()).focus().trigger("change");
    });

    var file_frame;
    $(".user-signature-image").on("click", function (event) {
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data("uploader_title"),
            button: {
                text: $(this).data("uploader_button_text"),
            },
            multiple: false, // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on("select", function () {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get("selection").first().toJSON();
            $("#user_signature_image").val(attachment.url);
            // Do something with attachment.id and/or attachment.url here
        });

        // Finally, open the modal
        file_frame.open();
    });

    $("body").on("click", ".del-user-social-links", function (e) {
        var confirmation = confirm(sociconsmsg.msg);
        if (!confirmation) {
            return false;
        }
        $(this)
            .parent()
            .fadeOut("slow", function () {
                $(this).remove();
                $("#add-user-socicon").focus().trigger("change");
            });
        return;
    });

    $(document).on("focus", ".user-contact-social-profile", function () {
        // if($(this).val()=='')
        // {
        // if( $(this).siblings('.bttk-icons-list').length < 1 )
        // {
        var $iconlist = $(".bttk-icons-wrap").clone();
        $(this).after($iconlist.html());
        $(this).siblings(".bttk-icons-list").fadeIn("slow");
        // }

        // if ( $(this).siblings('.bttk-icons-list').find('#remove-icon-list').length < 1 )
        // {
        var input = '<span id="remove-icon-list" class="fas fa-times"></span>';
        $(this).siblings(".bttk-icons-list:visible").prepend(input);
        // }
        // }
    });

    $(document).on("blur", ".user-contact-social-profile", function (e) {
        e.preventDefault();
        $(this)
            .siblings(".bttk-icons-list")
            .fadeOut("slow", function () {
                $(this).remove();
            });
    });

    $(document).on("click", ".bttk-icons-list li", function (event) {
        var prefix = $(this).children("svg").attr("data-prefix");
        var icon = $(this).children("svg").attr("data-icon");
        var val = prefix + " fa-" + icon;

        $(this).parent().siblings(".user-social-profile").val(icon);
        $(this).parent().siblings(".user-contact-social-profile").val(icon);

        $(this)
            .parent()
            .parent()
            .siblings(".bttk-contact-social-length")
            .attr("value", "https://" + icon + ".com");
        $(this)
            .parent()
            .parent()
            .siblings(".bttk-social-length")
            .attr("value", "https://" + icon + ".com");
        $(this)
            .parent()
            .siblings(".user-social-links")
            .attr("value", "https://" + icon + ".com");

        $(this).siblings(".bttk-icons-wrap-search").remove("slow");
        $(this)
            .parent()
            .fadeOut("slow", function () {
                $(this).remove();
            });

        $(this).parent().siblings(".user-social-profile").trigger("change");
        $(this).parent().siblings(".user-social-links").trigger("change");
        $(this).parent().siblings(".user-contact-social-profile").trigger("change");
        $(this).parent().siblings(".bttk-contact-social-length").trigger("change");
        $(this).parent().siblings(".bttk-social-length").trigger("change");

        event.preventDefault();
    });

    $(document).on("keyup", ".user-contact-social-profile", function () {
        var value = $(this).val();
        var matcher = new RegExp(value, "gi");
        $(this)
            .siblings(".bttk-icons-list")
            .children("li")
            .show()
            .not(function () {
                return matcher.test($(this).find("svg").attr("data-icon"));
            })
            .hide();
    });

    $(document).on("keyup", ".search-itw-icons", function () {
        var value = $(this).val();
        var matcher = new RegExp(value, "gi");
        $(this)
            .siblings(".bttk-font-awesome-list")
            .find("li")
            .show()
            .not(function () {
                return matcher.test($(this).find("svg").attr("data-icon"));
            })
            .hide();
    });

    $(document).on("keyup", ".bttk-sc-icons", function () {
        var value = $(this).val();
        var matcher = new RegExp(value, "gi");
        $(this)
            .siblings(".bttk-font-awesome-list")
            .find("li")
            .show()
            .not(function () {
                return matcher.test($(this).find("svg").attr("data-icon"));
            })
            .hide();
    });

    $(document).on("keyup", ".bttk-icons-wrap-search", function () {
        var value = $(this).val();
        var matcher = new RegExp(value, "gi");
        $(this)
            .parent(".bttk-icons-list")
            .children("li")
            .show()
            .not(function () {
                return matcher.test($(this).find("svg").attr("data-icon"));
            })
            .hide();
    });

    $(document).on("keyup", ".user-social-profile", function () {
        var value = $(this).val();
        var matcher = new RegExp(value, "gi");
        $(this)
            .siblings(".bttk-icons-list")
            .children("li")
            .show()
            .not(function () {
                return matcher.test($(this).find("svg").attr("data-icon"));
            })
            .hide();
    });

    $(document).on("focus", ".user-social-profile", function () {
        // if( $(this).siblings('.bttk-icons-list').length < 1 )
        // {
        var $iconlist = $(".bttk-icons-wrap").clone();
        $(this).after($iconlist.html());
        $(this).siblings(".bttk-icons-list").fadeIn("slow");
        // }

        // if ( $(this).siblings('.bttk-icons-list').find('#remove-icon-list').length < 1 )
        // {
        var input = '<span id="remove-icon-list" class="fas fa-times"></span>';
        $(this).siblings(".bttk-icons-list:visible").prepend(input);
        // }
    });

    $(document).on("blur", ".user-social-profile", function (e) {
        e.preventDefault();
        $(this)
            .siblings(".bttk-icons-list")
            .fadeOut("slow", function () {
                $(this).remove();
            });
    });

    // $(document).on('click', function (e) {
    //     if( $(event.target).attr('class') == 'user-social-links' || $(event.target).attr('class') == 'bttk-social-length' || $(event.target).attr('class') == 'bttk-icons-wrap-search')
    //     {
    //       return;
    //     }
    //     $('.bttk-icons-list:visible').fadeOut('slow',function(){
    //         $(this).remove();
    //     });
    //     $('.bttk-icons-wrap-search:visible').fadeOut('slow',function(){
    //         $(this).remove();
    //     });
    // });

    var frame;

    // ADD IMAGE LINK
    $("body").on("click", ".bttk-upload-button", function (e) {
        e.preventDefault();
        var clicked = $(this).closest("div");
        var custom_uploader = wp
            .media({
                title: "Blossom Image Uploader",
                // button: {
                //     text: 'Custom Button Text',
                // },
                multiple: false, // Set this to true to allow multiple files to be selected
            })
            .on("select", function () {
                var attachment = custom_uploader
                    .state()
                    .get("selection")
                    .first()
                    .toJSON();
                var str = attachment.url.split(".").pop();
                var strarray = ["jpg", "gif", "png", "jpeg"];
                if ($.inArray(str, strarray) != -1) {
                    clicked
                        .find(".bttk-screenshot")
                        .empty()
                        .hide()
                        .append(
                            '<img src="' +
                            attachment.url +
                            '"><a class="bttk-remove-image"></a>'
                        )
                        .slideDown("fast");
                } else {
                    clicked
                        .find(".bttk-screenshot")
                        .empty()
                        .hide()
                        .append(
                            "<small>" + bttk_theme_toolkit_pro_uploader.msg + "</small>"
                        )
                        .slideDown("fast");
                }

                clicked.find(".bttk-upload").val(attachment.id).trigger("change");
                clicked
                    .find(".bttk-upload-button")
                    .val(bttk_theme_toolkit_pro_uploader.change);
            })
            .open();
    });

    $("body").on("click", ".bttk-remove-image", function (e) {
        var selector = $(this).parent("div").parent("div");
        selector.find(".bttk-upload").val("").trigger("change");
        selector.find(".bttk-remove-image").hide();
        selector.find(".bttk-screenshot").slideUp();
        selector
            .find(".bttk-upload-button")
            .val(bttk_theme_toolkit_pro_uploader.upload);

        return false;
    });

    // Upload / Change Image
    function bttk_image_upload(button_class) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

        jQuery("body").on("click", button_class, function (e) {
            var button_id = "#" + jQuery(this).attr("id"),
                self = jQuery(button_id),
                send_attachment_bkp = wp.media.editor.send.attachment,
                button = jQuery(button_id),
                id = button.attr("id").replace("-button", "");

            _custom_media = true;

            wp.media.editor.send.attachment = function (props, attachment) {
                if (_custom_media) {
                    jQuery("#" + id + "-preview")
                        .attr("src", attachment.url)
                        .css("display", "block");
                    jQuery("#" + id + "-remove").css("display", "inline-block");
                    jQuery("#" + id + "-noimg").css("display", "none");
                    jQuery("#" + id)
                        .val(attachment.url)
                        .trigger("change");
                } else {
                    return _orig_send_attachment.apply(button_id, [props, attachment]);
                }
            };

            wp.media.editor.open(button);

            return false;
        });
    }
    bttk_image_upload(".bttk-media-upload");

    // set var
    var in_customizer = false;

    // check for wp.customize return boolean
    if (typeof wp !== "undefined") {
        in_customizer = typeof wp.customize !== "undefined" ? true : false;
    }

    // Remove Image
    function bttk_image_remove(button_class) {
        jQuery("body").on("click", button_class, function (e) {
            var button = jQuery(this),
                id = button.attr("id").replace("-remove", "");
            jQuery("#" + id + "-preview").css("display", "none");
            jQuery("#" + id + "-noimg").css("display", "block");
            button.css("display", "none");
            jQuery("#" + id)
                .val("")
                .trigger("change");
        });
    }
    bttk_image_remove(".bttk-media-remove");

    $("body").on("click", "#add-user-socicon", function (e) {
        e.preventDefault();
        da = $(this).siblings(".bttk-sortable-icons").attr("id");
        if ($("body").hasClass("elementor-editor-active")) {
            suffix = "REPLACE_TO_ID";
        } else {
            suffix = da.match(/\d+/);
        }
        var maximum = 0;
        $(".social-share-list").each(function () {
            var value = $(this).attr("data-id");
            if (!isNaN(value)) {
                value = parseInt(value);
                maximum = value > maximum ? value : maximum;
            }
        });
        var newField = $(".bttk-socicon-template").clone();
        maximum++;
        var name =
            "widget-bttk_author_bio[" + suffix + "][socicon][" + maximum + "]";
        newField.find(".user-social-links").attr("name", name);

        var profile =
            "widget-bttk_author_bio[" +
            suffix +
            "][socicon_profile][" +
            maximum +
            "]";
        newField.find(".user-social-profile").attr("name", profile);

        newField.html(function (i, oldHTML) {
            return oldHTML.replace(/{{socicon_index}}/g, maximum);
        });
        $(".bttk-socicon-holder").before(newField.html());
    });

    $("body").on("click", ".bttk-social-add", function (e) {
        e.preventDefault();
        da = $(this).siblings(".bttk-sortable-links").attr("id");
        if ($("body").hasClass("elementor-editor-active")) {
            suffix = "REPLACE_TO_ID";
        } else {
            suffix = da.match(/\d+/);
        }
        var maximum = 0;
        $(".bttk-social-icon-wrap:visible").each(function () {
            var value = $(this).attr("data-id");
            if (!isNaN(value)) {
                value = parseInt(value);
                maximum = value > maximum ? value : maximum;
            }
        });
        var newinput = $(".bttk-social-template").clone();
        maximum++;
        newinput
            .find(".bttk-social-length")
            .attr(
                "name",
                "widget-bttk_social_links[" + suffix + "][social][" + maximum + "]"
            );
        newinput
            .find(".user-social-profile")
            .attr(
                "name",
                "widget-bttk_social_links[" +
                suffix +
                "][social_profile][" +
                maximum +
                "]"
            );
        newinput.html(function (i, oldHTML) {
            return oldHTML.replace(/{{indexes}}/g, maximum);
        });

        $(this)
            .siblings(".bttk-sortable-links")
            .find(".bttk-social-icon-holder")
            .before(newinput.html());
    });

    $("body").on("click", ".del-bttk-icon", function () {
        var con = confirm(sociconsmsg.msg);
        if (!con) {
            return false;
        }
        $(this)
            .parent()
            .fadeOut("slow", function () {
                $(this).remove();
                $(".bttk-social-title-test").focus().trigger("change");
            });
        return;
    });

    $("body").on("click", ".del-contact-bttk-icon", function () {
        var con = confirm(sociconsmsg.msg);
        if (!con) {
            return false;
        }
        $(this)
            .parent()
            .fadeOut("slow", function () {
                $(this).remove();
                $(".bttk-contact-social-title-test").focus().trigger("change");
            });
        return;
    });

    $("body").on("click", ".bttk-contact-social-add:visible", function (e) {
        e.preventDefault();
        da = $(this).siblings(".bttk-contact-sortable-links").attr("id");
        if ($("body").hasClass("elementor-editor-active")) {
            suffix = "REPLACE_TO_ID";
        } else {
            suffix = da.match(/\d+/);
        }
        var maximum = 0;
        $(".bttk-contact-social-icon-wrap:visible").each(function () {
            var value = $(this).attr("data-id");
            if (!isNaN(value)) {
                value = parseInt(value);
                maximum = value > maximum ? value : maximum;
            }
        });
        var newinput = $(".bttk-contact-social-template").clone();
        maximum++;
        newinput
            .find(".bttk-contact-social-length")
            .attr(
                "name",
                "widget-bttk_contact_social_links[" +
                suffix +
                "][social][" +
                maximum +
                "]"
            );
        newinput
            .find(".user-contact-social-profile")
            .attr(
                "name",
                "widget-bttk_contact_social_links[" +
                suffix +
                "][social_profile][" +
                maximum +
                "]"
            );
        newinput.html(function (i, oldHTML) {
            return oldHTML.replace(/{{ind}}/g, maximum);
        });
        $(this)
            .siblings(".bttk-contact-sortable-links")
            .find(".bttk-contact-social-icon-holder")
            .before(newinput.html())
            .trigger("change");
    });
    // $(document).on('click','.bttk-icons-wrap-search',function() {
    //     if($(this).val()=='')
    //     {
    //         if( $(this).siblings('.bttk-icons-list').length < 1 )
    //         {
    //             var $iconlist = $('.bttk-icons-wrap').clone();
    //             $(this).after($iconlist.html());
    //             $(this).siblings('.bttk-icons-list').fadeIn('slow');
    //         }

    //         if ( $(this).siblings('.bttk-icons-list').find('.bttk-icons-wrap-search').length < 1 )
    //         {
    //             // var input = '<span id="remove-icon-list" class="dashicons dashicons-no"></span>';
    //             // $(this).siblings('.bttk-icons-list:visible').prepend(input);
    //             $('.bttk-icons-wrap-search').attr('value','');
    //         }
    //     }
    // });

    $("body").on("click", ".bttk-itw-add", function (e) {
        e.preventDefault();
        da = $(this).siblings(".bttk-img-text-outer").attr("id");
        if ($("body").hasClass("elementor-editor-active")) {
            suffix = "REPLACE_TO_ID";
        } else {
            suffix = da.match(/\d+/);
        }
        var maximum = 0;
        $(".image-text-widget-wrap:visible").each(function () {
            var value = $(this).attr("data-id");
            if (!isNaN(value)) {
                value = parseInt(value);
                maximum = value > maximum ? value : maximum;
            }
        });
        var newinput = $(".bttk-itw-template").clone();

        newinput.html(function (i, oldHTML) {
            maximum++;

            newinput.find(".image-text-widget-wrap").attr("data-id", maximum);
            newinput
                .find(".text input")
                .attr(
                    "name",
                    "widget-bttk_image_text_widget[" + suffix + "][link_text][]"
                );
            newinput
                .find(".link input")
                .attr("name", "widget-bttk_image_text_widget[" + suffix + "][link][]");
            newinput
                .find(".widget-upload input")
                .attr("name", "widget-bttk_image_text_widget[" + suffix + "][image][]");

            newinput
                .find(".text input")
                .attr(
                    "id",
                    "widget-bttk_image_text_widget[" + suffix + "][link_text][]"
                );
            newinput
                .find(".link input")
                .attr("id", "widget-bttk_image_text_widget[" + suffix + "][link][]");
            newinput
                .find(".widget-upload input")
                .attr("id", "widget-bttk_image_text_widget[" + suffix + "][image][]");

            newinput
                .find(".text label")
                .attr(
                    "for",
                    "widget-bttk_image_text_widget[" + suffix + "][link_text][]"
                );
            newinput
                .find(".link label")
                .attr("for", "widget-bttk_image_text_widget[" + suffix + "][link][]");
            newinput
                .find(".widget-upload label")
                .attr("for", "widget-bttk_image_text_widget[" + suffix + "][image][]");

            // newinput.find( '.bttk-screenshot' ).attr('id','widget-bttk_image_text_widget-'+suffix+'-image');

            // oldHTML.replace(/{{indexes}}/g, maximum);
        });

        $(this)
            .siblings(".bttk-img-text-outer")
            .find(".itw-holder")
            .before(newinput.html());
        // $(this).siblings('.bttk-sortable-links').find('.bttk-social-icon-holder').before(newinput.html());
    });
    $("body").on("click", ".image-text-cancel", function (e) {
        e.preventDefault();
        $(this)
            .parent()
            .fadeOut("slow", function () {
                $(this).remove();
            });
    });
    $("body").on("click", "#remove-icon-list", function (e) {
        e.preventDefault();
        $(this)
            .parent()
            .fadeOut("slow", function () {
                $(this).remove();
            });
    });

    $("body").on("click", ".add-logo:visible", function (e) {
        e.preventDefault();
        da = $(this).siblings(".widget-client-logo-repeater").attr("id");
        if ($("body").hasClass("elementor-editor-active")) {
            suffix = "REPLACE_TO_ID";
        } else {
            suffix = da.match(/\d+/);
        }
        var len = $(".link-image-repeat:visible").length;
        len++;
        var newinput = $(".bttk-client-logo-template").clone();
        newinput.html(function (i, oldHTML) {
            newinput
                .find(".featured-link")
                .attr(
                    "name",
                    "widget-blossom_client_logo_widget[" + suffix + "][link][" + len + "]"
                );
            newinput
                .find(".widget-upload .link")
                .attr(
                    "name",
                    "widget-blossom_client_logo_widget[" +
                    suffix +
                    "][image][" +
                    len +
                    "]"
                );
            $(".widget-client-logo-repeater").trigger("change");
        });
        $(this)
            .siblings(".widget-client-logo-repeater")
            .find(".cl-repeater-holder")
            .before(newinput.html());
    });
    $("body").on("click", ".cross", function (e) {
        e.preventDefault();
        $(this)
            .parent()
            .fadeOut("slow", function () {
                $(this).remove();
                $(".widget-client-logo-repeater").trigger("change");
            });
    });

    $(document).on("click", ".bttk-font-group li", function () {
        var id = $(this).parents(".widget").attr("id");
        $("#" + id)
            .find(".bttk-font-group li")
            .removeClass();
        $("#" + id)
            .find(".icon-receiver")
            .children("a")
            .remove(".bttk-remove-icon");
        $(this).addClass("selected");
        var prefix = $(this)
            .parents(".bttk-font-awesome-list")
            .find(".bttk-font-group li.selected")
            .children("svg")
            .attr("data-prefix");
        var icon = $(this)
            .parents(".bttk-font-awesome-list")
            .find(".bttk-font-group li.selected")
            .children("svg")
            .attr("data-icon");
        var aa = prefix + " fa-" + icon;
        $(this)
            .parents(".bttk-font-awesome-list")
            .siblings("p")
            .find(".hidden-icon-input")
            .val(aa);
        $(this)
            .parents(".bttk-font-awesome-list")
            .siblings("p")
            .find(".icon-receiver")
            .html('<i class="' + aa + '"></i>');
        $("#" + id)
            .find(".icon-receiver")
            .children("i")
            .after('<a class="bttk-remove-icon"></a>');

        if (in_customizer) {
            $(".hidden-icon-input").trigger("change");
        }
        return $(this).focus().trigger("change");
    });
    $(document).on("click", ".bttk-remove-icon", function () {
        var id = $(this).parents(".widget").attr("id");
        $("#" + id)
            .find(".bttk-font-group li")
            .removeClass();
        $("#" + id)
            .find(".hidden-icon-input")
            .val("");
        $("#" + id)
            .find(".icon-receiver")
            .html('<i class=""></i>')
            .children("a")
            .remove(".bttk-remove-icon");
        if (in_customizer) {
            $(".hidden-icon-input").trigger("change");
        }
        return $("#" + id)
            .find(".icon-receiver")
            .trigger("change");
    });

    /** To add remove button if icon is selected in widget update event */
    $(document).on("widget-updated", function (e, widget) {
        // "widget" represents jQuery object of the affected widget's DOM element
        var $this = $("#" + widget[0].id).find(".yes");
        $this.append('<a class="bttk-remove-icon"></a>');
    });

    bttktheme_pro_check_icon();

    /** function to check if icon is selected and saved when loading in widget.php */
    function bttktheme_pro_check_icon() {
        $(".icon-receiver").each(function () {
            // var id = $(this).parents('.widget').attr('id');
            if ($(this).hasClass("yes")) {
                $(this).append('<a class="bttk-remove-icon"></a>');
            }
        });
    }
    function initColorPicker(widget) {
        widget.find(".my-widget-color-field").wpColorPicker({
            change: _.throttle(function () {
                // For Customizer
                jQuery(this).trigger("change");
            }, 3000),
        });
    }
    function onFormUpdate(event, widget) {
        initColorPicker(widget);
    }

    jQuery(document).on("widget-added widget-updated", onFormUpdate);

    $(document).on("change", ".cta-button-number", function (e) {
        if ($(this).val() == 2) {
            $(this).parent().siblings(".button-one-info, .button-two-info").show();
        }
        else {
            $(this).parent().siblings(".button-two-info").fadeOut();
        }
    });
});
