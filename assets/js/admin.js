jQuery(document).ready(function($) {

    /* ---------------------------
       THUMBNAIL UPLOAD FIELD
    --------------------------- */
    const thumbnailWrapper = $('#cp_thumbnail_wrapper');

    $('#cp_add_thumbnail').on('click', function(e) {
        e.preventDefault();
        const frame = wp.media({
            title: 'Select Thumbnail',
            multiple: false,
            library: { type: 'image' },
            button: { text: 'Use this image' }
        });

        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            thumbnailWrapper.find('img').remove();
            thumbnailWrapper.prepend(`<img src="${attachment.url}" style="max-width:150px; display:block; margin-bottom:10px;">`);
            thumbnailWrapper.find('input[name="cp_thumbnail"]').val(attachment.id);
            $('#cp_remove_thumbnail').show();
        });

        frame.open();
    });

    $('#cp_remove_thumbnail').on('click', function(e) {
        e.preventDefault();
        thumbnailWrapper.find('img').remove();
        thumbnailWrapper.find('input[name="cp_thumbnail"]').val('');
        $(this).hide();
    });


    /* ---------------------------
       SCREENSHOTS GALLERY
    --------------------------- */
    const screenshotsContainer = $('#cp_screenshots_container');

    // Add new screenshots
    $('#cp_add_screenshot').on('click', function(e) {
        e.preventDefault();
        const frame = wp.media({
            title: 'Select Screenshot(s)',
            multiple: true,
            library: { type: 'image' },
            button: { text: 'Use these images' }
        });

        frame.on('select', function() {
            const attachments = frame.state().get('selection').toJSON();
            attachments.forEach(att => {
                screenshotsContainer.append(`
                    <div class="cp-screenshot">
                        <img src="${att.url}" style="max-width:150px;">
                        <input type="hidden" name="cp_screenshots[]" value="${att.id}">
                        <button class="cp-remove-screenshot button">Remove</button>
                    </div>
                `);
            });
        });

        frame.open();
    });

    // Remove screenshot
    screenshotsContainer.on('click', '.cp-remove-screenshot', function(e) {
        e.preventDefault();
        $(this).closest('.cp-screenshot').remove();
    });

    // Make screenshots sortable
    screenshotsContainer.sortable({
        placeholder: "cp-screenshot-placeholder",
        items: '.cp-screenshot',
        cursor: 'move',
        tolerance: 'pointer'
    });

});
