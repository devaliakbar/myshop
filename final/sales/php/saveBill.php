<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

$billId = $q['billid'];
$vendor = $q['vendor'];
$vid = $q['vid'];
$vname = $q['vname'];
$vpho = $q['vpho'];
$vaddr = $q['vaddr'];
$vgst = $q['vgst'];

$date = $q['date'];
$tax = $q['tax'];

$taxi = $q['taxi'];
$taxc = $q['taxc'];
$taxs = $q['taxs'];

$mrp = $q['mrp'];
$dis = $q['dis'];
$discper = $q['discper'];

$igst = $q['igst'];
$sgst = $q['sgst'];
$cgst = $q['cgst'];
$pay = $q['pay'];

//************************* */SETTING VENDOR**************************
$VENDOR_FLAG = false;
if ($vendor) {
    $insertQuery = "INSERT INTO customer(name,phone,address,gst) VALUES('$vname','$vpho','$vaddr','$vgst');";
    if (mysqli_query($conn, $insertQuery)) {
        $vid = mysqli_insert_id($conn);
        $VENDOR_FLAG = true;
    }
} else {
    $VENDOR_FLAG = true;
}

//************************ */SETTING STOCK****************************
$STOCK_FLAG = true;
$query = "SELECT itemid,quantity FROM sales_item WHERE billid = $billId;";
$aresult = mysqli_query($conn, $query);
if (mysqli_num_rows($aresult) > 0) {
    while ($row = mysqli_fetch_assoc($aresult)) {
        $stockId = $row["itemid"];
        $qnt = $row["quantity"];
        //SELECT EACH STOCK BALANCE
        $query = "SELECT bal,sold FROM stock WHERE itemid = $stockId;";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bal = $row["bal"] - $qnt;
                $sold = $row["sold"] + $qnt;
                //UPDATING
                $updatestock = "UPDATE stock SET bal = '$bal' , sold = '$sold' WHERE itemid='$stockId';";
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

//************************ */SETTING STOCK END****************************
$BILL_FLAG = false;
$updateBill = "UPDATE sales_bill SET dates = '$date', custid = '$vid' ,amount = '$tax' ,cgstper = '$taxc' ,sgstper = '$taxs' ,
igstper = '$taxi' , cgst = '$cgst' , sgst = '$sgst' , igst = '$igst' , total = '$mrp' , discount = '$dis' , discountper = '$discper' , pay = '$pay'
WHERE id = '$billId';";
if (mysqli_query($conn, $updateBill)) {
    $BILL_FLAG = true;
}

if ($VENDOR_FLAG && $STOCK_FLAG && $BILL_FLAG) {
    $response["success"] = true;
}

echo json_encode($response);
