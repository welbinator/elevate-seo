<?php

namespace Elevate_SEO\Admin;

defined( 'ABSPATH' ) || exit;

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_styles' );

function enqueue_admin_styles( $hook ) {
	$allowed_hooks = [
		'toplevel_page_elevate-seo',
		'seo_page_elevate-seo-cpts',
		'seo_page_elevate-seo-robots',
	];

	if ( ! in_array( $hook, $allowed_hooks, true ) ) {
		return;
	}

	wp_enqueue_style(
		'elevate-seo-admin-styles',
		plugin_dir_url( __DIR__ ) . 'assets/css/admin-styles.css',
		[],
		filemtime( plugin_dir_path( __DIR__ ) . 'assets/css/admin-styles.css' )
	);
}

