<?php
namespace Elevate_SEO\Admin\Meta_Boxes;

use Elevate_SEO\Admin\Fields;

defined( 'ABSPATH' ) || exit;

/**
 * Adds the SEO meta box to all public post types.
 */
function register_seo_meta_box() {
    $post_types = get_post_types(['public' => true], 'names');

    foreach ( $post_types as $post_type ) {
        add_meta_box(
            'elevate_seo_meta',
            __( 'Elevate SEO Settings', 'elevate-seo' ),
            __NAMESPACE__ . '\\render_meta_box',
            $post_type,
            'normal',
            'default'
        );
    }
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\\register_seo_meta_box' );

/**
 * Renders the SEO meta box fields.
 */
function render_meta_box( $post ) {
    $title_format     = get_post_meta( $post->ID, '_elevate_seo_title_format', true );
    $meta_description = get_post_meta( $post->ID, '_elevate_seo_meta_description', true );
    $og_image         = get_post_meta( $post->ID, '_elevate_seo_og_image', true );
    $twitter_image    = get_post_meta( $post->ID, '_elevate_seo_twitter_image', true );

    wp_nonce_field( 'elevate_seo_save_meta_box', 'elevate_seo_nonce' );
    ?>
    <p>
        <label for="elevate_seo_title_format"><?php esc_html_e( 'Meta Title Format', 'elevate-seo' ); ?></label><br>
        <input type="text" id="elevate_seo_title_format" name="elevate_seo_title_format" class="widefat" value="<?php echo esc_attr( $title_format ); ?>">
        <small><?php esc_html_e( 'Use tags like %title%, %sitename%, %category%, etc.', 'elevate-seo' ); ?></small>
    </p>

    <p>
        <label for="elevate_seo_meta_description"><?php esc_html_e( 'Meta Description', 'elevate-seo' ); ?></label><br>
        <textarea id="elevate_seo_meta_description" name="elevate_seo_meta_description" class="widefat" rows="3"><?php echo esc_textarea( $meta_description ); ?></textarea>
    </p>

    <p>
        <label><?php esc_html_e( 'OG Image', 'elevate-seo' ); ?></label><br>
        <?php Fields::render_media_upload( 'elevate_seo_og_image', $og_image, 'og_image' ); ?>
    </p>

    <p>
        <label><?php esc_html_e( 'Twitter Image', 'elevate-seo' ); ?></label><br>
        <?php Fields::render_media_upload( 'elevate_seo_twitter_image', $twitter_image, 'twitter_image' ); ?>
    </p>
    <?php
}

/**
 * Saves the SEO meta box data.
 */
function save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! isset( $_POST['elevate_seo_nonce'] ) || ! wp_verify_nonce( $_POST['elevate_seo_nonce'], 'elevate_seo_save_meta_box' ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    update_post_meta( $post_id, '_elevate_seo_title_format', sanitize_text_field( $_POST['elevate_seo_title_format'] ?? '' ) );
    update_post_meta( $post_id, '_elevate_seo_meta_description', sanitize_textarea_field( $_POST['elevate_seo_meta_description'] ?? '' ) );
    update_post_meta( $post_id, '_elevate_seo_og_image', esc_url_raw( $_POST['elevate_seo_og_image'] ?? '' ) );
    update_post_meta( $post_id, '_elevate_seo_twitter_image', esc_url_raw( $_POST['elevate_seo_twitter_image'] ?? '' ) );
}
add_action( 'save_post', __NAMESPACE__ . '\\save_meta_box' );
