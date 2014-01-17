<?php

//The projects custom post type archive template

get_header();

?>

<div class="container_12 sub-page">

    <?php if ( have_posts() ) : ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <?php get_template_part('content', 'thumbposts'); ?>

        <?php endwhile; ?>

    <?php endif; ?>

    <!-- no pagination anymore

    <div class="grid_12">

        <?php if(function_exists('wp_paginate')) {
        wp_paginate();
    } ?>

    </div>

    -->

</div>

<?php // get_sidebar();
get_footer(); ?>