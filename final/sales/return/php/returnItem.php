<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$qty = $q['qty'];
$billId = $q['billid'];
$itemId = $q['iid'];
$date = $q['date'];

$ALREADY_SAFE = false;
$upQty = 0;
$alreadyQuery = "SELECT id,quantity FROM sreturn WHERE itemid = '$itemId' AND billid = '$billId';";
$aresult = mysqli_query($conn, $alreadyQuery);
if (mysqli_num_rows($aresult) > 0) {
    while ($row = mysqli_fetch_assoc($aresult)) {
        $upQty = $qty - $row['quantity'];
    }
} else {
    $ALREADY_SAFE = true;
}
if ($ALREADY_SAFE) {
    $upQty = $qty;
}
if ($ALREADY_SAFE == false && $upQty < 1) {
    $response["success"] = true;
    $response["status"] = "ALREADY";

} else {

    $taxQuery = "SELECT  `amount`, `hsn`, `cgstper`, `sgstper`, `igstper`, `cgst`, `sgst`, `igst`, `total`
    FROM sales_item WHERE itemid = '$itemId' AND billid = '$billId';";
    $taxresult = mysqli_query($conn, $taxQuery);
    if (mysqli_num_rows($taxresult) > 0) {

        while ($row = mysqli_fetch_assoc($taxresult)) {

            $amount = $row['amount'];
            $hsn = $row['hsn'];
            $cgstper = $row['cgstper'];
            $sgstper = $row['sgstper'];
            $igstper = $row['igstper'];

            $cgst = $row['cgst'];
            $sgst = $row['sgst'];
            $igst = $row['igst'];

            $total = $row['total'];

            $uptaxable = $row['amount'] * $upQty;
            $upcgst = $row['cgst'] * $upQty;
            $upsgst = $row['sgst'] * $upQty;
            $upigst = $row['igst'] * $upQty;
            $uptotal = $row['total'] * $upQty;

            $insertReturn = "INSERT INTO `sreturn`(`billid`, `itemid`, `amount`,
           `hsn`, `cgstper`, `sgstper`, `igstper`, `cgst`, `sgst`, `igst`, `total`, `quantity`, `taxable`, `totcgst`, `totsgst`, `totigst`, `pay`, `rdate`)
           VALUES ('$billId' , '$itemId' , '$amount' , '$hsn' , '$cgstper' , '$sgstper' , '$igstper' , '$cgst' , '$sgst','$igst',
           '$total' , '$upQty' , '$uptaxable' , '$upcgst' , '$upsgst' , '$upigst' , '$uptotal','$date')";
            if (mysqli_query($conn, $insertReturn)) {

                $stockQuery = "SELECT bal,sold FROM stock WHERE itemid = '$itemId'";
                $stockresult = mysqli_query($conn, $stockQuery);
                if (mysqli_num_rows($stockresult) > 0) {
                    while ($srow = mysqli_fetch_assoc($stockresult)) {
                        $stockBal = $srow['bal'] + $upQty;
                        $sold = $srow['sold'] - $upQty;
                        $updateStock = "UPDATE stock SET bal='$stockBal' , sold ='$sold' WHERE itemid = '$itemId'";
                        if (mysqli_query($conn, $updateStock)) {
                            $response["success"] = true;
                        }
                    }
                }

            }

        }
    }
}

echo json_encode($response);
