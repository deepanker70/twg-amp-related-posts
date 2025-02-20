<?php
/**
 * Plugin Name: TWG AMP Related Posts
 * Description: Display related posts on AMP pages with images and titles. Includes settings for customization.
 * Version: 1.1
 * Author: Deepanker Verma
 * Author URI: https://thewpguides.com
 * License: GPL2
 * Text Domain: twg-amp-related-posts
 */

// Load Plugin Text Domain for Translations
function twg_amp_related_posts_load_textdomain() {
    load_plugin_textdomain('twg-amp-related-posts', false, dirname(plugin_basename(__FILE__)));
}
add_action('plugins_loaded', 'twg_amp_related_posts_load_textdomain');

// Add Admin Settings Page
function twg_amp_related_posts_menu() {
    add_options_page(
        __('TWG AMP Related Posts', 'twg-amp-related-posts'),
        __('AMP Related Posts', 'twg-amp-related-posts'),
        'manage_options',
        'twg-amp-related-posts',
        'twg_amp_related_posts_settings_page'
    );
}
add_action('admin_menu', 'twg_amp_related_posts_menu');

// Register Plugin Settings
function twg_amp_related_posts_register_settings() {
    register_setting('twg_amp_related_posts_options', 'twg_amp_related_posts_count', 'intval');
    register_setting('twg_amp_related_posts_options', 'twg_amp_related_posts_orderby', 'sanitize_text_field');
    register_setting('twg_amp_related_posts_options', 'twg_amp_related_posts_show_thumbnail', 'sanitize_text_field');

    add_settings_section('twg_amp_related_posts_main_section', __('Settings', 'twg-amp-related-posts'), '__return_false', 'twg-amp-related-posts');

    add_settings_field('twg_amp_related_posts_count', __('Number of Related Posts:', 'twg-amp-related-posts'), 'twg_amp_related_posts_count_field', 'twg-amp-related-posts', 'twg_amp_related_posts_main_section');
    add_settings_field('twg_amp_related_posts_orderby', __('Order By:', 'twg-amp-related-posts'), 'twg_amp_related_posts_orderby_field', 'twg-amp-related-posts', 'twg_amp_related_posts_main_section');
    add_settings_field('twg_amp_related_posts_show_thumbnail', __('Show Thumbnails:', 'twg-amp-related-posts'), 'twg_amp_related_posts_show_thumbnail_field', 'twg-amp-related-posts', 'twg_amp_related_posts_main_section');
}
add_action('admin_init', 'twg_amp_related_posts_register_settings');

// Plugin Settings Page
function twg_amp_related_posts_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('TWG AMP Related Posts Settings', 'twg-amp-related-posts'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('twg_amp_related_posts_options');
            do_settings_sections('twg-amp-related-posts');
            submit_button(__('Save Settings', 'twg-amp-related-posts'));
            ?>
        </form>
    </div>
    <?php
}

// Settings Fields
function twg_amp_related_posts_count_field() {
    $value = get_option('twg_amp_related_posts_count', 5);
    echo '<input type="number" name="twg_amp_related_posts_count" value="' . esc_attr($value) . '" min="1">';
}

function twg_amp_related_posts_orderby_field() {
    $value = get_option('twg_amp_related_posts_orderby', 'recent');
    ?>
    <select name="twg_amp_related_posts_orderby">
        <option value="recent" <?php selected($value, 'recent'); ?>><?php esc_html_e('Recent', 'twg-amp-related-posts'); ?></option>
        <option value="random" <?php selected($value, 'random'); ?>><?php esc_html_e('Random', 'twg-amp-related-posts'); ?></option>
    </select>
    <?php
}

function twg_amp_related_posts_show_thumbnail_field() {
    $value = get_option('twg_amp_related_posts_show_thumbnail', 'yes');
    ?>
    <input type="checkbox" name="twg_amp_related_posts_show_thumbnail" value="yes" <?php checked($value, 'yes'); ?>>
    <?php esc_html_e('Enable thumbnails', 'twg-amp-related-posts'); ?>
    <?php
}

// Add CSS for AMP Pages
function twg_amp_related_posts_inline_styles() {
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        $css = "
            .twg-amp-related-posts {
                margin-top: 30px;
                padding: 10px;
                border-top: 2px solid #ddd;
            }
            .twg-amp-related-posts h3 {
                font-size: 1.2em;
                margin-bottom: 10px;
            }
            .related-post-item {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                padding: 10px;
                border: 1px solid #f0f0f0;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            .related-post-image {
                margin-right: 15px;
                width: 75px;
                height: 75px;
            }
            .related-post-title {
                flex-grow: 1;
            }
            .related-post-title a {
                color: #333;
                text-decoration: none;
            }
            .related-post-title a:hover {
                color: #0073e6;
                text-decoration: underline;
            }
        ";
        echo '<style amp-custom>' . esc_html($css) . '</style>';
    }
}
add_action('amp_post_template_head', 'twg_amp_related_posts_inline_styles');

// Display Related Posts on AMP Pages
function twg_amp_related_posts($content) {
    if (is_single() && function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        global $post;

        $count = get_option('twg_amp_related_posts_count', 5);
        $orderby = get_option('twg_amp_related_posts_orderby', 'recent') === 'random' ? 'rand' : 'date';
        $show_thumbnail = get_option('twg_amp_related_posts_show_thumbnail', 'yes') === 'yes';

        $related_posts = get_posts(array(
            'category__in'   => wp_get_post_categories($post->ID),
            'post__not_in'   => array($post->ID),
            'posts_per_page' => (int) $count,
            'orderby'        => $orderby,
        ));

        if ($related_posts) {
            ob_start();
            ?>
            <div class="twg-amp-related-posts">
                <h3><?php esc_html_e('Related Posts', 'twg-amp-related-posts'); ?></h3>
                <?php foreach ($related_posts as $related_post) : ?>
                    <div class="related-post-item">
                        <?php if ($show_thumbnail && has_post_thumbnail($related_post->ID)) : ?>
                            <div class="related-post-image">
                                <?php echo wp_get_attachment_image(get_post_thumbnail_id($related_post->ID), 'thumbnail', false, array('class' => 'amp-img')); ?>
                            </div>
                        <?php endif; ?>
                        <div class="related-post-title">
                            <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>">
                                <p><strong><?php echo esc_html($related_post->post_title); ?></strong></p>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            $content .= ob_get_clean();
        }
    }
    return $content;
}
add_filter('the_content', 'twg_amp_related_posts');
