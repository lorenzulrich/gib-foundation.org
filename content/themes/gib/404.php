<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

    <div class="container_12 sub-page">

        <div class="grid_12">

			<article id="post-0" class="post error404 not-found">

				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', '_s' ); ?></h1>
				</header>

				<div class="entry-content">

					<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', '_s' ); ?></p>

                    <div style="max-width:480px"><?php get_search_form(); ?></div>

				</div><!-- .entry-content -->

			</article><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #primary .site-content -->

<?php get_footer(); ?>