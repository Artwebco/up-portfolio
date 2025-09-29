<?php
if (!defined('ABSPATH')) exit;

/* --------------------
   SHORTCODE TO DISPLAY PORTFOLIO
-------------------- */
function cp_portfolio_shortcode($atts)
{
    $atts = shortcode_atts(['posts_per_page' => 6], $atts, 'portfolio');

    $query = new WP_Query([
        'post_type' => 'portfolio',
        'posts_per_page' => intval($atts['posts_per_page']),
    ]);

    if (!$query->have_posts()) return '<p>No portfolio items found.</p>';

    $output = '<div class="cp-portfolio-grid">';

    while ($query->have_posts()) {
        $query->the_post();
        $role = get_post_meta(get_the_ID(), '_cp_role', true);
        $skills = get_post_meta(get_the_ID(), '_cp_skills', true);
        $project_link = get_post_meta(get_the_ID(), '_cp_project_link', true);
        $screenshots = get_post_meta(get_the_ID(), '_cp_screenshots', true) ?: [];

        $output .= '<div class="cp-portfolio-item">';
        $output .= '<h3>' . get_the_title() . '</h3>';
        if ($role) $output .= '<p><strong>Role:</strong> ' . esc_html($role) . '</p>';
        if ($skills) {
            $tags = explode(',', $skills);
            $output .= '<p><strong>Skills:</strong> ' . implode(', ', array_map('esc_html', $tags)) . '</p>';
        }
        if ($project_link) $output .= '<p><a href="' . esc_url($project_link) . '" target="_blank">Visit Project</a></p>';

        // Screenshots gallery
        if (!empty($screenshots)) {
            $output .= '<div class="cp-screenshots">';
            foreach ($screenshots as $img_id) {
                $output .= wp_get_attachment_image($img_id, 'medium');
            }
            $output .= '</div>';
        }

        $output .= '</div>'; // end item
    }

    $output .= '</div>'; // end grid
    wp_reset_postdata();

    return $output;
}
add_shortcode('portfolio', 'cp_portfolio_shortcode');
