<?php

class UIC_Media {

    public function __construct() {
        // No need to initialize anything for this class as of now
    }

    // Get the list of unused images
    public static function get_unused_images() {
        global $wpdb;

        // List of images in the media library
        $images = [];

        $media_query = new WP_Query([
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
        ]);

        while ($media_query->have_posts()) {
            $media_query->the_post();
            $media_id = get_the_ID();
            $media_url = wp_get_attachment_url($media_id);
            $media_title = get_the_title($media_id);

            // Check if the image is used in any post, page, or product
            $is_used = UIC_Media::is_image_used($media_url);
            
            if (!$is_used) {
                $images[] = [
                    'id' => $media_id,
                    'url' => $media_url,
                    'title' => $media_title,
                ];
            }
        }

        wp_reset_postdata();

        return $images;
    }

    // Check if the image is used in posts, pages, or products
    public static function is_image_used($image_url) {
        global $wpdb;

        // Check if the image is used in posts/pages
        $post_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_content LIKE %s",
            '%' . $wpdb->esc_like($image_url) . '%'
        ));
        
        if ($post_check > 0) {
            return true;
        }

        // Check if the image is used as a featured image
        $featured_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_thumbnail = %s",
            $image_url
        ));

        if ($featured_check > 0) {
            return true;
        }

        return false;
    }
}
