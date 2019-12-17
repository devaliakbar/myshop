<?php
    include_once('../../php/conn.php');
    $response            = array();
    $response["success"] = false;
    $response["status"]  = "INVALID";
    $q                   = json_decode($_REQUEST["q"], true);
//BACKUP
myShopBackup();

    $billId = $q['billId'];
    if(isset($q['id'])){
        $itemId = $q['id'];
        $deleteQuery = "DELETE FROM sales_item WHERE itemid = '$itemId' AND billid = '$billId'";
        
    }else{
        $deleteQuery = "DELETE FROM sales_item WHERE billid = '$billId'";
    }
    $response["query:"] = $deleteQuery;
    if (mysqli_query($conn, $deleteQuery)) {
        $response["success"] = true;
    }else{
        $response["status"] = "FAILED TO DELETE";
    }
    echo json_encode($response);
?>