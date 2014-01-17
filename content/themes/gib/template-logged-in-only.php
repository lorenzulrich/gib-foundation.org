<?php
/*
Template Name: Restricted page (logged in only)
*/

 if ( ! is_user_logged_in() ) {

     wp_redirect( add_query_arg( "redirect_to", urlencode( add_query_arg( '', '' ) ), site_url('/wp-login.php') ) );
     exit;
 }

get_template_part( 'page-full' );