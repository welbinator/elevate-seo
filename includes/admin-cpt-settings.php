<?php
namespace Elevate_SEO\Admin\CPT;

use Elevate_SEO\Admin\Fields;

defined( 'ABSPATH' ) || exit;

/**
 * Adds the CPTs submenu page.
 */
function register_cpt_menu() {
	add_submenu_page(
		'elevate-seo',
		__( 'CPT Settings', 'elevate-seo' ),
		__( 'CPTs', 'elevate-seo' ),
		'manage_options',
		'elevate-seo-cpts',
		__NAMESPACE__ . '\\render_cpt_settings_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\\register_cpt_menu' );

/**
 * Registers CPT-specific settings.
 */
function register_cpt_settings() {
	register_setting( 'elevate_seo_cpt_settings', 'elevate_seo_cpt_options', [
		'type'              => 'array',
		'sanitize_callback' => __NAMESPACE__ . '\\sanitize_cpt_options',
		'default'           => [],
	] );
}
add_action( 'admin_init', __NAMESPACE__ . '\\register_cpt_settings' );

/**
 * Sanitizes CPT options.
 */
function sanitize_cpt_options( $input ) {
	$output = [];

	foreach ( $input as $cpt => $settings ) {
		$output[ $cpt ] = [
			'title_format'     => sanitize_text_field( $settings['title_format'] ?? '' ),
			'meta_description' => sanitize_textarea_field( $settings['meta_description'] ?? '' ),
			'og_image'         => esc_url_raw( $settings['og_image'] ?? '' ),
			'twitter_image'    => esc_url_raw( $settings['twitter_image'] ?? '' ),
		];
	}

	return $output;
}

/**
 * Renders the CPT settings page.
 */
function render_cpt_settings_page() {
	$cpts    = get_post_types([
		'_builtin'     => false,
		'public'       => true,
		'show_ui'      => true,
		'show_in_menu' => true,
	], 'objects');
	$options = get_option( 'elevate_seo_cpt_options', [] );
	?>
	<div class="wrap">
		<h1 class="text-2xl font-semibold mb-6"><?php esc_html_e( 'CPT SEO Settings', 'elevate-seo' ); ?></h1>

		<form method="post" action="options.php" class="space-y-12">
			<?php settings_fields( 'elevate_seo_cpt_settings' ); ?>

			<?php foreach ( $cpts as $cpt ) :
				$name         = $cpt->name;
				$label        = $cpt->labels->singular_name ?? $name;
				$cpt_options  = $options[ $name ] ?? [];
				?>
				<div class="bg-white p-6 rounded-lg shadow border border-gray-200 max-w-3xl">
					<h2 class="text-xl font-medium mb-4"><?php echo esc_html( $label ); ?></h2>

					<?php Fields::render_input( "elevate_seo_cpt_options[$name][title_format]", $cpt_options['title_format'] ?? '', 'meta_title_format' ); ?>

					<?php Fields::render_textarea( "elevate_seo_cpt_options[$name][meta_description]", $cpt_options['meta_description'] ?? '', 'meta_description' ); ?>

					<?php Fields::render_media_upload( "elevate_seo_cpt_options[$name][og_image]", $cpt_options['og_image'] ?? '', 'default_og_image' ); ?>

					<?php Fields::render_media_upload( "elevate_seo_cpt_options[$name][twitter_image]", $cpt_options['twitter_image'] ?? '', 'default_twitter_image' ); ?>
				</div>
			<?php endforeach; ?>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
