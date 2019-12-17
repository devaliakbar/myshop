<?php
include_once 'conn.php';
$minDate = "";
$maxDate = "";
if (isset($_GET['submit-dat'])) {
    $minDate = $_GET['date-min'];
    $maxDate = $_GET['date-max'];
}
?>
<html>
<body>
    <h1 style="text-align:center;">PURCHASE</h1>
    <br>
    <form action="" method="GET">
    <input type="date" name="date-min" value="<?php echo $minDate; ?>">
    TO
    <input type="date" name="date-max"value="<?php echo $maxDate; ?>">
    <input type="submit" name="submit-dat" value="Show">
</form>
</form>
    <form action ="pur-re.php" method="POST">
    <table border="1" style="border-collapse: collapse;">
        <tr>
            <th>GSTIN/UIN of Recipient</th>
            <th>Invoice Number</th>
            <th>Invoice date</th>
            <th>Invoice Value</th>
            <th>Rate</th>
            <th>Taxable Value</th>
            <th>CGST</th>
            <th>SGST</th>
        </tr>


<?php
$dataArray = array();
$i = 0;
if ($minDate != "" && $maxDate != "") {
    $billquery = "SELECT purchase_bill.id, vendor.gst , purchase_bill.vbid , purchase_bill.dates, purchase_bill.total
    FROM purchase_bill INNER JOIN vendor ON vendor.id = purchase_bill.vendorid WHERE purchase_bill.vendorid > 0 AND purchase_bill.dates BETWEEN '$minDate' AND '$maxDate'";
} else {
    $billquery = "SELECT purchase_bill.id, vendor.gst , purchase_bill.vbid , purchase_bill.dates, purchase_bill.total
    FROM purchase_bill INNER JOIN vendor ON vendor.id = purchase_bill.vendorid WHERE purchase_bill.vendorid > 0";
}
$result = mysqli_query($conn, $billquery);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $BILLID = $row['id'];
        $gst = $row['gst'];
        $bill = $row['vbid'];
        $date = $row['dates'];
        $total = $row['total'];

        $taxPerq = "SELECT DISTINCT `igstper` FROM purchase_item WHERE `purchaseid` = '$BILLID'";
        $tpresult = mysqli_query($conn, $taxPerq);
        if (mysqli_num_rows($tpresult) > 0) {
            while ($trow = mysqli_fetch_assoc($tpresult)) {

                $taxpercentage = $trow['igstper'];

                $tottaxper = "SELECT sum(`taxable`) as taxa,sum(`totcgst`) as cgst,sum(`totsgst`) as sgst FROM purchase_item
                    WHERE `purchaseid` = '$BILLID' AND `igstper`='$taxpercentage'";

                $ttresult = mysqli_query($conn, $tottaxper);
                if (mysqli_num_rows($ttresult) > 0) {
                    while ($ttrow = mysqli_fetch_assoc($ttresult)) {
                        $taxable = $ttrow['taxa'];
                        $cgst = $ttrow['cgst'];
                        $sgst = $ttrow['sgst'];

                        $dataArray[$i][0] = $gst;
                        $dataArray[$i][1] = $bill;
                        $dataArray[$i][2] = $date;
                        $dataArray[$i][3] = $total;
                        $dataArray[$i][4] = $taxpercentage;
                        $dataArray[$i][5] = $taxable;
                        $dataArray[$i][6] = $cgst;
                        $dataArray[$i][7] = $sgst;

                        $i++;

                    }
                }

            }
        }
    }
}

$n = $i;

if ($minDate != "" && $maxDate != "") {
    $billquery = "SELECT purchase_bill.id, vendor.gst , purchase_bill.vbid , purchase_bill.dates, purchase_bill.total
    FROM purchase_bill INNER JOIN vendor ON vendor.id = purchase_bill.vendorid INNER JOIN ireturn ON ireturn.billid = purchase_bill.id
    WHERE purchase_bill.vendorid > 0 AND ireturn.rdate BETWEEN '$minDate' AND '$maxDate'";
} else {
    $billquery = "SELECT purchase_bill.id, vendor.gst , purchase_bill.vbid , purchase_bill.dates, purchase_bill.total
    FROM purchase_bill INNER JOIN vendor ON vendor.id = purchase_bill.vendorid INNER JOIN ireturn ON ireturn.billid = purchase_bill.id
    WHERE purchase_bill.vendorid > 0";
}
$result = mysqli_query($conn, $billquery);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $BILLID = $row['id'];
        $gst = $row['gst'];
        $bill = $row['vbid'];
        $date = $row['dates'];
        $total = $row['total'];

        if ($minDate != "" && $maxDate != "") {
            $taxPerq = "SELECT DISTINCT `igstper` FROM ireturn
        WHERE `billid` = '$BILLID' AND ireturn.rdate BETWEEN '$minDate' AND '$maxDate'";
        } else {
            $taxPerq = "SELECT DISTINCT `igstper` FROM ireturn
            WHERE `billid` = '$BILLID'";
        }
        $tpresult = mysqli_query($conn, $taxPerq);
        if (mysqli_num_rows($tpresult) > 0) {
            while ($trow = mysqli_fetch_assoc($tpresult)) {

                $taxpercentage = $trow['igstper'];
                if ($minDate != "" && $maxDate != "") {
                    $tottaxper = "SELECT sum(`taxable`) as taxa,sum(`totcgst`) as cgst,sum(`totsgst`) as sgst FROM ireturn
                WHERE `billid` = '$BILLID' AND `igstper`='$taxpercentage' AND ireturn.rdate BETWEEN '$minDate' AND '$maxDate'";
                } else {
                    $tottaxper = "SELECT sum(`taxable`) as taxa,sum(`totcgst`) as cgst,sum(`totsgst`) as sgst FROM ireturn
                    WHERE `billid` = '$BILLID' AND `igstper`='$taxpercentage'";
                }

                $ttresult = mysqli_query($conn, $tottaxper);
                if (mysqli_num_rows($ttresult) > 0) {
                    while ($ttrow = mysqli_fetch_assoc($ttresult)) {
                        $taxable = $ttrow['taxa'];
                        $cgst = $ttrow['cgst'];
                        $sgst = $ttrow['sgst'];

                        $EXIST_FLAG = false;
                        for ($j = 0; $j < $n; $j++) {
                            if ($bill == $dataArray[$j][1] && $dataArray[$j][4] == $taxpercentage) {
                                $EXIST_FLAG = true;
                                $dataArray[$j][6] = $dataArray[$j][6] - $cgst;
                                $dataArray[$j][7] = $dataArray[$j][7] - $sgst;
                            }
                        }
                        if ($EXIST_FLAG == false) {
                            $dataArray[$i][0] = $gst;
                            $dataArray[$i][1] = $bill;
                            $dataArray[$i][2] = $date;
                            $dataArray[$i][3] = $total;
                            $dataArray[$i][4] = $taxpercentage;
                            $dataArray[$i][5] = $taxable;
                            $dataArray[$i][6] = -1 * $cgst;
                            $dataArray[$i][7] = -1 * $sgst;

                            $i++;
                        }

                    }
                }

            }
        }
    }
}

for ($j = 0; $j < $i; $j++) {
    $r1 = $dataArray[$j][0];
    $r2 = $dataArray[$j][1];
    $r3 = $dataArray[$j][2];
    $r4 = $dataArray[$j][3];
    $r5 = $dataArray[$j][4];
    $r6 = $dataArray[$j][5];
    $r7 = $dataArray[$j][6];
    $r8 = $dataArray[$j][7];
    echo "<tr>
                        <td><input type='text' name='$j-0' value='$r1' ></td>
                        <td><input type='text' name='$j-1' value='$r2' ></td>
                        <td><input type='text' name='$j-2' value='$r3' ></td>
                        <td><input type='text' name='$j-3' value='$r4' ></td>
                        <td><input type='text' name='$j-4' value='$r5' ></td>
                        <td><input type='text' name='$j-5' value='$r6' ></td>
                        <td><input type='text' name='$j-6' value='$r7' ></td>
                        <td><input type='text' name='$j-7' value='$r8' ></td>
                        </tr>";
}
?>
    </table>

    <input type="text" value="<?php echo $i; ?>" name="total" >
    <input type="submit" name="submit" value="Download">
</form>
</body>
</html>