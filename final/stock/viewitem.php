<?php
if (isset($_REQUEST["q"])) {
    $itemId = $_REQUEST["q"];
    include_once '../php/conn.php';

    $query = "SELECT item.name as r2,item.manufactor as r3,item.catagory as r4,
         amount.price as r5,amount.cgst as r6,amount.sgst as r7,amount.igst as r8,amount.total as r9,
         tax.cgstper as r10,tax.sgstper as r11,tax.igstper as r12,tax.hsn as r13,
         stock.bal AS r14,stock.sold AS r15
         FROM item INNER JOIN amount ON item.id = amount.itemid
         INNER JOIN tax ON item.id = tax.itemid
         INNER JOIN stock ON item.id = stock.itemid
         WHERE amount.type='S' AND item.id = $itemId;";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $itemName = $row["r2"];
            $itemManu = $row["r3"];
            $itemCat = $row["r4"];

            $itemPrice = $row["r5"];
            $itemCgst = $row["r6"];
            $itemSgst = $row["r7"];
            $itemIgst = $row["r8"];
            $itemTotal = $row["r9"];

            $itemTaxc = $row["r10"];
            $itemTaxs = $row["r11"];
            $itemTaxi = $row["r12"];
            $itemHsn = $row["r13"];
            $itemStock = $row["r14"];
            $sold = $row["r15"];
        }
    }

    $query = "SELECT total FROM amount WHERE amount.type='P' AND itemid = $itemId;";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $itemPurPrice = $row["total"];
        }
        ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title>View Item</title>
</head>

<body>
    <main class="viewItem-page">
        <section class="single-stock">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center">
                            Item
                        </h1>
                        <a href="http://localhost/final/" class="btn-home"><span><i class="fas fa-home"></i></span></a>
                        <div class="table-wrapper">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="id">ID</label>
                                            <input type="number" min="0" class="form-control" id="id" autocomplete="off" disabled
                                                placeholder="Enter Product ID" value="<?php echo $itemId; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" autocomplete="off"
                                                placeholder="Product Name" value="<?php echo $itemName; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="categ">Category</label>
                                            <input type="text" class="form-control" id="categ" autocomplete="off"
                                                placeholder="Category" value="<?php echo $itemCat; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="categ">Manufacture</label>
                                            <input type="text" class="form-control" id="manufact" autocomplete="off"
                                                placeholder="Manufacture" value="<?php echo $itemManu; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="amount" disabled
                                            placeholder="" value="<?php echo $itemPrice; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hsn">HSN Code</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="hsn" disabled
                                            placeholder="" value="<?php echo $itemHsn; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cgst-per">CGST %</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="cgst-per" disabled
                                            placeholder="" value="<?php echo $itemTaxc; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sgst-per">SGST %</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="sgst-per" disabled
                                            placeholder="" value="<?php echo $itemTaxs; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="igst-per">IGST %</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="igst-per" disabled
                                            placeholder="" value="<?php echo $itemTaxi; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cgst">CGST</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="cgst" disabled
                                            placeholder="" value="<?php echo $itemCgst; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sgst">SGST</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="sgst" disabled
                                            placeholder="" value="<?php echo $itemSgst; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="igst">IGST</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="igst" disabled
                                            placeholder="" value="<?php echo $itemIgst; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stock-left">Stock Left</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="stock-left" disabled
                                            placeholder="" value="<?php echo $itemStock; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sold">Sold</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="sold" disabled
                                            placeholder="" value="<?php echo $sold; ?>">
                                    </div>
                                </div>






                                <div class="col-md-4 offset-md-8">
                                    <div class="form-group">
                                        <label for="pur-price">Purchase Price Include Tax</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="pur-price" disabled
                                            placeholder="" value="<?php echo $itemPurPrice; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 offset-md-8">
                                    <div class="form-group">
                                        <label for="amnt-n-tax">MRP</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="amnt-n-tax"
                                            placeholder="" value="<?php echo $itemTotal; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="btn-grp d-flex">
                                <button onClick="cancel()" class="btn btn-danger">Cancel</button>
                                    <button name="barcode" onClick="viewBarcode()" class="btn btn-primary mx-auto">Get Barcode</button>
                                    <button onClick="update()" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="js/viewItem.js"></script>
    <script src="../js/custom.js"></script>
</body>

</html>
<?php
}
}
?>