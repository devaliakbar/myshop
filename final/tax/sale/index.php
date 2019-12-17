<?php
include_once 'conn.php';
$minDate = "";
$maxDate = "";
$bminDate = "";
$bmaxDate = "";
$hmaxDate = "";
$hminDate = "";
if (isset($_GET['submit-dat'])) {
    $minDate = $_GET['date-min'];
    $maxDate = $_GET['date-max'];

    $bminDate = $_GET['b-date-min'];
    $bmaxDate = $_GET['b-date-max'];

    $hminDate = $_GET['h-date-min'];
    $hmaxDate = $_GET['h-date-max'];
} 
?>
<html>

<body>
    <h1 style="text-align:center;">SALES</h1>

    <form action="" method="GET">
    <h3>B2C FILTER</h3>
    <input type="date" name="date-min" value="<?php echo $minDate; ?>">
    TO
    <input type="date" name="date-max"value="<?php echo $maxDate; ?>">

    <h3>B2B FILTER</h3>
    <input type="date" name="b-date-min" value="<?php echo $bminDate; ?>">
    TO
    <input type="date" name="b-date-max"value="<?php echo $bmaxDate; ?>">

    <h3>HSN SUMMARY FILTER</h3>
    <input type="date" name="h-date-min" value="<?php echo $hminDate; ?>">
    TO
    <input type="date" name="h-date-max"value="<?php echo $hmaxDate; ?>">
    <input type="submit" name="submit-dat" value="Show">
</form>
    <form action ="sale-re.php" method="POST">
    <h3>B2C</h3>
    <table border="1" style="border-collapse: collapse;">
        <tr>
            <th>Place Of Supply</th>
            <th>Rate Of Tax</th>
            <th>Taxable Value</th>
            <th>CGST</th>
            <th>SGST</th>
        </tr>
<?php
$dataArray = array();
$i = 0;

if ($minDate != "" && $maxDate != "") {
    $taxPerq = "SELECT DISTINCT sales_item.igstper FROM sales_item INNER JOIN sales_bill
    ON sales_bill.id = sales_item.billid INNER JOIN customer ON sales_bill.custid = customer.id
    WHERE sales_bill.custid > 0 AND sales_bill.dates BETWEEN '$minDate' AND '$maxDate' AND customer.gst ='NA'";
} else {
    $taxPerq = "SELECT DISTINCT sales_item.igstper FROM sales_item INNER JOIN sales_bill
    ON sales_bill.id = sales_item.billid INNER JOIN customer ON sales_bill.custid = customer.id
    WHERE sales_bill.custid > 0 AND customer.gst ='NA'";
}
$tpresult = mysqli_query($conn, $taxPerq);
if (mysqli_num_rows($tpresult) > 0) {
    while ($trow = mysqli_fetch_assoc($tpresult)) {

        $taxpercentage = $trow['igstper'];

        if ($minDate != "" && $maxDate != "") {
            $tottaxper = "SELECT sum(sales_item.taxable) as taxa,sum(sales_item.totcgst) as cgst,sum(sales_item.totsgst) as sgst
            FROM sales_item INNER JOIN sales_bill ON sales_bill.id = sales_item.billid INNER JOIN customer ON sales_bill.custid = customer.id
            WHERE sales_bill.custid > 0 AND sales_item.igstper='$taxpercentage' AND sales_bill.dates BETWEEN '$minDate' AND '$maxDate' AND customer.gst ='NA'";
        } else {
            $tottaxper = "SELECT sum(sales_item.taxable) as taxa,sum(sales_item.totcgst) as cgst,sum(sales_item.totsgst) as sgst
            FROM sales_item INNER JOIN sales_bill ON sales_bill.id = sales_item.billid INNER JOIN customer ON sales_bill.custid = customer.id
            WHERE sales_bill.custid > 0 AND sales_item.igstper='$taxpercentage' AND customer.gst ='NA'";
        }

        $ttresult = mysqli_query($conn, $tottaxper);
        if (mysqli_num_rows($ttresult) > 0) {
            while ($ttrow = mysqli_fetch_assoc($ttresult)) {
                $taxable = $ttrow['taxa'];
                $cgst = $ttrow['cgst'];
                $sgst = $ttrow['sgst'];

                $dataArray[$i][0] = '32-Kerala';
                $dataArray[$i][1] = $taxpercentage;
                $dataArray[$i][2] = $taxable;
                $dataArray[$i][3] = $cgst;
                $dataArray[$i][4] = $sgst;
                $i++;
            }
        }

    }
}

$n = $i;

if ($minDate != "" && $maxDate != "") {
    $taxPerq = "SELECT DISTINCT sreturn.igstper
    FROM sreturn INNER JOIN sales_bill
    ON sales_bill.id = sreturn.billid INNER JOIN customer ON sales_bill.custid = customer.id
    WHERE sreturn.rdate BETWEEN '$minDate' AND '$maxDate' AND customer.gst ='NA'";
} else {
    $taxPerq = "SELECT DISTINCT sreturn.igstper
    FROM sreturn INNER JOIN sales_bill
    ON sales_bill.id = sreturn.billid INNER JOIN customer ON sales_bill.custid = customer.id
    WHERE customer.gst ='NA'";
}
$tpresult = mysqli_query($conn, $taxPerq);
if (mysqli_num_rows($tpresult) > 0) {
    while ($trow = mysqli_fetch_assoc($tpresult)) {

        $taxpercentage = $trow['igstper'];

        if ($minDate != "" && $maxDate != "") {
            $tottaxper = "SELECT sum(sreturn.taxable) as taxa,sum(sreturn.totcgst) as cgst,sum(sreturn.totsgst) as sgst
            FROM sreturn INNER JOIN sales_bill
            ON sales_bill.id = sreturn.billid INNER JOIN customer ON sales_bill.custid = customer.id
            WHERE sreturn.igstper='$taxpercentage' AND sreturn.rdate BETWEEN '$minDate' AND '$maxDate' AND customer.gst ='NA'";
        } else {
            $tottaxper = "SELECT sum(sreturn.taxable) as taxa,sum(sreturn.totcgst) as cgst,sum(sreturn.totsgst) as sgst
            FROM sreturn INNER JOIN sales_bill
            ON sales_bill.id = sreturn.billid INNER JOIN customer ON sales_bill.custid = customer.id
            WHERE sreturn.igstper='$taxpercentage' AND customer.gst ='NA'";
        }

        $ttresult = mysqli_query($conn, $tottaxper);
        if (mysqli_num_rows($ttresult) > 0) {
            while ($ttrow = mysqli_fetch_assoc($ttresult)) {
                $taxable = $ttrow['taxa'];
                $cgst = $ttrow['cgst'];
                $sgst = $ttrow['sgst'];

                $EXIST_FLAG = false;
                for ($j = 0; $j < $n; $j++) {
                    if ($taxpercentage == $dataArray[$j][1]) {
                        $EXIST_FLAG = true;
                        $dataArray[$j][3] = $dataArray[$j][3] - $cgst;
                        $dataArray[$j][4] = $dataArray[$j][4] - $sgst;
                    }
                }
                if ($EXIST_FLAG == false) {
                    $dataArray[$i][0] = '32-Kerala';
                    $dataArray[$i][1] = $taxpercentage;
                    $dataArray[$i][2] = $taxable;
                    $dataArray[$i][3] = -1 * $cgst;
                    $dataArray[$i][4] = -1 * $sgst;
                    $i++;
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

    echo "<tr>
    <td><input type='text' name='$j--0' value='$r1' ></td>
    <td><input type='text' name='$j--1' value='$r2' ></td>
    <td><input type='text' name='$j--2' value='$r3' ></td>
    <td><input type='text' name='$j--3' value='$r4' ></td>
    <td><input type='text' name='$j--4' value='$r5' ></td>
    </tr>";
}
///***************************************************ending b2c here ************************************************************/
?>
    </table>
    <input type="text" value="<?php echo $i; ?>" name="total" >














    <h3>B2B</h3>
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
$minDate = $bminDate;
$maxDate = $bmaxDate;
if ($minDate != "" && $maxDate != "") {
    $billquery = "SELECT sales_bill.id, customer.gst , sales_bill.dates, sales_bill.total
    FROM sales_bill INNER JOIN customer ON customer.id = sales_bill.custid WHERE sales_bill.custid > 0 AND customer.gst <> 'NA' AND sales_bill.dates BETWEEN '$minDate' AND '$maxDate'";
} else {
    $billquery = "SELECT sales_bill.id, customer.gst , sales_bill.dates, sales_bill.total
    FROM sales_bill INNER JOIN customer ON customer.id = sales_bill.custid WHERE sales_bill.custid > 0 AND customer.gst <> 'NA'";
}
$result = mysqli_query($conn, $billquery);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $BILLID = $row['id'];
        $gst = $row['gst'];
        $bill = $BILLID;
        $date = $row['dates'];
        $total = $row['total'];

        $taxPerq = "SELECT DISTINCT `igstper` FROM sales_item WHERE `billid` = '$BILLID'";
        $tpresult = mysqli_query($conn, $taxPerq);
        if (mysqli_num_rows($tpresult) > 0) {
            while ($trow = mysqli_fetch_assoc($tpresult)) {

                $taxpercentage = $trow['igstper'];

                $tottaxper = "SELECT sum(`taxable`) as taxa,sum(`totcgst`) as cgst,sum(`totsgst`) as sgst FROM sales_item
                    WHERE `billid` = '$BILLID' AND `igstper`='$taxpercentage'";

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
    $billquery = "SELECT sales_bill.id, customer.gst , sales_bill.dates, sales_bill.total
    FROM sales_bill INNER JOIN customer ON customer.id = sales_bill.custid INNER JOIN sreturn ON sreturn.billid = sales_bill.id
    WHERE sales_bill.custid > 0 AND sreturn.rdate BETWEEN '$minDate' AND '$maxDate' AND customer.gst <> 'NA'";
} else {
    $billquery = "SELECT sales_bill.id, customer.gst , sales_bill.dates, sales_bill.total
    FROM sales_bill INNER JOIN customer ON customer.id = sales_bill.custid INNER JOIN sreturn ON sreturn.billid = sales_bill.id
    WHERE sales_bill.custid > 0 AND customer.gst <> 'NA'";
}
$result = mysqli_query($conn, $billquery);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $BILLID = $row['id'];
        $gst = $row['gst'];
        $bill = $BILLID;
        $date = $row['dates'];
        $total = $row['total'];

        if ($minDate != "" && $maxDate != "") {
            $taxPerq = "SELECT DISTINCT `igstper` FROM sreturn
        WHERE `billid` = '$BILLID' AND rdate BETWEEN '$minDate' AND '$maxDate'";
        } else {
            $taxPerq = "SELECT DISTINCT `igstper` FROM sreturn
            WHERE `billid` = '$BILLID'";
        }
        $tpresult = mysqli_query($conn, $taxPerq);
        if (mysqli_num_rows($tpresult) > 0) {
            while ($trow = mysqli_fetch_assoc($tpresult)) {

                $taxpercentage = $trow['igstper'];
                if ($minDate != "" && $maxDate != "") {
                    $tottaxper = "SELECT sum(`taxable`) as taxa,sum(`totcgst`) as cgst,sum(`totsgst`) as sgst FROM sreturn
                WHERE `billid` = '$BILLID' AND `igstper`='$taxpercentage' AND rdate BETWEEN '$minDate' AND '$maxDate'";
                } else {
                    $tottaxper = "SELECT sum(`taxable`) as taxa,sum(`totcgst`) as cgst,sum(`totsgst`) as sgst FROM sreturn
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

    <input type="text" value="<?php echo $i; ?>" name="s-total" >


<?php
//STARTING SUMMARY OF HSN
$dataArray = array();
$i = 0;
$F_TOTAL = 0;
$F_TAXABLE = 0;
$F_IGST = 0;
$F_CGST = 0;
$F_SGST = 0;
$F_CESS = "";

$minDate = $hminDate;
$maxDate = $hmaxDate;
if ($minDate != "" && $maxDate != "") {
    $selectItemQuery = "SELECT DISTINCT sales_item.hsn,item.name,item.id
    FROM sales_item INNER JOIN sales_bill ON sales_bill.id = sales_item.billid INNER JOIN item ON item.id = sales_item.itemid
    WHERE sales_bill.custid > 0 AND sales_bill.dates BETWEEN '$minDate' AND '$maxDate'";
} else {
    $selectItemQuery = "SELECT DISTINCT sales_item.hsn,item.name,item.id
    FROM sales_item INNER JOIN sales_bill ON sales_bill.id = sales_item.billid INNER JOIN item ON item.id = sales_item.itemid
    WHERE sales_bill.custid > 0";
}
$IQresult = mysqli_query($conn, $selectItemQuery);
if (mysqli_num_rows($IQresult) > 0) {
    while ($irow = mysqli_fetch_assoc($IQresult)) {
        $hsn = $irow['hsn'];
        $name = $irow['name'];
        $itemId = $irow['id'];

        if ($minDate != "" && $maxDate != "") {
            $selectValue = "SELECT SUM(sales_item.quantity) AS Q, SUM(sales_item.pay) AS TOT, SUM(sales_item.taxable) AS TAX,
            SUM(sales_item.totigst) AS I, SUM(sales_item.totcgst) AS C, SUM(sales_item.totsgst) AS S
            FROM sales_item INNER JOIN sales_bill ON sales_bill.id = sales_item.billid
            WHERE sales_bill.custid > 0 AND sales_item.itemid = '$itemId' AND sales_bill.dates BETWEEN '$minDate' AND '$maxDate'";
        } else {
            $selectValue = "SELECT SUM(sales_item.quantity) AS Q, SUM(sales_item.pay) AS TOT, SUM(sales_item.taxable) AS TAX,
            SUM(sales_item.totigst) AS I, SUM(sales_item.totcgst) AS C, SUM(sales_item.totsgst) AS S
            FROM sales_item INNER JOIN sales_bill ON sales_bill.id = sales_item.billid
            WHERE sales_bill.custid > 0 AND sales_item.itemid = '$itemId'";
        }
        $Sresult = mysqli_query($conn, $selectValue);
        if (mysqli_num_rows($Sresult) > 0) {
            while ($srow = mysqli_fetch_assoc($Sresult)) {
                $totQty = $srow['Q'];
                $totaV = $srow['TOT'];
                $taxable = $srow['TAX'];
                $igst = $srow['I'];
                $cgst = $srow['C'];
                $sgst = $srow['S'];

                $F_TOTAL = $F_TOTAL + $totaV;
                $F_TAXABLE = $F_TAXABLE + $taxable;
                $F_IGST = $F_IGST + $igst;
                $F_CGST = $F_CGST + $cgst;
                $F_SGST = $F_SGST + $sgst;

                $dataArray[$i][0] = $hsn;
                $dataArray[$i][1] = $name;
                $dataArray[$i][2] = "NOS-NUMBERS";
                $dataArray[$i][3] = $totQty;
                $dataArray[$i][4] = $totaV;
                $dataArray[$i][5] = $taxable;
                $dataArray[$i][6] = $igst;
                $dataArray[$i][7] = $cgst;
                $dataArray[$i][8] = $sgst;
                $dataArray[$i][9] = "";
                $i++;

            }
        }
    }
}

$n = $i;
if ($minDate != "" && $maxDate != "") {
    $selectItemQuery = "SELECT DISTINCT sreturn.hsn,item.name,item.id
    FROM sreturn INNER JOIN item ON item.id = sreturn.itemid
    WHERE sreturn.rdate BETWEEN '$minDate' AND '$maxDate'";
} else {
    $selectItemQuery = "SELECT DISTINCT sreturn.hsn,item.name,item.id
    FROM sreturn INNER JOIN item ON item.id = sreturn.itemid";
}

$IQresult = mysqli_query($conn, $selectItemQuery);
if (mysqli_num_rows($IQresult) > 0) {
    while ($irow = mysqli_fetch_assoc($IQresult)) {
        $hsn = $irow['hsn'];
        $name = $irow['name'];
        $itemId = $irow['id'];

        if ($minDate != "" && $maxDate != "") {
            $selectValue = "SELECT SUM(sreturn.quantity) AS Q, SUM(sreturn.pay) AS TOT, SUM(sreturn.taxable) AS TAX,
            SUM(sreturn.totigst) AS I, SUM(sreturn.totcgst) AS C, SUM(sreturn.totsgst) AS S
            FROM sreturn
            WHERE sreturn.itemid = '$itemId' AND sreturn.rdate BETWEEN '$minDate' AND '$maxDate'";
        } else {
            $selectValue = "SELECT SUM(sreturn.quantity) AS Q, SUM(sreturn.pay) AS TOT, SUM(sreturn.taxable) AS TAX,
            SUM(sreturn.totigst) AS I, SUM(sreturn.totcgst) AS C, SUM(sreturn.totsgst) AS S
            FROM sreturn
            WHERE sreturn.itemid = '$itemId'";
        }

        $Sresult = mysqli_query($conn, $selectValue);
        if (mysqli_num_rows($Sresult) > 0) {
            while ($srow = mysqli_fetch_assoc($Sresult)) {
                $totQty = $srow['Q'];
                $totaV = $srow['TOT'];
                $taxable = $srow['TAX'];
                $igst = $srow['I'];
                $cgst = $srow['C'];
                $sgst = $srow['S'];
                $EXIST_FLAG = false;
                for ($j = 0; $j < $n; $j++) {
                    if ($hsn == $dataArray[$j][0] && $dataArray[$j][1] == $name) {
                        $EXIST_FLAG = true;
                        $dataArray[$j][6] = $dataArray[$j][6] - $igst;
                        $dataArray[$j][7] = $dataArray[$j][7] - $cgst;
                        $dataArray[$j][8] = $dataArray[$j][8] - $sgst;

                        $F_IGST = $F_IGST - $igst;
                        $F_CGST = $F_CGST - $cgst;
                        $F_SGST = $F_SGST - $sgst;
                    }
                }
                if ($EXIST_FLAG == false) {
                    $F_TOTAL = $F_TOTAL + $totaV;
                    $F_TAXABLE = $F_TAXABLE + $taxable;
                    $F_IGST = $F_IGST - $igst;
                    $F_CGST = $F_CGST - $cgst;
                    $F_SGST = $F_SGST - $sgst;

                    $dataArray[$i][0] = $hsn;
                    $dataArray[$i][1] = $name;
                    $dataArray[$i][2] = "NOS-NUMBERS";
                    $dataArray[$i][3] = $totQty;
                    $dataArray[$i][4] = $totaV;
                    $dataArray[$i][5] = $taxable;
                    $dataArray[$i][6] = -1 * $igst;
                    $dataArray[$i][7] = -1 * $cgst;
                    $dataArray[$i][8] = -1 * $sgst;
                    $dataArray[$i][9] = "";

                    $i++;
                }

            }
        }

    }
}
$F_IGST = number_format($F_IGST,3);
if($F_IGST == 0){
    $F_IGST = 0;
}
$F_CGST = number_format($F_CGST,3);
if($F_CGST == 0){
    $F_CGST = 0;
}
$F_SGST = number_format($F_SGST,3);
if($F_SGST == 0){
    $F_SGST = 0;
}
?>

    <h3>Summary For HSN</h3>
    <table border="1" style="border-collapse: collapse;">
        <tr>
            <th>No. Of HSN</th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total Value</th>
            <th>Total Taxable Value</th>
            <th>Total Integrated Tax</th>
            <th>Total Central Tax</th>
            <th>Total State/UT Tax</th>
            <th>Total Cess</th>
        </tr>
<tr>
            <td><input type='text' name="s---thsn" value="<?php echo $i; ?>"></td>
            <td></td>
            <td></td>
            <td></td>
            <td><input type='text' name="s---tot" value="<?php echo $F_TOTAL; ?>"></td>
            <td><input type='text' name="s---tax" value="<?php echo $F_TAXABLE; ?>"></td>
            <td><input type='text' name="s---igst" value="<?php echo $F_IGST; ?>"></td>
            <td><input type='text' name="s---cgst" value="<?php echo $F_CGST; ?>"></td>
            <td><input type='text' name="s---sgst" value="<?php echo $F_SGST; ?>"></td>
            <td><input type='text' name="s---cess" value="<?php echo $F_CESS; ?>"></td>
<tr>
<tr>
            <th>HSN</th>
            <th>Description</th>
            <th>UQC</th>
            <th>Total Quantity</th>
            <th>Total Value</th>
            <th>Taxable Value</th>
            <th>Integrated Tax Amount</th>
            <th>Central Tax Amount</th>
            <th>State/UT Tax Amount</th>
            <th>Cess Amount</th>
</tr>

<?php
for ($j = 0; $j < $i; $j++) {
    $r1 = $dataArray[$j][0];
    $r2 = $dataArray[$j][1];
    $r3 = $dataArray[$j][2];
    $r4 = $dataArray[$j][3];
    $r5 = $dataArray[$j][4];
    $r6 = $dataArray[$j][5];
    $r7 = $dataArray[$j][6];
    $r8 = $dataArray[$j][7];
    $r9 = $dataArray[$j][8];
    $r10 = $dataArray[$j][9];
    echo "<tr>
                            <td><input type='text' name='$j---0' value='$r1' ></td>
                            <td><input type='text' name='$j---1' value='$r2' ></td>
                            <td><input type='text' name='$j---2' value='$r3' ></td>
                            <td><input type='text' name='$j---3' value='$r4' ></td>
                            <td><input type='text' name='$j---4' value='$r5' ></td>
                            <td><input type='text' name='$j---5' value='$r6' ></td>
                            <td><input type='text' name='$j---6' value='$r7' ></td>
                            <td><input type='text' name='$j---7' value='$r8' ></td>
                            <td><input type='text' name='$j---8' value='$r9' ></td>
                            <td><input type='text' name='$j---9' value='$r10' ></td>
                            </tr>";
}
?>


        </table>




    <input type="submit" name="submit" value="Download">
</form>
</body>
</html>