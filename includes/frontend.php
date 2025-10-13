<?php
if (!defined('ABSPATH')) exit;

/* ---------------------------
   Portfolio Shortcode
--------------------------- */
function up_portfolio_shortcode($atts)
{
    $atts = shortcode_atts([
        'posts_per_page' => -1,
    ], $atts, 'up_portfolio');

    $query = new WP_Query([
        'post_type'      => 'portfolio',
        'posts_per_page' => intval($atts['posts_per_page']),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if (!$query->have_posts()) return '<p>No projects found.</p>';

    $output = '<div class="section-grid">';

    while ($query->have_posts()) {
        $query->the_post();

        // Meta fields
        $thumbnail_id       = get_post_meta(get_the_ID(), '_cp_thumbnail', true);
        $thumbnail_url      = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : '';
        $role               = get_post_meta(get_the_ID(), '_cp_role', true);
        $skills             = get_post_meta(get_the_ID(), '_cp_skills', true);
        $short_description  = get_post_meta(get_the_ID(), '_cp_short_description', true);
        $period             = get_post_meta(get_the_ID(), '_cp_period', true);

        $output .= '<div class="card card--gradient up-portfolio-item">';

        // Thumbnail
        if ($thumbnail_url) {
            $output .= '<div class="up-portfolio-thumb">';
            $output .= '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '">';
            $output .= '</div>';
        }

        // Content wrapper
        $output .= '<div class="up-portfolio-content">';

        // Flex row for description and period
        if (!empty($short_description) || !empty($period)) {
            $output .= '<div class="up-portfolio-meta-row">';
            if (!empty($short_description)) {
                $output .= '<span class="up-portfolio-short-description">' . esc_html($short_description) . '</span>';
            }
            if (!empty($period)) {
                $output .= '<span class="up-portfolio-period">' . esc_html($period) . '</span>';
            }
            $output .= '</div>';
        }

        // Title
        $output .= '<h3 class="up-portfolio-title">' . esc_html(get_the_title()) . '</h3>';

        // Skills
        if (!empty($skills)) {
            $skills_array = preg_split('/[,|;]/', $skills);
            $output .= '<div class="card__tags">';
            foreach ($skills_array as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $output .= '<span class="tag">' . esc_html($skill) . '</span>';
                }
            }
            $output .= '</div>';
        }

        // View Project button
        $output .= '<a href="' . get_permalink() . '" class="btn btn--secondary">View Project</a>';

        $output .= '</div>'; // .up-portfolio-content
        $output .= '</div>'; // .up-portfolio-item
    }

    $output .= '</div>'; // .section-grid

    wp_reset_postdata();
    return $output;
}
add_shortcode('up_portfolio', 'up_portfolio_shortcode');
