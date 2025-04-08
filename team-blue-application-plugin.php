<?php

/**
 * 
 * Plugin Name: Team Blue Appliction Plugin
 * Description: A small WordPress plugin as part of the application process. 
 * Version: 1.0.0
 * Text Domain: application-plugin
 * 
 */

if(!defined('ABSPATH')){
    die('Access denied');
 }

 if (!class_exists('ApplicationPlugin')){

    class ApplicationPlugin{

        public function __construct()
        {
            define('APPLICATION_PLUGIN_PATH', plugin_dir_path(__FILE__));
            require_once( APPLICATION_PLUGIN_PATH . '/vendor/autoload.php' );
        }

    }

    $applicationPlugin = new ApplicationPlugin;

 }