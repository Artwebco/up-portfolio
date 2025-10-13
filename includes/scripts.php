<?php
if (!defined('ABSPATH')) exit;

function cp_enqueue_admin_scripts($hook) {
    global $post_type;

    // Load only on Portfolio post type edit screen
    if (($hook === 'post-new.php' || $hook === 'post.php') && $post_type === 'portfolio') {
        wp_enqueue_media(); // Enable WP Media Uploader

        wp_enqueue_script(
            'cp-admin-js',
            plugin_dir_url(__FILE__) . '../assets/admin.js',
            ['jquery'],
            '1.0.0',
            true
        );

        wp_enqueue_style(
            'cp-admin-css',
            plugin_dir_url(__FILE__) . '../assets/admin.css',
            [],
            '1.0.0'
        );
    }
}
add_action('admin_enqueue_scripts', 'cp_enqueue_admin_scripts');
