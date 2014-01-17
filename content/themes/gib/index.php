<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

<div class="container_12 sub-page">

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

        <?php get_template_part('content', 'thumbposts'); ?>

        <?php endwhile; ?>

    <?php endif; ?>

        <div class="grid_12">

        <?php if(function_exists('wp_paginate')) {
        wp_paginate();
        } ?>

        </div>

    </div>

<?php // get_sidebar();
get_footer(); ?>