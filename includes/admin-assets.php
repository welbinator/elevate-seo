<?php

add_action( 'admin_enqueue_scripts', function( $hook ) {
    // Load on Elevate SEO settings pages OR post editor screens
    if (
        strpos( $hook, 'elevate-seo' ) !== false ||
        $hook === 'post.php' ||
        $hook === 'post-new.php'
    ) {
        wp_enqueue_media(); // WordPress Media Library
        wp_enqueue_script(
            'elevate-seo-media-upload',
            ELEVATE_SEO_URL . 'assets/js/media-upload.js',
            ['jquery'],
            ELEVATE_SEO_VERSION,
            true
        );
    }
});
