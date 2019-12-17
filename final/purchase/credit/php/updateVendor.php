<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$q = json_decode($_REQUEST["q"], true);

$vid = $q['vid'];
$vname = $q['name'];
$vgst = $q['gst'];
$vaddr = $q['addrs'];
$vphone = $q['phone'];

$updateVendor = "UPDATE `vendor` SET `name`='$vname',`phone`='$vphone',`address`='$vaddr',`gst`='$vgst'
WHERE id = '$vid'";
if (mysqli_query($conn, $updateVendor)) {
    $response["success"] = true;
}

echo json_encode($response);
