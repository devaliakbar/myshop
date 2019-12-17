<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";
$q = json_decode($_REQUEST["q"], true);
$billId = $q['billId'];
//BACKUP
myShopBackup();

$returnCheck = "SELECT id FROM sreturn WHERE billid = '$billId'";
$checkReturn = mysqli_query($conn, $returnCheck);
if (mysqli_num_rows($checkReturn) > 0) {
    $response["success"] = true;
    $response["status"] = "RETURN";
} else {

    $oneItemCheck = "SELECT itemid,quantity FROM sales_item WHERE billid = '$billId';";
    $oneItemResult = mysqli_query($conn, $oneItemCheck);
    if (mysqli_num_rows($oneItemResult) == 1) {
        $response["success"] = true;
        $response["status"] = "ONE";
    } else {
        if (isset($q['id'])) {
            $itemId = $q['id'];
            $query = "SELECT itemid,quantity FROM sales_item WHERE billid = '$billId' AND itemid = '$itemId';";
            $aresult = mysqli_query($conn, $query);
            if (mysqli_num_rows($aresult) > 0) {
                while ($row = mysqli_fetch_assoc($aresult)) {
                    $stockId = $row["itemid"];
                    $qnt = $row["quantity"];
                    //SELECT STOCK BALANCE
                    $query = "SELECT bal,sold FROM stock WHERE itemid = $stockId;";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $bal = $row["bal"] + $qnt;
                            echo "ALI:" . $row['bal'] . " RAZ:" . $qnt;
                            $sold = $row["sold"] - $qnt;
                            //UPDATING
                            $updatestock = "UPDATE stock SET bal = '$bal' , sold ='$sold' WHERE itemid='$stockId';";
                            if (mysqli_query($conn, $updatestock)) {

//************************ *DELETEING FROM BILL ITEM****************************
                                $itemId = $q['id'];
                                $deleteQuery = "DELETE FROM sales_item WHERE itemid = '$itemId' AND billid = '$billId'";

                                if (mysqli_query($conn, $deleteQuery)) {
                                    $response["success"] = true;
                                }
//************************ *DELETEING FROM BILL ITEM END****************************
                            }
                        }

                    }
                }

            }
        }
    }
}
echo json_encode($response);
