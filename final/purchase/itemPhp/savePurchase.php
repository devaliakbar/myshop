<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
//BACKUP
myShopBackup();

$q = json_decode($_REQUEST["q"], true);

$UPDATE_FLAG = $q['update']; //IMPLEMENT LATER
$purchaseBillId = $q['purBillId'];
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

if ($amountUpdated && $taxUpdated && $insertItems && $stockUpdated && $amountSUpdated) {
    $response["success"] = true;
}

echo json_encode($response);
