<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$q = $_REQUEST["q"];
$query = "SELECT sreturn.id, sreturn.itemid, sreturn.amount, sreturn.hsn, sreturn.cgstper, sreturn.sgstper, sreturn.igstper,
 sreturn.cgst, sreturn.sgst, sreturn.igst, sreturn.total, sreturn.quantity,
 sreturn.taxable, sreturn.totcgst, sreturn.totsgst, sreturn.totigst, sreturn.pay, sreturn.rdate ,item.name,item.catagory,item.manufactor
 FROM sreturn INNER JOIN item ON sreturn.itemid = item.id
 WHERE sreturn.billid = '$q';";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['date'] = $row["rdate"];
        $temp['id'] = $row["id"];
        $temp['itemname'] = $row["name"];
        $temp['itemid'] = $row["itemid"];
        $temp['itemmanufactor'] = $row["manufactor"];
        $temp['itemcatagory'] = $row["catagory"];
        $temp['price'] = $row["amount"];
        $temp['taxhsn'] = $row["hsn"];
        $temp['cgst'] = $row["cgst"];
        $temp['sgst'] = $row["sgst"];
        $temp['igst'] = $row["igst"];
        $temp['total'] = $row["total"];
        $temp['qty'] = $row["quantity"];
        $temp['taxable'] = $row["taxable"];
        $temp['pay'] = $row["pay"];

        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
