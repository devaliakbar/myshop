<?php
include_once('../../php/conn.php');
$response            = array();
$response["success"] = false;
$response["status"]  = "INVALID";
$q = json_decode($_REQUEST["q"],true);
//BACKUP
myShopBackup();


$id = $q['id'];

if($q['tbl'] == "item"){
    $name = $q['name'];
    $cat = $q['cat'];
    $man = $q['man'];
    $updateQuery = "UPDATE item SET name = '$name', catagory = '$cat', manufactor = '$man' 
    WHERE id = '$id';";

}else{
    $price = $q['price'];
    $c = $q['c'];
    $s = $q['s'];
    $i = $q['i'];
    $tot = $q['tot'];
    $updateQuery = "UPDATE amount SET price = '$price',cgst = '$c',sgst = '$s',igst = '$i' ,total = '$tot' 
    WHERE type='S' AND itemid = '$id';";
}

if (mysqli_query($conn, $updateQuery)) {
    $response["success"] = true;
}
    echo json_encode($response);
?>