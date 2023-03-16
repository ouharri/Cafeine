jQuery(document).ready(function($) {

     $("#btn-search").on( 'click', function() {
        $(".site-header .form-holder").show("fast");
    }); 

    $('.btn-close-form').on( 'click', function(){
        $('.header-two .form-holder').hide("fast");
    });

    $(window).on( 'keyup', function(event){
        if(event.key == 'Escape') {
            $('.form-holder').hide("fast");    
        }
    });
});