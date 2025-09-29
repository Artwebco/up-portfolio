<?php
if (! defined('ABSPATH')) exit;

function cp_register_portfolio_cpt()
{
    $labels = [
        'name' => 'Portfolios',
        'singular_name' => 'Portfolio',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Portfolio',
        'edit_item' => 'Edit Portfolio',
        'new_item' => 'New Portfolio',
        'view_item' => 'View Portfolio',
        'search_items' => 'Search Portfolios',
        'not_found' => 'No portfolios found',
        'not_found_in_trash' => 'No portfolios found in Trash',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ];

    register_post_type('portfolio', $args);
}
add_action('init', 'cp_register_portfolio_cpt');
