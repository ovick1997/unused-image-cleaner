<?php

class UIC_Admin {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    // Add a menu in the WordPress Dashboard
    public function add_menu() {
        add_menu_page(
            'Unused Image Cleaner',       // Page title
            'Unused Images',              // Menu title
            'manage_options',             // Capability required
            'unused-image-cleaner',       // Menu slug
            [$this, 'display_page'],      // Callback function to display the page
            'dashicons-images-alt2',            // Icon for the menu
            6                             // Position in the menu
        );
    }

    // Enqueue assets (CSS/JS) for admin page
    public function enqueue_assets($hook) {
        if ($hook !== 'toplevel_page_unused-image-cleaner') {
            return;
        }
        wp_enqueue_style('uic-styles', plugin_dir_url(__FILE__) . '../assets/css/uic-styles.css');
        wp_enqueue_script('uic-scripts', plugin_dir_url(__FILE__) . '../assets/js/uic-scripts.js', ['jquery'], null, true);
    }

    // Display the page content
    public function display_page() {
        $unused_images = UIC_Media::get_unused_images();

        ?>
        <div class="wrap uic-admin-wrap">
            <h1 class="wp-heading-inline">Unused Images</h1>
            <form method="POST">
                <?php if (empty($unused_images)) : ?>
                    <p>No unused images found.</p>
                <?php else: ?>
                    <div class="uic-image-list">
                        <?php foreach ($unused_images as $image): ?>
                            <div class="uic-image-item">
                                
                                <div class="uic-image-preview">
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="" />
                                </div>
                                <div class="uic-image-details">
                                    <p class="uic-image-id-text"><span>Image ID:</span> <?php echo esc_html($image['id']); ?></p>
                                    <p class="uic-mage-title" title="<?php echo esc_html($image['title']); ?>"><?php echo esc_html($image['title']); ?></p>
                                </div>
                                <div class="uic-image-actions">
                                    <input type="checkbox" name="delete_images[]" value="<?php echo esc_attr($image['id']); ?>" />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="submit" name="delete_unused_images" class="button button-danger delete-img-selected-btn" value="Delete Selected Images" />
                <?php endif; ?>
            </form>
        </div>
        <?php
    }
}
