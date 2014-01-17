<?php

// Content - News

?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-list'); ?>>

            <div class="grid_4" id="<?php ?>">

                <a class="single-thumb" href="<?php the_permalink(); ?>">

                <?php

                    $post_image = get_post_thumbnail_id( get_the_ID() );
                    if ( $post_image ) {
                        if ( $image = wp_get_attachment_image_src( $post_image, 'full', false) )
                            $post_image = wpthumb( ( string ) $image[0], 'width=300&height=160&crop=1', false);
                    }
                    ?>

                    <img src="<?php echo $post_image; ?>" />

                </a>

            </div>

            <div class="grid_4">

                        <header class="entry-header">

                            <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

                            <p class="entry-meta"><?php _s_posted_on(); ?></p>

                        </header>

                        <div class="entry-content">
                            <?php hm_the_excerpt( 50 ); ?>
                        </div>

			</div>

    </article>