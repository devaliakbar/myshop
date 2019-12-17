<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

if (isset($_REQUEST["q"])) {
//BACKUP
    myShopBackup();

    $q = json_decode($_REQUEST["q"], true);
    $vid = $q['vid'];
    $billId = $q['billId'];
    $amount = $q['amount'];
    $upPay = $q['upPay'];
    $upBal = $q['upBal'];
    $VupPay = $q['VupPay'];
    $VupBal = $q['VupBal'];
    $date = $q['date'];

    $insertPaymentQuery = "INSERT INTO `payment`(billid, amount,tdate) VALUES ('$billId' , '$amount','$date');";
    if (mysqli_query($conn, $insertPaymentQuery)) {
        $updateBill = "UPDATE `bill_credit` SET `pay`='$upPay',`balance`='$upBal' WHERE billid = '$billId'";
        if (mysqli_query($conn, $updateBill)) {
            $updateVendor = "UPDATE `vendor_credit` SET `pay`='$VupPay',`balance`='$VupBal' WHERE vid = '$vid'";
            if (mysqli_query($conn, $updateVendor)) {
                $response["success"] = true;
            }
        }
    }

}
echo json_encode($response);
