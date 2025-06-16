<?php
namespace Elevate_SEO\Redirects;

defined( 'ABSPATH' ) || exit;

add_action( 'post_updated', __NAMESPACE__ . '\\maybe_save_slug_redirect', 10, 3 );
add_action( 'parse_request', __NAMESPACE__ . '\\handle_slug_redirect' );

/**
 * Saves a redirect from old slug to new slug when post slug changes.
 */
function maybe_save_slug_redirect( $post_ID, $post_after, $post_before ) {
	if ( wp_is_post_revision( $post_ID ) || $post_after->post_status !== 'publish' ) {
		return;
	}

	if ( $post_before->post_name !== $post_after->post_name ) {
		$old_slug = $post_before->post_name;
		$old_url  = home_url( '/' . $old_slug . '/' );
		$new_url  = get_permalink( $post_ID );

		$redirects = get_option( 'elevate_seo_redirects', [] );

		if ( ! isset( $redirects[ $old_url ] ) ) {
			$redirects[ $old_url ] = $new_url;
			update_option( 'elevate_seo_redirects', $redirects );
		}
	}
}

/**
 * Checks if the current URL matches a stored redirect and performs the redirect.
 */
function handle_slug_redirect( $wp ) {
	if ( is_admin() ) return;

	$request_uri = home_url( untrailingslashit( $wp->request ) . '/' );
	$redirects   = get_option( 'elevate_seo_redirects', [] );

	if ( isset( $redirects[ $request_uri ] ) ) {
		wp_redirect( $redirects[ $request_uri ], 301 );
		exit;
	}
}
