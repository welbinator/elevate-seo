<?php
namespace Elevate_SEO\Frontend;

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', __NAMESPACE__ . '\\handle_404_redirect' );

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

    // Make sure it's a valid URL
    $target = esc_url_raw( $options['redirect_404_target'] );

    if ( ! empty( $target ) ) {
        wp_redirect( $target, 301 );
        exit;
    }
}
