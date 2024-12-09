jQuery(document).ready(function($) {
    // Handle the deletion of selected images
    $('form').on('submit', function(e) {
        e.preventDefault();

        var selected_images = [];
        $('input[name="unused_images[]"]:checked').each(function() {
            selected_images.push($(this).val());
        });

        if (selected_images.length === 0) {
            alert("Please select at least one image to delete.");
            return;
        }

        var nonce = '<?php echo wp_create_nonce('uic_nonce'); ?>';

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'uic_delete_images',
                nonce: nonce,
                image_ids: selected_images
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });
});
