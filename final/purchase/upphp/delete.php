<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$q = json_decode($_REQUEST["q"], true);
$billId = $q['billId'];

$creditCheck = "SELECT id FROM ireturn WHERE billid = '$billId'";
$checkCredit = mysqli_query($conn, $creditCheck);
if (mysqli_num_rows($checkCredit) > 0) {
    $response["success"] = true;
    $response["status"] = "RETURN";
} else {

    $creditCheck = "SELECT id FROM payment WHERE billid = '$billId'";
    $checkCredit = mysqli_query($conn, $creditCheck);
    if (mysqli_num_rows($checkCredit) > 0) {
        $response["success"] = true;
        $response["status"] = "CREDIT";
    } else {

        $queryOneItemCheck = "SELECT id FROM purchase_item WHERE purchaseid = '$billId';";
        $resultOneItemCheck = mysqli_query($conn, $queryOneItemCheck);
        if (mysqli_num_rows($resultOneItemCheck) == 1) {
            $response["success"] = true;
            $response["status"] = "ONE";
        } else {

            $itemId = $q['id'];
            $vid = $q['vid'];
            $pay = null;
            $STOCK_FLAG = false;
            $STOCK_BLOCK = false;

            $qntQuery = "SELECT quantity,pay FROM purchase_item WHERE itemid = $itemId AND  purchaseid = '$billId';";
            $qtyresult = mysqli_query($conn, $qntQuery);
            if (mysqli_num_rows($qtyresult) > 0) {
                while ($row = mysqli_fetch_assoc($qtyresult)) {
                    $qnt = $row["quantity"];
                    $pay = $row["pay"];
                    $query = "SELECT bal FROM stock WHERE itemid = $itemId;";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $bal = $row["bal"] - $qnt;
                            if ($bal < 0) {
                                $response["status"] = "STOCK";
                                $STOCK_FLAG = true;
                                $STOCK_BLOCK = true;
                            } else {
                                //UPDATING
                                $updatestock = "UPDATE stock SET bal = '$bal' WHERE itemid='$itemId';";
                                if (mysqli_query($conn, $updatestock)) {
                                    $STOCK_FLAG = true;
                                }
                            }
                        }
                    }

                }
            }
            $DELETE_FLAG = false;
            $CREDIT_FLAG = true;
            if (!$STOCK_BLOCK == true && $STOCK_FLAG == true) {
                $deleteQuery = "DELETE FROM purchase_item WHERE itemid = '$itemId' AND purchaseid = '$billId'";
                if (mysqli_query($conn, $deleteQuery)) {
                    $DELETE_FLAG = true;
                }

                //******************** */CREDIT SETTING STARTS HERE*********************************

                $query = "SELECT balance,pay,amount,total FROM bill_credit WHERE billid = '$billId';";
                $aaresult = mysqli_query($conn, $query);
                if (mysqli_num_rows($aaresult) > 0) {
                    while ($row = mysqli_fetch_assoc($aaresult)) {
                        $bals = $row["balance"];
                        $payFromBill = $row["pay"];
                        $aamount = $row["amount"];
                        $aatotal = $row["total"];

                        $query = "SELECT amount,total,balance,pay FROM vendor_credit WHERE vid = '$vid';";
                        $aresult = mysqli_query($conn, $query);
                        if (mysqli_num_rows($aresult) > 0) {
                            while ($row = mysqli_fetch_assoc($aresult)) {
                                $amount = $row["amount"] - $pay;
                                $total = $row["total"] - $pay;
                                $bal = ($row["balance"] - $bals) + ($aatotal - $pay);
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

//UPDATING BILL CREDIT
                        $amount = $aamount - $pay;
                        $total = $aatotal - $pay;
                        $bal = $total;
                        $payUp = '0';
                        $updateQuery = "UPDATE `bill_credit` SET `amount`='$amount', `total`='$total',`balance`='$bal' , `pay`= '$payUp' WHERE billid = '$billId';";
                        if (mysqli_query($conn, $updateQuery)) {

                        } else {
                            $CREDIT_FLAG = false;
                        }

                    }
                } else {
                    $CREDIT_FLAG = false;
                }
//******************** */CREDIT SETTING ENDS HERE*********************************

            } else {
                $DELETE_FLAG = true;
            }

            if ($STOCK_FLAG == true && $DELETE_FLAG == true && $CREDIT_FLAG == true) {
                $response["success"] = true;
            }

        }
    }
}
echo json_encode($response);
