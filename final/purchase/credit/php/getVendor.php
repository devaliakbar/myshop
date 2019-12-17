<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$vid = $_REQUEST["q"];

$query = "SELECT vendor.name, vendor.phone, vendor.address, vendor.gst, 
 vendor_credit.amount,vendor_credit.returned,vendor_credit.total,vendor_credit.pay,vendor_credit.balance
 FROM vendor INNER JOIN vendor_credit ON vendor_credit.vid = vendor.id
WHERE vendor.id = '$vid'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['gst'] = $row["gst"];
        $temp['name'] = $row["name"];
        $temp['phone'] = $row["phone"];
        $temp['addr'] = $row["address"];

        $temp['amt'] = $row["amount"];
        $temp['ret'] = $row["returned"];
        $temp['tot'] = $row["total"];
        $temp['paid'] = $row["pay"];
        $temp['bal'] = $row["balance"];
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
