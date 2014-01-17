<?php
/*
Plugin Name: SEO Score Dashboard by SEO-Visuals
Plugin URI: http://www.seo-visuals.com/us/wp-seo-score-plugin
Description: Quickly scans and displays the score for the current WordPress website regarding their SEO performance. Uses the external SEO-Visuals service for free webscanning.
Version: 1.3.2
Author: Eric Ververs
Author URI: http://www.seo-visuals.com/us
License: GPL v3
Copyright (C) 2012-2013, SEO-Visuals - info@seo-visuals.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Create the function to output the contents of our Dashboard Widget

function seoscore_dashboard_widget_function() {

	$homedomain = home_url();
	while(substr($homedomain, strlen($homedomain) - 1, 1) == '/') {
		$homedomain = substr($homedomain, 0, strlen($homedomain) - 1);
	}

	$status_description = 'Calculating your SEO score, please refresh this page to view the score. This could take up to 8 minutes.<br><br><img src="' . $homedomain . '/wp-content/plugins/seo-score-dashboard-by-seo-visuals/loadbar.gif" alt="loading" /><br>';
	
	$json = json_decode(file_get_contents('http://www.seo-visuals.com/api/8315poc87h64aaeon6tg526a6/scores/' . $homedomain));
	
	$advice = "";
	
	if(isset($json->{'status'})) {
		switch(true) {
			case ($json->{'status'} >= 0 && $json->{'status'} <= 1) :
				$status_description = 'Request failed';
			break;
			case ($json->{'status'} >= 3 && $json->{'status'} <= 6) :
				$status_description = 'Calculating your SEO score, please refresh this page to view the score. This could take up to 8 minutes.<br><br><img src="' . $homedomain . '/wp-content/plugins/seo-score-dashboard-by-seo-visuals/loadbar.gif" alt="loading" />';
			break;
			case ($json->{'status'} == 7) :
				$status_description = 'Finished, will recalculate each 14 days';
				$overall = $json->{'scores'}->{'overall'};
				if($json->{'scores'}->{'overall'} < 61) {
					$advice = "Your current overall scores indicates: Not optimized yet.";
				} elseif($json->{'scores'}->{'overall'} < 71) {
					$advice = "Your current overall scores indicates: Moderately optimized. There is a lot of room for improvement.";
				} elseif($json->{'scores'}->{'overall'} < 81) {
					$advice = "Your current overall scores indicates: Reasonably optimized. You are on the right track. Optimize your website even more to climb a couple of search positions or to strengthen your current search position.";
				} elseif($json->{'scores'}->{'overall'} < 81) {
					$advice = "Your current overall scores indicates: Well optimized. Yet there are still some improvements to be made.";
				} elseif($json->{'scores'}->{'overall'} < 101) {
					$advice = "Your current overall scores indicates: Almost perfectly optimized. Your website is optimized. Don't forget that SEO is a continuous process, meaning you are never completely finished.";
				}
				$onpage = $json->{'scores'}->{'onpage'};
				$structure = $json->{'scores'}->{'websitestructure'};
				$technical = $json->{'scores'}->{'technical'};
				$popularity = $json->{'scores'}->{'popularity'};
				$social = $json->{'scores'}->{'socialmedia'};
				
				$pdfreport = $json->{'pdf-report'};
			break;
		}
	} else {
		$status_description = 'error, service currently unavailable';
	}
	
	?>
	
	<style>
		div.score_element {
			margin-right:8px; margin-bottom:4px; text-align:center; width:100%; border-width:1px; border-style: solid; border-top-color:#ccc; border-bottom-color:#bbb; border-left-color:#ccc; border-right-color:#ccc; background: #ffffff; display: block; margin-right:5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; padding-top:5px; padding-bottom: 5px;
			cursor:help;
		}
		
		div.score_element span {
			line-height:16pt;
			text-align:center;
			font-size:8pt;
			padding-bottom: 8px;
		}
		
		div.score_element span.overall_normal {
			font-size:12pt;
			color:#8F8F8F;
		}
		
		div.score_element span.normal {
			font-size:12pt;
			color:#8F8F8F;
		}
		
		p.providedby {
			font-size:8pt;
			text-align:right;
		}
		
		div.score_element span.subitem {
			font-size:12pt;
		}
		
		div.score_element span.subitem_small {
			font-size:9pt;
		}
		
		div.score_element span.overall_subitem {
			font-size:14pt;
		}
		
		div.score_element span.overal_subitem_small {
			font-size:11pt;
		}
		
		span.score_advice {
			text-decoration:underline;
		}
		
		div#downloadpdf {
			width:100%;
			padding:10px 10px 0px 0px;
			border-top:1px solid #BBB;
		}
		
		div#overallscore {
			clear:both;
			border-bottom:1px solid #BBB;
		}
		
		div.submeters {
			width:160px;
			display:inline-block;
			position:relative;
		}
		
		div#subwrapper {
			text-align:center;
			width:100%;
			overflow:auto;
		}
		
	</style>
	
	<script type="text/javascript">
	var overallscore;
	var pagescore;
	var websitestructure;
	var techfactors;
	var linkbuilding;
	var socialmedia;	
	
	window.onload = function(){
		var overallscore = new JustGage({
			id: "overallscore", 
			value: <?php echo $overall; ?>, 
			min: 0,
			max: 100,
			title: "Overall website score",
			label: "/ 100",
			shadowOpacity: 1,
			shadowSize: 0,
			shadowVerticalOffset: 5,
			levelColors: ["#FF3333","#FFCC33","#66CC33"]  
		});
		
		var pagescore = new JustGage({
			id: "pagescore", 
			value: <?php echo $onpage; ?>, 
			min: 0,
			max: 30,
			title: "Page score",
			label: "/ 30",
			gaugeWidthScale: 0.2,
			levelColors: ["#FF3333","#FFCC33","#66CC33"]  
		});
		
		var websitestructure = new JustGage({
			id: "websitestructure", 
			value: <?php echo $structure; ?>, 
			min: 0,
			max: 15,
			title: "Website structure",
			label: "/ 15",
			gaugeWidthScale: 0.2,
			levelColors: ["#FF3333","#FFCC33","#66CC33"]  
		});
		
		var techfactors = new JustGage({
			id: "techfactors", 
			value: <?php echo $technical; ?>, 
			min: 0,
			max: 20,
			title: "Technical factors",
			label: "/ 20",
			gaugeWidthScale: 0.2,
			levelColors: ["#FF3333","#FFCC33","#66CC33"]  
		});
		
		var linkbuilding = new JustGage({
			id: "linkbuilding", 
			value: <?php echo $popularity; ?>, 
			min: 0,
			max: 25,
			title: "Link building",
			label: "/ 25",
			gaugeWidthScale: 0.2,
			levelColors: ["#FF3333","#FFCC33","#66CC33"]  
		});

		var socialmedia = new JustGage({
			id: "socialmedia", 
			value: <?php echo $social; ?>, 
			min: 0,
			max: 10,
			title: "Social media",
			label: "/ 10",
			gaugeWidthScale: 0.2,
			levelColors: ["#FF3333","#FFCC33","#66CC33"]  
		});
		
	};
	</script>
		
	<script src="<?php echo plugins_url(); ?>/seo-score-dashboard-by-seo-visuals/js/raphael.2.1.0.min.js"></script>
	<script src="<?php echo plugins_url(); ?>/seo-score-dashboard-by-seo-visuals/js/justgage.1.0.1.min.js"></script>
	
	<p>Current status: <?php echo $status_description; ?></p>
	
	<a title="<?php if ($json->{'status'} == 7) { echo $advice; } ?>"><div id="overallscore"></div></a>
	
	<div id="subwrapper"><a title="All SEO factors regarding issues that can occur on a page specific level. For example, presence of the meta description tag or the proper use of heading tags."><div id="pagescore" class="submeters"></div></a>
	<a title="All SEO factors regarding issues about the internal website structure and URLS. For example, do you use search engine friendly URLs."><div id="websitestructure" class="submeters"></div></a>
	<a title="All SEO factors regarding technical issues. For example, how fast is your website or are you using a sitemap"><div id="techfactors" class="submeters"></div></a>
	<a title="Getting a lot of backlinks is good, but getting quality backlinks is more important. This score reviews your link building efforts"><div id="linkbuilding" class="submeters"></div></a>
	<a title="How active are you on Social Media? Even if you don't participate in Social Media, that doesn't mean people dont talk about you"><div id="socialmedia" class="submeters"></div></a></div>
	
	<div id="downloadpdf">
		<form action="<?php echo $pdfreport; ?>" method="post" target="_blank">
			<input type="submit" class="button" value="Download Free Quickscan PDF report" />
		</form>
	</div>

	
	<p class="providedby">
		Powered by <a href="http://www.seo-visuals.com/us" target="_blank" title="SEO-Visuals">SEO-Visuals</a>
	</p>
	<?php
} 

// Create the function use in the action hook

function seoscore_add_dashboard_widgets() {
	wp_add_dashboard_widget('seoscore_dashboard_widget',  '<img src="' . $homedomain . '/wp-content/plugins/seo-score-dashboard-by-seo-visuals/small_icon.gif" alt="SEO-Visuals -" /> Search Engine Optimization (SEO) Score', 'seoscore_dashboard_widget_function');
	
	// Globalize the metaboxes array, this holds all the widgets for wp-admin

	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)

	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
	// Backup and delete our new dashbaord widget from the end of the array

	$example_widget_backup = array('seoscore_dashboard_widget' => $normal_dashboard['seoscore_dashboard_widget']);
	unset($normal_dashboard['seoscore_dashboard_widget']);

	// Merge the two arrays together so our widget is at the beginning

	$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes 

	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
} 

// Hook into the 'wp_dashboard_setup' action to register our other functions

add_action('wp_dashboard_setup', 'seoscore_add_dashboard_widgets' ); // Hint: For Multisite Network Admin Dashboard use wp_network_dashboard_setup instead of wp_dashboard_setup.

?>