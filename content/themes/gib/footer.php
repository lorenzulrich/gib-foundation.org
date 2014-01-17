<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package _s
 * @since _s 1.0
 */
?>

	</div>

    <a href="<?php echo site_url( '/event_gib.ics' ); ?>" class="cal-download">

        <div class="container_12">

            <div class="grid_12">

                <span>Save the Event to your Desktop Calendar</span>

            </div>

        </div>

    </a>

    <footer class="site-footer" role="footer">

    <div class="container_12">

            <div class="grid_8">

                <nav role="navigation" class="footer-navigation">

                    <?php wp_nav_menu( array( 'theme_location' => 'footer', 'depth' => 1 ) ); ?>

                </nav>

            </div>

            <div class="grid_4">
                <div class="search-wrap"><?php get_search_form(); ?></div>
            </div>

        <div class="clear"></div>

        <div class="grid_8">

            <div class="site-info">
                <div>&copy; Global Infrastructure Basel - The Sustainable Infrastructure Financing Summit</div>
                <div>All Rights Reserved. Designated trademarks and brands are the property of their respective owners. Use of this website constitutes acceptance of the Global Infrastructure Basel website Terms and Conditions and Global Infrastructure Basel Legal Privacy Policy.</div>
            </div>

        </div>

        <div class="grid_4">

        </div>

    </div>

    </footer>

</div>

<?php wp_footer(); ?>


<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-29400546-1']);
    _gaq.push(['_setDomainName', 'globalenergybasel.com']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

</body>
</html>