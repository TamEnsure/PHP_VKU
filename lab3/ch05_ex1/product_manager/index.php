<?php
require('../model/database.php');
require('../model/product_db.php');
require('../model/category_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_products';
    }
}

if ($action == 'list_products') {
    $category_id = filter_input(INPUT_GET, 'category_id',
            FILTER_VALIDATE_INT);
    if ($category_id == NULL || $category_id == FALSE) {
        $category_id = 1;
    }
    $category_name = get_category_name($category_id);
    if ($category_name === NULL) {
        $error = 'Category not found.';
        include('../errors/error.php');
        exit();
    }
    $categories = get_categories();
    $products = get_products_by_category($category_id);
    include('product_list.php');
} else if ($action == 'delete_product') {
    $product_id = filter_input(INPUT_POST, 'product_id',
            FILTER_VALIDATE_INT);
    $category_id = filter_input(INPUT_POST, 'category_id',
            FILTER_VALIDATE_INT);
    if ($category_id == NULL || $category_id == FALSE ||
            $product_id == NULL || $product_id == FALSE) {
        $error = "Missing or incorrect product id or category id.";
        include('../errors/error.php');
    } else {
        delete_product($product_id);
        header("Location: .?category_id=$category_id");
        exit();
    }
} else if ($action == 'show_add_form') {
    $categories = get_categories();
    include('product_add.php');
} else if ($action == 'add_product') {
    $category_id = filter_input(INPUT_POST, 'category_id',
            FILTER_VALIDATE_INT);
    $code = trim((string) filter_input(INPUT_POST, 'code'));
    $name = trim((string) filter_input(INPUT_POST, 'name'));
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    if ($category_id == NULL || $category_id == FALSE || $code === '' ||
            $name === '' || $price === NULL || $price === FALSE || $price < 0) {
        $error = "Invalid product data. Check all fields and try again.";
        include('../errors/error.php');
    } else {
        try {
            add_product($category_id, $code, $name, $price);
        } catch (PDOException $e) {
            $error = "Could not add product. The product code may already exist.";
            include('../errors/error.php');
            exit();
        }
        header("Location: .?category_id=$category_id");
        exit();
    }
} else if ($action == 'list_categories') {
    $categories = get_categories();
    include('category_list.php');
} else if ($action == 'add_category') {
    $name = trim((string) filter_input(INPUT_POST, 'name'));
    if ($name === '') {
        $error = "Invalid category name. Check name and try again.";
        include('../errors/error.php');
    } else {
        add_category($name);
        header('Location: .?action=list_categories');
        exit();
    }
} else if ($action == 'delete_category') {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    if ($category_id == NULL || $category_id == FALSE) {
        $error = "Missing or incorrect category id.";
        include('../errors/error.php');
    } else {
        try {
            delete_category($category_id);
        } catch (PDOException $e) {
            $error = "Cannot delete this category. Delete its products first.";
            include('../errors/error.php');
            exit();
        }
        header('Location: .?action=list_categories');
        exit();
    }
}
?>
