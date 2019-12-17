<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$billId = $_REQUEST['q'];

$query = "SELECT vendor.id, vendor.name
    FROM purchase_bill INNER JOIN vendor ON purchase_bill.vendorid = vendor.id WHERE purchase_bill.id = '$billId';";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['id'] = $row["id"];
        $temp['name'] = $row["name"];
        array_push($cursorArray,$temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
