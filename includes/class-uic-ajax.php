<?php

class UIC_Ajax {

    public function __construct() {
        add_action('wp_ajax_uic_delete_images', [$this, 'delete_unused_images']);
    }

    // Handle the AJAX request to delete unused images
    public function delete_unused_images() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'uic_nonce')) {
            wp_send_json_error(['message' => 'Nonce verification failed']);
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have sufficient permissions.']);
        }

        if (empty($_POST['image_ids'])) {
            wp_send_json_error(['message' => 'No images selected for deletion.']);
        }

        // Loop through and delete the images
        foreach ($_POST['image_ids'] as $image_id) {
            wp_delete_attachment($image_id, true);
        }

        wp_send_json_success(['message' => 'Images successfully deleted.']);
    }
}
