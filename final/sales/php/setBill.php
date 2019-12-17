<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$query = "SELECT id FROM sales_bill WHERE custid = '0';";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $row = mysqli_fetch_assoc($result);
    $response["billid"] = $row["id"];
} else {
    //BACKUP
    myShopBackup();
    $query = "INSERT INTO sales_bill(custid) VALUES('0');";
    if (mysqli_query($conn, $query)) {
        $response["billid"] = mysqli_insert_id($conn);
        $response["success"] = true;
    } else {
        $response["status"] = "INSERT ERROR";
    }
}
echo json_encode($response);
