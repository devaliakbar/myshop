var mouseItem = false;

var index = -1;

var PRICE_HELPER = 0;

var idHelp = {
    realid: null,
    realname: null,
    realcat: null,
    realman: null,
    stock: null
};


function addItemReady() {
    //************************************************************************************ 
    //SMARTFILL FOR BILL FINAL PAY
    $("#pay-fin").keyup(function () {

        if ($('#pay-fin').val() != "" && $("#total").val() != "") {
            var offr = parseFloat($("#pay-fin").val());
            var non_offer = parseFloat($("#total").val());
            if (offr <= non_offer) {
                var taxhsn = parseFloat($("#hsn").val());
                if (isNaN(taxhsn)) { taxhsn = 0; }
                var taxcgst = parseFloat($("#cgst-per").val());
                if (isNaN(taxcgst)) { taxcgst = 0; }
                var taxsgst = parseFloat($("#sgst-per").val());
                if (isNaN(taxsgst)) { taxsgst = 0; }
                var taxigst = parseFloat($("#igst-per").val());
                if (isNaN(taxigst)) { taxigst = 0; }

                var price = parseFloat($("#amount").val());
                if (isNaN(price)) { price = 0; }
                var cgst = parseFloat($("#cgst").val());
                if (isNaN(cgst)) { cgst = 0; }
                var sgst = parseFloat($("#sgst").val());
                if (isNaN(sgst)) { sgst = 0; }
                var igst = parseFloat($("#igst").val());
                if (isNaN(igst)) { igst = 0; }
                var total = parseFloat($("#amnt-n-tax").val());
                if (isNaN(total)) { total = 0; }

                var quantity = parseFloat($("#qnt").val());
                if (isNaN(quantity)) { quantity = 0; }
                var taxable = parseFloat($("#tax").val());
                if (isNaN(taxable)) { taxable = 0; }
                var pay = parseFloat($("#pay-fin").val());
                if (isNaN(pay)) { pay = 0; }

                var tcgst = parseFloat($("#tot-cgst").val());
                if (isNaN(tcgst)) { tcgst = 0; }
                var tsgst = parseFloat($("#tot-sgst").val());
                if (isNaN(tsgst)) { tsgst = 0; }
                var tigst = parseFloat($("#tot-igst").val());
                if (isNaN(tigst)) { tigst = 0; }

                if (pay > 0) {
                    if (quantity > 0) {
                        if (taxigst > 0) {
                            if (taxsgst == 0 && taxcgst == 0) {
                                taxcgst = Number((taxigst / 2));
                                taxsgst = Number((taxcgst));
                            }
                            var yy = taxigst / 100;
                            var zz = pay;
                            var xx = zz / (1 + yy); //CALCULATED TAXABLE FROM PAY AND DISCOUNT PERCENTAGE

                            taxable = Number((xx));

                            total = Number((pay / quantity));
                            price = Number((taxable / quantity));
                            sgst = Number(((taxsgst / 100) * price));
                            cgst = Number(((taxcgst / 100) * price));
                            igst = Number((sgst + cgst));

                            tcgst = cgst * quantity;
                            tsgst = sgst * quantity;
                            tigst = igst * quantity;
                        } else {
                            taxable = Number((pay));
                            total = Number((pay / quantity));
                            price = Number((total));
                            sgst = Number((0));
                            cgst = Number((0));
                            igst = Number((0));

                            taxhsn = Number((0));
                            taxcgst = Number((0));
                            taxsgst = Number((0));
                            taxigst = Number((0));

                            tsgst = Number((0));
                            tcgst = Number((0));
                            tigst = Number((0));
                        }

                        //************************************DISCOUND**********************************
                        var actualTotal = $("#total").val();
                        var discount_Amount = actualTotal - pay;
                        var discount_Amount_Per = (discount_Amount / actualTotal) * 100;

                        $("#disc").val(discount_Amount);
                        $("#disc-per").val(discount_Amount_Per);
                        //************************************DISCOUNT CALCULATION END *****************

                        $("#tax").val(taxable);
                        $("#amnt-n-tax").val(total);
                        $("#amount").val(price);
                        $("#sgst").val(sgst);
                        $("#cgst").val(cgst);
                        $("#igst").val(igst);

                        $("#hsn").val(taxhsn);
                        $("#cgst-per").val(taxcgst);
                        $("#sgst-per").val(taxsgst);
                        $("#igst-per").val(taxigst);

                        $("#tot-sgst").val(tsgst);
                        $("#tot-cgst").val(tcgst);
                        $("#tot-igst").val(tigst);
                    }
                }
            } else {
                $("#pay-fin").val(non_offer);
                $("#disc").val(0);
                $("#disc-per").val(0);
            }
        } else {
            $("#disc").val("");
            $("#disc-per").val("");
        }

    });

    $("#pay_fin").focusout(function () {
        if ($("#total").val() != "") {
            if ($("#pay-fin").val() == "") {
                $("#pay-fin").val($("#total").val());
                $("#disc").val(0);
                $("#disc-per").val(0);
            }
        }
    })

    //************************************************************************************ 
    //FOR NAME
    var $input = $('#name');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (index < count - 1) {
                if (!mouseItem) {

                    index++;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * index }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            index = -1;
            $(".list").html("");
        }
        else if (e.keyCode == 38) {
            if (index > 0) {
                if (!mouseItem) {

                    index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * index) - 72 }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                itemselectedHint($(".selected").attr('id'));
            }
        } else {
            itemshowHint($('#name').val(), "name");
        }
    });

    //FOR ID
    var $input = $('#id');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (index < count - 1) {
                if (!mouseItem) {
                    index++;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * index }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            index = -1;
            $(".list").html("");
        }

        else if (e.keyCode == 38) {
            if (index > 0) {
                if (!mouseItem) {

                    index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * index) - 72 }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                itemselectedHint($(".selected").attr('id'));
            }


        } else {
            itemshowHint($('#id').val(), "id");
        }
    });

    //FOR CATEGORY
    var $input = $('#categ');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (index < count - 1) {
                if (!mouseItem) {

                    index++;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * index }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            index = -1;
            $(".list").html("");
        }

        else if (e.keyCode == 38) {
            if (index > 0) {
                if (!mouseItem) {

                    index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * index) - 72 }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                itemselectedHint($(".selected").attr('id'));
            }


        } else {
            itemshowHint($('#categ').val(), "categ");
        }
    });

    //FOR MANUFACTOR
    var $input = $('#manufact');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (index < count - 1) {
                if (!mouseItem) {

                    index++;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * index }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            index = -1;
            $(".list").html("");
        }

        else if (e.keyCode == 38) {
            if (index > 0) {
                if (!mouseItem) {

                    index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * index) - 72 }, 100);

                    itemremoveAll();
                    itemappendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                itemselectedHint($(".selected").attr('id'));
            }


        } else {
            itemshowHint($('#manufact').val(), "manufact");
        }
    });

    //WHEN FOCUS LOST,REMOVE SUGGESION
    $(".form-control").focusout(function () {
        if (!$(".selected").attr('id')) {
            index = -1;
            $(".list").html("");
        }
    });

    //WHEN ID FOCUS LOST TRACK IT'S VALUE
    $("#id").focusout(function () {
        if (idHelp.realid == null) {
            $("#id").val("");
            $("#name").val("");
        } else {
            if (idHelp.realid != $("#id").val()) {
                $("#id").val("");
                $("#name").val("");
            }
        }
    });

    //************************************* */ SMART FILL START HERE****************************************

    //CHANGING SMART PERCENTAGE FROM CGST
    $("#cgst-per").keyup(function () {
        var taxcgst = parseFloat($("#cgst-per").val());
        if (isNaN(taxcgst)) { taxcgst = 0; }

        var taxsgst = parseFloat($("#sgst-per").val());
        if (isNaN(taxsgst)) { taxsgst = 0; }

        var taxigst = parseFloat($("#igst-per").val());
        if (isNaN(taxigst)) { taxigst = 0; }

        //AFTER CONVERSION
        if (taxcgst >= 0 && taxcgst < 100) {
            if (taxcgst > 0) {
                //LOGIC START HERE
                if (taxigst > 0) {
                    taxsgst = taxigst - taxcgst;
                } else {
                    if (taxsgst > 0) {
                        if ((taxcgst + taxsgst) <= 100) {
                            taxigst = taxcgst + taxsgst;
                        } else {
                            taxsgst = 0;
                            taxcgst = 0;
                        }
                    } else {
                        taxsgst = 0;
                        taxigst = taxcgst;
                    }
                }
                if (taxigst < 0 || taxsgst < 0 || taxcgst < 0) {
                    $("#cgst-per").val("");
                    $("#sgst-per").val("");
                    $("#igst-per").val("");
                } else {
                    $("#cgst-per").val(Number(taxcgst));
                    $("#sgst-per").val(Number(taxsgst));
                    $("#igst-per").val(Number(taxigst));
                    changeAllFromIGST();
                }
                //LOGIC END HERE
            }
        } else {
            $("#cgst-per").val("");
            $("#sgst-per").val("");
            $("#igst-per").val("");
        }
    });

    //CHANGING SMART PERCENTAGE FROM SGST
    $("#sgst-per").keyup(function () {
        var taxcgst = parseFloat($("#cgst-per").val());
        if (isNaN(taxcgst)) { taxcgst = 0; }

        var taxsgst = parseFloat($("#sgst-per").val());
        if (isNaN(taxsgst)) { taxsgst = 0; }

        var taxigst = parseFloat($("#igst-per").val());
        if (isNaN(taxigst)) { taxigst = 0; }

        //AFTER CONVERSION
        if (taxsgst >= 0 && taxsgst < 100) {
            if (taxsgst > 0) {
                //LOGIC START HERE
                if (taxigst > 0) {
                    taxcgst = taxigst - taxsgst;
                } else {
                    if (taxcgst > 0) {
                        if ((taxsgst + taxcgst) <= 100) {
                            taxigst = taxsgst + taxcgst;
                        } else {
                            taxcgst = 0;
                            taxsgst = 0;
                        }
                    } else {
                        taxcgst = 0;
                        taxigst = taxsgst;
                    }
                }
                if (taxigst < 0 || taxsgst < 0 || taxcgst < 0) {
                    $("#cgst-per").val("");
                    $("#sgst-per").val("");
                    $("#igst-per").val("");
                } else {
                    $("#cgst-per").val(Number(taxcgst));
                    $("#sgst-per").val(Number(taxsgst));
                    $("#igst-per").val(Number(taxigst));
                    changeAllFromIGST();
                }
                //LOGIC END HERE
            }
        } else {
            $("#cgst-per").val("");
            $("#sgst-per").val("");
            $("#igst-per").val("");
        }
    });

    //CHANGING SMART PERCENTAGE FROM IGST
    $("#igst-per").keyup(function () {
        var taxcgst = parseFloat($("#cgst-per").val());
        if (isNaN(taxcgst)) { taxcgst = 0; }

        var taxsgst = parseFloat($("#sgst-per").val());
        if (isNaN(taxsgst)) { taxsgst = 0; }

        var taxigst = parseFloat($("#igst-per").val());
        if (isNaN(taxigst)) { taxigst = 0; }

        //AFTER CONVERSION
        if (taxigst >= 0 && taxigst < 100) {
            if (taxigst > 0) {
                //LOGIC START HERE
                if (taxcgst == 0 && taxsgst == 0) {
                    taxcgst = taxigst / 2;
                    taxsgst = taxcgst;
                } else {
                    if (taxcgst == 0) {
                        taxcgst = taxigst - taxsgst;
                    } else if (taxsgst == 0) {
                        taxsgst = taxigst - taxcgst;
                    } else {
                        if ((taxsgst + taxcgst) != taxigst) {
                            taxcgst = taxigst / 2;
                            taxsgst = taxcgst;
                        }
                    }
                }
                if (taxigst < 0 || taxsgst < 0 || taxcgst < 0) {
                    $("#cgst-per").val("");
                    $("#sgst-per").val("");
                    $("#igst-per").val("");
                } else {
                    $("#cgst-per").val(Number(taxcgst));
                    $("#sgst-per").val(Number(taxsgst));
                    $("#igst-per").val(Number(taxigst));
                    changeAllFromIGST();
                }
                //LOGIC END HERE
            }
        } else {
            $("#cgst-per").val("");
            $("#sgst-per").val("");
            $("#igst-per").val("");
        }
    });

    //CHANGING EVRYTHING WHEN TOTAL BUTTON UP
    $("#total").keyup(function () {
        var taxhsn = parseFloat($("#hsn").val());
        if (isNaN(taxhsn)) { taxhsn = 0; }
        var taxcgst = parseFloat($("#cgst-per").val());
        if (isNaN(taxcgst)) { taxcgst = 0; }
        var taxsgst = parseFloat($("#sgst-per").val());
        if (isNaN(taxsgst)) { taxsgst = 0; }
        var taxigst = parseFloat($("#igst-per").val());
        if (isNaN(taxigst)) { taxigst = 0; }

        var price = parseFloat($("#amount").val());
        if (isNaN(price)) { price = 0; }
        var cgst = parseFloat($("#cgst").val());
        if (isNaN(cgst)) { cgst = 0; }
        var sgst = parseFloat($("#sgst").val());
        if (isNaN(sgst)) { sgst = 0; }
        var igst = parseFloat($("#igst").val());
        if (isNaN(igst)) { igst = 0; }
        var total = parseFloat($("#amnt-n-tax").val());
        if (isNaN(total)) { total = 0; }

        var quantity = parseFloat($("#qnt").val());
        if (isNaN(quantity)) { quantity = 0; }
        var taxable = parseFloat($("#tax").val());
        if (isNaN(taxable)) { taxable = 0; }
        var pay = parseFloat($("#total").val());
        if (isNaN(pay)) { pay = 0; }

        var tcgst = parseFloat($("#tot-cgst").val());
        if (isNaN(tcgst)) { tcgst = 0; }
        var tsgst = parseFloat($("#tot-sgst").val());
        if (isNaN(tsgst)) { tsgst = 0; }
        var tigst = parseFloat($("#tot-igst").val());
        if (isNaN(tigst)) { tigst = 0; }

        if (pay > 0) {
            if (quantity > 0) {
                if (taxigst > 0) {
                    if (taxsgst == 0 && taxcgst == 0) {
                        taxcgst = Number((taxigst / 2));
                        taxsgst = Number((taxcgst));
                    }
                    var yy = taxigst / 100;
                    var zz = pay;
                    var xx = zz / (1 + yy); //CALCULATED TAXABLE FROM PAY AND DISCOUNT PERCENTAGE

                    taxable = Number((xx));

                    total = Number((pay / quantity));
                    price = Number((taxable / quantity));
                    sgst = Number(((taxsgst / 100) * price));
                    cgst = Number(((taxcgst / 100) * price));
                    igst = Number((sgst + cgst));

                    tcgst = cgst * quantity;
                    tsgst = sgst * quantity;
                    tigst = igst * quantity;
                } else {
                    taxable = Number((pay));
                    total = Number((pay / quantity));
                    price = Number((total));
                    sgst = Number((0));
                    cgst = Number((0));
                    igst = Number((0));

                    taxhsn = Number((0));
                    taxcgst = Number((0));
                    taxsgst = Number((0));
                    taxigst = Number((0));

                    tsgst = Number((0));
                    tcgst = Number((0));
                    tigst = Number((0));
                }
                $("#tax").val(taxable);
                $("#amnt-n-tax").val(total);
                $("#amount").val(price);
                $("#sgst").val(sgst);
                $("#cgst").val(cgst);
                $("#igst").val(igst);

                $("#hsn").val(taxhsn);
                $("#cgst-per").val(taxcgst);
                $("#sgst-per").val(taxsgst);
                $("#igst-per").val(taxigst);

                $("#tot-sgst").val(tsgst);
                $("#tot-cgst").val(tcgst);
                $("#tot-igst").val(tigst);
            }
        }

    });

    //CHANGING EVRYTHING WHEN QUANTITY BUTTON UP
    $("#qnt").keyup(function () {
        var taxhsn = parseFloat($("#hsn").val());
        if (isNaN(taxhsn)) { taxhsn = 0; }
        var taxcgst = parseFloat($("#cgst-per").val());
        if (isNaN(taxcgst)) { taxcgst = 0; }
        var taxsgst = parseFloat($("#sgst-per").val());
        if (isNaN(taxsgst)) { taxsgst = 0; }
        var taxigst = parseFloat($("#igst-per").val());
        if (isNaN(taxigst)) { taxigst = 0; }

        var price = parseFloat($("#amount").val());
        if (isNaN(price)) { price = 0; }
        var cgst = parseFloat($("#cgst").val());
        if (isNaN(cgst)) { cgst = 0; }
        var sgst = parseFloat($("#sgst").val());
        if (isNaN(sgst)) { sgst = 0; }
        var igst = parseFloat($("#igst").val());
        if (isNaN(igst)) { igst = 0; }
        var total = parseFloat($("#amnt-n-tax").val());
        if (isNaN(total)) { total = 0; }

        var quantity = parseFloat($("#qnt").val());
        if (isNaN(quantity)) { quantity = 0; }
        var taxable = parseFloat($("#tax").val());
        if (isNaN(taxable)) { taxable = 0; }
        var pay = parseFloat($("#total").val());
        if (isNaN(pay)) { pay = 0; }

        var tcgst = parseFloat($("#tot-cgst").val());
        if (isNaN(tcgst)) { tcgst = 0; }
        var tsgst = parseFloat($("#tot-sgst").val());
        if (isNaN(tsgst)) { tsgst = 0; }
        var tigst = parseFloat($("#tot-igst").val());
        if (isNaN(tigst)) { tigst = 0; }
        if (quantity > 0) {
            if (quantity <= idHelp.stock) {
                if (total > 0) {
                    total = PRICE_HELPER;
                    if (taxigst == 0) {
                        taxable = Number((total * quantity));
                        pay = Number((taxable));
                        price = Number((total));
                        sgst = Number((0));
                        cgst = Number((0));
                        igst = Number((0));

                        taxhsn = Number((0));
                        taxcgst = Number((0));
                        taxsgst = Number((0));
                        taxigst = Number((0));

                        tsgst = Number((0));
                        tcgst = Number((0));
                        tigst = Number((0));

                    } else {
                        if (taxsgst == 0 && taxcgst == 0) {
                            taxcgst = Number((taxigst / 2));
                            taxsgst = Number((taxcgst));
                        }

                        pay = Number((total * quantity));

                        var yy = taxigst / 100;
                        var zz = pay;
                        var xx = zz / (1 + yy); //CALCULATED TAXABLE FROM PAY AND DISCOUNT PERCENTAGE

                        taxable = Number((xx));

                        total = Number((pay / quantity));
                        price = Number((taxable / quantity));
                        sgst = Number(((taxsgst / 100) * price));
                        cgst = Number(((taxcgst / 100) * price));
                        igst = Number((sgst + cgst));

                        tcgst = cgst * quantity;
                        tsgst = sgst * quantity;
                        tigst = igst * quantity;
                    }
                    $("#total").val(pay);
                    $("#tax").val(taxable);
                    $("#amnt-n-tax").val(total);
                    $("#amount").val(price);
                    $("#sgst").val(sgst);
                    $("#cgst").val(cgst);
                    $("#igst").val(igst);

                    $("#hsn").val(taxhsn);
                    $("#cgst-per").val(taxcgst);
                    $("#sgst-per").val(taxsgst);
                    $("#igst-per").val(taxigst);

                    $("#tot-sgst").val(tsgst);
                    $("#tot-cgst").val(tcgst);
                    $("#tot-igst").val(tigst);

                    $("#disc").val(0);
                    $("#disc-per").val(0);
                    $("#pay-fin").val(pay);
                } else if (pay > 0) {

                    if (taxigst > 0) {
                        if (taxsgst == 0 && taxcgst == 0) {
                            taxcgst = Number((taxigst / 2));
                            taxsgst = Number((taxcgst));
                        }
                        var yy = taxigst / 100;
                        var zz = pay;
                        var xx = zz / (1 + yy); //CALCULATED TAXABLE FROM PAY AND DISCOUNT PERCENTAGE

                        taxable = Number((xx));

                        total = Number((pay / quantity));
                        price = Number((taxable / quantity));
                        sgst = Number(((taxsgst / 100) * price));
                        cgst = Number(((taxcgst / 100) * price));
                        igst = Number((sgst + cgst));

                        tcgst = cgst * quantity;
                        tsgst = sgst * quantity;
                        tigst = igst * quantity;
                    } else {
                        taxable = Number((pay));
                        total = Number((pay / quantity));
                        price = Number((total));
                        sgst = Number((0));
                        cgst = Number((0));
                        igst = Number((0));

                        taxhsn = Number((0));
                        taxcgst = Number((0));
                        taxsgst = Number((0));
                        taxigst = Number((0));

                        tsgst = Number((0));
                        tcgst = Number((0));
                        tigst = Number((0));
                    }
                    $("#tax").val(taxable);
                    $("#amnt-n-tax").val(total);
                    $("#amount").val(price);
                    $("#sgst").val(sgst);
                    $("#cgst").val(cgst);
                    $("#igst").val(igst);

                    $("#hsn").val(taxhsn);
                    $("#cgst-per").val(taxcgst);
                    $("#sgst-per").val(taxsgst);
                    $("#igst-per").val(taxigst);

                    $("#tot-sgst").val(tsgst);
                    $("#tot-cgst").val(tcgst);
                    $("#tot-igst").val(tigst);

                }
            } else {
                alert("This Item Is Only " + idHelp.stock + " Left!");
                $("#qnt").val("");
            }
        }
    });

    //************************************* */ SMART FILL END HERE*******************************************

}

//************************************* */ SMART FILL START HERE******************************************

//CHANGING ALL VALUE ACCORDING TO PERCENTAGE AND PAY AMOUNT
function changeAllFromIGST() {
    var taxcgst = parseFloat($("#cgst-per").val());
    if (isNaN(taxcgst)) { taxcgst = 0; }
    var taxsgst = parseFloat($("#sgst-per").val());
    if (isNaN(taxsgst)) { taxsgst = 0; }
    var taxigst = parseFloat($("#igst-per").val());
    if (isNaN(taxigst)) { taxigst = 0; }

    var price = parseFloat($("#amount").val());
    if (isNaN(price)) { price = 0; }
    var cgst = parseFloat($("#cgst").val());
    if (isNaN(cgst)) { cgst = 0; }
    var sgst = parseFloat($("#sgst").val());
    if (isNaN(sgst)) { sgst = 0; }
    var igst = parseFloat($("#igst").val());
    if (isNaN(igst)) { igst = 0; }
    var total = parseFloat($("#amnt-n-tax").val());
    if (isNaN(total)) { total = 0; }

    var quantity = parseFloat($("#qnt").val());
    if (isNaN(quantity)) { quantity = 0; }
    var taxable = parseFloat($("#tax").val());
    if (isNaN(taxable)) { taxable = 0; }
    var pay = parseFloat($("#total").val());
    if (isNaN(pay)) { pay = 0; }

    var tcgst = parseFloat($("#tot-cgst").val());
    if (isNaN(tcgst)) { tcgst = 0; }
    var tsgst = parseFloat($("#tot-sgst").val());
    if (isNaN(tsgst)) { tsgst = 0; }
    var tigst = parseFloat($("#tot-igst").val());
    if (isNaN(tigst)) { tigst = 0; }


    if (pay > 0 && quantity > 0) {
        if (taxsgst == 0 && taxcgst == 0) {
            taxcgst = taxigst / 2;
            taxsgst = taxcgst;
        }
        var yy = taxigst / 100;
        var zz = pay;
        var xx = zz / (1 + yy); //CALCULATED TAXABLE FROM PAY AND DISCOUNT PERCENTAGE

        taxable = Number((xx));

        total = Number((pay / quantity));
        price = Number((taxable / quantity));
        sgst = Number(((taxsgst / 100) * price));
        cgst = Number(((taxcgst / 100) * price));
        igst = Number((sgst + cgst));

        tcgst = cgst * quantity;
        tsgst = sgst * quantity;
        tigst = igst * quantity;

        $("#tax").val(taxable);
        $("#amnt-n-tax").val(total);
        $("#amount").val(price);
        $("#sgst").val(sgst);
        $("#cgst").val(cgst);
        $("#igst").val(igst);

        $("#cgst-per").val(taxcgst);
        $("#sgst-per").val(taxsgst);

        $("#tot-sgst").val(tsgst);
        $("#tot-cgst").val(tcgst);
        $("#tot-igst").val(tigst);

    }
}

//************************************* */ SMART FILL END HERE**********************************************

function itemappendClassName() {
    var a = $('ul li').eq(index).attr('id');
    $('#' + a).addClass("selected");
}

function itemremoveAll() {
    $(".items").removeClass("selected");
}

function itemshowHint(str, idOfdiv) {
    index = -1;
    $(".list").html("");
    if (str.length != 0) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var resobj = JSON.parse(this.responseText);
                if (resobj.success) {
                    var cursor = resobj.cursor;
                    for (var i = 0; i < cursor.length; i++) {
                        var ids = cursor[i].id;
                        $("." + idOfdiv).append("<li id='" + ids + "' class='items'> | ID:" + cursor[i].id + " | NAME:" + cursor[i].name + " | CATEGORY:" + cursor[i].cat + " | MANUFACTOR:" + cursor[i].manu + " | PRICE:" + cursor[i].price + " |</li>");
                    }
                    $('.items').hover(function () {
                        mouseItem = true;
                        index = $(this).index();
                        itemremoveAll();
                        itemappendClassName();
                    }, function () {
                        mouseItem = false;
                    });
                    $(".items").click(function () {
                        itemselectedHint(this.id);
                    });

                }
            }
        };

        var filterItemName = "";
        if (idOfdiv == "categ") {
            filterItemName = "catagory";
        } else if (idOfdiv == "id") {
            filterItemName = "id";
        } else if (idOfdiv == "manufact") {
            filterItemName = "manufactor";
        } else {
            filterItemName = "name";
        }
        var sendObj = {
            filter: filterItemName,
            value: str
        };
        var myJSON = JSON.stringify(sendObj);
        xmlhttp.open("GET", "itemPhp/search.php?q=" + myJSON, true);
        xmlhttp.send();
    }
}

function itemselectedHint(idOfItem) {
    index = -1;
    $(".list").html("");
    var fillRequest = new XMLHttpRequest();
    fillRequest.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;

                //FOR ID HELP
                idHelp.stock = cursor[0].stock;
                idHelp.realid = cursor[0].itemid;
                idHelp.realname = cursor[0].itemname;
                idHelp.realcat = cursor[0].itemcatagory;
                idHelp.realman = cursor[0].itemmanufactor;

                var ids = cursor[0].itemid;
                var name = cursor[0].itemname;
                var catagorys = cursor[0].itemcatagory;
                var manufactors = cursor[0].itemmanufactor;

                var taxhsn = parseFloat(cursor[0].taxhsn);
                var taxcgst = parseFloat(cursor[0].taxcgst);
                var taxsgst = parseFloat(cursor[0].taxsgst);
                var taxigst = parseFloat(cursor[0].taxigst);

                var price = parseFloat(cursor[0].amountprice);
                var cgst = parseFloat(cursor[0].amountcgst);
                var sgst = parseFloat(cursor[0].amountsgst);
                var igst = parseFloat(cursor[0].amountigst);
                var total = parseFloat(cursor[0].amounttotal);

                $("#id").val(ids);
                $("#name").val(name);
                $("#categ").val(catagorys);
                $("#manufact").val(manufactors);

                $("#hsn").val(taxhsn);
                $("#cgst-per").val(taxcgst);
                $("#sgst-per").val(taxsgst);
                $("#igst-per").val(taxigst);

                $("#amount").val(price);
                $("#cgst").val(cgst);
                $("#sgst").val(sgst);
                $("#igst").val(igst);
                $("#amnt-n-tax").val(total);
                PRICE_HELPER = total;
                $("#qnt").focus();
            }
        }
    };
    fillRequest.open("POST", "itemPhp/getPurchaseElement.php?q=" + idOfItem, true);
    fillRequest.send();
}

function checkItem() {
    if (idHelp.realid != $("#id").val() || idHelp.realname != $("#name").val() || idHelp.realcat != $("#categ").val() || idHelp.realman != $("#manufact").val()) {
        alert("Please Selecet A Item,You Can't Change It's Property From Here")
    } else {
        if ($("#qnt").val() > 0) {
            if ($("#pay-fin").val() == "" || $("#pay-fin").val() <= 0) {
                alert("Fix Pay Field");
            } else {
                var ids = parseFloat($("#id").val());
                var billId = billdetail.billId;

                var price = parseFloat($("#amount").val());
                var taxhsn = parseFloat($("#hsn").val());
                var taxcgst = parseFloat($("#cgst-per").val());
                var taxsgst = parseFloat($("#sgst-per").val());
                var taxigst = parseFloat($("#igst-per").val());

                var cgst = parseFloat($("#cgst").val());
                var sgst = parseFloat($("#sgst").val());
                var igst = parseFloat($("#igst").val());
                var total = parseFloat($("#amnt-n-tax").val());

                var quantity = parseFloat($("#qnt").val());
                var taxable = parseFloat($("#tax").val());
                var tcgst = parseFloat($("#tot-cgst").val());
                var tsgst = parseFloat($("#tot-sgst").val());
                var tigst = parseFloat($("#tot-igst").val());
                var pay = parseFloat($("#pay-fin").val());

                var discountPer = parseFloat($("#disc-per").val());
                var discount = parseFloat($("#disc").val());
                var totMrp = parseFloat($("#total").val());
                var mrp = totMrp / quantity;

                var insertQuery = {
                    ids: ids,
                    billId: billId,
                    price: price,
                    taxhsn: taxhsn,
                    taxcgst: taxcgst,
                    taxsgst: taxsgst,
                    taxigst: taxigst,
                    cgst: cgst,
                    sgst: sgst,
                    igst: igst,
                    total: total,
                    quantity: quantity,
                    taxable: taxable,
                    tcgst: tcgst,
                    tsgst: tsgst,
                    tigst: tigst,
                    pay: pay,
                    discountPer: discountPer,
                    discount: discount,
                    mrp: mrp,
                    totMrp: totMrp

                };
                itemsaveData(insertQuery);
            }
        } else {
            alert("Please Select quantity");
        }
    }
}

function itemsaveData(valuesForSending) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                if (resobj.status == "EXIST") {
                    alert("This Item Is Already Added");
                } else if (resobj.status == "RETURN") {
                    alert("This Bill Can't Be Modify.");
                }
            } else {
                alert("Failed To Save");
            }
            hideAddBox();
        }
    };
    var myJSON = JSON.stringify(valuesForSending);
    xmlhttp.open("GET", "upitemPhp/savePurchase.php?q=" + myJSON, true);
    xmlhttp.send();
}