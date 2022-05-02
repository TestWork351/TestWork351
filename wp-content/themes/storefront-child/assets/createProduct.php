<?php
//Функция создания продукта из формы
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

function createProduct($product)
{
    $post = [
        'post_status' => "publish",
        'post_title' => strip_tags($product['title']),
        'post_name' => $product['title'],
        'post_parent' => '',
        'post_type' => 'product',
    ];
    $product_id = wp_insert_post($post);
    //Установим тип продукта в Простой
    wp_set_object_terms($product_id, 'simple', 'product_type');

    //Обработаем картинку
    $file = &$_FILES['product_image'];
    $overrides = ['test_form' => false];
    $img = wp_handle_upload($file, $overrides);
    $img_id = media_sideload_image($img['url'], $product_id, null, 'id');

    //Установим значения метабоксов
    update_post_meta($product_id, 'uploader_custom', absint($img_id));
    set_post_thumbnail($product_id, absint($img_id));
    update_post_meta($product_id, 'product_date', esc_attr($product['date']));
    update_post_meta($product_id, '_product_type', $product['type']);
    update_post_meta($product_id, '_price', esc_attr($product['price']));
    update_post_meta($product_id, '_regular_price', esc_attr($product['price']));

    echo '<br /><span class="success_form">Продукт успешно создан</span>';
}
