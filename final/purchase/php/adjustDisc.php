<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$billid = $q['id'];
$disc = $q['disc'];

$CORRECT_FLAG = true;

$query = "SELECT id, itemid, amount, hsn, cgstper, sgstper, igstper, cgst, sgst, igst, total, quantity, taxable, totcgst, totsgst, totigst, pay
 FROM purchase_item WHERE purchaseid = '$billid';";
$result = mysqli_query($conn, $query);
$count = mysqli_num_rows($result);
if ($count > 0) {
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
        $pay = $row["pay"];

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

        if (mysqli_query($conn, $updateQuery)) {

        } else {
            $CORRECT_FLAG = false;
        }
    }
} else {
    $CORRECT_FLAG = false;
}

if ($CORRECT_FLAG) {
    $response["success"] = true;
}
echo json_encode($response);
