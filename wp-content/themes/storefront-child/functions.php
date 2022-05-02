<?php

//Подключаем скрипты
add_action('admin_enqueue_scripts', 'ystar_scripts');
add_action('wp_enqueue_scripts', 'ystar_scripts');
function ystar_scripts()
{
    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    wp_enqueue_script('mediaupload',  get_theme_file_uri('assets/js/mediaupload.js'));
    wp_enqueue_script('clear_fields',  get_theme_file_uri('assets/js/clear_fields.js'));
    wp_enqueue_script('checkForm',  get_theme_file_uri('assets/js/checkform.js'), '', '', true);
}

//Регистрируем метабоксы для WooCommerce товаровв
add_action('add_meta_boxes', 'additional_product_tabs_metabox');
function additional_product_tabs_metabox()
{
    add_meta_box(
        'add_product_tabs',
        __('Дополнительные поля', 'woocommerce'),
        'add_product_tabs_content',
        'product',
        'normal',
        'high'
    );
}

function add_product_tabs_content($post)
{
    $date_pick = get_post_meta($post->ID, 'product_date', true);

    echo '<p>';
    if (function_exists('ystar_img_proccesing')) {
        ystar_img_proccesing(array(
            'name' => 'uploader_custom',
            'value' => get_post_meta($post->ID, 'uploader_custom', true),
        ));
    }
    echo '</p>';
    echo '<p>';
    echo '<label>' . __('Время создания продукта', 'woocommerce') . '</label>
    <input type="date" id="product_date" name="product_date" value="' . esc_attr($date_pick) . '" style="max-width: 140px;">';
    echo '</p>';
    echo '<p>';
    woocommerce_wp_select(
        array(
            'id'      => '_product_type',
            'label'   => __('Тип продукта', 'woocommerce'),
            'options' => array(
                'Rare'   => __('Rare', 'woocommerce'),
                'Frequent'   => __('Frequent', 'woocommerce'),
                'Unusual' => __('Unusual', 'woocommerce')
            )
        )
    );
    echo '</p>';
    echo '<div style="text-align: end;">
            <button type="submit" class="clear_fields button-large button" style="color:red">Очистить поля</button>
            <input type="submit" name="save" id="publish" class="button button-primary button-large" value="Обновить">
        </div>';
}

add_action('save_post', 'ystar_save_add_product_tabs');
function ystar_save_add_product_tabs($post_id)
{
    if (isset($_POST['uploader_custom'])) {
        update_post_meta($post_id, 'uploader_custom', absint($_POST['uploader_custom']));
        set_post_thumbnail($post_id, absint($_POST['uploader_custom']));
    } else {
        delete_post_meta($post_id, '_thumbnail_id');
        delete_post_meta($post_id, 'uploader_custom');
    }

    if (isset($_POST['_product_type'])) {
        update_post_meta($post_id, '_product_type', esc_attr($_POST['_product_type']));
    } else {
        delete_post_meta($post_id, '_product_type');
    }

    if (isset($_POST['product_date'])) {
        update_post_meta($post_id, 'product_date', esc_attr($_POST['product_date']));
    } else {
        delete_post_meta($post_id, 'product_date');
    }

    return $post_id;
}

//Обрабатываем загружаемое фото
function ystar_img_proccesing($args)
{
    $value = $args['value'];
    //$default = get_theme_file_uri('assets/images/placeholder.png');

    if ($value && ($image_attributes = wp_get_attachment_image_src($value, array(150, 110)))) {
        $src = $image_attributes[0];
    } else {
        $src = '';
    }
    echo '
	<div>
		<img id="product_image" data-src="' . $default . '" src="' . $src . '" width="150" />
		<div>
            <label for="custom_field_type">' . __('Изображение товара', 'woocommerce') . '</label>
			<input type="hidden" id="uploader_hidden" name="' . $args['name'] . '" id="' . $args['name'] . '" value="' . $value . '" />
			<button type="submit" class="upload_image_button button">Загрузить</button>
			<button type="submit" class="remove_image_button button" style="color:red">Удалить</button>
		</div>
	</div>
	';
}
