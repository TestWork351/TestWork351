jQuery('#submitForm').on('click', function (e) {
    jQuery('#createProduct').find('input, textearea, select').each(function () {
        if (!jQuery(this).val()) {
            e.preventDefault();
            jQuery(this).after('<br /><span class="err_field">Заполните поле</span>');
        };
    })
});