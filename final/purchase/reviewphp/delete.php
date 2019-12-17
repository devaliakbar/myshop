<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = $_REQUEST["q"];
//BACKUP
myShopBackup();

$creditCheck = "SELECT id FROM ireturn WHERE billid = '$q'";
$checkCredit = mysqli_query($conn, $creditCheck);
if (mysqli_num_rows($checkCredit) > 0) {
    $response["success"] = true;
    $response["status"] = "RETURN";
} else {

    $creditCheck = "SELECT id FROM payment WHERE billid = '$q'";
    $checkCredit = mysqli_query($conn, $creditCheck);
    if (mysqli_num_rows($checkCredit) > 0) {
        $response["success"] = true;
        $response["status"] = "CREDIT";
    } else {

//*************************/SETTING STOCK****************************
        $STOCK_FLAG = true;
        $STOCK_STOP = false;
        $temp = array();

        $query = "SELECT itemid,quantity FROM purchase_item WHERE purchaseid = $q;";
        $aresult = mysqli_query($conn, $query);
        if (mysqli_num_rows($aresult) > 0) {
            while ($row = mysqli_fetch_assoc($aresult)) {
                $stockId = $row["itemid"];
                $qnt = $row["quantity"];
                $query = "SELECT bal FROM stock WHERE itemid = $stockId;";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($arow = mysqli_fetch_assoc($result)) {
                        $bal = $arow["bal"] - $qnt;
                        if ($bal < 0) {
                            $STOCK_STOP = true;
                            $response["status"] = "LESS THAN 0";
                            $temp["$stockId"] = $bal;
                        }
                    }
                }
            }
        }

        if (!$STOCK_STOP) {
            $query = "SELECT itemid,quantity FROM purchase_item WHERE purchaseid = $q;";
            $aresult = mysqli_query($conn, $query);
            if (mysqli_num_rows($aresult) > 0) {
                while ($row = mysqli_fetch_assoc($aresult)) {
                    $stockId = $row["itemid"];
                    $qnt = $row["quantity"];
                    //SELECT EACH STOCK BALANCE
                    $query = "SELECT bal FROM stock WHERE itemid = $stockId;";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $bal = $row["bal"] - $qnt;
                            //UPDATING
                            $updatestock = "UPDATE stock SET bal = '$bal' WHERE itemid='$stockId';";
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
        }
//************************ */SETTING STOCK END****************************
        $SALE_FLAG = false;
        $CREDIT_FLAG = true;
        if (!$STOCK_STOP) {

            //******************** */CREDIT SETTING STARTS HERE*********************************
            $query = "SELECT vendorid,total FROM purchase_bill WHERE id = '$q';";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $vid = $row['vendorid'];
                    $pay = $row['total'];

                    $query = "SELECT balance,pay FROM bill_credit WHERE billid = '$q';";
                    $aaresult = mysqli_query($conn, $query);
                    if (mysqli_num_rows($aaresult) > 0) {
                        while ($row = mysqli_fetch_assoc($aaresult)) {
                            $bals = $row["balance"];
                            $payFromBill = $row["pay"];

                            $query = "SELECT amount,total,balance,pay FROM vendor_credit WHERE vid = '$vid';";
                            $aresult = mysqli_query($conn, $query);
                            if (mysqli_num_rows($aresult) > 0) {
                                while ($row = mysqli_fetch_assoc($aresult)) {
                                    $amount = $row["amount"] - $pay;
                                    $total = $row["total"] - $pay;
                                    $bal = $row["balance"] - $bals;
                                    $payUp = $row["pay"] - $payFromBill;
                                    $updateQuery = "UPDATE `vendor_credit` SET `amount`='$amount', `total`='$total',`balance`='$bal' , `pay`= '$payUp' WHERE vid = $vid;";
                                    if (mysqli_query($conn, $updateQuery)) {

                                    } else {
                                        $CREDIT_FLAG = false;
                                    }
                                }
                            } else {
                                $CREDIT_FLAG = false;
                            }

                        }
                    } else {
                        $CREDIT_FLAG = false;
                    }

                }
            } else {
                $CREDIT_FLAG = false;
            }

            $deleteQuery = "DELETE FROM bill_credit WHERE billid = '$q'";
            if (mysqli_query($conn, $deleteQuery)) {

            } else {
                $CREDIT_FLAG = false;
            }

            //******************** */CREDIT SETTING ENDS HERE*********************************

            $deleteQuery = "DELETE FROM purchase_item WHERE purchaseid = '$q'";
            if (mysqli_query($conn, $deleteQuery)) {
                $SALE_FLAG = true;
            } else {
                $response["status:ITEMBILL:"] = "FAILED TO DELETE";
            }

            $deleteQuery = "DELETE FROM purchase_bill WHERE id = '$q'";
            $BILL_FLAG = false;
            if (mysqli_query($conn, $deleteQuery)) {
                $BILL_FLAG = true;
            } else {
                $response["status:ITEMBILL:"] = "FAILED TO DELETE";
            }
        }

        if ($SALE_FLAG && $STOCK_FLAG && $BILL_FLAG && $CREDIT_FLAG) {
            $response["success"] = true;
        }
    }
}

echo json_encode($response);
