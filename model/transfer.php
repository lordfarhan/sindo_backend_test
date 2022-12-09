<?php

require_once "koneksi.php";

class Transfer
{
  // number 10
  public  function get_all_transfers()
  {
    global $mysqli;

    $data = array();
    $user_id = $_GET['user_id'];

    $query = "SELECT * FROM transfers WHERE source_id = $user_id OR destination_id = $user_id";
    $result = $mysqli->query($query);
    $index = 0;
    while ($row = mysqli_fetch_object($result)) {
      if (strval($row->source_id) == strval($user_id)) {
        $row->type = 'OUT';
      } else {
        $row->type = 'IN';
      }
      $data[] = $row;
    }

    $response = array(
      'success' => true,
      'message' => "Get List Transfers Successfully.",
      'data' => $data
    );

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  // number 9
  public function insert_transfer()
  {
    global $mysqli;

    $arr_check_post = array('source_id' => '', 'destination_id' => '', 'nominal' => '');
    $count = count(array_intersect_key($_POST, $arr_check_post));

    if ($count == count($arr_check_post)) {

      $user_source_exist = mysqli_query($mysqli, "SELECT balance FROM users WHERE id=$_POST[source_id] LIMIT 1");
      $user_source_balance = mysqli_fetch_object($user_source_exist);
      // check is balance sufficient
      if ($user_source_balance->balance != null) {
        $nom = doubleval($_POST['nominal']);
        $bal = doubleval($user_source_balance->balance);
        if ($bal >= $nom) {
          $user_destination_exist = mysqli_query($mysqli, "SELECT balance FROM users WHERE id=$_POST[destination_id] LIMIT 1");
          if ($user_destination_exist != null) {

            // begin transaction
            $mysqli->autocommit(FALSE);
            $mysqli->query("INSERT INTO transfers SET
              source_id = '$_POST[source_id]',
              destination_id = '$_POST[destination_id]',
              nominal = '$_POST[nominal]'
          ");
            $mysqli->query("UPDATE users SET balance = balance - $nom WHERE id = '$_POST[source_id]'");
            $mysqli->query("UPDATE users SET balance = balance + $nom WHERE id = '$_POST[destination_id]'");

            $result = $mysqli->commit();

            if ($result) {
              $response = array(
                'success' => true,
                'message' => 'Transfer Added Successfully.'
              );
            } else {
              $response = array(
                'success' => false,
                'message' => 'Transfer Addition Failed.'
              );
            }
          } else {
            $response = array(
              'success' => false,
              'message' => 'Transfer Addition Failed (user destination doesnt exist).'
            );
          }
        } else {
          $response = array(
            'success' => false,
            'message' => 'Transfer Addition Failed (unsufficient balance).'
          );
        }
      } else {
        $response = array(
          'success' => false,
          'message' => 'Transfer Addition Failed (user source doesnt exist).'
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
}
