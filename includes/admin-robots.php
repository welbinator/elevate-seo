<?php
namespace Elevate_SEO\Admin;

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
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="elevate_seo_robots_txt"><?php esc_html_e( 'robots.txt Content', 'elevate-seo' ); ?></label>
						<a href="#" class="elevate-seo-tooltip-link" data-tooltip-target="tooltip_robots" style="margin-left: 10px; font-size: 0.9em;">
							<?php esc_html_e( "What's this?", 'elevate-seo' ); ?>
						</a>
						<div id="tooltip_robots" class="elevate-seo-tooltip" style="display:none; background:#fff; padding:1em; border:1px solid #ccd0d4; max-width: 600px; margin-top: 10px;">
							<p>
								<?php esc_html_e( 'The robots.txt file tells search engine crawlers which parts of your site to crawl or ignore. Example:', 'elevate-seo' ); ?>
							</p>
<pre style="background:#f6f8fa; padding:10px;">
User-agent: *
Disallow: /wp-admin/
Allow: /wp-admin/admin-ajax.php

Sitemap: <?php echo esc_url( get_site_url() ); ?>/wp-sitemap.xml
</pre>
							<p><?php esc_html_e( 'Do not include `<robots>` or `<html>` tags — this is raw content.', 'elevate-seo' ); ?></p>
						</div>
					</th>
					<td>
						<textarea id="elevate_seo_robots_txt" name="elevate_seo_robots_txt" rows="12" cols="70" class="large-text code"><?php echo esc_textarea( $robots_txt ); ?></textarea>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
