<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$billId = $q['billid'];

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

        $vendor = $q['vendor'];

        $VBID = $q['vbid'];

        $date = $q['date'];
        $tax = $q['tax'];
        $taxi = $q['taxi'];
        $taxc = $q['taxc'];
        $taxs = $q['taxs'];
        $igst = $q['igst'];
        $sgst = $q['sgst'];
        $cgst = $q['cgst'];
        $pay = $q['pay'];
        $vid = $q['vid'];

        $vidOld = $q['vndId'];

//************************* */SETTING VENDOR**************************
        $VENDOR_FLAG = false;
        if ($vendor) {
            $vid = $q['vid'];
            $vname = $q['vname'];
            $vadd = $q['vadd'];
            $vpho = $q['vpho'];
            $gstnom = $q['gst'];
            $insertQuery = "INSERT INTO vendor(name,phone,address,gst) VALUES('$vname','$vpho','$vadd','$gstnom');";
            if (mysqli_query($conn, $insertQuery)) {
                $vid = mysqli_insert_id($conn);
                $VENDOR_FLAG = true;
            }
        } else {
            $VENDOR_FLAG = true;
        }

//************************* */CREDIT SESSION START HERE************************************************************///////////
        $CREDIT_FLAG = true;
//////////////////////////////CLEAR OLD CREATE
        if ($vid != $vidOld) {
            $query = "SELECT total,balance,pay FROM bill_credit WHERE billid = '$billId';";
            $aaresult = mysqli_query($conn, $query);
            if (mysqli_num_rows($aaresult) > 0) {
                while ($row = mysqli_fetch_assoc($aaresult)) {
                    $bals = $row["balance"];
                    $payFromBill = $row["pay"];
                    $totals = $row["total"];

                    $query = "SELECT amount,total,balance,pay FROM vendor_credit WHERE vid = '$vidOld';";
                    $aresult = mysqli_query($conn, $query);
                    if (mysqli_num_rows($aresult) > 0) {
                        while ($row = mysqli_fetch_assoc($aresult)) {
                            $amount = $row["amount"] - $pay;
                            $total = $row["total"] - $pay;
                            $bal = $row["balance"] - $bals;
                            $payUp = $row["pay"] - $payFromBill;
                            $updateQuery = "UPDATE `vendor_credit` SET `amount`='$amount', `total`='$total',`balance`='$bal' , `pay`= '$payUp' WHERE vid = $vidOld;";
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

            $updateQuery = "UPDATE `bill_credit` SET `balance`='$totals' , `pay`= '0' WHERE billid = '$billId';";
            if (mysqli_query($conn, $updateQuery)) {

            } else {
                $CREDIT_FLAG = false;
            }

            ///////////////////////////SETTING NEW VENDOR

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
                    }
                } else {
                    $CREDIT_FLAG = false;
                }
            }
        }

//************************* */CREDIT SESSION END HERE***************************************************************////////////////
        $BILL_FLAG = false;
        $updateBill = "UPDATE purchase_bill SET dates = '$date', vendorid = '$vid' ,amount = '$tax' ,cgstper = '$taxc' ,sgstper = '$taxs' ,
igstper = '$taxi' , cgst = '$cgst' , sgst = '$sgst' , igst = '$igst' , total = '$pay' ,vbid = '$VBID'
WHERE id = '$billId';";
        if (mysqli_query($conn, $updateBill)) {
            $BILL_FLAG = true;
        }

        if ($VENDOR_FLAG && $BILL_FLAG) {
            $response["success"] = true;
        }
    }
}
echo json_encode($response);
