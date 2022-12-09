<?php
require_once "model/transfer.php";

$transfer = new Transfer();

$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
  case 'GET':
    $transfer->get_all_transfers();
    break;
  case 'POST':
    $transfer->insert_transfer();
    break;
  default:
    // Invalid Request Method
    header("HTTP/1.0 405 Method Not Allowed");
    break;
    break;
}
