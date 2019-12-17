<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$salesBillId = $q['billId'];
$id = $q['ids'];
$price = $q['price'];
$hsn = $q['taxhsn'];
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

$discountPer = $q['discountPer'];
$discount = $q['discount'];
$mrp = $q['mrp'];
$totMrp = $q['totMrp'];

$CHECK_ITEM = "SELECT id FROM sales_item WHERE itemid ='" . $id . "' AND billid = '$salesBillId';";
$result = mysqli_query($conn, $CHECK_ITEM);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $response["status"] = "EXIST";
} else {

    $insertItemSales = "INSERT INTO `sales_item`(`billid`, `itemid`, `amount`, `hsn`, `cgstper`, `sgstper`, `igstper`,
         `cgst`, `sgst`, `igst`, `mrp`, `total`, `quantity`, `taxable`, `totcgst`, `totsgst`, `totigst`, `totmrp`, `discount`, `discountper`, `pay`)
         VALUES('$salesBillId','$id','$price','$hsn','$taxcgst','$taxsgst','$taxigst',
         '$cgst','$sgst','$igst','$mrp','$total','$quantity','$taxable','$tcgst','$tsgst','$tigst','$totMrp','$discount','$discountPer','$pay'
         )";

    if (mysqli_query($conn, $insertItemSales)) {
        $response["success"] = true;
    }
}
echo json_encode($response);
