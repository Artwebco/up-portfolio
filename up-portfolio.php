<?php
/*
Plugin Name: Up Portfolio
Description: Custom portfolio plugin
Version: 1.0
Author: Nikola Nikovski
*/

if (!defined('ABSPATH')) exit;

// CPT is needed everywhere
require_once plugin_dir_path(__FILE__) . 'includes/cpt.php';

// Admin-only files
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
    require_once plugin_dir_path(__FILE__) . 'includes/scripts.php';
}

// Frontend files
require_once plugin_dir_path(__FILE__) . 'includes/frontend.php';
