<?php
/**
 * Template Name: Home
 *
 * @package _s
 * @since _s 1.0
 */
get_header();
the_post();
global $post;
?>

<!-- desktop - slider -->

<div class="slider-wrap">

	<ul id="slider-container">
	<?php
	// Get selected Slide ID's
	for ($i = 1; $i <= 5; $i++) {
		// Get ID of selected Slide Page
		$id = $post->ID;

		// Get additional meta of Slide Page
		$postMeta = get_post_meta($id, 'geb_slide_img_' . $i . '_id', TRUE);
		$sliderImageInformation = wp_get_attachment_image_src($postMeta, 'thumb');
		$sliderTitle = get_post_meta($id, 'geb_slide_title_' . $i, TRUE);
		$sliderText = get_post_meta($id, 'geb_slide_text_' . $i, TRUE);
		$buttonText = get_post_meta($id, 'geb_slide_buttontext_' . $i, TRUE);
		$buttonLink = get_post_meta($id, 'geb_slide_link_' . $i, TRUE);

		if (empty($sliderTitle)) {
			// if the slider title is empty, we don't render the slider item at all
			continue;
		}

		$sliderImageConfiguration = array (
			'width' 				    => 0,
			'height'				    => 0,
			'crop'					    => TRUE,
			'crop_from_position' 	    => 'center,center',
			'resize'				    => TRUE,
			'cache'					    => TRUE,
		);

		if (is_array($sliderImageInformation)) {
			// the default image
			$sliderImageConfiguration['width'] = 1000;
			$sliderImageConfiguration['height'] = 350;
			$normalSliderImageUri = wpthumb($sliderImageInformation[0], $sliderImageConfiguration);

			// the retina image
			$sliderImageConfiguration['width'] = 2000;
			$sliderImageConfiguration['height'] = 700;
			$retinaSliderImageUri = wpthumb($sliderImageInformation[0], $sliderImageConfiguration);

			// the mobile image
			$sliderImageConfiguration['width'] = 500;
			$sliderImageConfiguration['height'] = 175;
			$mobileSliderImageUri = wpthumb($sliderImageInformation[0], $sliderImageConfiguration);
		}

	?>

		<li <?php if($i==1){echo 'class="slide-active"';} else { echo 'style="display: none;"'; } ?> id="slide-<?php echo $i; ?>">
			<span data-picture data-alt="<?php echo $sliderText; ?>">
				<span data-src="<?php echo $mobileSliderImageUri; ?>" data-media="(max-width: 480px)"></span>
				<span data-src="<?php echo $normalSliderImageUri; ?>" data-media="(min-width: 481px)"></span>
				<span data-src="<?php echo $retinaSliderImageUri; ?>" data-media="(min-width: 1400px)"></span>
				<noscript>
					<img src="<?php echo $normalSliderImageUri; ?>" alt="<?php echo $sliderText; ?>">
				</noscript>
			</span>
			<div class="slider-content-container">
				<div class="slide-content-container-inner">
					<div class="slide-content">
						<div class="slide-title">
							<?php echo $sliderTitle; ?>
						</div>
						<div class="slide-text">
							<?php echo $sliderText; ?>
						</div>
						<div class="slide-link">
							<a class="button" href="<?php echo $buttonLink; ?>"><?php echo $buttonText; ?></a>
						</div>
						<div class="slide-navigation"></div>
					</div>
				</div>
			</div>
		</li>

	<?php

	}

	?>

	</ul>

</div>
<script src="<?php echo get_template_directory_uri(); ?>/js/picturefill.js"></script>

    <div class="container_12">

		<?php the_content(); ?>

    </div>

<?php get_footer(); ?>