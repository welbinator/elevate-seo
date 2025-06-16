<?php
namespace Elevate_SEO\Frontend;

use function Elevate_SEO\Admin\parse_title_format;

defined( 'ABSPATH' ) || exit;

/**
 * Outputs meta tags for SEO, OG, and Twitter cards.
 */
function output_meta_tags() {
	if ( is_admin() || ! is_singular() ) {
		return;
	}

	add_filter( 'document_title_parts', __NAMESPACE__ . '\\override_title' );

	$post_id       = get_the_ID();
	$post_type     = get_post_type( $post_id );

	$global_options   = get_option( 'elevate_seo_options', [] );
	$cpt_all_options  = get_option( 'elevate_seo_cpt_options', [] );
	$cpt_options      = $cpt_all_options[ $post_type ] ?? [];

	// Use CPT override if available, else global fallback
	$title_format     = $cpt_options['title_format']         ?? $global_options['default_title_format']     ?? '%title% | %sitename%';
	$meta_description = get_post_meta( $post_id, '_elevate_seo_meta_description', true )
	                 ?: $cpt_options['meta_description']     ?? $global_options['default_meta_description'] ?? '';
	$og_image         = get_post_meta( $post_id, '_elevate_seo_og_image', true )
	                 ?: $cpt_options['og_image']             ?? $global_options['default_og_image']         ?? '';
	$twitter_image    = get_post_meta( $post_id, '_elevate_seo_twitter_image', true )
	                 ?: $cpt_options['twitter_image']        ?? $global_options['default_twitter_image']    ?? $og_image;
	$twitter_card     = $global_options['twitter_card_type'] ?? 'summary_large_image'; // Not overridden per CPT

	$title = parse_title_format( $title_format, $post_id );

	echo "\n<!-- Elevate SEO Meta Tags -->\n";

	if ( $meta_description ) {
		printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( $meta_description ) );
	}

	// Open Graph
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $title ) );
	if ( $meta_description ) {
		printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $meta_description ) );
	}
	if ( $og_image ) {
		printf( "<meta property=\"og:image\" content=\"%s\">\n", esc_url( $og_image ) );
	}
	printf( "<meta property=\"og:type\" content=\"article\">\n" );
	printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( get_permalink( $post_id ) ) );

	// Twitter
	printf( "<meta name=\"twitter:card\" content=\"%s\">\n", esc_attr( $twitter_card ) );
	printf( "<meta name=\"twitter:title\" content=\"%s\">\n", esc_attr( $title ) );
	if ( $meta_description ) {
		printf( "<meta name=\"twitter:description\" content=\"%s\">\n", esc_attr( $meta_description ) );
	}
	if ( $twitter_image ) {
		printf( "<meta name=\"twitter:image\" content=\"%s\">\n", esc_url( $twitter_image ) );
	}

	echo "<!-- /Elevate SEO Meta Tags -->\n";
}

/**
 * Filters document title tag output.
 */
function override_title( $title_parts ) {
	if ( is_admin() ) return $title_parts;

	$post_id       = get_the_ID();
	$post_type     = get_post_type( $post_id );

	$global_options  = get_option( 'elevate_seo_options', [] );
	$cpt_all_options = get_option( 'elevate_seo_cpt_options', [] );
	$cpt_options     = $cpt_all_options[ $post_type ] ?? [];

	$title_format = $cpt_options['title_format'] ?? $global_options['default_title_format'] ?? '%title% | %sitename%';
	$final        = parse_title_format( $title_format, $post_id );

	return [ 'title' => $final ];
}

add_action( 'wp_head', __NAMESPACE__ . '\\output_meta_tags', 1 );
