jQuery(document).ready(function ($) {
    $('.elevate-seo-media-upload').on('click', function (e) {
        e.preventDefault();

        const targetInput = $(this).data('target');
        const previewImage = $(this).data('preview');

        const frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();
            $('#' + targetInput).val(attachment.url);
            $('#' + previewImage).attr('src', attachment.url).show();
        });

        frame.open();
    });

    $('.elevate-seo-media-remove').on('click', function (e) {
        e.preventDefault();

        const targetInput = $(this).data('target');
        const previewImage = $(this).data('preview');

        $('#' + targetInput).val('');
        $('#' + previewImage).hide();
    });
});

jQuery(document).ready(function ($) {
    $('.elevate-seo-tooltip-link').on('click', function (e) {
        e.preventDefault();

        const targetId = $(this).data('tooltip-target');
        const tooltip = $('#' + targetId);

        if (tooltip.length) {
            tooltip.toggle();
        }
    });
});

