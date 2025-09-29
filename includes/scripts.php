<?php
if (! defined('ABSPATH')) exit;

function cp_enqueue_admin_scripts($hook)
{
    global $post_type;
    if ($hook === 'post-new.php' || $hook === 'post.php') {
        if ($post_type === 'portfolio') {
            wp_enqueue_media();
            wp_enqueue_script('cp-admin-js', plugin_dir_url(__FILE__) . '../assets/admin.js', ['jquery'], '1.0', true);
        }
    }
}
add_action('admin_enqueue_scripts', 'cp_enqueue_admin_scripts');
