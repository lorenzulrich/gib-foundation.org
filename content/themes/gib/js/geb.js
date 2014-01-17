jQuery(document).ready(function($){
    /*
    ___________________________________________________________

    GLOBAL INFRASTRUCTURE BASEL - JQUERY FUNCTIONS
    ___________________________________________________________

    */

    /*
    -----------------------------------------------------------
    Custom Slider
    -----------------------------------------------------------
    */

    /* Functions */

    function buildSlideNavigation() {
        var $allSlides = $('li', '#slider-container');
        var $slideNavigation = $('.slide-navigation', '.slide-active');

        var slideNav = '';
        $.each($allSlides, function(e) {
            var $element = $(this);
            var elementClass = $element.attr('class') == 'slide-active' ? 'class="active slide-select"' : 'class="slide-select"';
            slideNav = slideNav + '<a data-target="' + $element.attr('id') + '" ' + elementClass + '>■</a>';
        });
        $slideNavigation.html(slideNav);
    }

    function bindSlideNavigation() {
        var $activeSlider = $('.slide-active');
        $('.slide-select').click(function(e) {
            var target = $(e.target).attr('data-target');
            $activeSlider.fadeOut().removeClass('slide-active').promise().done(function() {
                $('#' + target).fadeIn().addClass('slide-active');
                buildSlideNavigation();
                bindSlideNavigation();
            });
        });
    }

    buildSlideNavigation();
    bindSlideNavigation();




    /*
     -----------------------------------------------------------
     Calendar Download
     -----------------------------------------------------------
     */


    /* Switch out text for Calendar Download */

    if ( $( window ).width() < 481 ) {
        $('.cal-download span').text('Save the date to your Mobile');
    }

    /*
     -----------------------------------------------------------
     Gallery - prettyPhoto
     -----------------------------------------------------------
     */

    $("a[rel^='prettyPhoto']").prettyPhoto({
        allow_resize: true,
        show_title: false,
        slideshow: false,
        horizontal_padding: 10,
        theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
        social_tools:'',
        markup: '<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_top"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
						<div class="pp_content_container"> \
							<div class="pp_left"> \
							<div class="pp_right"> \
								<div class="pp_content"> \
									<div class="pp_loaderIcon"></div> \
									<div class="pp_fade"> \
										<div class="pp_hoverContainer"> \
											<a class="pp_next" href="#">next</a> \
											<a class="pp_previous" href="#">previous</a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
											<div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous">Previous</a> \
												<div style="display:inline; float:left; line-height:18px; padding: 0 5px 0 4px;" class="currentTextHolder">0/0</div> \
												<a href="#" class="pp_arrow_next">Next</a> \
											</div> \
											<p class="pp_description"></p> \
											{pp_social} \
											<a class="pp_close" href="#">Close</a> \
										</div> \
									</div> \
								</div> \
							</div> \
							</div> \
						</div> \
						<div class="pp_bottom"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
					</div> \
					<div class="pp_overlay"></div>',
    });

    /*
     -----------------------------------------------------------
     Mobile Toggle
     -----------------------------------------------------------
     */

    $("#nav-mobile-toggle").on("click", function(event){
        $(this).toggleClass('toggle-active');
        $('#nav-mobile-container').slideToggle('fast', function() {
            // Animation complete.
        });
    });

});