<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = $_REQUEST["q"];
$query = "SELECT id AS r1, name AS r2,address AS r3,phone AS r4 ,gst as r5
    FROM vendor WHERE id LIKE '%" . $q . "%';";
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
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
