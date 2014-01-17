<?php

//The projects custom post type archive template

get_header();
?>

<div class="container_12 sub-page">

    <div class="grid_12">
        <h1 class="entry-title"><?php $term = $wp_query->get_queried_object(); echo $term->name; ?></h1>
    </div>

    <div class="grid_9 projects-index">

    <?php

    // Grab Current Cat
    $cat = $wp_query->query_vars['geb_projects_cat'];

    // Grab All Industries
    $industries = get_terms( 'geb_projects_industry_cat' );
    $industries_active = array();

    // Loop through each Industry Cat & get Posts

    foreach( $industries as $industry ) {

        $args = array(
			'posts_per_page' => 99,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'geb_projects_cat',
                    'field' => 'slug',
                    'terms' => $cat
                ),
                array(
                    'taxonomy' => 'geb_projects_industry_cat',
                    'field' => 'slug',
                    'terms' => $industry->slug
                )
            )
        );

        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) :

            // Set Anchor for Sticky Industry Headers
            echo '<h2 id="anchor-' . $industry->slug . '">' . $industry->name . '</h2>';
            //$n++;

            // Save Term for display (as not to show terms with no posts)

            $industries_active[] = array(
                'slug' => $industry->slug,
                'name' => $industry->name
                );

            // Post Loop
            while ( $the_query->have_posts() ) : $the_query->the_post();

                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('single-list'); ?>>

                    <div class="grid_4 alpha">

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

                    <div class="grid_5 omega">

                        <header class="entry-header">

                            <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                            <p class="entry-meta"><?php _s_posted_on(); ?></p>

                        </header>

                        <div class="entry-content">
                            <?php hm_the_excerpt( 50 ); ?>
                        </div>

                    </div>

                </article>

            <?php

            endwhile;

        endif; wp_reset_postdata();

    }

    ?>

    </div>

    <div class="grid_3" style="position:relative">

        <div id="sticky-sidebar">

        <?php

        foreach( $industries_active as $industry_active ) {

            ?>

            <a class="lightgray" href="#anchor-<?php echo $industry_active['slug']; ?>"><?php echo $industry_active['name']; ?></a>

            <?php

        }

        ?>

        </div>

    </div>

</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#sticky-sidebar").stickyScroll();
    });
</script>

<?php // get_sidebar();
get_footer(); ?>