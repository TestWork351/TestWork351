<?php
/*
    Template Name: Create product
*/
require_once('assets/createProduct.php');
session_start();
if (isset($_POST['submit'])) {
    $_SESSION["success"] = 'Продукт ' . $_POST['product_title'] . ' успешно создан';
    $product = [
        'title' => $_POST['product_title'],
        'price' => $_POST['product_price'],
        'image' => $_POST['product_image'],
        'date' => $_POST['product_date'],
        'type' => $_POST['product_type'],
    ];
    createProduct($product);
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
get_header(); ?>

<form id="createProduct" action="" method="post" enctype="multipart/form-data">
    <p>Название товара<span class="neccesarry-field"></span>: <input type="text" name="product_title"></p>
    <p>Цена товара<span class="neccesarry-field"></span>: <input type="text" name="product_price"></p>
    <p>Изображение товара<span class="neccesarry-field"></span>: <input type="file" accept="image/png, image/gif, image/jpeg" name="product_image"></p>
    <p>Время создания продукта<span class="neccesarry-field"></span>: <input type="date" name="product_date"></p>
    <p>Тип продукта<span class="neccesarry-field"></span>:
        <select name="product_type">
            <option value="Rare">Rare</option>
            <option value="Frequent">Frequent</option>
            <option value="Unusual">Unusual</option>
        </select>
    </p>

    <p>
        <input id="submitForm" type="submit" value="Отправить" name="submit" style="background-color: greenyellow;">
        <input type="reset" name="Reset" value="Очистить форму">
    </p>
    <?php
    if (isset($_SESSION["success"])) {
        echo '<span class="success_form">' . $_SESSION["success"] . '</span>';
    }
    ?>
</form>


<?php
get_footer();
