<?php
namespace Elevate_SEO\Admin;

class Fields {

	public static function render_input( $name, $value = '', $type = '' ) {
		$id         = 'input_' . md5( $name );
		$tooltip_id = 'tooltip_' . md5( $name );

		$label_texts = [
			'meta_title_format'     => 'Meta Title Format',
			'default_title_format'  => 'Meta Title Format',
		];

		$label = $label_texts[ $type ] ?? '';

		echo '<div class="space-y-2">';
		if ( $label ) {
			echo '<label for="' . esc_attr( $id ) . '" class="text-sm font-medium leading-none">';
			echo esc_html( $label );
			if ( in_array( $type, [ 'meta_title_format', 'default_title_format' ], true ) ) {
				echo ' <a href="#" class="elevate-seo-tooltip-link text-xs text-muted-foreground ml-2 underline" data-tooltip-target="' . esc_attr( $tooltip_id ) . '">?</a>';
			}
			echo '</label>';
		}
		if ( in_array( $type, [ 'meta_title_format', 'default_title_format' ], true ) ) {
			self::render_title_format_tooltip_modal( $tooltip_id );
		}
		echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="w-full border rounded px-3 py-2" />';
		echo '</div>';
	}

	public static function render_textarea( $name, $value = '', $type = '' ) {
		$id = 'textarea_' . md5( $name );

		$label_texts = [
			'meta_description' => 'Meta Description',
			'robots_txt'       => 'robots.txt Content',
		];

		$label = $label_texts[ $type ] ?? '';

		echo '<div class="space-y-2">';
		if ( $label ) {
			echo '<label for="' . esc_attr( $id ) . '" class="text-sm font-medium leading-none">' . esc_html( $label ) . '</label>';
		}
		echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="3" class="w-full border rounded px-3 py-2 robots-textarea">' . esc_textarea( $value ) . '</textarea>';
		echo '</div>';
	}

	public static function render_media_upload( $name, $value = '', $type = '' ) {
		$id         = 'upload_' . md5( $name );
		$tooltip_id = 'tooltip_' . md5( $name );

		$label_texts = [
			'default_og_image'      => 'Default OG Image',
			'default_twitter_image' => 'Default Twitter Image',
		];

		$tooltips = [
			'default_og_image'      => 'Used for social previews on platforms like Facebook, LinkedIn, and Slack.',
			'default_twitter_image' => 'Used specifically when sharing on Twitter. Can be the same or different from the OG image.',
		];

		$label   = $label_texts[ $type ] ?? '';
		$tooltip = $tooltips[ $type ] ?? '';
		?>

		<div class="space-y-2">
			<?php if ( $label ) : ?>
				<label class="text-sm font-medium leading-none"><?php echo esc_html( $label ); ?></label>
			<?php endif; ?>

			<?php if ( $tooltip ) : ?>
				<a href="#" class="elevate-seo-tooltip-link text-xs text-gray-500" data-tooltip-target="<?php echo esc_attr( $tooltip_id ); ?>">?</a>
				<div id="<?php echo esc_attr( $tooltip_id ); ?>" class="elevate-seo-tooltip bg-white text-sm text-gray-800 rounded shadow-md border border-gray-200 p-4 mt-2 max-w-md" style="display:none;">
					<p><?php echo esc_html( $tooltip ); ?></p>
				</div>
			<?php endif; ?>

			<div class="image-wrapper" id="<?php echo esc_attr( $id ); ?>_wrapper">
				<?php if ( $value ) : ?>
					<div class="image-wrapper-inner relative">
						<img src="<?php echo esc_url( $value ); ?>" alt="og preview" class="w-full max-w-md aspect-[16/9] object-cover rounded-md border" />
						<button class="inline-flex items-center justify-center gap-2 text-sm font-medium bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 rounded-md px-3 absolute top-2 right-0" onclick="elevateSeoRemoveImage('<?php echo esc_js( $id ); ?>')" type="button">
							X
						</button>
					</div>
				<?php else : ?>
					<div class="w-full max-w-md aspect-[16/9] border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center cursor-pointer hover:border-gray-400 transition-colors" onclick="elevateSeoUploadImage('<?php echo esc_js( $id ); ?>')">
						<div class="text-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload h-8 w-8 mx-auto mb-2 text-gray-500">
								<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
								<polyline points="17 8 12 3 7 8"></polyline>
								<line x1="12" x2="12" y1="3" y2="15"></line>
							</svg>
							<p class="text-sm text-gray-500">Click to upload image</p>
							<p class="text-sm text-gray-500">Recommended image size is 1200px x 675px</p>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<input type="hidden" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_url( $value ); ?>" />
		</div>

		<script>
			function elevateSeoUploadImage(id) {
				const wrapper = document.getElementById(id + '_wrapper');
				const customUploader = wp.media({
					title: 'Select or Upload an Image',
					button: { text: 'Use this image' },
					multiple: false
				});

				customUploader.on('select', function () {
					const attachment = customUploader.state().get('selection').first().toJSON();
					document.getElementById(id).value = attachment.url;

					wrapper.innerHTML = `
						<div class="image-wrapper-inner relative">
							<img src="${attachment.url}" alt="og preview" class="w-full max-w-md aspect-[16/9] object-cover rounded-md border" />
							<button class="inline-flex items-center justify-center gap-2 text-sm font-medium bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 rounded-md px-3 absolute top-2 right-0" onclick="elevateSeoRemoveImage('${id}')" type="button">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4">
									<path d="M18 6 6 18"></path>
									<path d="m6 6 12 12"></path>
								</svg>
							</button>
						</div>`;
				});

				customUploader.open();
			}

			function elevateSeoRemoveImage(id) {
				document.getElementById(id).value = '';
				const wrapper = document.getElementById(id + '_wrapper');
				wrapper.innerHTML = `
					<div class="w-full max-w-md aspect-[16/9] border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center cursor-pointer hover:border-gray-400 transition-colors" onclick="elevateSeoUploadImage('${id}')">
						<div class="text-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload h-8 w-8 mx-auto mb-2 text-gray-500">
								<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
								<polyline points="17 8 12 3 7 8"></polyline>
								<line x1="12" x2="12" y1="3" y2="15"></line>
							</svg>
							<p class="text-sm text-gray-500">Click to upload image</p>
							<p class="text-sm text-gray-500">Recommended image size is 1200px x 675px</p>
						</div>
					</div>`;
			}
		</script>
		<?php
	}

	public static function render_title_format_tooltip_modal( $tooltip_id = 'meta-title-help-modal' ) {
		?>
		<div id="<?php echo esc_attr( $tooltip_id ); ?>" style="display:none; background: #fff; padding: 1em; border: 1px solid #ccd0d4; max-width: 500px; margin-top: 10px;">
			<h2><?php esc_html_e( 'Available Template Tags', 'elevate-seo' ); ?></h2>
			<ul>
				<li><code>%title%</code> – <?php esc_html_e( 'The post or page title.', 'elevate-seo' ); ?></li>
				<li><code>%sitename%</code> – <?php esc_html_e( 'Your site’s name.', 'elevate-seo' ); ?></li>
				<li><code>%tagline%</code> – <?php esc_html_e( 'Your site’s tagline.', 'elevate-seo' ); ?></li>
				<li><code>%category%</code> – <?php esc_html_e( 'First category (for posts).', 'elevate-seo' ); ?></li>
				<li><code>%date%</code> – <?php esc_html_e( 'The publish date.', 'elevate-seo' ); ?></li>
				<li><code>%taxonomy_slug%</code> – <?php esc_html_e( 'First term from a given taxonomy.', 'elevate-seo' ); ?></li>
			</ul>
		</div>
		<?php
	}

	public static function get_field_label( $key ) {
		$labels = [
			'default_meta_description' => 'Default Meta Description',
			'default_og_image'         => 'Default OG Image',
			'default_twitter_image'    => 'Default Twitter Image',
			'default_title_format'     => 'Meta Title Format',
			'twitter_card_type'        => 'Twitter Card Type',
			'redirect_slug_changes'    => 'Automatically Redirect Changed URLs',
			'redirect_404'             => 'Redirect 404 Pages to a Custom URL',
			'redirect_404_target'      => 'Custom 404 Redirect URL',
			'enable_canonical_pages'   => 'Add Canonical Meta Tag to Pages',
		];
		return $labels[ $key ] ?? $key;
	}

	public static function get_section_label( $key ) {
		$sections = [
			'global_seo' => 'Global SEO Settings',
			'redirects'  => 'Redirect Settings',
		];
		return $sections[ $key ] ?? $key;
	}
}
