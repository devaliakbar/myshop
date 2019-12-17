<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$q = json_decode($_REQUEST["q"], true);
$value = $q['value'];
$filter = $q['filter'];
$query = "SELECT item.name AS r1,item.id AS r2,item.catagory AS r3,item.manufactor AS r4,amount.total AS r5 FROM item INNER JOIN amount ON item.id = amount.itemid WHERE amount.type='P' AND item." . $filter . " LIKE '%" . $value . "%';";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['name'] = $row["r1"];
        $temp['id'] = $row["r2"];
        $temp['cat'] = $row["r3"];
        $temp['manu'] = $row["r4"];
        $temp['price'] = $row["r5"];
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
