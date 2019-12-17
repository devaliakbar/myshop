<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$que = json_decode($_REQUEST["q"], true);

$date = $que['date'];

$q = $que["id"];

$CHECK_ITEM = "SELECT billid, pay, quantity , itemid  FROM ireturn WHERE id = '$q';";
$result = mysqli_query($conn, $CHECK_ITEM);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $billId = $row["billid"];
        $pay = $row["pay"];
        $quantity = $row["quantity"];
        $itemId = $row["itemid"];

        $balStock = "SELECT bal FROM  stock WHERE itemid = '$itemId'";
        $balresult = mysqli_query($conn, $balStock);
        if (mysqli_num_rows($balresult) > 0) {
            while ($brow = mysqli_fetch_assoc($balresult)) {
                $upBal = $brow['bal'] + $quantity;

                $upBalQuery = "UPDATE stock SET bal ='$upBal' WHERE itemid = '$itemId'";
                if (mysqli_query($conn, $upBalQuery)) {

                    $creditQuery = "SELECT purchase_bill.vendorid, bill_credit.billid, bill_credit.amount, bill_credit.returned,
                    bill_credit.total, bill_credit.pay, bill_credit.balance
                     FROM bill_credit INNER JOIN purchase_bill ON purchase_bill.id = bill_credit.billid
                    WHERE bill_credit.billid = '$billId';";
                    $creResult = mysqli_query($conn, $creditQuery);
                    if (mysqli_num_rows($creResult) > 0) {
                        while ($crow = mysqli_fetch_assoc($creResult)) {
                            $vid = $crow['vendorid'];
                            $amount = $crow['amount'];
                            $return = $crow['returned'] - $pay;
                            $total = $crow['total'] + $pay;
                            $paid = $crow['pay'];
                            $balance = $crow['balance'] + $pay;

                            $creditUpdate = "UPDATE `bill_credit` SET
                        `returned`=$return,`total`=$total,`balance`=$balance
                            WHERE billid ='$billId';";
                            if (mysqli_query($conn, $creditUpdate)) {
                                $vendorQuery = "SELECT `amount`, `returned`, `total`, `pay`, `balance`
                                FROM `vendor_credit`
                                WHERE vid = '$vid';";
                                $vResult = mysqli_query($conn, $vendorQuery);
                                if (mysqli_num_rows($vResult) > 0) {
                                    while ($vrow = mysqli_fetch_assoc($vResult)) {
                                        $vamount = $vrow['amount'];
                                        $vreturn = $vrow['returned'] - $pay;
                                        $vtotal = $vrow['total'] + $pay;
                                        $vpaid = $vrow['pay'];
                                        $vbalance = $vrow['balance'] + $pay;
                                        $updateVendor = "UPDATE `vendor_credit` SET
                                        `returned`=$vreturn,`total`=$vtotal,`balance`=$vbalance
                                            WHERE vid ='$vid'";
                                        if (mysqli_query($conn, $updateVendor)) {

                                            $vbal = $vrow['balance'] * -1;
                                            $vbal = $vbal - ($crow['balance'] * -1);
                                            $cbal = $balance;

                                            if ($vbal > 0 && $cbal > 0) {
                                                if ($vbal > $cbal) {
                                                    $uppay = $cbal;
                                                    $upBal = 0;
                                                    $upAmount = $cbal;
                                                } else {
                                                    $uppay = $vbal;
                                                    $upBal = $cbal - $vbal;
                                                    $upAmount = $vbal;
                                                }

                                                $uppay = $crow['pay'] + $uppay;
                                                $ccreditUpdate = "UPDATE `bill_credit` SET
                                                `balance`='$upBal',pay='$uppay'
                                                 WHERE billid ='$billId';";

                                                if (mysqli_query($conn, $ccreditUpdate)) {
                                                    setCurrentPayment($vid, $upAmount, $conn);
                                                    $paymentCreate = "INSERT INTO `payment`(`billid`, `amount`, `tdate`) VALUES
                                                ('$billId' ,'$upAmount' , '$date')";
                                                    if (mysqli_query($conn, $paymentCreate)) {

                                                    }
                                                }
                                            }

                                            $deletePayment = "DELETE FROM ireturn WHERE id = '$q';";
                                            if (mysqli_query($conn, $deletePayment)) {
                                                $response["success"] = true;
                                            }
                                        }
                                    }
                                }

                            }

                        }
                    }
                }
            }
        }
    }
}

echo json_encode($response);

function setCurrentPayment($vid, $diffFrom, $conn)
{

    $negBill = "SELECT bill_credit.billid
    FROM bill_credit INNER JOIN purchase_bill ON purchase_bill.id = bill_credit.billid
     WHERE bill_credit.balance < 0 AND purchase_bill.vendorid ='$vid';";
    $negresult = mysqli_query($conn, $negBill);
    if (mysqli_num_rows($negresult) > 0) {
        while ($negrow = mysqli_fetch_assoc($negresult)) {
            $billId = $negrow['billid'];

            $tempUpDif = $diffFrom;
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
            $diffFrom = $tempUpDif;
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
