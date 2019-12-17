<?php
include_once '../../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

$q = json_decode($_REQUEST["q"], true);

$vid = $q['vid'];

$type = $q['type'];

if ($type == true) {

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

    $query = "SELECT purchase_bill.id, purchase_bill.dates,
    bill_credit.amount,bill_credit.returned,bill_credit.total,bill_credit.pay,bill_credit.balance
    FROM purchase_bill INNER JOIN bill_credit ON purchase_bill.id = bill_credit.billid
    INNER JOIN vendor ON vendor.id = purchase_bill.vendorid WHERE vendor.id = '$vid'$selection;";

} else {

    $query = "SELECT purchase_bill.id, purchase_bill.dates,
        bill_credit.amount,bill_credit.returned,bill_credit.total,bill_credit.pay,bill_credit.balance
        FROM purchase_bill INNER JOIN bill_credit ON purchase_bill.id = bill_credit.billid
        INNER JOIN vendor ON vendor.id = purchase_bill.vendorid WHERE vendor.id = '$vid';";
}

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['id'] = $row["id"];
        $temp['date'] = $row["dates"];
        $temp['amount'] = $row["amount"];
        $temp['return'] = $row["returned"];
        $temp['total'] = $row["total"];
        $temp['pay'] = $row["pay"];
        $temp['bal'] = $row["balance"];
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
