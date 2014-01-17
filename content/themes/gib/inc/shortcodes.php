<?php

// 66/33%, Lorenz Ulrich 2014
function gib_col_66($atts, $content = NULL) {
	return '<div class="clearfix"></div><div class="grid_8">' . do_shortcode($content) . '</div>';
}

add_shortcode('col-66', 'gib_col_66');

function gib_col_33($atts, $content = NULL) {
	return '<div class="grid_4">' . do_shortcode($content) . '</div><div class="clearfix"></div>';
}

add_shortcode('col-33', 'gib_col_33');

// Half Cols

function nt_col_half( $atts, $content = null ) {
    return '<div class="clearfix"></div><div class="half_col">' . do_shortcode($content) . '</div>';
}

add_shortcode('half_columns', 'nt_col_half');

function nt_col_half_last( $atts, $content = null ) {
    return '<div class="half_col last">' . do_shortcode($content) . '</div><div class="clearfix"></div>';
}

add_shortcode('half_columns_last', 'nt_col_half_last');

// Third Cols

function nt_col_third( $atts, $content = null ) {
    return '<div class="third_col">' . do_shortcode($content) . '</div>';
}

add_shortcode('third_columns', 'nt_col_third');

function nt_col_third_last( $atts, $content = null ) {
    return '<div class="third_col last">' . do_shortcode($content) . '</div><div class="clearfix"></div>';
}

add_shortcode('third_columns_last', 'nt_col_third_last');

// Golden Cols

function nt_col_golden( $atts, $content = null ) {
    return '<div class="golden_col">' . do_shortcode($content) . '</div>';
}

add_shortcode('golden_first', 'nt_col_golden');

function nt_col_golden_last( $atts, $content = null ) {
    return '<div class="golden_last_col">' . do_shortcode($content) . '</div><div class="clearfix"></div>';
}

add_shortcode('golden_last', 'nt_col_golden_last');

// Buttons

function nt_button( $atts, $content = null ) {

    extract( shortcode_atts( array(
        'color' => 'orange',
        'full' => 'true',
        'link' => 'http://',
        'inline' => 'false'
    ), $atts) );

    if ( $full == 'true') { $display = 'block'; } else { $display = 'inline-block'; }
    if ( $inline == 'false') { $inline = 'block; width:100%'; } else { $inline = 'inline'; }

    return '<a class="button-link" style="display:' . $inline . '" href="' . $link . '"><div class="content-button ' . $color . '" style="display:' . $display . '">' . do_shortcode($content) . '</div></a>';
}

add_shortcode('button', 'nt_button');

// Fix for stupid Paragraphs

function shortcode_empty_paragraph_fix($content)
{
    global $post;

    $array = array (
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']'
    );

    $content = strtr($content, $array);

    return $content;
}

add_filter('the_content', 'shortcode_empty_paragraph_fix', 10);

?>