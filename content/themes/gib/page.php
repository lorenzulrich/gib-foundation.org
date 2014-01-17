<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

        <div class="container_12 sub-page">

            <!--

            User may want to revert this block, leave in

            <div class="grid_3" id="sub-navigation">

                <nav role="navigation" class="page-navigation">

                <?php

                global $post;
                echo '<div class="parent">' . get_the_title($post->post_parent) . '</div>';
                $pages = nt_sibling_list();

                foreach ( $pages as $page ) {

                    if ( $page == $post->ID ) { $current = ' current'; } else { $current = ''; }

                        ?>

                        <a href="<?php echo get_permalink($page); ?>" class="sibling<?php echo $current; ?>"><?php echo get_the_title($page); ?></a>

                        <?php

                }

                ?>

                </nav>

            </div>

            -->

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

<?php // get_sidebar(); ?>
<?php get_footer(); ?>