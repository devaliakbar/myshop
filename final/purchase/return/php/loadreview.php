<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$q = $_REQUEST["q"];
$query = "SELECT ireturn.id, ireturn.itemid, ireturn.amount, ireturn.hsn, ireturn.cgstper, ireturn.sgstper, ireturn.igstper,
 ireturn.cgst, ireturn.sgst, ireturn.igst, ireturn.total, ireturn.quantity,
 ireturn.taxable, ireturn.totcgst, ireturn.totsgst, ireturn.totigst, ireturn.pay, ireturn.rdate ,item.name,item.catagory,item.manufactor
 FROM ireturn INNER JOIN item ON ireturn.itemid = item.id
 WHERE ireturn.billid = '$q';";

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
