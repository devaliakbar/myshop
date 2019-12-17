<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = $_REQUEST["q"];
$query = "SELECT item.id as r1,item.name as r2,item.manufactor as r3,item.catagory as r4,
    amount.price as r5,amount.cgst as r6,amount.sgst as r7,amount.igst as r8,amount.total as r9,
    tax.cgstper as r10,tax.sgstper as r11,tax.igstper as r12,tax.hsn as r13,stock.bal as r14
     FROM item INNER JOIN amount ON item.id = amount.itemid
     INNER JOIN tax ON item.id = tax.itemid
	 INNER JOIN stock ON stock.itemid = item.id
     WHERE item.id = '" . $q . "' AND  amount.type='S';";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['itemname'] = $row["r2"];
        $temp['itemid'] = $row["r1"];
        $temp['itemmanufactor'] = $row["r3"];
        $temp['itemcatagory'] = $row["r4"];

        $temp['amountprice'] = $row["r5"];
        $temp['amountcgst'] = $row["r6"];
        $temp['amountsgst'] = $row["r7"];
        $temp['amountigst'] = $row["r8"];
        $temp['amounttotal'] = $row["r9"];

        $temp['taxcgst'] = $row["r10"];
        $temp['taxsgst'] = $row["r11"];
        $temp['taxigst'] = $row["r12"];
        $temp['taxhsn'] = $row["r13"];

        $temp['stock'] = $row["r14"];

        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
