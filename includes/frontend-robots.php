<?php
namespace Elevate_SEO\Frontend;

defined( 'ABSPATH' ) || exit;

add_filter( 'robots_txt', __NAMESPACE__ . '\\override_robots_txt', 10, 2 );

function override_robots_txt( $output, $public ) {
	$custom_robots = get_option( 'elevate_seo_robots_txt', '' );

	if ( $custom_robots ) {
		$output = trim( $custom_robots );
	}

	return $output;
}

