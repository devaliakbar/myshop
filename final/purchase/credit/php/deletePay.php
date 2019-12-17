<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$q = json_decode($_REQUEST["q"], true);

$vid = $q['vid'];
$billId = $q['billId'];
$paid = $q['paid'];
$tid = $q['tid'];

$billQuery = "SELECT pay,balance FROM bill_credit where billid = '$billId'";
$result = mysqli_query($conn, $billQuery);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $UPPAY = $row['pay'] - $paid;
        $UPBAL = $paid + $row['balance'];
        $updateBill = "UPDATE `bill_credit` SET `pay`='$UPPAY',`balance`='$UPBAL' WHERE billid = '$billId'";
        $response["query1:"] = $updateBill;
        if (mysqli_query($conn, $updateBill)) {

            $venQuery = "SELECT pay,balance FROM vendor_credit where vid = '$vid'";
            $result = mysqli_query($conn, $venQuery);
            if (mysqli_num_rows($result) > 0) {
                while ($rows = mysqli_fetch_assoc($result)) {
                    $uppay = $rows['pay'] - $paid;
                    $upbal = $paid + $rows['balance'];
                    $updateVendor = "UPDATE `vendor_credit` SET `pay`='$uppay',`balance`='$upbal' WHERE vid = '$vid'";
                    $response["query2:"] = $updateVendor;
                    if (mysqli_query($conn, $updateVendor)) {
                        $deletePay = "DELETE FROM payment WHERE id = '$tid'";
                        $response["query3:"] = $deletePay;
                        if (mysqli_query($conn, $deletePay)) {
                            $response["success"] = true;
                        }
                    }
                }
            }

        }
    }
}
echo json_encode($response);
