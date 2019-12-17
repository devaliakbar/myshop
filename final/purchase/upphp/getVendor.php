<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = $_REQUEST["q"];

$query = "SELECT vendor.id as r1, vendor.name as r2,vendor.address as r3,vendor.phone as r4,vendor.gst as r5,
	purchase_bill.dates as a6, purchase_bill.amount as a7, purchase_bill.cgstper as a8, purchase_bill.sgstper as a9, purchase_bill.igstper as a10,
	purchase_bill.cgst as a11, purchase_bill.sgst as a12, purchase_bill.igst as a13, purchase_bill.total as a14, purchase_bill.vbid as a15
    FROM vendor INNER JOIN purchase_bill ON purchase_bill.vendorid = vendor.id WHERE purchase_bill.id ='$q';";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['id'] = $row["r1"];
        $temp['name'] = $row["r2"];
        $temp['addrs'] = $row["r3"];
        $temp['phone'] = $row["r4"];
        $temp['gst'] = $row["r5"];

        $temp['date'] = $row["a6"];
        $temp['tax'] = $row["a7"];
        $temp['cp'] = $row["a8"];
        $temp['sp'] = $row["a9"];
        $temp['ip'] = $row["a10"];
        $temp['c'] = $row["a11"];
        $temp['s'] = $row["a12"];
        $temp['i'] = $row["a13"];
        $temp['tot'] = $row["a14"];
        $temp['vbid'] = $row["a15"];
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
$response["query"] = $query;
echo json_encode($response);
