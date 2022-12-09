<?php

    require_once "koneksi.php";
    
    class User {
        public  function get_all_users()
        {
            global $mysqli;

            $data = array();

            $query = "SELECT * FROM users";
            $result = $mysqli->query($query);
            while($row = mysqli_fetch_object($result)) {
                $data[] = $row;
            }

            $response = array(
                'success' => true,
                'message' =>'Get List Users Successfully.',
                'data' => $data
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        }

        public function get_user($id = 0)
        {
            global $mysqli;

            $query = "SELECT * FROM users";
            if ($id != 0) {
                $query .= " WHERE id=" . $id . " LIMIT 1";
            }

            $data = array();
            $result = $mysqli->query($query);
            while($row = mysqli_fetch_object($result)) {
                $data[] = $row;
            }

            $response = array(
                'success' => true,
                'message' =>'Get User Successfully.',
                'data' => $data
            );

            header('Content-Type: application/json');
            echo json_encode($response);    
        }

        public function insert_user()
        {
            global $mysqli;

            $arr_check_post = array('email' => '', 'name' => '', 'region' => '');
            $count = count(array_intersect_key($_POST, $arr_check_post));

            if ($count == count($arr_check_post)) {
            
                $result = mysqli_query($mysqli, "INSERT INTO users SET
                    email = '$_POST[email]',
                    name = '$_POST[name]',
                    region = '$_POST[region]'
                ");
                    
                if ($result) {
                    $response = array(
                        'success' => true,
                        'message' =>'User Added Successfully.'
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' =>'User Addition Failed.'
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'message' =>'Parameter Do Not Match'
                );
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }

        function update_user($id)
        {
            global $mysqli;
            
            $arr_check_post = array('email' => '', 'name' => '', 'region' => '');
            $count = count(array_intersect_key($_POST, $arr_check_post));

            if($count == count($arr_check_post)) {
            
                $result = mysqli_query($mysqli, "UPDATE users SET
                email = '$_POST[email]',
                name = '$_POST[name]',
                region = '$_POST[region]'
                WHERE id = '$id'");
            
                if ($result) {
                    $response = array(
                        'success' => true,
                        'message' =>'User Updated Successfully.'
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' =>'User Updation Failed.'
                    );
                }
            } else {
                $response = array(
                    'success' => false,
                    'message' =>'Parameter Do Not Match'
                );
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        }

        function delete_user($id)
        {
            global $mysqli;

            $query = "DELETE FROM users WHERE id = " . $id;
            if(mysqli_query($mysqli, $query)) {
                $response = array(
                    'success' => true,
                    'message' =>'User Deleted Successfully.'
                );
            } else {
                $response=array(
                    'success' => false,
                    'message' =>'User Deletion Failed.'
                );
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

 ?>