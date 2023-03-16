<?php
/**
 * Widget Areas
 *
 * @package Blossom_Fashion
 */

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blossom_fashion_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'blossom-fashion' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'blossom-fashion' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'blossom-fashion' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'blossom-fashion' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'blossom-fashion' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'blossom-fashion' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'blossom-fashion' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'blossom-fashion' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'blossom-fashion' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'blossom-fashion' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => '<section id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</section>',
    		'before_title'  => '<h2 class="widget-title" itemprop="name">',
    		'after_title'   => '</h2>',
    	) );
    }
}
add_action( 'widgets_init', 'blossom_fashion_widgets_init' );