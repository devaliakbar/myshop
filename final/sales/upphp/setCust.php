<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = $_REQUEST["q"];
//BACKUP
myShopBackup();

$query = "SELECT customer.id as r1, customer.name as r2,customer.address as r3,customer.phone as r4,customer.gst as r5,
	sales_bill.dates as a6
    FROM customer INNER JOIN sales_bill ON sales_bill.custid = customer.id WHERE sales_bill.id ='$q';";
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

        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
$response["query"] = $query;
echo json_encode($response);
