<?php
/**
 * Delicious Recipes Functions.
 *
 * @package Blossom_Recipe
 */

if( ! function_exists( 'blossom_recipe_prep_time' ) ) :
/**
 * Prep Time.
 */
function blossom_recipe_prep_time(){
    global $recipe;
    if( $recipe->total_time ) : ?>
        <span class="cook-time">
            <svg class="icon">
                <use xlink:href="<?php echo plugin_dir_url( DELICIOUS_RECIPES_PLUGIN_FILE ) ?>assets/images/sprite.svg#time"></use>
            </svg>
            <span class="meta-text"><?php echo esc_html( $recipe->total_time ); ?></span>
        </span>
    <?php endif;
}
endif;

if( ! function_exists( 'blossom_recipe_difficulty_level' ) ) :
/**
 * Difficulty Level.
 */
function blossom_recipe_difficulty_level(){
    global $recipe;
    if( $recipe->difficulty_level ) : ?>
        <span class="cook-difficulty">
            <svg class="icon">
                <use xlink:href="<?php echo plugin_dir_url( DELICIOUS_RECIPES_PLUGIN_FILE ) ?>assets/images/sprite.svg#difficulty"></use>
            </svg>
            <span class="meta-text"><?php echo esc_html( ucfirst( $recipe->difficulty_level ) ); ?></span>
        </span>
    <?php endif;
}
endif;

if( ! function_exists( 'blossom_recipe_recipe_category' ) ) :
/**
 * Difficulty Level.
 */
function blossom_recipe_recipe_category(){
    global $recipe;
    if ( ! empty( $recipe->ID ) ) : ?>
        <span class="post-cat">
            <?php the_terms( $recipe->ID, 'recipe-course', '', '', '' ); ?>
        </span>
    <?php endif;
}
endif;

if( ! function_exists( 'blossom_recipe_recipe_rating' ) ) :
/**
 * Rating
 */
function blossom_recipe_recipe_rating(){
    global $recipe;
    if ( $recipe->rating ): ?>
        <span class="post-rating">
            <svg class="icon">
                <use xlink:href="<?php echo plugin_dir_url( DELICIOUS_RECIPES_PLUGIN_FILE ) ?>assets/images/sprite.svg#rating"></use>
            </svg>
            <span class="meta-text"><?php echo esc_html( $recipe->rating ); ?></span>
        </span>
    <?php endif;
}
endif;