<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$billId = $q['billid'];
$vendor = $q['vendor'];
$vid = $q['vid'];

$date = $q['date'];
$tax = $q['tax'];

$taxi = $q['taxi'];
$taxc = $q['taxc'];
$taxs = $q['taxs'];

$mrp = $q['mrp'];
$dis = $q['dis'];
$discper = $q['discper'];

$igst = $q['igst'];
$sgst = $q['sgst'];
$cgst = $q['cgst'];
$pay = $q['pay'];

$returnCheck = "SELECT id FROM sreturn WHERE billid = '$billId'";
$checkReturn = mysqli_query($conn, $returnCheck);
if (mysqli_num_rows($checkReturn) > 0) {
    $response["success"] = true;
    $response["status"] = "RETURN";
} else {

//************************* */SETTING VENDOR**************************
    $VENDOR_FLAG = false;
    if ($vendor) {
        $vname = $q['vname'];
        $vpho = $q['vpho'];
        $vaddr = $q['vaddr'];
        $vgst = $q['vgst'];
        $insertQuery = "UPDATE customer SET name = '$vname' , phone = '$vpho', address = '$vaddr' , gst ='$vgst' WHERE id = '$vid';";
        if (mysqli_query($conn, $insertQuery)) {
            $VENDOR_FLAG = true;
        }
    } else {
        $VENDOR_FLAG = true;
    }

    $BILL_FLAG = false;
    $updateBill = "UPDATE sales_bill SET dates = '$date', custid = '$vid' ,amount = '$tax' ,cgstper = '$taxc' ,sgstper = '$taxs' ,
igstper = '$taxi' , cgst = '$cgst' , sgst = '$sgst' , igst = '$igst' , total = '$mrp' , discount = '$dis' , discountper = '$discper' , pay = '$pay'
WHERE id = '$billId';";
    if (mysqli_query($conn, $updateBill)) {
        $BILL_FLAG = true;
    }

    if ($VENDOR_FLAG && $BILL_FLAG) {
        $response["success"] = true;
    }
}
echo json_encode($response);
