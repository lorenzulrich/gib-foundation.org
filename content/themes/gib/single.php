<?php
/**
 * The Template for displaying all single posts.
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

<div class="container_12 sub-page">

	<?php // <div class="grid_12 breadcrumbs"> ?>
	<?php
	// if ( function_exists('yoast_breadcrumb') ) {
	// yoast_breadcrumb('<p id="breadcrumbs">','</p>');}
	?>
	<?php // </div> ?>

    <div class="grid_9">

                <?php while ( have_posts() ) : the_post(); ?>

                    <header class="entry-header">

                        <h1 class="entry-title"><?php the_title(); ?></h1>

                        <p class="entry-meta"><?php _s_posted_on(); ?></p>

                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <?php _s_content_nav( 'nav-below' ); ?>

                <?php endwhile; // end of the loop. ?>

    </div>

    <div class="grid_3">

        <?php if ( 'geb_projects' != get_post_type() ) { ?>

        <aside id="archives" class="widget">
            <h2 class="widget-title"><?php _e( 'Archives', '_s' ); ?></h2>
            <ul class="sidebar-list">
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </aside>

        <?php } ?>

    </div>

</div>


<?php get_footer(); ?>