<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = $_REQUEST["q"];
//BACKUP
myShopBackup();

$returnCheck = "SELECT id FROM sreturn WHERE billid = '$q'";
$checkReturn = mysqli_query($conn, $returnCheck);
if (mysqli_num_rows($checkReturn) > 0) {
    $response["success"] = true;
    $response["status"] = "RETURN";
} else {

    //************************ */SETTING STOCK****************************
    $STOCK_FLAG = true;
    $query = "SELECT itemid,quantity FROM sales_item WHERE billid = $q;";
    $aresult = mysqli_query($conn, $query);
    if (mysqli_num_rows($aresult) > 0) {
        while ($row = mysqli_fetch_assoc($aresult)) {
            $stockId = $row["itemid"];
            $qnt = $row["quantity"];
            //SELECT EACH STOCK BALANCE
            $query = "SELECT bal,sold FROM stock WHERE itemid = $stockId;";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $bal = $row["bal"] + $qnt;
                    $sold = $row["sold"] - $qnt;
                    //UPDATING
                    $updatestock = "UPDATE stock SET bal = '$bal' , sold ='$sold' WHERE itemid='$stockId';";
                    if (mysqli_query($conn, $updatestock)) {

                    } else {
                        $STOCK_FLAG = false;
                    }
                }

            } else {
                $STOCK_FLAG = false;
            }
        }

    } else {
        $STOCK_FLAG = false;
    }

//************************ */SETTING STOCK END****************************

    $deleteQuery = "DELETE FROM sales_item WHERE billid = '$q'";
    $SALE_FLAG = false;
    if (mysqli_query($conn, $deleteQuery)) {
        $SALE_FLAG = true;
    } else {
        $response["status:ITEMBILL:"] = "FAILED TO DELETE";
    }

    $selectVendorQuery = "SELECT custid FROM sales_bill WHERE id = '$q'";
    $custresult = mysqli_query($conn, $selectVendorQuery);
    if (mysqli_num_rows($custresult) > 0) {
        while ($crow = mysqli_fetch_assoc($custresult)) {
            $custId = $crow["custid"];
            $deleteCustQuery = "DELETE FROM customer WHERE id = '$custId'";
            if (mysqli_query($conn, $deleteCustQuery)) {

            }
        }
    }

    $deleteQuery = "DELETE FROM sales_bill WHERE id = '$q'";
    $BILL_FLAG = false;
    if (mysqli_query($conn, $deleteQuery)) {
        $BILL_FLAG = true;
    } else {
        $response["status:ITEMBILL:"] = "FAILED TO DELETE";
    }

    if ($SALE_FLAG && $STOCK_FLAG && $BILL_FLAG) {
        $response["success"] = true;
    }
}
echo json_encode($response);
