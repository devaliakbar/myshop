<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = true;
$response["status"] = "INVALID";
$itemId = $_REQUEST["q"];

$query = "SELECT id FROM purchase_item WHERE itemid = '$itemId';";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) < 1) {
//BACKUP
    myShopBackup();

    $deleteQuery = "DELETE FROM amount WHERE itemid = '$itemId'";
    if (mysqli_query($conn, $deleteQuery)) {
    } else {
        $response["success"] = false;
    }

    $deleteQuery = "DELETE FROM item WHERE id = '$itemId'";
    if (mysqli_query($conn, $deleteQuery)) {
    } else {
        $response["success"] = false;
    }

    $deleteQuery = "DELETE FROM stock WHERE itemid = '$itemId'";
    if (mysqli_query($conn, $deleteQuery)) {
    } else {
        $response["success"] = false;
    }

    $deleteQuery = "DELETE FROM tax WHERE itemid = '$itemId'";
    if (mysqli_query($conn, $deleteQuery)) {
    } else {
        $response["success"] = false;
    }

} else {
    $response["status"] = "USING";
}

echo json_encode($response);
