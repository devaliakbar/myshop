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

$returnCheck = "SELECT id FROM sreturn WHERE billid = '$salesBillId'";
$checkReturn = mysqli_query($conn, $returnCheck);
if (mysqli_num_rows($checkReturn) > 0) {
    $response["success"] = true;
    $response["status"] = "RETURN";
} else {

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

        //************************ */SETTING STOCK****************************
        //SELECT EACH STOCK BALANCE
        $query = "SELECT bal,sold FROM stock WHERE itemid = $id;";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bal = $row["bal"] - $quantity;
                $sold = $row["sold"] + $quantity;
                //UPDATING
                $updatestock = "UPDATE stock SET bal = '$bal' , sold = '$sold' WHERE itemid='$id';";
                if (mysqli_query($conn, $updatestock)) {

                } else {
                    $STOCK_FLAG = false;
                }
            }

        } else {
            $STOCK_FLAG = false;
        }
//************************ */SETTING STOCK END****************************
    }
}
echo json_encode($response);
