<?php
/**
 * Blossom Recipe Widget Areas
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package Blossom_Recipe
 */

function blossom_recipe_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'blossom-recipe' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'blossom-recipe' ),
        ),
        'newsletter-section'   => array(
            'name'        => __( 'Newsletter Section', 'blossom-recipe' ),
            'id'          => 'newsletter-section', 
            'description' => __( 'Add "BlossomThemes: Email Newsletter Widget" for newsletter section.', 'blossom-recipe' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'blossom-recipe' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'blossom-recipe' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'blossom-recipe' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'blossom-recipe' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'blossom-recipe' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'blossom-recipe' ),
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
add_action( 'widgets_init', 'blossom_recipe_widgets_init' );