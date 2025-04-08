<?php

if(!defined('ABSPATH')){
    die('Access denied');
 }

add_action('init', 'register_custom_post_type');

function register_custom_post_type(){
    $args= [
        'public' => true,
        'archive' => true,
        'labels' => [
            'name' => 'API Data',
            'singular_name' => 'API Data',
        ],
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => false
        ),
        'map_meta_cap' => true,
    ];
    register_post_type('api-data', $args);
}