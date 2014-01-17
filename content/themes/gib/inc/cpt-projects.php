<?php

function geb_cpt_projects_register() {

    $args = array(

        'public' => true,
        'label' => 'GEB Projects',
        'rewrite' => array( 'slug' => 'projects', 'with_front' => true ),
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'has_archive' => true

    );

    register_post_type( 'geb_projects', $args );

}

add_action( 'init', 'geb_cpt_projects_register' );

function geb_cpt_projects_registertax() {

    register_taxonomy('geb_projects_cat', 'geb_projects', array(

        'label' => 'Project Categories',
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'projects-category')

    ));

}

add_action( 'init', 'geb_cpt_projects_registertax' );

function geb_cpt_projects_industry_registertax() {

    register_taxonomy('geb_projects_industry_cat', 'geb_projects', array(

        'label' => 'Industry Categories',
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,

    ));

}

add_action( 'init', 'geb_cpt_projects_industry_registertax' );