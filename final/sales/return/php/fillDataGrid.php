<?php
include_once '../../../php/conn.php';
$q = $_REQUEST["q"];
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$query = "SELECT item.id as r1,item.name as r2,item.manufactor as r3,item.catagory as r4,
    sales_item.amount as r5,sales_item.total as r6,sales_item.quantity as r7,sales_item.taxable as r8,sales_item.pay as r9,
    tax.cgstper as r10,tax.sgstper as r11,tax.igstper as r12,tax.hsn as r13,
    sales_item.totcgst as r14,sales_item.totsgst as r15,sales_item.totigst as r16,
    sales_item.mrp as r17,sales_item.totmrp as r18,sales_item.discount as r19, sales_item.discountper as r20
     FROM item INNER JOIN sales_item ON item.id = sales_item.itemid
     INNER JOIN tax ON item.id = tax.itemid
     WHERE sales_item.billid ='" . $q . "';";
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

        $temp['price'] = $row["r5"];
        $temp['tot'] = $row["r6"];
        $temp['qnt'] = $row["r7"];
        $temp['tax'] = $row["r8"];
        $temp['pay'] = $row["r9"];

        $temp['taxcgst'] = $row["r10"];
        $temp['taxsgst'] = $row["r11"];
        $temp['taxigst'] = $row["r12"];
        $temp['taxhsn'] = $row["r13"];

        $temp['cgst'] = $row["r14"];
        $temp['sgst'] = $row["r15"];
        $temp['igst'] = $row["r16"];

        $temp['mrp'] = $row["r17"];
        $temp['totmrp'] = $row["r18"];
        $temp['disc'] = $row["r19"];
        $temp['discper'] = $row["r20"];

        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
