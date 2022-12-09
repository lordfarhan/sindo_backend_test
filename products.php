<?php
require_once "model/product.php";

$product = new Product();

$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
  case 'GET':
    if (!empty($_GET["id"])) {
      $id = intval($_GET["id"]);
      $product->get_product($id);
    } else if (!empty($_GET['type'])) {
      if ($_GET['type'] == 'product_groupped') {
        $product->get_products_groupped_by_product_name();
      } else if ($_GET['type'] == 'highest_price') {
        $product->get_product_price_groupped_by_product_name();
      } else if ($_GET['type'] == 'region_based') {
        $product->get_product_groupped_by_user_region();
      }
    } else {
      $product->get_all_products();
    }
    break;
  case 'POST':
    if (!empty($_GET["id"])) {
      $id = intval($_GET["id"]);
      $product->update_product($id);
    } else {
      $product->insert_product();
    }
    break;
  case 'DELETE':
    $id = intval($_GET["id"]);
    $product->delete_product($id);
    break;
  default:
    // Invalid Request Method
    header("HTTP/1.0 405 Method Not Allowed");
    break;
    break;
}
