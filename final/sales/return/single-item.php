<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Returns</title>
</head>

<body>

    <main class="return-detail-page">

        <section class="content">
            <div class="container-fluid">
                <div class="head text-center">
                    <div class="row">
                        <div class="col-md-4 offset-md-4 text-center">
                    <h1>Item Returns</h1>
                        <a href="http://localhost/final/" class="btn-home"><span><i class="fas fa-home"></i></span></a>
                </div>
                        <div class="col-md-4 text-right">

                    <button class="btn btn-primary ml-auto btn-review" onClick="showReview()">Returns Review</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group v-group">
                            <label class="colun" for="id">Bill ID </label>
                            <div class="inputs">
                                <input type="number" min="0" class="form-control" id="id" autocomplete="off" value="<?php echo $_REQUEST["q"]; ?>"
                                  disabled  placeholder="Enter Product ID">
                                <ul class="list id">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 offset-md-4">
                        <div class="form-group ">
                            <label class="colun" for="name">Cust Name</label>
                            <div class="inputs">
                                <input type="text" class="form-control" id="name" autocomplete="off" placeholder="Vendor Name" disabled>
                                <ul class="list name">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-items">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="wrapper">

                                <table class="table table-striped table-bordered table-responsive excel-table">
                                    <thead class="w-100">
                                        <tr>
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

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="something position-absolute">
            <div class="outer-wrapper">
                <div class="container-fluid">
                    <div class="wrapper">
                        <div class="row">

                                <button class="btn close" onClick="hideAddBox()">X</button>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="colun" for="id">Item ID </label>
                                    <div class="inputs">
                                        <input type="number" min="0" class="form-control" id="iid" autocomplete="off" disabled
                                            placeholder="Enter Product ID">
                                        <ul class="list id">
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="colun" for="re-date">Date</label>
                                    <div class="inputs">
                                        <input type="date" min="0" class="form-control" id="re-date" autocomplete="off"
                                          >
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-4 offset-md-4">
                                <div class="form-group ">
                                    <label class="colun" for="name">Item Name </label>
                                    <div class="inputs">
                                        <input type="text" class="form-control" id="iname" autocomplete="off"
                                            placeholder="Item Name" disabled>
                                        <ul class="list name">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="colun" for="icat">Category </label>
                                    <div class="inputs">
                                        <input type="text" min="0" class="form-control" id="icat" autocomplete="off"
                                            placeholder="Category" disabled>
                                        <ul class="list id">
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="colun" for="iman">Manufacturer </label>
                                    <div class="inputs">
                                        <input type="text" class="form-control" id="iman" autocomplete="off"
                                            placeholder="Manufacturer" disabled>
                                        <ul class="list name">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="colun" for="iprice">Price </label>
                                    <div class="inputs">
                                        <input type="number" min="0" class="form-control" id="iprice" autocomplete="off"
                                            placeholder="Price" disabled>
                                        <ul class="list id">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <div class="form-group ">
                                    <label class="colun" for="iqty">Quantity </label>
                                    <div class="inputs">
                                        <input type="number" class="form-control" id="iqty" autocomplete="off"
                                            placeholder="Quantity" disabled>
                                        <ul class="list name" >
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 offset-md-4 text-center">
                                <div class="form-group ">
                                        <div class="inputs qty-selector">
                                        <button class="btn btn-success ml-auto">+</button>
                                        <input type="number" class="form-control" id="rmv-qty" autocomplete="off"
                                            placeholder="Enter QTY Here">
                                        <button class="btn btn-danger mr-auto">-</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <button class="btn btn-danger" onClick="removeAll()">
                                    Remove All
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-danger ml-auto" onClick="remove()">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="return-review position-absolute">
            <div class="wrapper">

<button class="btn close" onClick="hideAddBox()">X</button>

            <div class="container-fluid">

<div class="row">

        <div class="col-md-12 text-center">
        <h1>Return Review</h1>
        </div>

        <div class="col-2">

            <div class="inputs date-adv d-flex">
                <label for="ope-sele">Date:</label>
                <select id="ope-sele" class="operation">
                    <option value="0" selected> = </option>
                    <option value="1">></option>
                    <option value="2">&lt;</option>
                    <option value="3">>=</option>
                    <option value="4">&lt;=</option>
                    <option value="5">&lt;-Between-></option>
                </select>
            </div>
        </div>

        <div class="col-3">
            <input class="search-inputs use-list" type="date" id="date" autocomplete='off'>
        </div>

        <div class="col-1 text-center">
            <span class=""> &amp; </span>
        </div>

        <div class="col-3">
            <input class="search-inputs date-max" type="date" id="date-max" autocomplete='off'>
        </div>

        <div class="col-3 text-right">
            <button class="btn-success" onClick="goRefresh()">Refresh</button>
        </div>

    </div>

    <div class="table-items">
        <div class="row">
            <div class="col-md-12">
                <div class="wrapper">

                    <table class="table table-striped table-bordered table-responsive excel-table">
                        <thead class="w-100">
                            <tr>
                            <th scope="col"></th>
                                <th class='r-grid-slno' scope="col">Sl.No</th>
                                <th class='r-grid-date' scope="col">Date</th>
                                <th class='r-grid-id' scope="col">Id</th>
                            <th class='r-grid-itemid' scope="col">ItemId</th>
                            <th class='r-grid-name' scope="col">Name</th>
                            <th class='r-grid-cat' scope="col">Category</th>
                            <th class='r-grid-man' scope="col">Manufacture</th>
                            <th class='r-grid-price' scope="col">Price</th>
                            <th class='r-grid-hsn' scope="col">HSN Code</th>
                            <th class='r-grid-igst' scope="col">IGST</th>
                            <th class='r-grid-sgst' scope="col">SGST</th>
                            <th class='r-grid-cgst' scope="col">CGST</th>
                            <th class='r-grid-total' scope="col">Total</th>
                            <th class='r-grid-qnt' scope="col">QNT</th>
                            <th class='r-grid-tax' scope="col">Taxable</th>
                            <th class='r-grid-pay' scope="col">Pay</th>
                            </tr>
                        </thead>
                        <tbody id="r-grid-data">
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
            </div>
        </section>

    </main>
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/fontawesome.min.js"></script>
    <script src="../../js/custom.js"></script>
    <script src="js/item.js"></script>
</body>

</html>