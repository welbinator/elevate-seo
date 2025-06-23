<?php
namespace Elevate_SEO\Admin;

use Elevate_SEO\Admin\Fields;

defined( 'ABSPATH' ) || exit;

function register_admin_menu() {
	add_menu_page(
		'SEO Settings',
		'SEO',
		'manage_options',
		'elevate-seo',
		__NAMESPACE__ . '\\render_settings_page',
		'dashicons-chart-area',
		80
	);

	// These submenus are sufficient, no need to add the top-level one again
	add_submenu_page(
		'elevate-seo',
		'Custom Post Types',
		'CPTs',
		'manage_options',
		'elevate-seo-cpts',
		'\\Elevate_SEO\\Admin\\CPT\\render_cpt_settings_page'
	);

	add_submenu_page(
		'elevate-seo',
		'robots.txt',
		'robots.txt',
		'manage_options',
		'elevate-seo-robots',
		'\\Elevate_SEO\\Admin\\Robots\\render_robots_settings_page'
	);
}

add_action( 'admin_menu', __NAMESPACE__ . '\\register_admin_menu' );

function register_main_settings() {
	register_setting( 'elevate_seo_settings', 'elevate_seo_options' );
}
add_action( 'admin_init', __NAMESPACE__ . '\\register_main_settings' );

function render_settings_page() {
	$options = get_option( 'elevate_seo_options', [] );
	?>
	<div class="wrap">
		<h1 class="text-2xl font-semibold mb-6"><?php esc_html_e( 'SEO Settings', 'elevate-seo' ); ?></h1>
		<form method="post" action="options.php" class="space-y-12">
			<?php settings_fields( 'elevate_seo_settings' ); ?>

			<div class="space-y-8">
				<?php Fields::render_input( 'elevate_seo_options[default_title_format]', $options['default_title_format'] ?? '', 'default_title_format' ); ?>
				<?php Fields::render_textarea( 'elevate_seo_options[default_meta_description]', $options['default_meta_description'] ?? '', 'meta_description' ); ?>
				<?php Fields::render_media_upload( 'elevate_seo_options[default_og_image]', $options['default_og_image'] ?? '', 'default_og_image' ); ?>
				<?php Fields::render_media_upload( 'elevate_seo_options[default_twitter_image]', $options['default_twitter_image'] ?? '', 'default_twitter_image' ); ?>

				<div class="space-y-2">
					<label for="twitter_card_type" class="text-sm font-medium leading-none">
						<?php echo esc_html( Fields::get_field_label( 'twitter_card_type' ) ); ?>
					</label>
					<select name="elevate_seo_options[twitter_card_type]" id="twitter_card_type" class="border rounded px-3 py-2">
						<option value="summary" <?php selected( $options['twitter_card_type'] ?? '', 'summary' ); ?>>Summary</option>
						<option value="summary_large_image" <?php selected( $options['twitter_card_type'] ?? '', 'summary_large_image' ); ?>>Summary with Large Image</option>
					</select>
				</div>
			</div>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
