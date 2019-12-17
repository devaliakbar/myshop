<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$gstnom = $q['gst'];

$billId = $q['billid'];
$VBID = $q['vbid'];
$vendor = $q['vendor'];
$vid = $q['vid'];
$vname = $q['vname'];
$vadd = $q['vadd'];
$vpho = $q['vpho'];
$date = $q['date'];
$tax = $q['tax'];
$taxi = $q['taxi'];
$taxc = $q['taxc'];
$taxs = $q['taxs'];
$igst = $q['igst'];
$sgst = $q['sgst'];
$cgst = $q['cgst'];
$pay = $q['pay'];

//************************* */SETTING VENDOR**************************
$VENDOR_FLAG = false;
if ($vendor) {
    $insertQuery = "INSERT INTO vendor(name,phone,address,gst) VALUES('$vname','$vpho','$vadd','$gstnom');";
    if (mysqli_query($conn, $insertQuery)) {
        $vid = mysqli_insert_id($conn);
        $VENDOR_FLAG = true;
    }
} else {
    $VENDOR_FLAG = true;
}

//************************ */SETTING STOCK****************************
$STOCK_FLAG = true;
$query = "SELECT itemid,quantity FROM purchase_item WHERE purchaseid = $billId;";
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
                $bal = $row["bal"] + $qnt;
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

//************************ */SETTING STOCK****************************
$BILL_FLAG = false;
$updateBill = "UPDATE purchase_bill SET dates = '$date', vendorid = '$vid' ,amount = '$tax' ,cgstper = '$taxc' ,sgstper = '$taxs' ,
igstper = '$taxi' , cgst = '$cgst' , sgst = '$sgst' , igst = '$igst' , total = '$pay',vbid = '$VBID'
WHERE id = '$billId';";
if (mysqli_query($conn, $updateBill)) {
    $BILL_FLAG = true;
}

//************************ */SETTING credit****************************
$CREDIT_FLAG = true;
$UBCpay = 0;
if ($vendor) {
    $insertQuery = "INSERT INTO vendor_credit(`vid`, `amount`, `returned`, `total`, `pay`, `balance`)
    VALUES('$vid','$pay','0','$pay','0','$pay');";
    if (mysqli_query($conn, $insertQuery)) {
    } else {
        $CREDIT_FLAG = false;
    }
} else {
    $query = "SELECT amount,total,balance FROM vendor_credit WHERE vid = '$vid';";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $amount = $row["amount"] + $pay;
            $total = $row["total"] + $pay;
            $bal = $row["balance"] + $pay;
            $updateQuery = "UPDATE `vendor_credit` SET `amount`='$amount', `total`='$total',`balance`='$bal' WHERE vid = $vid;";
            if (mysqli_query($conn, $updateQuery)) {

            } else {
                $CREDIT_FLAG = false;
            }

            if ($row["balance"] < 0) {
                $UBCpay = $row["balance"] * -1;
            }
        }
    } else {
        $CREDIT_FLAG = false;
    }
}

if ($UBCpay > 0) {

    if ($UBCpay > $pay) {
        $insertQuery = "INSERT INTO bill_credit(`billid`, `amount`, `returned`, `total`, `pay`, `balance`)
    VALUES('$billId','$pay','0','$pay','$pay','0');";

        $upPaymentQuery = "INSERT INTO `payment`(`billid`, `amount`, `tdate`) VALUES ('$billId' , '$pay' , '$date')";
        if (mysqli_query($conn, $upPaymentQuery)) {
            setCurrentPayment($vid, $pay, $conn);
        } else {
            $CREDIT_FLAG = false;
        }
    } else {
        $ubcpaid = $UBCpay;
        $ubcbalance = $pay - $ubcpaid;
        $insertQuery = "INSERT INTO bill_credit(`billid`, `amount`, `returned`, `total`, `pay`, `balance`)
        VALUES('$billId','$pay','0','$pay','$ubcpaid','$ubcbalance');";
        $upPaymentQuery = "INSERT INTO `payment`(`billid`, `amount`, `tdate`) VALUES ('$billId' , '$ubcpaid' , '$date')";
        if (mysqli_query($conn, $upPaymentQuery)) {
            setCurrentPayment($vid, $ubcpaid, $conn);
        } else {
            $CREDIT_FLAG = false;
        }
    }

} else {
    $insertQuery = "INSERT INTO bill_credit(`billid`, `amount`, `returned`, `total`, `pay`, `balance`)
    VALUES('$billId','$pay','0','$pay','0','$pay');";
}

if (mysqli_query($conn, $insertQuery)) {
} else {
    $CREDIT_FLAG = false;
}

//************************ */SETTING credit****************************

if ($VENDOR_FLAG && $STOCK_FLAG && $BILL_FLAG && $CREDIT_FLAG) {
    $response["success"] = true;
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
