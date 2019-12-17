<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

if (isset($_REQUEST["q"])) {

    $q = json_decode($_REQUEST["q"], true);
    $operation = $q['operation'];

    $opr = "";

    if ($operation == '0') {
        $opr = "=";
    } elseif ($operation == '1') {
        $opr = ">";
    } elseif ($operation == '2') {
        $opr = "<";
    } elseif ($operation == '3') {
        $opr = ">=";
    } elseif ($operation == '4') {
        $opr = "<=";
    }

    $date = $q['date'];
    $maxDate = $q['maxDate'];

    $selection = "";

    if ($operation == '5') {
        if ($date != "" && $maxDate !== "") {
            $selection .= " AND purchase_bill.dates BETWEEN '$date' AND '$maxDate'";
        }
    } else {
        if ($date != "") {
            $selection .= " AND purchase_bill.dates $opr '$date'";
        }
    }

    $selection .= ";";

    $query = "SELECT purchase_bill.id, purchase_bill.dates, vendor.name, purchase_bill.amount,
    purchase_bill.cgstper, purchase_bill.sgstper, purchase_bill.igstper, purchase_bill.cgst, purchase_bill.sgst, purchase_bill.igst, purchase_bill.total
    FROM purchase_bill INNER JOIN vendor ON purchase_bill.vendorid = vendor.id WHERE purchase_bill.id > '0'$selection";

} else {
    $query = "SELECT purchase_bill.id, purchase_bill.dates, vendor.name, purchase_bill.amount,
    purchase_bill.cgstper, purchase_bill.sgstper, purchase_bill.igstper, purchase_bill.cgst, purchase_bill.sgst, purchase_bill.igst, purchase_bill.total
    FROM purchase_bill INNER JOIN vendor ON purchase_bill.vendorid = vendor.id;";
}

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['id'] = $row["id"];
        $temp['dates'] = $row["dates"];
        $temp['name'] = $row["name"];
        $temp['amount'] = $row["amount"];
        $temp['cgstper'] = $row["cgstper"];
        $temp['sgstper'] = $row["sgstper"];
        $temp['igstper'] = $row["igstper"];
        $temp['cgst'] = $row["cgst"];
        $temp['sgst'] = $row["sgst"];
        $temp['igst'] = $row["igst"];
        $temp['total'] = $row["total"];
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
