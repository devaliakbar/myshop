<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

if (isset($_REQUEST["q"])) {
    $q = json_decode($_REQUEST["q"], true);
    $id = $q['id'];
    $name = $q['name'];
    $phone = $q['phone'];

    $selection = " WHERE vendor.id > 0";
    if ($id != "") {
        $selection .= " AND vendor.id = '$id'";
    }
    if ($name != "") {
        $selection .= " AND vendor.name LIKE '%$name%'";
    }
    if ($phone != "") {
        $selection .= " AND vendor.phone LIKE '$phone%'";
    }

    $query = "SELECT vendor.id, vendor.name, vendor.phone, vendor.address,
        vendor_credit.amount,vendor_credit.returned,vendor_credit.total,vendor_credit.pay,vendor_credit.balance
        FROM vendor_credit INNER JOIN vendor ON vendor_credit.vid = vendor.id$selection;";

} else {
    $query = "SELECT vendor.id, vendor.name, vendor.phone, vendor.address,
        vendor_credit.amount,vendor_credit.returned,vendor_credit.total,vendor_credit.pay,vendor_credit.balance
        FROM vendor_credit INNER JOIN vendor ON vendor_credit.vid = vendor.id;";
}
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['id'] = $row["id"];
        $temp['name'] = $row["name"];
        $temp['phone'] = $row["phone"];
        $temp['addr'] = $row["address"];
        $temp['amount'] = $row["amount"];
        $temp['return'] = $row["returned"];
        $temp['total'] = $row["total"];
        $temp['pay'] = $row["pay"];
        $temp['bal'] = $row["balance"];
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
