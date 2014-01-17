<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _s
 * @since _s 1.0
 */
?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php
        /*
         * Print the <title> tag based on what is being viewed.
         */
        global $page, $paged;

        wp_title( '|', true, 'right' );

        // Add the blog name.
        bloginfo( 'name' );

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            echo " | $site_description";

        // Add a page number if necessary:
        if ( $paged >= 2 || $page >= 2 )
            echo ' | ' . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );

        ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<?php do_action( 'before' ); ?>

    <!-- nav - desktop -->

    <nav role="navigation" class="top-navigation">

        <div class="container_12">

            <div class="grid_12">

            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'depth' => 4 ) ); ?>

            </div>

        </div>

    </nav>

    <!-- nav - mobile -->

    <div id="nav-mobile-toggle" style="display:none"><span>Navigation</span></div>

    <div id="nav-mobile-container" style="display:none">

            <nav role="navigation" class="mobile-navigation">

                <?php wp_nav_menu( array( 'theme_location' => 'mobile', 'depth' => 2 ) ); ?>

            </nav>

    </div>

    <!-- desktop - header -->

	<div class="full-width">

		<div class="container_12" id="site-header">

				<header id="masthead" role="banner">

					<div class="grid_8">

						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><h1 class="site-title"><?php bloginfo( 'name' ); ?></h1></a>

					</div>

					<div class="grid_4">

						<div class="savetheintro" style="">The 4th Global Infrastructure Basel Summit</div>
						<div class="savethedate" style="">21-22 May, 2014</div>
						<div class="savethelocation" style="">Congress Center Basel, Switzerland</div>

						<!-- <a class="button" href="<?php echo site_url(); ?>/geb-summit/register/">Register Now</a> -->
						<!--<a class="button" href="http://www.globalinfrastructurebasel.com/register">Register now</a>-->

						<!--
						<div class="action-newsletter">
							<input type="text" placeholder="Enter your e-mail address" />
							<input type="submit" value="Send me updates" class="button-newsletter" />
						</div>
						-->

					</div>

				</header>

		</div>

	</div>

    <!-- mobile - header -->

    <div class="container_12" id="mobile-header" style="display:none;">

        <header id="mobile-masthead" role="banner">

            <div class="grid_12" style="overflow:auto">

                <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><h1 class="mobile-site-title"><?php bloginfo( 'name' ); ?></h1></a>

            </div>

        </header>

    </div>

    <!-- #masthead .site-header -->

	<div id="main" class="<?php if( !is_page_template( 'page-home.php' ) ) { echo 'regular-page'; }; ?>">