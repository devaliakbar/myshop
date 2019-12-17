<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$billid = $q['id'];
$vendorid = $q['vid'];
$creditCheck = "SELECT id FROM ireturn WHERE billid = '$billid'";
$checkCredit = mysqli_query($conn, $creditCheck);
if (mysqli_num_rows($checkCredit) > 0) {
    $response["success"] = true;
    $response["status"] = "RETURN";
} else {

    $creditCheck = "SELECT id FROM payment WHERE billid = '$billid'";
    $checkCredit = mysqli_query($conn, $creditCheck);
    if (mysqli_num_rows($checkCredit) > 0) {
        $response["success"] = true;
        $response["status"] = "CREDIT";
    } else {

        $disc = $q['disc'];

        $CORRECT_FLAG = true;

        $query = "SELECT id, itemid, amount, hsn, cgstper, sgstper, igstper, cgst, sgst, igst, total, quantity, taxable, totcgst, totsgst, totigst, pay
 FROM purchase_item WHERE purchaseid = '$billid';";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $totPay = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row["id"];
                $itemId = $row["itemid"];
                $price = $row["amount"];
                $hsn = $row["hsn"];
                $taxcgst = $row["cgstper"];
                $taxsgst = $row["sgstper"];

                $taxigst = $row["igstper"];
                $cgst = $row["cgst"];
                $sgst = $row["sgst"];
                $igst = $row["igst"];
                $total = $row["total"];

                $quantity = $row["quantity"];
                $taxable = $row["taxable"];
                $tcgst = $row["totcgst"];
                $tsgst = $row["totsgst"];
                $tigst = $row["totigst"];
                $Apay = $row["pay"];

                $offer = ($disc / 100) * $taxable;
                $taxable = $taxable - $offer;

                if ($hsn > 0) {

                    $taxAmount = ($taxigst / 100) * $taxable;
                    $pay = $taxable + $taxAmount;

                    $total = $pay / $quantity;
                    $price = $taxable / $quantity;
                    $sgst = ($taxsgst / 100) * $price;
                    $cgst = ($taxcgst / 100) * $price;
                    $igst = $sgst + $cgst;

                    $tcgst = $cgst * $quantity;
                    $tsgst = $sgst * $quantity;
                    $tigst = $igst * $quantity;
                } else {
                    $pay = $taxable;
                    $total = $pay / $quantity;
                    $price = $total;
                }
                $updateQuery = "UPDATE purchase_item SET
            amount = '$price', cgstper = '$taxcgst', sgstper = '$taxsgst', igstper = '$taxigst',
            cgst = '$cgst', sgst = '$sgst', igst = '$igst',
            total = '$total', quantity = '$quantity', taxable = '$taxable',
            totcgst = '$tcgst', totsgst = '$tsgst', totigst = '$tigst',
            pay =  '$pay' WHERE id = '$id'";

                $totPay .= $pay;
                if (mysqli_query($conn, $updateQuery)) {

                } else {
                    $CORRECT_FLAG = false;
                }
            }
            if ($CORRECT_FLAG == true) {

//setting creditttt

                $selectOldPay = "SELECT `amount`, `total`, `pay`, `balance` FROM `bill_credit` WHERE billid ='$billid';";
                $oldPayresult = mysqli_query($conn, $selectOldPay);
                if (mysqli_num_rows($oldPayresult) > 0) {
                    while ($oldRow = mysqli_fetch_assoc($oldPayresult)) {

                        $subtot = $oldRow['total'];
                        $subPay = $oldRow['pay'];

                        $bamount = $totPay;
                        $btotal = $totPay;
                        if ($subPay > 0) {
                            if ($subPay == $subtot) {
                                $bpay = $totPay;
                                $bbalance = 0;
                            } else {
                                $bpay = $subPay;
                                $bbalance = $btotal - $bpay;
                            }
                        } else {
                            $bpay = 0;
                            $bbalance = $btotal;
                        }

                        $updateBillCreditQuery = "UPDATE `bill_credit` SET
                        `amount`='$bamount', `total`='$btotal',`pay`='$bpay',`balance`='$bbalance'
                        WHERE billid ='$billid';";
                        if (mysqli_query($conn, $updateBillCreditQuery)) {

                            $VoldPay = "SELECT `amount`, `total`, `pay`, `balance` FROM `vendor_credit`
                WHERE vid ='$vendorid';";
                            $VoldPayresult = mysqli_query($conn, $VoldPay);

                            if (mysqli_num_rows($VoldPayresult) > 0) {
                                while ($VoldRow = mysqli_fetch_assoc($VoldPayresult)) {
                                    $Voamount = $VoldRow['amount'] - $subtot;
                                    $Vototal = $VoldRow['total'] - $subtot;
                                    $Vobal = $VoldRow['balance'] - $subtot;

                                    $Voamount = $Voamount + $totPay;
                                    $Vototal = $Vototal + $totPay;
                                    $Vobal = $Vobal + $totPay;
                                    $updateVendorCreditQuery = "UPDATE `vendor_credit` SET
                        `amount`='$Voamount', `total`='$Vototal',`balance`='$Vobal'
                        WHERE vid ='$vendorid';";

                                    if (mysqli_query($conn, $updateVendorCreditQuery)) {

                                    } else {
                                        $CORRECT_FLAG = false;
                                    }
                                }
                            }

                        } else {
                            $CORRECT_FLAG = false;
                        }

                    }
                }

            }
        } else {
            $CORRECT_FLAG = false;
        }

        if ($CORRECT_FLAG) {
            $response["success"] = true;
        }
    }
}
echo json_encode($response);
