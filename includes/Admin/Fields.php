<?php

namespace Elevate_SEO\Admin;

class Fields {
	public static function render_media_upload( $name, $value = '', $type = '' ) {
		$button_id  = 'upload_' . md5( $name );
		$preview_id = 'preview_' . md5( $name );
		$tooltip_id = 'tooltip_' . md5( $name );

		$labels = [
			'default_og_image'      => 'Used for social previews on platforms like Facebook, LinkedIn, and Slack.',
			'default_twitter_image' => 'Used specifically when sharing on Twitter. Can be the same or different from the OG image.',
		];
		$tooltip_text = $labels[ $type ] ?? '';
		?>
		<div>
			<input type="hidden" id="<?php echo esc_attr( $button_id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_url( $value ); ?>" />
			<img id="<?php echo esc_attr( $preview_id ); ?>" src="<?php echo esc_url( $value ); ?>" style="max-width: 150px; max-height: 150px; <?php echo $value ? '' : 'display:none;'; ?>" />
			<br />
			<button type="button" class="button elevate-seo-media-upload" data-target="<?php echo esc_attr( $button_id ); ?>" data-preview="<?php echo esc_attr( $preview_id ); ?>">
				<?php esc_html_e( 'Select or Upload Image', 'elevate-seo' ); ?>
			</button>
			<button type="button" class="button elevate-seo-media-remove" data-target="<?php echo esc_attr( $button_id ); ?>" data-preview="<?php echo esc_attr( $preview_id ); ?>" style="margin-left: 5px;">
				<?php esc_html_e( 'Remove Image', 'elevate-seo' ); ?>
			</button>
			<?php if ( $tooltip_text ) : ?>
				<a href="#" class="elevate-seo-tooltip-link" data-tooltip-target="<?php echo esc_attr( $tooltip_id ); ?>" style="margin-left: 10px; font-size: 0.9em;">
					<?php esc_html_e( "What's this?", 'elevate-seo' ); ?>
				</a>
				<div id="<?php echo esc_attr( $tooltip_id ); ?>" class="elevate-seo-tooltip" style="display:none; background: #fff; padding: 1em; border: 1px solid #ccd0d4; max-width: 500px; margin-top: 10px;">
					<p><?php echo esc_html( $tooltip_text ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
