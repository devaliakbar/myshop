<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = true;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$old = $q['old'];
$new = $q['new'];
$billid = $q['id'];
$job; //IF JOB IS TRUE SUBSTRACTION ELSE ADDITION

if ($old > $new) {
    $diff = $old - $new;
    $job = true;
} else {
    $diff = $new - $old;
    $job = false;
}

$query = "SELECT id, itemid, amount, hsn, cgstper, sgstper, igstper, cgst, sgst, igst, total, quantity, taxable, totcgst, totsgst, totigst, pay
 FROM purchase_item WHERE purchaseid = '$billid' ORDER BY pay ASC;";
$result = mysqli_query($conn, $query);
$count = mysqli_num_rows($result);
if ($count > 0) {
    $diff = $diff / $count;
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
        if ($job) {
            $pay = $pay - $diff;
            //SPECIAL CASE WHEN PAY IS LESS THAN 0
            if ($pay < 0) {
                $temp = $pay * -1;
                $diff = $diff + $temp;
                $pay = 0;
            }
        } else {
            $pay = $pay + $diff;
        }

        if ($hsn > 0) {
            $yy = $taxigst / 100;
            $zz = $pay;
            $xx = $zz / (1 + $yy); //CALCULATED TAXABLE FROM PAY AND DISCOUNT PERCENTAGE
            $taxable = $xx;

            $total = $pay / $quantity;
            $price = $taxable / $quantity;
            $sgst = ($taxsgst / 100) * $price;
            $cgst = ($taxcgst / 100) * $price;
            $igst = $sgst + $cgst;

            $tcgst = $cgst * $quantity;
            $tsgst = $sgst * $quantity;
            $tigst = $igst * $quantity;
        } else {
            $taxable = $pay;
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
            $response["success"] = false;
        }
    }
} else {
    $response["success"] = false;
}

echo json_encode($response);
