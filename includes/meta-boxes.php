<?php
if (!defined('ABSPATH')) exit;

/* --------------------
   ADD META BOXES
-------------------- */
function cp_add_portfolio_meta_boxes()
{
    add_meta_box(
        'cp_portfolio_details',
        'Portfolio Details',
        'cp_portfolio_details_callback',
        'portfolio',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cp_add_portfolio_meta_boxes');

/* --------------------
   META BOX HTML
-------------------- */
function cp_portfolio_details_callback($post)
{
    wp_nonce_field('cp_save_portfolio_details', 'cp_portfolio_nonce');

    // Project role, skills, link
    $period = get_post_meta($post->ID, '_cp_period', true);
    $short_description = get_post_meta($post->ID, '_cp_short_description', true);
    $role = get_post_meta($post->ID, '_cp_role', true);
    $skills = get_post_meta($post->ID, '_cp_skills', true);
    $project_link = get_post_meta($post->ID, '_cp_project_link', true);
    $screenshots = get_post_meta($post->ID, '_cp_screenshots', true) ?: [];
    $thumbnail_id = get_post_meta($post->ID, '_cp_thumbnail', true);
    $thumbnail_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';

    // Role, skills, link
    echo '<p><label>Project Period: <input type="text" name="cp_period" value="' . esc_attr($period) . '" style="width:100%;" placeholder="e.g. Jan 2024 - Mar 2024" /></label></p>';
    echo '<p><label>What I Did: <input type="text" name="cp_short_description" value="' . esc_attr($short_description) . '" style="width:100%;" placeholder="Performance Optimization, Custom Theme Development, etc." /></label></p>';
    echo '<p><label>Project Role: <input type="text" name="cp_role" value="' . esc_attr($role) . '" style="width:100%;" /></label></p>';
    echo '<p><label>Skills (comma separated): <input type="text" name="cp_skills" value="' . esc_attr($skills) . '" style="width:100%;" /></label></p>';
    echo '<p><label>Project Link: <input type="url" name="cp_project_link" value="' . esc_attr($project_link) . '" style="width:100%;" /></label></p>';

    // Screenshots
    echo '<p>Screenshots:</p><div id="cp_screenshots_container">';
    foreach ($screenshots as $image_id) {
        $img_url = wp_get_attachment_url($image_id);
        echo '<div class="cp-screenshot">
                <img src="' . esc_url($img_url) . '" style="max-width:150px;">
                <input type="hidden" name="cp_screenshots[]" value="' . esc_attr($image_id) . '">
                <button class="cp-remove-screenshot">Remove</button>
              </div>';
    }
    echo '</div><button id="cp_add_screenshot" class="button">Add Screenshot</button>';

    // Thumbnail (now safely inside the callback)
    echo '<p><label>Project Thumbnail:</label></p>';
    echo '<div id="cp_thumbnail_wrapper">';
    if ($thumbnail_url) {
        echo '<img src="' . esc_url($thumbnail_url) . '" style="max-width:150px; display:block; margin-bottom:10px;">';
    }
    echo '<input type="hidden" name="cp_thumbnail" value="' . esc_attr($thumbnail_id) . '">';
    echo '<button id="cp_add_thumbnail" class="button">Select Thumbnail</button> ';
    echo '<button id="cp_remove_thumbnail" class="button" ' . ($thumbnail_id ? '' : 'style="display:none;"') . '>Remove</button>';
    echo '</div><hr>';
}


/* --------------------
   SAVE META BOX DATA
-------------------- */
function cp_save_portfolio_details($post_id)
{
    if (!isset($_POST['cp_portfolio_nonce']) || !wp_verify_nonce($_POST['cp_portfolio_nonce'], 'cp_save_portfolio_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    update_post_meta($post_id, '_cp_role', sanitize_text_field($_POST['cp_role']));
    update_post_meta($post_id, '_cp_skills', sanitize_text_field($_POST['cp_skills']));
    update_post_meta($post_id, '_cp_project_link', esc_url_raw($_POST['cp_project_link']));
    update_post_meta($post_id, '_cp_screenshots', array_map('intval', $_POST['cp_screenshots'] ?? []));
    update_post_meta($post_id, '_cp_thumbnail', intval($_POST['cp_thumbnail'] ?? 0));
    update_post_meta($post_id, '_cp_short_description', sanitize_text_field($_POST['cp_short_description']));
    update_post_meta($post_id, '_cp_period', sanitize_text_field($_POST['cp_period']));
}

add_action('save_post', 'cp_save_portfolio_details');
