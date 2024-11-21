<?php
/**
 * Plugin Name: TWG AMP Related Posts
 * Description: Display related posts on AMP pages with images and titles.
 * Version: 1.0
 * Author: Deepanker Verma
 */

// Enqueue inline CSS for AMP pages
function twg_amp_related_posts_inline_styles() {
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        // Inline CSS
        $css = "
            .twg-amp-related-posts {
                margin-top: 30px;
                padding: 10px;
                border-top: 2px solid #ddd;
            }
            
            /* Title for related posts */
            .twg-amp-related-posts h3 {
                font-size: 1.2em;
                margin-bottom: 10px;
            }
            
            /* Each related post item */
            .related-post-item {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                padding: 10px;
                border: 1px solid #f0f0f0;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            
            /* Image on the left */
            .related-post-image {
                margin-right: 15px;
                width: 75px;  /* You can adjust the size as per your preference */
                height: 75px; /* Keep aspect ratio consistent */
            }
            
            /* Image styling */
            .related-post-image img,
            .related-post-image .amp-img {
                max-width: 100%;
                height: auto;
                border-radius: 5px;
            }
            
            /* Title on the right */
            .related-post-title {
                flex-grow: 1;
            }
            
            .related-post-title a {
                color: #333;
                text-decoration: none;
            }
            
            .related-post-title p {
                font-size: 1em;
                margin: 0;
            }
            
            /* Hover effect for the link */
            .related-post-title a:hover {
                color: #0073e6;
                text-decoration: underline;
            }
        ";
        
        // Output the CSS in the <head> section
        echo '<style amp-custom>' . $css . '</style>';
    }
}

add_action('amp_post_template_head', 'twg_amp_related_posts_inline_styles');

// Display related posts on AMP pages
function twg_amp_related_posts($content) {
    if (is_single() && function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        global $post;

        // Get related posts based on categories
        $related_posts = get_posts(array(
            'category__in'   => wp_get_post_categories($post->ID),
            'post__not_in'   => array($post->ID),
            'posts_per_page' => 5, // Number of related posts to display
        ));

        if ($related_posts) {
            $related_content = '<div class="twg-amp-related-posts">';
            $related_content .= '<h3>' . esc_html__('Related Posts', 'twg-amp-related-posts') . '</h3>';
            foreach ($related_posts as $related_post) {
                $related_content .= '<div class="related-post-item">';
                
                // Image on the left
                if (has_post_thumbnail($related_post->ID)) {
                    $related_content .= '<div class="related-post-image">';
                    $related_content .= get_the_post_thumbnail($related_post->ID, 'thumbnail', array('class' => 'amp-img'));
                    $related_content .= '</div>';
                } else {
                    // Add a placeholder image if no thumbnail is available
                    $placeholder_url = plugin_dir_url(__FILE__) . 'assets/placeholder.jpg';
                    $related_content .= '<div class="related-post-image">';
                    $related_content .= '<img src="' . esc_url($placeholder_url) . '" alt="No image" class="amp-img">';
                    $related_content .= '</div>';
                }
                
                // Title on the right
                $related_content .= '<div class="related-post-title">';
                $related_content .= '<a href="' . esc_url(get_permalink($related_post->ID)) . '">';
                $related_content .= '<p><strong>' . esc_html($related_post->post_title) . '</strong></p>';
                $related_content .= '</a>';
                $related_content .= '</div>';

                $related_content .= '</div>'; // Close related-post-item div
            }
            $related_content .= '</div>'; // Close twg-amp-related-posts div

            // Append related posts to the content
            $content .= $related_content;
        }
    }

    return $content;
}

add_filter('the_content', 'twg_amp_related_posts');
?>