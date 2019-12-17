<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$UPDATE_FLAG = $q['update']; //IMPLEMENT LATER
$purchaseBillId = $q['purBillId'];

$creditCheck = "SELECT id FROM payment WHERE billid = '$purchaseBillId'";
$checkCredit = mysqli_query($conn, $creditCheck);
if (mysqli_num_rows($checkCredit) > 0) {
    $response["success"] = true;
    $response["status"] = "CREDIT";
} else {

    $id = $q['id'];
    $name = $q['name'];
    $cat = $q['catagory'];
    $manu = $q['manufactor'];
    $price = $q['price'];
    $hsn = $q['hsn'];
    $taxigst = $q['taxigst'];
    $taxcgst = $q['taxcgst'];
    $taxsgst = $q['taxsgst'];
    $igst = $q['igst'];
    $cgst = $q['cgst'];
    $sgst = $q['sgst'];
    $total = $q['total'];
    $quantity = $q['quantity'];
    $taxable = $q['taxable'];
    $tcgst = $q['tcgst'];
    $tsgst = $q['tsgst'];
    $tigst = $q['tigst'];
    $pay = $q['pay'];

    $CHECK_ITEM = "SELECT id FROM purchase_item WHERE itemid ='" . $id . "' AND purchaseid = '$purchaseBillId';";
    $result = mysqli_query($conn, $CHECK_ITEM);
    if (mysqli_num_rows($result) > 0) {
        $response["success"] = true;
        $response["status"] = "EXIST";
    } else {

        $query = "SELECT id FROM item WHERE id ='" . $id . "';";
        $result = mysqli_query($conn, $query);
        $itemExist;
        if (mysqli_num_rows($result) > 0) {
            $itemExist = true;
        } else {
            $itemExist = false;
        }

        $itemId;
        $amountUpdated = false;
        $amountSUpdated = true;
        $taxUpdated = false;
        $stockUpdated = false;
        if ($itemExist) {
            $stockUpdated = true;
            $itemId = $id;
            $updateamount = "UPDATE amount SET price = '$price',cgst = '$cgst',sgst = '$sgst',igst = '$igst' ,total = '$total' WHERE type='P' AND itemid = '$itemId';";
            if (mysqli_query($conn, $updateamount)) {
                $amountUpdated = true;
            }

            $updatetax = "UPDATE tax SET hsn='$hsn',cgstper='$taxcgst',sgstper='$taxsgst',igstper='$taxigst' WHERE itemid='$itemId';";
            if (mysqli_query($conn, $updatetax)) {
                $taxUpdated = true;
            }

        } else {

            $insertQuery = "INSERT INTO item(name,manufactor,catagory) VALUES('$name','$manu','$cat');";
            if (mysqli_query($conn, $insertQuery)) {
                $itemId = mysqli_insert_id($conn);
            }

            $insertQuery = "INSERT INTO stock(itemid,bal,sold) VALUES('$itemId','0','0');";
            if (mysqli_query($conn, $insertQuery)) {
                $stockUpdated = true;
            }

            $insertPamount = "INSERT INTO amount(itemid, type, price, cgst, sgst, igst, total) VALUES ('$itemId','P','$price','$cgst','$sgst','$igst','$total');";
            if (mysqli_query($conn, $insertPamount)) {
                $amountUpdated = true;
            }

            $insertSamount = "INSERT INTO amount(itemid, type, price, cgst, sgst, igst, total) VALUES ('$itemId','S','0','0','0','0','0');";
            if (mysqli_query($conn, $insertSamount)) {
                $$amountSUpdated = true;
            } else {
                $amountSUpdated = false;
            }

            $inserttax = "INSERT INTO tax(itemid, hsn, cgstper, sgstper, igstper) VALUES ('$itemId','$hsn','$taxcgst','$taxsgst','$taxigst');";
            if (mysqli_query($conn, $inserttax)) {
                $taxUpdated = true;
            }
        }

        $insertItems = false;
        $insertItemPurchase = "INSERT INTO purchase_item(purchaseid, itemid, amount, hsn, cgstper, sgstper, igstper, cgst, sgst, igst, total, quantity, taxable, totcgst, totsgst, totigst, pay)
        VALUES ('$purchaseBillId','$itemId','$price','$hsn','$taxcgst','$taxsgst','$taxigst','$cgst','$sgst','$igst','$total','$quantity','$taxable','$tcgst','$tsgst','$tigst','$pay');";
        if (mysqli_query($conn, $insertItemPurchase)) {
            $insertItems = true;
        }

        //SETTING STOCK FOR UPDATE
        $STOCK_FLAG = false;
        $query = "SELECT bal FROM stock WHERE itemid = $itemId;";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bal = $row["bal"] + $quantity;
                $updatestock = "UPDATE stock SET bal = '$bal' WHERE itemid='$itemId';";
                if (mysqli_query($conn, $updatestock)) {
                    $STOCK_FLAG = true;
                }
            }
        }

        //************************ */SETTING credit****************************
        $CREDIT_FLAG = true;
        $vid = $q['VID'];
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

        $query = "SELECT amount,total,balance FROM bill_credit WHERE billid = '$purchaseBillId';";

        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $amount = $row["amount"] + $pay;
                $total = $row["total"] + $pay;
                $bal = $row["balance"] + $pay;
                $updateQuery = "UPDATE `bill_credit` SET `amount`='$amount', `total`='$total',`balance`='$bal' WHERE billid = '$purchaseBillId';";
                if (mysqli_query($conn, $updateQuery)) {

                } else {
                    $CREDIT_FLAG = false;
                }
            }
        } else {
            $CREDIT_FLAG = false;
        }

//************************ */SETTING credit****************************

        if ($amountUpdated && $taxUpdated && $insertItems && $stockUpdated && $amountSUpdated && $STOCK_FLAG && $CREDIT_FLAG) {
            $response["success"] = true;
        }
    }
}
echo json_encode($response);
