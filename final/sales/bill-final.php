<?php
include_once '../php/conn.php';
if (isset($_REQUEST["q"])) {
    $billId = $_REQUEST["q"];
    $CHECK_QUERY = "SELECT id FROM sales_bill WHERE custid > '0' AND id = '$billId';";
    $result = mysqli_query($conn, $CHECK_QUERY);
    if (mysqli_num_rows($result) > 0) {

        $queryCHECK = "SELECT sales_bill.id, sales_bill.dates, customer.name,customer.phone, sales_bill.amount,
    sales_bill.cgst, sales_bill.sgst, sales_bill.total,sales_bill.pay,customer.gst,customer.address,sales_bill.discount
    FROM sales_bill INNER JOIN customer ON sales_bill.custid = customer.id WHERE sales_bill.id = '$billId';";

        $result = mysqli_query($conn, $queryCHECK);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $BILLID = $row["id"];
                $DATE = $row["dates"];
                $NAME = $row["name"];
                $PHONE = $row["phone"];

                $PRICE = $row["amount"];
                $TCGST = $row["cgst"];
                $TSGST = $row["sgst"];
                $TOTAL = $row["pay"];
                $DISCOUNT_A = $row["discount"];

                $CGST = $row['gst'];
                $ADDRESS = $row['address'];
            }
        }

        $query = "SELECT item.id as r1,item.name as r2,item.manufactor as r3,
    sales_item.amount as r4,sales_item.total as r5,sales_item.quantity as r6,sales_item.pay as r7,
    sales_item.cgstper as r8,sales_item.hsn as r9,sales_item.cgst as r10,
    sales_item.sgstper as r11,sales_item.sgst as r12,sales_item.mrp as r13
     FROM item INNER JOIN sales_item ON item.id = sales_item.itemid
     WHERE sales_item.billid ='" . $billId . "';";

        $result = mysqli_query($conn, $query);
        ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title>Bill Review</title>
</head>

<body class="bill-final-page">

    <main>

        <section class="addr">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4>FAIZ BABY SHOP</h4>
                        <h6>KANNANKERI ESTATE</h6>
                        <h6>MARINE DRIVE,ERNAKULAM - 682031</h6>
                        <h6>Phone: 9495126720 E-mail: faizbabyshopmarinedrive@gmail.com</h6>
                    </div>
                    <div class="col-md-12 d-flex main">
                        <h6>GSTIN : 32AAGFF2699L1Z2</h6>
                        <h6 class="ml-auto"><i>ORIGINAL FOR BUYER</i></h6>
                    </div>
                </div>
            </div>
        </section>

        <section class="head">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 d-flex">
                        <p>Invoice No. <?php echo $BILLID; ?></p>
                        <h5 class="mx-auto">TAX INVOICE-CASH</h5>
                        <p class="date"><span>DATE:<?php echo $DATE; ?></span></p>
    </div>
                    <div class="col-md-7">
                        <p>To :<?php echo $NAME; ?></p>
                        <p><b>GST :<?php echo $CGST; ?></b></p>
                        <p>Phone :<?php echo $PHONE; ?></p>
                    </div>
                    <div class="col-md-2 p-0 text-right">
                        <p>Address :</p>
                    </div>
                    <div class="col-md-3 p-0">
                        <p><?php echo $ADDRESS; ?></p>
                    </div>
                </div>
            </div>
        </section>


        <section class="table-items">
            <div class="row no-gutters">
                <div class="col-md-12">
                    <div class="wrapper">

                        <table class="table excel-table" style="height: 80vh;">
                            <thead class="w-100">
                                <tr>
                                    <th class='grid-slNo' scope="col" rowspan="2">Sl.</th>
                                    <th class='grid-item' scope="col" rowspan="2">Item(s)</th>
                                    <th class='grid-hsn' scope="col" rowspan="2">HSN Code</th>
                                    <th class='grid-qty' scope="col" rowspan="2">Qty</th>
                                    <th class='grid-MRP' scope="col" rowspan="2">MRP</th>
                                    <th class='grid-disc' scope="col" rowspan="2">Discount</th>
                                    <th class='grid-price' scope="col" rowspan="2">Price</th>
                                    <th class='grid-net' scope="col" rowspan="2">Net Value</th>
                                    <th class='grid-gst g' scope="col" colspan="2">CGST</th>
                                    <th class='grid-gst g' scope="col" colspan="2">SGST</th>
                                    <th class='grid-tot' scope="col" rowspan="2">TOTAL</th>
                                </tr>
                                <tr>
                                    <th class='grid-gst-per g' scope="col">%</th>
                                    <th class='grid-gst-amnt g' scope="col">Amt</th>
                                    <th class='grid-gst-per g' scope="col">%</th>
                                    <th class='grid-gst-amnt g' scope="col">Amt</th>
                                </tr>
                            </thead>
                            <tbody id="grid-data">
<?php
if (mysqli_num_rows($result) > 0) {
            $sl = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $sl++;
                ?>
                                <tr>
                                    <td class='grid-slNo'><?php echo $sl; ?></td>
                                    <td class='grid-item'><?php echo $row['r1'] . "/" . $row['r2'] . "/" . $row['r3']; ?></td>
                                    <td class='grid-hsn'><?php echo $row['r9']; ?></td>
                                    <td class='grid-qty'><?php echo $row['r6']; ?></td>
                                    <td class='grid-MRP'><?php echo $row['r13']; ?></td>
                                    <td class='grid-disc'><?php echo ($row['r13'] - $row['r5']); ?></td>
                                    <td class='grid-price'><?php echo $row['r5']; ?></td>
                                    <td class='grid-net'><?php echo $row['r4']; ?></td>
                                    <td class='grid-gst-per'><?php echo $row['r8']; ?></td>
                                    <td class='grid-gst-amnt'><?php echo $row['r10']; ?></td>
                                    <td class='grid-gst-per'><?php echo $row['r11']; ?></td>
                                    <td class='grid-gst-amnt'><?php echo $row['r12']; ?></td>
                                    <td class='grid-tot'><?php echo $row['r7']; ?></td>
                                </tr>
<?php
}
        }
        ?>
                                <tr class="footer-row">
                                    <td></td>
                                    <td style="text-align:center">Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $DISCOUNT_A; ?></td>
                                    <td></td>
                                    <td><?php echo $PRICE; ?></td>
                                    <td></td>
                                    <td><?php echo $TCGST; ?></td>
                                    <td></td>
                                    <td><?php echo $TSGST; ?></td>
                                    <td><?php echo $TOTAL; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>

        <section class="bill-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="pages">
                            <p>Page <span>1</span> of <span>1</span></p>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <h3>FAIZ BABY SHOP</h3>
                        <p>Authorised Signatory</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>print()</script>


    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/fontawesome.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/addItem.js"></script>
    <script src="../js/custom.js"></script>
</body>

</html>

<?php
} else {
        echo '<script>alert("Save Bill And Then Print");</script>';
    }
}
?>