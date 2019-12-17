<?php
if (isset($_REQUEST["q"])) {
    $billId = $_REQUEST["q"];
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title>Add Bill</title>
</head>
<body>
    <main class="add-bill-page purchase-page position-relative">

        <section class="head">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center">Add Bill</h1>
                        <a href="http://localhost/final/" class="btn-home"><span><i class="fas fa-home"></i></span></a>
                    </div>

                    <div class="col-md-3">
                        <div class="inputs  labelled">
                            <label for="pur-id">Bill-ID</label>
                            <input class="search-inputs" type="number" id="pur-id" placeholder="Bill-ID" autocomplete='off'
                            value="<?php echo $billId; ?>"   disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="inputs  labelled">
                            <label for="gst-no">GST-No</label>
                            <input class="search-inputs" type="text" id="gst-no" placeholder="GST No" autocomplete='off'>
                        </div>
                    </div>
                    <div class="col-md-3">

                        <div class="inputs  labelled">
                            <label for="date">Date</label>
                            <input class="search-inputs" type="date" id="date" placeholder="Date" autocomplete='off'>
                        </div>
                    </div>
                    </div>
                    <div class="row row-2">
                        <div class="col-md-3">
                            <div class="inputs  labelled">
                                <label for="cust-name">Customer Name</label>
                                <input class="search-inputs use-list" type="text" id="cust-name" placeholder="Customer Name"
                                    autocomplete='off'>
                                <ul class="list cust-name">
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="inputs  labelled">
                                <label for="addr">Address</label>
                                <input class="search-inputs use-list" type="text" id="cust-addr" placeholder="Address"
                                    autocomplete='off'>
                            </div>
                        </div>
                        <div class="col-md-3">

                            <div class="inputs  labelled">
                                <label for="cust-phone">Phone</label>
                                <input class="search-inputs use-list" type="text" id="cust-phone" placeholder="Phone"
                                    autocomplete='off'>
                                <ul class="list cust-phone">
                                </ul>
                            </div>
                        </div>
                </div>
            </div>
        </section>

        <section class="table-items">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="wrapper">

                            <table class="table table-striped table-bordered table-responsive excel-table">
                                <thead>
                                    <tr>
                                        <th class='grid-del-icon' scope="col"></th>
                                        <th class='grid-slno' scope="col">Sl.No</th>
                                        <th class='grid-id' scope="col">Id</th>
                                        <th class='grid-name' scope="col">Name</th>
                                        <th class='grid-cat' scope="col">Category</th>
                                        <th class='grid-man' scope="col">Manufacture</th>
                                        <th class='grid-price' scope="col">Price</th>
                                        <th class='grid-hsn' scope="col">HSN Code</th>
                                        <th class='grid-igst' scope="col">IGST%</th>
                                        <th class='grid-sgst' scope="col">SGST%</th>
                                        <th class='grid-cgst' scope="col">CGST%</th>

                                        <th class='grid-i' scope="col">IGST</th>
                                        <th class='grid-s' scope="col">SGST</th>
                                        <th class='grid-c' scope="col">CGST</th>

                                        <th class='grid-mrp' scope="col">MRP</th>

                                        <th class='grid-total' scope="col">Total</th>
                                        <th class='grid-qnt' scope="col">QNT</th>
                                        <th class='grid-tax' scope="col">Taxable</th>

                                        <th class='grid-totmrp' scope="col">Total MRP</th>
                                        <th class='grid-discper' scope="col">Discound %</th>
                                        <th class='grid-disc' scope="col">Discount</th>

                                        <th class='grid-pay' scope="col">Pay</th>
                                    </tr>
                                </thead>
                                <tbody id="grid-data">
                                </tbody>
                            </table>
                        </div>
                        <div class="btn-grp">
                            <button class="btn btn-success" id="add-page" onclick="showAddBox()">Add Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="billing">
            <div class="container-fluid">
                <div class="wrapper">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="pur-igst">IGST</label>
                            <input type="number" id="pur-igst" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="pur-cgst">CGST</label>
                            <input type="number" id="pur-cgst" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="pur-sgst">SGST</label>
                            <input type="number" id="pur-sgst" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="pur-tax">Taxable</label>
                            <input type="number" id="pur-tax" disabled>
                        </div>
                        <div class="col-md-4 offset-md-4">

                            <label for="pur-mrp">MRP</label>
                            <input type="text" id="pur-mrp" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="pur-disc">Discount</label>
                            <input type="text" id="pur-disc" disabled>
                        </div>
                        <div class="col-md-4 offset-md-4">
                            <label for="pur-disc-per">Discount%</label>
                            <input type="text" id="pur-disc-per" disabled>
                        </div>
                        <div class="col-md-4 offset-md-8">
                            <label for="pur-pay">Pay</label>
                            <input type="text" id="pur-pay" autocomplete='off' disabled>
                        </div>
                    </div>

                </div>

                <div class="btn-grp">
                    <button class="btn btn-primary" onClick="saveItems()">Save</button>
                    <button class="btn btn-danger mx-auto" onClick="printBill()">Print</button>
                    <button class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </section>

        <section class="add-products-page position-absolute" >
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="wrapper">

                            <button onClick="hideAddBox()" class="btn close">X</button>

                            <h1 class="text-center">Add Item</h1>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="id">ID</label>
                                        <div class="inputs">
                                            <input type="number" min="0" class="form-control" id="id" autocomplete="off"
                                                placeholder="Enter Product ID">
                                            <ul class="list id">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <div class="inputs">
                                            <input type="text" class="form-control" id="name" autocomplete="off"
                                                placeholder="Product Name">
                                            <ul class="list name">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="categ">Category</label>
                                        <div class="inputs">
                                            <input type="text" class="form-control" id="categ" autocomplete="off"
                                                placeholder="Category">
                                            <ul class="list categ">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="categ">Manufacture</label>
                                        <div class="inputs">
                                            <input type="text" class="form-control" id="manufact" autocomplete="off"
                                                placeholder="Manufacture">
                                            <ul class="list manufact">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Price</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="amount"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hsn">HSN Code</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="hsn"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="igst-per">IGST %</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="igst-per"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sgst-per">SGST %</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="sgst-per"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cgst-per">CGST %</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="cgst-per"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="igst">IGST</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="igst"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sgst">SGST </label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="sgst"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cgst">CGST</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="cgst"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amnt-n-tax">Amount + Tax</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="amnt-n-tax"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="qnt">Quantity</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="qnt"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tax">Taxable</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="tax"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tot-igst">TOTAL IGST</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="tot-igst"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tot-sgst">TOTAL SGST </label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="tot-sgst"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tot-cgst">TOTAL CGST</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="tot-cgst"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="disc">DIscount </label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="disc"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-md-4">
                                    <div class="form-group">
                                        <label for="disc-per">Discount %</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="disc-per"
                                            placeholder="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-md-8">
                                    <div class="form-group">
                                        <label for="total">Total MRP</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="total"
                                            placeholder="" disabled>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-md-8">
                                    <div class="form-group">
                                        <label for="pay-fin">Pay</label>
                                        <input type="number" min="0" autocomplete="off" class="form-control" id="pay-fin"
                                            placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="btn-grp p-0">
                                    <button class="btn btn-danger" onClick="hideAddBox()">Cancel</button>
                                    <button class="btn btn-primary ml-auto" onclick="checkItem()">Save</button>

                                    </div>
                                </div>
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
    <script src="../js/fontawesome.min.js"></script>
    <script src="upjs/custom.js"></script>
    <script src="upjs/addItem.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>

<?php
}
?>