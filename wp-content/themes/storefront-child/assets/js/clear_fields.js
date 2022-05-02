jQuery(function ($) {
    $('.clear_fields').click(function (event) {
        event.preventDefault();
        $('#product_image').attr('src', '');
        $('#uploader_hidden').val('');
        $('#_thumbnail_id').val('-1');
        $('#product_date').val('');
        $('#_product_type').val('');
    });
});