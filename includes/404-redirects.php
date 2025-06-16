<?php
namespace Elevate_SEO\Frontend;

defined( 'ABSPATH' ) || exit;

// Run after WP has parsed the query, giving core redirects time to resolve
add_action( 'wp', __NAMESPACE__ . '\\handle_404_redirect', 99 );

function handle_404_redirect() {
    if ( ! is_404() ) {
        return;
    }

    $options = get_option( 'elevate_seo_options' );

    if (
        empty( $options['redirect_404'] ) ||
        empty( $options['redirect_404_target'] )
    ) {
        return;
    }

    $target = trim( $options['redirect_404_target'] );

    // Allow relative URLs (e.g. /custom-404) or full URLs
    if ( ! empty( $target ) ) {
        // If target starts with /, treat it as relative and convert to absolute
        if ( strpos( $target, '/' ) === 0 ) {
            $target = home_url( $target );
        }

        // Ensure it's a valid URL
        $target = esc_url_raw( $target );

        if ( ! empty( $target ) ) {
            wp_redirect( $target, 301 );
            exit;
        }
    }
}
