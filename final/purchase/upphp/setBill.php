<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$query = "SELECT id FROM purchase_bill WHERE vendorid = '0';";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $row = mysqli_fetch_assoc($result);
    $response["billid"] = $row["id"];
} else {
    $query = "INSERT INTO purchase_bill(vendorid) VALUES('0');";
    if (mysqli_query($conn, $query)) {
        $response["billid"] = mysqli_insert_id($conn);
        $response["success"] = true;
    } else {
        $response["status"] = "INSERT ERROR";
    }
}
echo json_encode($response);
