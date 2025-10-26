<?php
if (!defined('ABSPATH')) exit;

function up_portfolio_shortcode($atts)
{
    $atts = shortcode_atts([
        'posts_per_page' => -1,
    ], $atts, 'up_portfolio');

    $query = new WP_Query([
        'post_type' => 'portfolio',
        'posts_per_page' => intval($atts['posts_per_page']),
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    if (!$query->have_posts()) return '<p>No projects found.</p>';

    $output = '<div class="section-grid">';

    while ($query->have_posts()) {
        $query->the_post();

        $thumbnail_id  = get_post_meta(get_the_ID(), '_cp_thumbnail', true);
        $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : '';
        $role          = get_post_meta(get_the_ID(), '_cp_role', true);
        $skills        = get_post_meta(get_the_ID(), '_cp_skills', true);
        $screenshots   = get_post_meta(get_the_ID(), '_cp_screenshots', true);
        $period        = get_post_meta(get_the_ID(), '_cp_period', true);
        $short_description  = get_post_meta(get_the_ID(), '_cp_short_description', true);
        $project_link    = get_post_meta(get_the_ID(), '_cp_project_link', true);

        $output .= '<div class="card card--gradient up-portfolio-item">';

        if ($thumbnail_url) {
            $output .= '<div class="up-portfolio-thumb"><img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
        }

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

        $output .= '<h3 class="up-portfolio-title">' . esc_html(get_the_title()) . '</h3>';


        // Build skills HTML (same as frontend)
        $skills_html = '';
        if (!empty($skills)) {
            $skills_array = preg_split('/[,|;]/', $skills);
            $skills_html .= '<div class="card__tags">';
            foreach ($skills_array as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $skills_html .= '<span class="tag">' . esc_html($skill) . '</span>';
                }
            }
            $skills_html .= '</div>';
            $output .= $skills_html; // display in card
        }

        // Screenshots
        $screenshots_array = !empty($screenshots) ? (array) $screenshots : [];
        $screenshots_urls = [];
        foreach ($screenshots_array as $id) {
            if (is_numeric($id)) {
                $screenshots_urls[] = wp_get_attachment_image_url($id, 'full');
            } else {
                $screenshots_urls[] = esc_url($id);
            }
        }

        // Description and date
        $description = get_the_content();
        $project_date = get_the_date('F j, Y');

        // Button for modal (pass all needed data)
        $output .= '<button 
                class="btn btn--secondary up-view-project" 
                data-id="' . get_the_ID() . '" 
                data-title="' . esc_attr(get_the_title()) . '" 
                data-role="' . esc_attr($role) . '" 
                data-description="' . esc_attr($description) . '" 
                data-skills-html=\'' . esc_attr($skills_html) . '\'
                data-date="' . esc_attr($project_date) . '"
                data-screenshots=\'' . json_encode($screenshots_urls) . '\'
                data-link="' . esc_url($project_link) . '"
            >
        View Project
        </button>';

        $output .= '</div>'; // .up-portfolio-content
        $output .= '</div>'; // .up-portfolio-item
    }

    $output .= '</div>'; // .section-grid

    // Modal markup
    $output .= '
    <div id="up-modal-overlay" class="up-modal-overlay" style="display:none;">
        <div class="up-modal">
            <div class="up-modal-header">
                <h3 class="up-modal-title"></h3>
                    <a href="#" target="_blank" class="up-modal-link" style="display:none;">
                      See Live Site <i class="fa-regular fa-up-right-from-square"></i>
                    </a>
                <button class="up-modal-close" aria-label="Close">&times;</button>
            </div>
            <div class="up-modal-body">
                <div class="up-modal-left"></div>
                <div class="up-modal-right"></div>
            </div>
        </div>
    </div>';

    wp_reset_postdata();
    return $output;
}
add_shortcode('up_portfolio', 'up_portfolio_shortcode');
