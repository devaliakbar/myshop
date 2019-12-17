<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Purchase Credit</title>
</head>

<body>

    <main class="purchase-credit-page position-relative">

        <section class="head">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center">Credit Detail</h1>
                        <a href="http://localhost/final/" class="btn-home"><span><i class="fas fa-home"></i></span></a>
                    </div>


                    <div class="col-md-3">
                        <div class="inputs">
                            <button class="btn-primary" onClick="showTrans()">Transactions</button>
                        </div>
                    </div>
                    <div class="col-md-3 offset-md-6 text-right">

                        <div class="inputs">
                            <button class="btn-primary" onClick="payNow()">Pay</button>
                        </div>
                    </div>
                </div>

                <div class="row row-2">
                    <div class="col-md-3">
                        <div class="inputs">
                            <label for="vid">Vendor Id</label>
                            <input type="number" id="vid" placeholder="Vendor Id" autocomplete='off' value="<?php echo $_REQUEST["q"]; ?>" disabled>
                        </div>
                    </div>


                    <div class="col-md-3 offset-md-6">
                        <div class="inputs">
                            <label for="vname">Vendor Name</label>
                            <input type="text" id="vname" placeholder="Name" autocomplete='off'>
                        </div>
                    </div>
                </div>

                <div class="row row-2 p-t-0">

                    <div class="col-md-3">
                        <div class="inputs">
                            <label for="vgst-no">GST No</label>
                            <input type="text" id="gst-no" placeholder="GST No" autocomplete='off'>
                        </div>
                    </div>
                    <div class="col-md-4 mx-auto">
                        <div class="inputs">
                            <label for="vaddr">Address</label>
                            <input type="text" id="vaddr" placeholder="Address" autocomplete='off'>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="inputs">
                            <label for="vphone">Phone</label>
                            <input type="text" id="vphone" placeholder="Phone" autocomplete='off'>
                        </div>
                    </div>

                    <div class="col-md-1 mr-auto">
                        <button class="btn-success" onClick="updateVendor()">Update Vendor</button>
                    </div>

                </div>
            </div>
        </section>

        <section class="table-items">
            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-4 ml-auto">

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
                            <input class="search-inputs use-list" type="date" id="date-min" autocomplete='off'>
                            <span class="dte-bet"> &amp; </span>
                            <input class="search-inputs date-max dte-bet" type="date" id="date-max" autocomplete='off'>
                        </div>

                    </div>
                    <div class="col-md-1 mr-auto">
                        <button class="btn-success" onClick="goRefresh()">Refresh</button>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="wrapper">

                            <table class="table table-striped table-bordered table-responsive excel-table">
                                <thead class="w-100">
                                    <tr>
                                        <th class='grid-slno' scope="col">Sl.No</th>
                                        <th class='grid-id' scope="col">Billid</th>
                                        <th class='grid-date' scope="col">Date</th>
                                        <th class='grid-amt' scope="col">Amount</th>
                                        <th class='grid-return' scope="col">Return</th>
                                        <th class='grid-tot' scope="col">Total</th>
                                        <th class='grid-pay' scope="col">Pay</th>
                                        <th class='grid-bal' scope="col">Balance</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody id="grid-data" class = "bills-com">
                                    <tr>
                                        <td>1</td>
                                        <td>ghjas</td>
                                        <td>aghghfdyhgtdrfyhgtdfyghjhgjs</td>
                                        <td>sghjhghgjhgkjhbkjhkjhkjhkjhkjhkja</td>
                                        <td>aghjhgjs</td>
                                        <td>asjjmhvgjhgjhgjhgjghjghjhgjhgjhg</td>
                                        <td>saghjghj</td>
                                        <td>dfgfhfgh</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>


            </div>
        </section>

        <section class="table-footer">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-3">
                        <div class="inputs">
                            <label for="vamount">Amount</label>
                            <input type="number" id="vamount" placeholder="Amount" autocomplete='off'>
                        </div>
                    </div>

                    <div class="col-md-3 mx-auto">
                        <div class="inputs">
                            <label for="vreturn">Return</label>
                            <input type="number" id="vreturn" placeholder="Return" autocomplete='off'>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="inputs">
                            <label for="vtotal">Total</label>
                            <input type="number" id="vtotal" placeholder="Total" autocomplete='off'>
                        </div>
                    </div>
                </div>
                <div class="row row-2">

                    <div class="col-md-3">
                        <div class="inputs">
                            <label for="vpaid">Paid</label>
                            <input type="number" id="vpaid" placeholder="paid" autocomplete='off'>
                        </div>
                    </div>
                    <div class="col-md-3 ml-auto">
                        <div class="inputs">
                            <label for="vbal">Balance</label>
                            <input type="number" id="vbal" placeholder="Balance" autocomplete='off'>
                        </div>
                    </div>
                </div>
        </section>

        <section class="add-products-page position-absolute">
            <div class="container-fluid">
            <div class="wrapper">
                <div class="row no-gutters">
                    <div class="col-md-12">

                        <button onClick="hideAddBox()" class="btn close">X</button>

                        <h1 class="text-center">Pay</h1>

                    </div>
                    <div class="col-md-4">
                        <div class="inputs">
                            <label for="date-pay">Date:</label>
                            <input type="date" id="date-pay" placeholder="Date" autocomplete='off'>
                        </div>
                    </div>

                    <div class="col-md-4 offset-md-1">
                        <div class="inputs">
                            <label for="amount-pay">Amount:</label>
                            <input type="number" id="amount-pay" placeholder="Amount" autocomplete='off'>
                        </div>
                    </div>

                    <div class="col-md-3 text-right">
                        <div class="btn btn-primary" style="display:none;">Auto Apply</div>
                    </div>

                    <div class="col-md-12">

                        <div class="modal-table">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="wrapper">

                                        <table class="table table-striped table-bordered table-responsive excel-table">
                                            <thead class="w-100">
                                                <tr>
                                                    <th class='grid-slno' scope="col">Sl.No</th>
                                                    <th class='grid-id' scope="col">Billid</th>
                                                    <th class='grid-date' scope="col">Date</th>
                                                    <th class='grid-amt' scope="col">Amount</th>
                                                    <th class='grid-return' scope="col">Return</th>
                                                    <th class='grid-tot' scope="col">Total</th>
                                                    <th class='grid-pay' scope="col">Pay</th>
                                                    <th class='grid-bal' scope="col">Balance</th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody id="grid-bill" class = "bills-com">
                                                <tr>
                                                    <td>1</td>
                                                    <td>ghjas</td>
                                                    <td>aghghfdyhgtdrfyhgtdfyghjhgjs</td>
                                                    <td>sghjhghgjhgkjhbkjhkjhkjhkjhkjhkja</td>
                                                    <td>aghjhgjs</td>
                                                    <td>asjjmhvgjhgjhgjhgjghjghjhgjhgjhg</td>
                                                    <td>saghjghj</td>
                                                    <td>dfgfhfgh</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="btn-grp p-0">
                            <button class="btn btn-danger" onClick="hideAddBox()">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>
            </div>
        </section>

        <section class="transation-history position-absolute">
        <div class="container-fluid">
            <div class="wrapper">
                <div class="row no-gutters">
                    <div class="col-md-12">

                        <button onClick="hideAddBox()" class="btn close">X</button>

                        <h1 class="text-center">Transaction History</h1>

                    </div>

                    <div class="col-md-5 ml-auto">

                    <div class="inputs date-adv d-flex">
                    <label for="t-ope-sele">Date :</label>
                    <select id="t-ope-sele" class="operation">
                        <option value="0" selected> = </option>
                        <option value="1">></option>
                        <option value="2">&lt;</option>
                        <option value="3">>=</option>
                        <option value="4">&lt;=</option>
                        <option value="5">&lt;-Between-></option>
                    </select>
                    <input class="search-inputs use-list" type="date" id="t-date-min" autocomplete='off'>
                    <span class="dte-bet"> &amp; </span>
                    <input class="search-inputs date-max dte-bet" type="date" id="t-date-max" autocomplete='off'>
                </div>

            </div>
            <div class="col-md-1 mr-auto">
                <button class="btn-success" onClick="tgoRefresh()">Refresh</button>
            </div>

                    <div class="col-md-12">

                        <div class="modal-table">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="wrapper">

                                        <table class="table table-striped table-bordered table-responsive excel-table">
                                            <thead class="w-100">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th class='grid-slno-t' scope="col">Sl.No</th>
                                                    <th class='grid-id-t' scope="col">Billid</th>
                                                    <th class='grid-date-t' scope="col">Date</th>
                                                    <th class='grid-pay-t' scope="col">Paid</th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody id="grid-trans">
                                                <tr>
                                                    <td></td>
                                                    <td>1</td>
                                                    <td>asjjmhvgjhgjhgjhgjghjghjhgjhgjhg</td>
                                                    <td>saghjghj</td>
                                                    <td>dfgfhfgh</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="btn-grp p-0">
                            <button class="btn btn-danger" onClick="hideAddBox()">Cancel</button>
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
    <script src="js/credit.js"></script>
    <script src="js/pay.js"></script>
    <script src="js/trans.js"></script>
    <script src="../../js/custom.js"></script>
</body>

</html>