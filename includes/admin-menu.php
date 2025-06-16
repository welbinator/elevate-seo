<?php
namespace Elevate_SEO\Admin;

use Elevate_SEO\Admin\Fields;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the top-level menu and the settings subpage.
 */
function register_admin_menu() {
    add_menu_page(
        __( 'SEO', 'elevate-seo' ),
        __( 'SEO', 'elevate-seo' ),
        'manage_options',
        'elevate-seo',
        __NAMESPACE__ . '\\render_settings_page',
        'dashicons-chart-line',
        60
    );

    add_submenu_page(
        'elevate-seo',
        __( 'Settings', 'elevate-seo' ),
        __( 'Settings', 'elevate-seo' ),
        'manage_options',
        'elevate-seo',
        __NAMESPACE__ . '\\render_settings_page'
    );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\register_admin_menu' );

/**
 * Registers settings.
 */
function register_settings() {
    register_setting( 'elevate_seo_settings', 'elevate_seo_options', [
        'type'              => 'array',
        'sanitize_callback' => __NAMESPACE__ . '\\sanitize_options',
        'default'           => [],
    ] );

    add_settings_section(
        'elevate_seo_main_section',
        __( 'Global SEO Defaults', 'elevate-seo' ),
        '__return_null',
        'elevate-seo'
    );

    add_settings_field(
        'default_meta_description',
        __( 'Default Meta Description', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_textarea_field',
        'elevate-seo',
        'elevate_seo_main_section',
        ['name' => 'default_meta_description']
    );

    add_settings_field(
        'default_og_image',
        __( 'Default OG Image', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_media_upload_field',
        'elevate-seo',
        'elevate_seo_main_section',
        ['name' => 'default_og_image']
    );

    add_settings_field(
        'default_twitter_image',
        __( 'Default Twitter Image', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_media_upload_field',
        'elevate-seo',
        'elevate_seo_main_section',
        ['name' => 'default_twitter_image']
    );

    add_settings_field(
        'default_title_format',
        __( 'Default Meta Title Format', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_title_format_field',
        'elevate-seo',
        'elevate_seo_main_section'
    );

    add_settings_field(
        'twitter_card_type',
        __( 'Twitter Card Type', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_select_field',
        'elevate-seo',
        'elevate_seo_main_section',
        ['name' => 'twitter_card_type']
    );

        // Redirects Section
    add_settings_section(
        'elevate_seo_redirects_section',
        __( 'Redirects', 'elevate-seo' ),
        '__return_null',
        'elevate-seo'
    );

    add_settings_field(
        'redirect_slug_changes',
        __( 'Automatically Redirect Slug Changes', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_checkbox_field',
        'elevate-seo',
        'elevate_seo_redirects_section',
        ['name' => 'redirect_slug_changes']
    );

    add_settings_field(
        'redirect_404',
        __( 'Automatically Redirect 404s', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_checkbox_field',
        'elevate-seo',
        'elevate_seo_redirects_section',
        ['name' => 'redirect_404']
    );

    add_settings_field(
        'redirect_404_target',
        __( 'Redirect 404s To URL', 'elevate-seo' ),
        __NAMESPACE__ . '\\render_text_field',
        'elevate-seo',
        'elevate_seo_redirects_section',
        ['name' => 'redirect_404_target']
    );

}
add_action( 'admin_init', __NAMESPACE__ . '\\register_settings' );

/**
 * Sanitization callback.
 */
function sanitize_options( $input ) {
    $output = [];
    $output['default_focus_keyword']     = sanitize_text_field( $input['default_focus_keyword'] ?? '' );
    $output['default_meta_description']  = sanitize_textarea_field( $input['default_meta_description'] ?? '' );
    $output['default_og_image']          = esc_url_raw( $input['default_og_image'] ?? '' );
    $output['default_twitter_image']     = esc_url_raw( $input['default_twitter_image'] ?? '' );
    $output['default_title_format']      = wp_kses_post( $input['default_title_format'] ?? '' );

    $output['twitter_card_type'] = in_array( $input['twitter_card_type'] ?? '', [ 'summary', 'summary_large_image' ], true )
        ? $input['twitter_card_type']
        : 'summary_large_image';

    $output['redirect_slug_changes'] = ! empty( $input['redirect_slug_changes'] ) ? 1 : 0;
    $output['redirect_404']          = ! empty( $input['redirect_404'] ) ? 1 : 0;
    $output['redirect_404_target']   = esc_url_raw( $input['redirect_404_target'] ?? '' );


    return $output;
}

function render_textarea_field( $args ) {
    $options = get_option( 'elevate_seo_options' );
    $name    = $args['name'];
    $value   = esc_textarea( $options[ $name ] ?? '' );
    echo "<textarea name='elevate_seo_options[$name]' rows='4' class='large-text'>$value</textarea>";
}

function render_select_field( $args ) {
    $options = get_option( 'elevate_seo_options' );
    $name    = $args['name'];
    $value   = $options[ $name ] ?? 'summary_large_image';
    ?>
    <select name="elevate_seo_options[<?php echo esc_attr( $name ); ?>]">
        <option value="summary" <?php selected( $value, 'summary' ); ?>>summary</option>
        <option value="summary_large_image" <?php selected( $value, 'summary_large_image' ); ?>>summary_large_image</option>
    </select>
    <?php
}

function render_media_upload_field( $args ) {
    $options = get_option( 'elevate_seo_options' );
    $name    = $args['name'];
    $value   = esc_url( $options[ $name ] ?? '' );
    Fields::render_media_upload( "elevate_seo_options[$name]", $value, $name );
}

function render_title_format_field() {
    $options = get_option( 'elevate_seo_options' );
    $value   = esc_attr( $options['default_title_format'] ?? '%title% | %sitename%' );
    ?>
    <input type="text" name="elevate_seo_options[default_title_format]" value="<?php echo $value; ?>" class="regular-text" />
    <p class="description">
        <?php esc_html_e( 'Use template tags like %title%, %sitename%, %category%.', 'elevate-seo' ); ?>
        <a href="#" id="elevate-seo-title-help-link"><?php esc_html_e( 'What’s this?', 'elevate-seo' ); ?></a>
    </p>

    <div id="elevate-seo-title-help-modal" style="display:none; background: #fff; padding: 1em; border: 1px solid #ccd0d4; max-width: 500px; margin-top: 10px;">
        <h2><?php esc_html_e( 'Available Template Tags', 'elevate-seo' ); ?></h2>
        <ul>
            <li><code>%title%</code> – <?php esc_html_e( 'The post or page title.', 'elevate-seo' ); ?></li>
            <li><code>%sitename%</code> – <?php esc_html_e( 'Your site’s name.', 'elevate-seo' ); ?></li>
            <li><code>%tagline%</code> – <?php esc_html_e( 'Your site’s tagline.', 'elevate-seo' ); ?></li>
            <li><code>%category%</code> – <?php esc_html_e( 'First category (for posts).', 'elevate-seo' ); ?></li>
            <li><code>%date%</code> – <?php esc_html_e( 'The publish date.', 'elevate-seo' ); ?></li>
        </ul>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const helpLink = document.getElementById('elevate-seo-title-help-link');
        const modal = document.getElementById('elevate-seo-title-help-modal');
        if (helpLink && modal) {
            helpLink.addEventListener('click', function (e) {
                e.preventDefault();
                modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
            });
        }
    });
    </script>
    <?php
}

function render_checkbox_field( $args ) {
    $options = get_option( 'elevate_seo_options' );
    $name    = $args['name'];
    $value   = ! empty( $options[ $name ] ) ? 1 : 0;
    echo "<label><input type='checkbox' name='elevate_seo_options[$name]' value='1'" . checked( 1, $value, false ) . "> " . esc_html__( 'Enable', 'elevate-seo' ) . "</label>";
}

function render_text_field( $args ) {
    $options = get_option( 'elevate_seo_options' );
    $name    = $args['name'];
    $value   = esc_url( $options[ $name ] ?? '' );
    echo "<input type='text' name='elevate_seo_options[$name]' value='" . esc_attr( $value ) . "' class='regular-text'>";
}


function parse_title_format( $format, $post_id ) {
    $title    = get_the_title( $post_id );
    $site     = get_bloginfo( 'name' );
    $tagline  = get_bloginfo( 'description' );
    $date     = get_the_date( '', $post_id );

    $replacements = [
        '%title%'    => $title,
        '%sitename%' => $site,
        '%tagline%'  => $tagline,
        '%date%'     => $date,
    ];

    if ( 'post' === get_post_type( $post_id ) ) {
        $cats = get_the_category( $post_id );
        $replacements['%category%'] = ! empty( $cats ) ? $cats[0]->name : '';
    }

    if ( preg_match_all( '/%taxonomy_([a-zA-Z0-9_-]+)%/', $format, $matches ) ) {
        foreach ( $matches[1] as $i => $taxonomy ) {
            $term_name = '';
            $terms     = get_the_terms( $post_id, $taxonomy );
            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                $term_name = $terms[0]->name;
            }

            $tag = $matches[0][ $i ];
            if ( ! empty( $term_name ) ) {
                $replacements[ $tag ] = $term_name;
            } else {
                $format = preg_replace( '/(?:\s*[\|\-–—•]\s*)?' . preg_quote( $tag, '/' ) . '(?:\s*[\|\-–—•]\s*)?/', '', $format );
            }
        }
    }

    foreach ( $replacements as $tag => $replacement ) {
        $format = str_replace( $tag, $replacement, $format );
    }

    $format = trim( preg_replace( '/\s+/', ' ', $format ) );
    $format = trim( $format, "|-–—• " );

    return $format;
}

/**
 * Renders the settings page.
 */
function render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Elevate SEO Settings', 'elevate-seo' ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'elevate_seo_settings' );
            do_settings_sections( 'elevate-seo' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
