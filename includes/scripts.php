<?php
if (!defined('ABSPATH')) exit;

/**
 * Enqueue admin scripts and styles (only for Portfolio CPT)
 */
function cp_enqueue_admin_scripts($hook)
{
    global $post_type;

    // Load only on Portfolio post type edit screen
    if (($hook === 'post-new.php' || $hook === 'post.php') && $post_type === 'portfolio') {
        wp_enqueue_media(); // Enable WP Media Uploader

        wp_enqueue_script(
            'cp-admin-js',
            plugin_dir_url(__FILE__) . '../assets/js/admin.js',
            ['jquery'],
            '1.0.0',
            true
        );

        wp_enqueue_style(
            'cp-admin-css',
            plugin_dir_url(__FILE__) . '../assets/css/admin.css',
            [],
            '1.0.0'
        );
    }
}
add_action('admin_enqueue_scripts', 'cp_enqueue_admin_scripts');


/**
 * Enqueue frontend scripts and styles (for portfolio shortcode)
 */
function up_portfolio_enqueue_assets()
{
    if (is_admin()) return;

    // Go up one level from includes/ to plugin root
    $plugin_url = plugin_dir_url(dirname(__FILE__));

    wp_enqueue_style(
        'up-portfolio-styles',
        $plugin_url . 'assets/css/up-portfolio.css',
        [],
        '1.1.0'
    );

    wp_enqueue_script(
        'up-portfolio-js',
        $plugin_url . 'assets/js/up-portfolio.js',
        ['jquery'],
        '1.1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'up_portfolio_enqueue_assets');
