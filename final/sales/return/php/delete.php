<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$q = $_REQUEST["q"];
$CHECK_ITEM = "SELECT billid, pay, quantity , itemid  FROM sreturn WHERE id = '$q';";
$result = mysqli_query($conn, $CHECK_ITEM);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $billId = $row["billid"];
        $pay = $row["pay"];
        $quantity = $row["quantity"];
        $itemId = $row["itemid"];

        $balStock = "SELECT bal,sold FROM  stock WHERE itemid = '$itemId'";
        $balresult = mysqli_query($conn, $balStock);
        if (mysqli_num_rows($balresult) > 0) {
            while ($brow = mysqli_fetch_assoc($balresult)) {
                $upBal = $brow['bal'] - $quantity;
                $upSold = $brow['sold'] + $quantity;
                $upBalQuery = "UPDATE stock SET bal ='$upBal' , sold = '$upSold' WHERE itemid = '$itemId'";
                if (mysqli_query($conn, $upBalQuery)) {
                    $deletePayment = "DELETE FROM sreturn WHERE id = '$q';";
                    if (mysqli_query($conn, $deletePayment)) {
                        $response["success"] = true;
                    }

                }
            }
        }
    }
}

echo json_encode($response);
