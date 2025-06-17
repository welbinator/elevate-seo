<?php

namespace Elevate_SEO\Admin;

class Fields {
	public static function render_media_upload( $name, $value = '', $type = '' ) {
	$id         = 'upload_' . md5( $name );
	$tooltip_id = 'tooltip_' . md5( $name );

	$labels = [
		'default_og_image'      => 'Used for social previews on platforms like Facebook, LinkedIn, and Slack.',
		'default_twitter_image' => 'Used specifically when sharing on Twitter. Can be the same or different from the OG image.',
	];
	$tooltip_text = $labels[ $type ] ?? '';

	?>
	<div class="space-y-2">
		

		<div class="image-wrapper" id="<?php echo esc_attr( $id ); ?>_wrapper">
			<?php if ( $value ) : ?>
				<div class="image-wrapper-inner relative">
					<img src="<?php echo esc_url( $value ); ?>" alt="og preview"
					     class="w-full max-w-md h-32 object-contain rounded-md border" />
					
                        <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 rounded-md px-3 absolute top-2 right-0" onclick="elevateSeoRemoveImage('<?php echo esc_js( $id ); ?>')" type="button" __v0_r="0,1832,1856">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" __v0_r="0,1902,1911">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                         </button>
                    
                         
				</div>
			<?php else : ?>
				<div class="w-full max-w-md aspect-[16/9] border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center cursor-pointer hover:border-gray-400 transition-colors"
				     onclick="elevateSeoUploadImage('<?php echo esc_js( $id ); ?>')">
					<div class="text-center">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
						     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						     class="lucide lucide-upload h-8 w-8 mx-auto mb-2 text-gray-500">
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

		<?php if ( $tooltip_text ) : ?>
			<a href="#" class="elevate-seo-tooltip-link text-xs underline text-gray-500" data-tooltip-target="<?php echo esc_attr( $tooltip_id ); ?>">What's this?</a>
			<div id="<?php echo esc_attr( $tooltip_id ); ?>" class="elevate-seo-tooltip bg-white text-sm text-gray-800 rounded shadow-md border border-gray-200 p-4 mt-2 max-w-md" style="display:none;">

				<p><?php echo esc_html( $tooltip_text ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<script>
	function elevateSeoUploadImage(id) {
		const wrapper = document.getElementById(id + '_wrapper');

		const customUploader = wp.media({
			title: 'Select or Upload an Image',
			button: {
				text: 'Use this image'
			},
			multiple: false
		});

		customUploader.on('select', function () {
			const attachment = customUploader.state().get('selection').first().toJSON();
			document.getElementById(id).value = attachment.url;

			wrapper.innerHTML = `
				<div class="relative">
					<img src="${attachment.url}" alt="og preview"
					     class="w-full max-w-md h-32 object-contain rounded-md border" />
                         <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 rounded-md px-3 absolute top-2 right-0" onclick="elevateSeoRemoveImage('${id}')" type="button" __v0_r="0,1832,1856">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4" __v0_r="0,1902,1911">
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
			<div class="w-full max-w-md h-32 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center cursor-pointer hover:border-gray-400 transition-colors"
			     onclick="elevateSeoUploadImage('${id}')">
				<div class="text-center">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
					     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
					     class="lucide lucide-upload h-8 w-8 mx-auto mb-2 text-gray-500">
						<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
						<polyline points="17 8 12 3 7 8"></polyline>
						<line x1="12" x2="12" y1="3" y2="15"></line>
					</svg>
					<p class="text-sm text-gray-500">Click to upload image</p>
				</div>
			</div>`;
	}
	</script>
	<?php
}


}
