<?php
     include_once('../../../php/conn.php');
     $response            = array();
     $response["success"] = false;
     $response["status"]  = "INVALID";

     //BACKUP
myShopBackup();


     $q = $_REQUEST["q"];

     $CHECK_ITEM = "SELECT id FROM vendor_credit WHERE amount > 0 AND vid = '$q';";
     $result = mysqli_query($conn, $CHECK_ITEM);
     if (mysqli_num_rows($result) > 0) {
        $response["success"] = true;
        $response["status"]  = "USED";
    }else {
        $deleteQuery = "DELETE FROM vendor_credit WHERE vid = '$q';";
        if (mysqli_query($conn, $deleteQuery)) {
            $deleteVendorQuery =  "DELETE FROM vendor WHERE id = '$q';";
            if (mysqli_query($conn, $deleteVendorQuery)) {
            $response["success"] = true;
            }
        }
    }
    
    echo json_encode($response);
?>