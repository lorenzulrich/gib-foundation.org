<?php
/**
 * Template Name: Page - Full - Eval Form
 *
 * @package _s
 * @since _s 1.0
 */

if ( ! is_user_logged_in() ) {

    wp_redirect( add_query_arg( "redirect_to", urlencode( add_query_arg( '', '' ) ), site_url('/wp-login.php') ) );
    exit;
}

get_header(); ?>

        <div class="container_12 sub-page">

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

<script>

    jQuery(document).ready(function($){

        // Auto height

        if ( $(window).width() >= 480) {

            $('ul.gfield_radio').each(function(){

                var height = $(this).height();
                $(this).find('label').height(height);

            });

        }

        // Auto Numbering (Answers)

        $('ul.gfield_radio').each(function(){

            var i = 1;

            $(this).find('label').each(function(){

                $(this).append('<span class="answer">' + i + '</span>');
                i++;

            });

        });

        // Auto Numbering (Questions)

        $('.gform_page').each(function(){

            var i = 1;

            $(this).find('label.gfield_label').each(function(){

                $(this).replaceWith('<label class="gfield_label"><strong>' + i + ')</strong> ' + $(this).text() + '</label>');
                i++;

            });

        });

    });

</script>

<?php get_footer(); ?>