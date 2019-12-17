<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$q = json_decode($_REQUEST["q"], true);

$qty = $q['qty'];
$billId = $q['billid'];
$vid = $q['vid'];
$itemId = $q['iid'];
$date = $q['date'];

$ALREADY_SAFE = false;
$upQty = 0;
$alreadyQuery = "SELECT id,quantity FROM ireturn WHERE itemid = '$itemId' AND billid = '$billId';";
$aresult = mysqli_query($conn, $alreadyQuery);
if (mysqli_num_rows($aresult) > 0) {
    while ($row = mysqli_fetch_assoc($aresult)) {
        $upQty = $qty - $row['quantity'];
    }
} else {
    $ALREADY_SAFE = true;
}
if ($ALREADY_SAFE) {
    $upQty = $qty;
}
if ($ALREADY_SAFE == false && $upQty < 1) {
    $response["success"] = true;
    $response["status"] = "ALREADY";

} else {

    $taxQuery = "SELECT  `amount`, `hsn`, `cgstper`, `sgstper`, `igstper`, `cgst`, `sgst`, `igst`, `total`
    FROM purchase_item WHERE itemid = '$itemId' AND purchaseid = '$billId';";
    $taxresult = mysqli_query($conn, $taxQuery);
    if (mysqli_num_rows($taxresult) > 0) {

        while ($row = mysqli_fetch_assoc($taxresult)) {

            $amount = $row['amount'];
            $hsn = $row['hsn'];
            $cgstper = $row['cgstper'];
            $sgstper = $row['sgstper'];
            $igstper = $row['igstper'];

            $cgst = $row['cgst'];
            $sgst = $row['sgst'];
            $igst = $row['igst'];

            $total = $row['total'];

            $uptaxable = $row['amount'] * $upQty;
            $upcgst = $row['cgst'] * $upQty;
            $upsgst = $row['sgst'] * $upQty;
            $upigst = $row['igst'] * $upQty;
            $uptotal = $row['total'] * $upQty;

            $insertReturn = "INSERT INTO `ireturn`(`billid`, `itemid`, `amount`,
           `hsn`, `cgstper`, `sgstper`, `igstper`, `cgst`, `sgst`, `igst`, `total`, `quantity`, `taxable`, `totcgst`, `totsgst`, `totigst`, `pay`, `rdate`)
           VALUES ('$billId' , '$itemId' , '$amount' , '$hsn' , '$cgstper' , '$sgstper' , '$igstper' , '$cgst' , '$sgst','$igst',
           '$total' , '$upQty' , '$uptaxable' , '$upcgst' , '$upsgst' , '$upigst' , '$uptotal','$date')";
            if (mysqli_query($conn, $insertReturn)) {

                //SETTING BILL CREDIT
                $billCreditQuery = "SELECT amount, returned, total, pay, balance FROM bill_credit WHERE billid = '$billId'";

                $billCreResult = mysqli_query($conn, $billCreditQuery);
                if (mysqli_num_rows($billCreResult) > 0) {
                    while ($bcrow = mysqli_fetch_assoc($billCreResult)) {

                        $upReturn = $bcrow['returned'] + $uptotal;
                        $upTotalC = $bcrow['amount'] - $upReturn;
                        $upbal = $bcrow['balance'] - $uptotal;
                        $upPay = $bcrow['pay'];
                        updateCurrentBillCredit($conn, $billId, $upReturn, $upTotalC, $upPay, $upbal);
                        $upDIF = 0;
                        if ($upbal < 0) {
                            $upDIF = -1 * $upbal;
                            $upbal = $upbal + $upDIF;
                            //$upPay = $upPay - $upDIF;

                            /*  $paymentSettleQuery = "SELECT id,amount FROM payment WHERE billid = '$billId'";
                            $paySresult = mysqli_query($conn, $paymentSettleQuery);
                            if (mysqli_num_rows($paySresult) > 0) {
                            $tempUpDif = $upDIF;
                            while ($psrow = mysqli_fetch_assoc($paySresult)) {

                            if ($tempUpDif > 0) {

                            $decAmount = $psrow['amount'] - $tempUpDif;

                            if ($decAmount > 0) {

                            $tempUpDif = 0;

                            //UPDATE WITH DECAMOUNT
                            updateDifOnPayment($conn,$decAmount, $psrow['id']);
                            } else if ($decAmount == 0) {

                            $tempUpDif = 0;
                            //DELETE THAT BILL
                            deleteDifPayment($conn,$psrow['id']);
                            } else {

                            $tempUpDif = -1 * $decAmount;
                            //DELETE THAT BILL
                            deleteDifPayment($conn,$psrow['id']);

                            }
                            }
                            }
                            }*/

                            $paymentSettleQuery = "SELECT bill_credit.billid, bill_credit.id,bill_credit.amount, bill_credit.returned, bill_credit.total, bill_credit.pay, bill_credit.balance
                           FROM bill_credit INNER JOIN purchase_bill ON purchase_bill.id = bill_credit.billid WHERE purchase_bill.vendorid = '$vid'";
                            $paySresult = mysqli_query($conn, $paymentSettleQuery);
                            if (mysqli_num_rows($paySresult) > 0) {
                                $tempUpDif = $upDIF;
                                while ($psrow = mysqli_fetch_assoc($paySresult)) {
                                    if ($tempUpDif > 0 && $psrow['billid'] != $billId && $psrow['balance'] > 0) {
                                        $UPBILLCREDITPAY = 0;
                                        if ($psrow['balance'] < $tempUpDif) {
                                            $UPBILLCREDITPAY = $psrow['pay'] + $psrow['balance'];
                                            $UPBILLCREDITBAL = 0;
                                            makePayment($date, $conn, $psrow['balance'], $psrow['billid']);
                                            $tempUpDif = $tempUpDif - $psrow['balance'];
                                            setCurrentPayment($billId, $psrow['balance'], $conn);

                                        } else {
                                            $UPBILLCREDITPAY = $psrow['pay'] + $tempUpDif;
                                            $UPBILLCREDITBAL = $psrow['balance'] - $tempUpDif;
                                            makePayment($date, $conn, $tempUpDif, $psrow['billid']);
                                            setCurrentPayment($billId, $tempUpDif, $conn);
                                            $tempUpDif = 0;
                                        }
                                        updateCredit($conn, $psrow['id'], $UPBILLCREDITPAY, $UPBILLCREDITBAL);

                                    }
                                }
                            }
                        }

                        $queryForVendCre = "SELECT `amount`, `returned`, `total`, `pay`, `balance`
                       FROM `vendor_credit` WHERE vid = '$vid'";
                        $resultForVendCre = mysqli_query($conn, $queryForVendCre);
                        if (mysqli_num_rows($resultForVendCre) > 0) {
                            while ($vcrow = mysqli_fetch_assoc($resultForVendCre)) {
                                $UPVCret = $vcrow['returned'] + $uptotal;

                                $UPVCtot = $vcrow['amount'] - $UPVCret;

                                $UPVCbal = $vcrow['balance'] - $uptotal;
                                $response["success"] = updateCurrentVendorCredit($upQty, $itemId, $conn, $vid, $UPVCret, $UPVCtot, $UPVCbal);
                            }
                        }

                    }
                }
            }

        }
    }
}

echo json_encode($response);

function updateDifOnPayment($conn, $amountPay, $idPay)
{

    $updatePayment = "UPDATE `payment` SET amount = '$amountPay' WHERE id = '$idPay'";
    if (mysqli_query($conn, $updatePayment)) {

    }
}

function deleteDifPayment($conn, $idPay)
{

    $deletePayQuery = "DELETE FROM payment WHERE id = '$idPay'";
    if (mysqli_query($conn, $deletePayQuery)) {
    }
}

function makePayment($date, $conn, $amotPay, $billIdPay)
{
    $insertPaymentQuery = "INSERT INTO payment(billid, amount, tdate) VALUES('$billIdPay', '$amotPay' , '$date')";
    if (mysqli_query($conn, $insertPaymentQuery)) {
    }
}

function updateCredit($conn, $idForCredit, $upCrePay, $upCreBal)
{
    $updateCreditBillQuery = "UPDATE bill_credit SET pay='$upCrePay',balance='$upCreBal' WHERE id = '$idForCredit'";
    if (mysqli_query($conn, $updateCreditBillQuery)) {
    }
}

function updateCurrentBillCredit($conn, $billId, $returnCBC, $totalCBC, $payCBC, $balCBC)
{
    $qryFrUptCrtBlCrd = "UPDATE `bill_credit` SET
    `returned`= '$returnCBC',`total`= '$totalCBC',`pay`='$payCBC',`balance`='$balCBC' WHERE billid = '$billId'";
    if (mysqli_query($conn, $qryFrUptCrtBlCrd)) {
    }
}

function updateCurrentVendorCredit($QTY, $itemId, $conn, $vid, $retVCU, $totVCU, $balVCU)
{
    $qryFrUptCrtVCrd = "UPDATE `vendor_credit` SET
    `returned`= '$retVCU',`total`= '$totVCU',`balance`='$balVCU' WHERE vid = '$vid'";
    if (mysqli_query($conn, $qryFrUptCrtVCrd)) {
        return stockReduce($QTY, $conn, $itemId);
    } else {
        return false;
    }
}

function stockReduce($QTY, $conn, $itemId)
{
    $stockQuery = "SELECT bal FROM stock WHERE itemid = '$itemId'";
    $stockresult = mysqli_query($conn, $stockQuery);
    if (mysqli_num_rows($stockresult) > 0) {
        while ($srow = mysqli_fetch_assoc($stockresult)) {
            $stockBal = $srow['bal'] - $QTY;
            $updateStock = "UPDATE stock SET bal='$stockBal' WHERE itemid = '$itemId'";
            if (mysqli_query($conn, $updateStock)) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }

}

function setCurrentPayment($billId, $tempUpDif, $conn)
{

    $paymentQuery = "SELECT id,amount FROM payment WHERE billid = '$billId'";
    $paySresult = mysqli_query($conn, $paymentQuery);
    if (mysqli_num_rows($paySresult) > 0) {
        while ($psrow = mysqli_fetch_assoc($paySresult)) {

            if ($tempUpDif > 0) {

                $decAmount = $psrow['amount'] - $tempUpDif;

                if ($decAmount > 0) {
                    $removedAmt = $tempUpDif;
                    $tempUpDif = 0;

                    //UPDATE WITH DECAMOUNT
                    updateDifOnPayment($conn, $decAmount, $psrow['id']);
                } else if ($decAmount == 0) {
                    $removedAmt = $tempUpDif;
                    $tempUpDif = 0;
                    //DELETE THAT BILL
                    deleteDifPayment($conn, $psrow['id']);
                } else {
                    $removedAmt = $tempUpDif - (-1 * $decAmount);
                    $tempUpDif = -1 * $decAmount;
                    //DELETE THAT BILL
                    deleteDifPayment($conn, $psrow['id']);
                }
                billCreditSetUpOnPaymentDel($conn, $billId, $removedAmt);
            }
        }
    }
}

function billCreditSetUpOnPaymentDel($conn, $billId, $decAmount)
{
    $billCreditQuery = "SELECT pay, balance FROM bill_credit WHERE billid = '$billId'";
    $billCreResult = mysqli_query($conn, $billCreditQuery);
    if (mysqli_num_rows($billCreResult) > 0) {
        while ($bcrow = mysqli_fetch_assoc($billCreResult)) {

            $upbal = $bcrow['balance'] + $decAmount;

            $upPay = $bcrow['pay'] - $decAmount;

            $qryFrUptCrtBlCrd = "UPDATE `bill_credit` SET `pay`='$upPay',`balance`='$upbal' WHERE billid = '$billId'";
            if (mysqli_query($conn, $qryFrUptCrtBlCrd)) {
            }
        }
    }
}
