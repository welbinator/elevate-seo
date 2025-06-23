<?php
namespace Elevate_SEO\Frontend;

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', __NAMESPACE__ . '\\output_canonical_tag' );

function output_canonical_tag() {
    if ( ! is_singular( 'page' ) ) {
        return;
    }

    $options = get_option( 'elevate_seo_options' );
    if ( empty( $options['enable_canonical_pages'] ) ) {
        return;
    }

    $canonical_url = get_permalink();
    if ( $canonical_url ) {
        echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
    }
}
