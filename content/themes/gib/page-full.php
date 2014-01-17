<?php
/**
 * Template Name: Page - Full
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

            <div class="grid_12">

				<?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', '_s' ), 'after' => '</div>' ) ); ?>
                    </div><!-- .entry-content -->

                </article><!-- #post-<?php the_ID(); ?> -->

                <?php endwhile; ?>

			</div>

		</div>

<?php get_footer(); ?>