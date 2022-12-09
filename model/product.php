<?php

require_once "koneksi.php";

class Product
{
  public  function get_all_products()
  {
    global $mysqli;

    // pagination
    $pagination = $_GET['pagination'] ?? 10;
    $page = ($_GET['page'] ?? 1) - 1; // start from 0 brody
    $offset = $pagination * $page;

    // search query
    $search_key = $_GET['search_key'];

    $data = array();

    $query = "SELECT * FROM products 
      WHERE name LIKE '%$search_key%' 
      OFFSET $offset ROWS FETCH NEXT $pagination ROWS ONLY";
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
      $data[] = $row;
    }

    $page = $page + 1;
    $response = array(
      'success' => true,
      'message' => "Get List Products Successfully (showing page: $page).",
      'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function get_product($id = 0)
  {
    global $mysqli;

    $query = "SELECT * FROM products";
    if ($id != 0) {
      $query .= " WHERE id=" . $id . " LIMIT 1";
    }

    $data = array();
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
      $data[] = $row;
    }

    $response = array(
      'success' => true,
      'message' => 'Get Product Successfully.',
      'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function insert_product()
  {
    global $mysqli;

    $arr_check_post = array('user_id' => '', 'name' => '', 'price' => '');
    $count = count(array_intersect_key($_POST, $arr_check_post));

    if ($count == count($arr_check_post)) {

      $user_exist = mysqli_query($mysqli, "SELECT * FROM users WHERE id=$_POST[user_id]");
      if (mysqli_num_rows($user_exist) > 0) {
        $result = mysqli_query($mysqli, "INSERT INTO products SET
                      user_id = '$_POST[user_id]',
                      name = '$_POST[name]',
                      price = '$_POST[price]'
                  ");

        if ($result) {
          $response = array(
            'success' => true,
            'message' => 'Product Added Successfully.'
          );
        } else {
          $response = array(
            'success' => false,
            'message' => 'Product Addition Failed.'
          );
        }
      } else {
        $response = array(
          'success' => false,
          'message' => 'Product Addition Failed (user doesnt exist).'
        );
      }
    } else {
      $response = array(
        'success' => false,
        'message' => 'Parameter Do Not Match'
      );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  function update_product($id)
  {
    global $mysqli;

    $arr_check_post = array('user_id' => '', 'name' => '', 'price' => '');
    $count = count(array_intersect_key($_POST, $arr_check_post));

    if ($count == count($arr_check_post)) {
      $user_exist = mysqli_query($mysqli, "SELECT * FROM users WHERE id=$_POST[user_id]");
      if (mysqli_num_rows($user_exist) > 0) {
        $result = mysqli_query($mysqli, "UPDATE products SET
                user_id = '$_POST[user_id]',
                name = '$_POST[name]',
                price = '$_POST[price]'
                WHERE id = '$id'");

        if ($result) {
          $response = array(
            'success' => true,
            'message' => 'Product Updated Successfully.'
          );
        } else {
          $response = array(
            'success' => false,
            'message' => 'Product Updation Failed.'
          );
        }
      } else {
        $response = array(
          'success' => false,
          'message' => 'Product Updation Failed (user doesnt exist).'
        );
      }
    } else {
      $response = array(
        'success' => false,
        'message' => 'Parameter Do Not Match'
      );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  function delete_product($id)
  {
    global $mysqli;

    $query = "DELETE FROM products WHERE id = " . $id;
    if (mysqli_query($mysqli, $query)) {
      $response = array(
        'success' => true,
        'message' => 'Product Deleted Successfully.'
      );
    } else {
      $response = array(
        'success' => false,
        'message' => 'Product Deletion Failed.'
      );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  // number 5
  function get_products_groupped_by_product_name()
  {
    global $mysqli;

    $data = array();

    $query = "SELECT name as product_name, COUNT(id) as product_count FROM products GROUP BY name ORDER BY product_count DESC";
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
      $data[] = $row;
    }

    $response = array(
      'success' => true,
      'message' => "Get Product Groupped by Product Name Successfully.",
      'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  // number 6
  function get_product_price_groupped_by_product_name()
  {
    global $mysqli;

    $data = array();

    $query = "SELECT name as product_name, MAX(price) as highest_price FROM products GROUP BY name ORDER BY highest_price DESC";
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
      $data[] = $row;
    }

    $response = array(
      'success' => true,
      'message' => "Get Product Prices Groupped by Product Name Successfully.",
      'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  // number 7
  function get_product_groupped_by_user_region()
  {
    global $mysqli;

    $data = array();

    $query = "SELECT u.region as user_region, COUNT(p.id) as product_count 
      FROM products as p 
      JOIN users as u ON u.id = p.user_id
      GROUP BY user_region 
      ORDER BY product_count DESC";
    $result = $mysqli->query($query);
    while ($row = mysqli_fetch_object($result)) {
      $data[] = $row;
    }

    $response = array(
      'success' => true,
      'message' => "Get Product Prices Groupped by Product Name Successfully.",
      'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
  }
}
