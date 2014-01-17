<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

    <div class="container_12 sub-page">

        <div class="grid_12">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">Search Results</h1>
					<?php
						if ( is_category() ) {
							// show an optional category description
							$category_description = category_description();
							if ( ! empty( $category_description ) )
								echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );

						} elseif ( is_tag() ) {
							// show an optional tag description
							$tag_description = tag_description();
							if ( ! empty( $tag_description ) )
								echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
						}
					?>
				</header>

				<?php rewind_posts(); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

                <header class="entry-header">

                    <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

                    <p class="entry-meta"><?php _s_posted_on(); ?></p>

                </header>

                <div class="entry-content">
                    <?php the_content(false); ?>
                </div>

				<?php endwhile; ?>

			<?php else : ?>

				<h1>Sorry, no results!</h1>
                <p>Please try with more general terms:</p>
                <div style="max-width:480px"><?php get_search_form(); ?></div>

			<?php endif; ?>

            <div class="grid_12">

                <?php if(function_exists('wp_paginate')) {
                wp_paginate();
            } ?>

            </div>

        </div>

    </div>

<?php get_footer(); ?>