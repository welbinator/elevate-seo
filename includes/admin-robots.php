<?php
namespace Elevate_SEO\Admin;

use Elevate_SEO\Admin\Fields;

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_submenu_page(
		'elevate-seo',
		'robots.txt',
		'robots.txt',
		'manage_options',
		'elevate-seo-robots',
		__NAMESPACE__ . '\\render_robots_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'elevate_seo_robots_group', 'elevate_seo_robots_txt' );
} );

function render_robots_settings_page() {
	$robots_txt = get_option( 'elevate_seo_robots_txt', '' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'robots.txt Settings', 'elevate-seo' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'elevate_seo_robots_group' ); ?>

			<div class="space-y-2 max-w-3xl">
				<?php Fields::render_textarea(
					'elevate_seo_robots_txt',
					$robots_txt,
					'robots_txt'
				); ?>

				<a href="#" class="elevate-seo-tooltip-link text-xs text-gray-500" data-tooltip-target="tooltip_robots">
					<?php esc_html_e( "?", 'elevate-seo' ); ?>
				</a>
				<div id="tooltip_robots" class="elevate-seo-tooltip bg-white text-sm text-gray-800 rounded shadow-md border border-gray-200 p-4 mt-2 max-w-2xl" style="display:none;">
					<p><?php esc_html_e( 'The robots.txt file tells search engine crawlers which parts of your site to crawl or ignore. Example:', 'elevate-seo' ); ?></p>
                        <pre class="bg-gray-100 p-3 rounded">
                        User-agent: *
                        Disallow: /wp-admin/
                        Allow: /wp-admin/admin-ajax.php

                        Sitemap: <?php echo esc_url( get_site_url() ); ?>/wp-sitemap.xml
                        </pre>
					<p><?php esc_html_e( 'Do not include `<robots>` or `<html>` tags — this is raw content.', 'elevate-seo' ); ?></p>
				</div>
			</div>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
