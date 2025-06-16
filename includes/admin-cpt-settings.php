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
    $cpts = get_post_types([
        '_builtin'     => false,
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
    ], 'objects');

    $options = get_option( 'elevate_seo_cpt_options', [] );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'CPT SEO Settings', 'elevate-seo' ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'elevate_seo_cpt_settings' ); ?>

            <?php foreach ( $cpts as $cpt ) :
                $name = $cpt->name;
                $label = $cpt->labels->singular_name ?? $name;
                $cpt_options = $options[ $name ] ?? [];
                ?>
                <h2><?php echo esc_html( $label ); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Meta Title Format', 'elevate-seo' ); ?></th>
                        <td>
                            <input type="text" name="elevate_seo_cpt_options[<?php echo esc_attr( $name ); ?>][title_format]" class="regular-text"
                                   value="<?php echo esc_attr( $cpt_options['title_format'] ?? '' ); ?>">
                            <p class="description"><?php esc_html_e( 'Use tags like %title%, %sitename%, %category%, or %taxonomy_slug%.', 'elevate-seo' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Meta Description', 'elevate-seo' ); ?></th>
                        <td>
                            <textarea name="elevate_seo_cpt_options[<?php echo esc_attr( $name ); ?>][meta_description]" class="large-text" rows="3"><?php echo esc_textarea( $cpt_options['meta_description'] ?? '' ); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'OG Image', 'elevate-seo' ); ?></th>
                        <td>
                            <?php Fields::render_media_upload(
                                "elevate_seo_cpt_options[$name][og_image]",
                                $cpt_options['og_image'] ?? '',
                                'default_og_image'
                            ); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Twitter Image', 'elevate-seo' ); ?></th>
                        <td>
                            <?php Fields::render_media_upload(
                                "elevate_seo_cpt_options[$name][twitter_image]",
                                $cpt_options['twitter_image'] ?? '',
                                'default_twitter_image'
                            ); ?>
                        </td>
                    </tr>
                </table>
                <hr>
            <?php endforeach; ?>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
