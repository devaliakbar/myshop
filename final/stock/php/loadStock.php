<?php
include_once '../../php/conn.php';
$response = array();
$response["success"] = false;
$response["status"] = "INVALID";

if (isset($_REQUEST["q"])) {
    $q = json_decode($_REQUEST["q"], true);
    $filter = $q['filter'];
    if ($filter == "not") {
        $selection = " AND amount.total = '0';";
    } elseif ($filter == "empty") {
        $selection = " AND stock.bal = '0';";
    } elseif ($filter == "item") {
        $id = $q['id'];
        if ($id != "") {
            $selection = " AND item.id LIKE '$id%';";
        } else {
            $Sname = $q['name'];
            $Sman = $q['manuf'];
            $Scat = $q['cat'];
            $selection = "";
            if ($Sname != "") {
                $selection .= " AND item.name LIKE '$Sname%'";
            }
            if ($Sman != "") {
                $selection .= " AND item.manufactor LIKE '$Sman%'";
            }
            if ($Scat != "") {
                $selection .= " AND item.catagory LIKE '$Scat%'";
            }
            $selection .= ";";
            $response["query:"] = $selection;
        }
    }




    if ($filter == "ven" || $filter == "bill") {
        if ($filter == "ven") {
            $vname = $q['name'];
            $query = "SELECT DISTINCT item.id as r1,item.name as r2,item.manufactor as r3,item.catagory as r4,
            amount.price as r5,amount.cgst as r6,amount.sgst as r7,amount.igst as r8,amount.total as r9,
            tax.cgstper as r10,tax.sgstper as r11,tax.igstper as r12,tax.hsn as r13,
            stock.bal AS r14 ,stock.sold as r15
            FROM item INNER JOIN amount ON item.id = amount.itemid
            INNER JOIN tax ON item.id = tax.itemid
            INNER JOIN stock ON item.id = stock.itemid
            INNER JOIN purchase_item ON purchase_item.itemid = item.id
            INNER JOIN purchase_bill ON purchase_bill.id = purchase_item.purchaseid
            INNER JOIN vendor ON vendor.id = purchase_bill.vendorid
            WHERE amount.type='S' AND vendor.name LIKE '$vname%';";
        } else {
            $billId = $q['id'];
            $query = "SELECT DISTINCT item.id as r1,item.name as r2,item.manufactor as r3,item.catagory as r4,
            amount.price as r5,amount.cgst as r6,amount.sgst as r7,amount.igst as r8,amount.total as r9,
            tax.cgstper as r10,tax.sgstper as r11,tax.igstper as r12,tax.hsn as r13,
            stock.bal AS r14 ,stock.sold as r15
            FROM item INNER JOIN amount ON item.id = amount.itemid
            INNER JOIN tax ON item.id = tax.itemid
            INNER JOIN stock ON item.id = stock.itemid
            INNER JOIN purchase_item ON purchase_item.itemid = item.id
            INNER JOIN purchase_bill ON purchase_bill.id = purchase_item.purchaseid
            WHERE amount.type='S' AND purchase_bill.id ='$billId';";
        }
    } 
    
    
    
    
    else {
        $query = "SELECT item.id as r1,item.name as r2,item.manufactor as r3,item.catagory as r4,
            amount.price as r5,amount.cgst as r6,amount.sgst as r7,amount.igst as r8,amount.total as r9,
            tax.cgstper as r10,tax.sgstper as r11,tax.igstper as r12,tax.hsn as r13,
            stock.bal AS r14 ,stock.sold as r15
            FROM item INNER JOIN amount ON item.id = amount.itemid
            INNER JOIN tax ON item.id = tax.itemid
            INNER JOIN stock ON item.id = stock.itemid
            WHERE amount.type='S'" . $selection . ";";
    }
} else {
    $query = "SELECT item.id as r1,item.name as r2,item.manufactor as r3,item.catagory as r4,
         amount.price as r5,amount.cgst as r6,amount.sgst as r7,amount.igst as r8,amount.total as r9,
         tax.cgstper as r10,tax.sgstper as r11,tax.igstper as r12,tax.hsn as r13,
         stock.bal AS r14 , stock.sold as r15
         FROM item INNER JOIN amount ON item.id = amount.itemid
         INNER JOIN tax ON item.id = tax.itemid
         INNER JOIN stock ON item.id = stock.itemid
         WHERE amount.type='S';";
}
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $response["success"] = true;
    $cursorArray = array();
    $temp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $temp['itemname'] = $row["r2"];
        $temp['itemid'] = $row["r1"];
        $temp['itemmanufactor'] = $row["r3"];
        $temp['itemcatagory'] = $row["r4"];
        $temp['price'] = $row["r5"];
        $temp['cgst'] = $row["r6"];
        $temp['sgst'] = $row["r7"];
        $temp['igst'] = $row["r8"];
        $temp['total'] = $row["r9"];
        $temp['taxcgst'] = $row["r10"];
        $temp['taxsgst'] = $row["r11"];
        $temp['taxigst'] = $row["r12"];
        $temp['taxhsn'] = $row["r13"];
        $temp['left'] = $row["r14"];
        $temp['sold'] = $row["r15"];
        array_push($cursorArray, $temp);
    }
    $response["cursor"] = $cursorArray;
} else {
    $response["status"] = "EMPTY DATABASE";
}
echo json_encode($response);
