var UPDATE_FLAG = false;

var ITEM_BOOLEAN = false;//FOR AVOID COLLIDING DELETE AND SELECT ON DATA GRID
var billdetail = {
    billId: null,
    taxable: null,
    taxi: null,
    taxs: null,
    taxc: null,
    igst: null,
    sgst: null,
    cgst: null,
    pay: null
};

$(document).ready(function () {
    setUpDate();
    createBillId();
    dropReady();
    addItemReady();
});

function showAddBox() {
    $(".add-products-page").show();
}

function hideAddBox() {
    $(".add-products-page").hide();
    location.reload();
}

function showAddBoxWithUpdate(itemId) {
    UPDATE_FLAG = true;
    $(".add-products-page").show();
}

function clearAllItem() {
    var proceed = confirm("Are You Sure,Do You Want To Clear All Items?");
    if (proceed) {
        deleteitem("");
    }

}

function deleteitem(idForDelete) {
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {

            } else {
                alert("Failed To Deleted");
            }
            hideAddBox();
        }
    };
    if (idForDelete == "") {
        var dataForDelete = {
            billId: billdetail.billId
        }
    } else {
        var dataForDelete = {
            id: idForDelete,
            billId: billdetail.billId
        }
    }
    var myJSON = JSON.stringify(dataForDelete);
    delteAllHTTP.open("POST", "php/delete.php?q=" + myJSON, true);
    delteAllHTTP.send();
}

function setUpDate() {
    var today = new Date();
    $("#date").val(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
}

function initializeBilldetail() {
    billdetail.taxable = 0;
    billdetail.taxi = 0;
    billdetail.taxs = 0;
    billdetail.taxc = 0;
    billdetail.igst = 0;
    billdetail.sgst = 0;
    billdetail.cgst = 0;
    billdetail.pay = 0;
}

function createBillId() {
    var fillDatagridRequest = new XMLHttpRequest();
    fillDatagridRequest.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                billdetail.billId = resobj.billid;
                $("#pur-id").val(billdetail.billId);
                loadPreviousSaved();
            } else {
                alert("Failed To Create Bill Id");
            }
        }
    };
    fillDatagridRequest.open("GET", "php/setBill.php", true);
    fillDatagridRequest.send();
}

function loadPreviousSaved() {
    if (billdetail.billId !== null) {
        var fillDatagridRequest = new XMLHttpRequest();
        fillDatagridRequest.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var resobj = JSON.parse(this.responseText);
                if (resobj.success) {
                    var cursor = resobj.cursor;
                    initializeBilldetail();
                    for (var i = 0; i < cursor.length; i++) {
                        billdetail.taxable = parseFloat(billdetail.taxable) + parseFloat(cursor[i].tax);
                        billdetail.taxi = parseFloat(billdetail.taxi) + parseFloat(cursor[i].taxigst);
                        billdetail.taxs = parseFloat(billdetail.taxs) + parseFloat(cursor[i].taxsgst);
                        billdetail.taxc = parseFloat(billdetail.taxc) + parseFloat(cursor[i].taxcgst);
                        billdetail.igst = parseFloat(billdetail.igst) + parseFloat(cursor[i].igst);
                        billdetail.sgst = parseFloat(billdetail.sgst) + parseFloat(cursor[i].sgst);
                        billdetail.cgst = parseFloat(billdetail.cgst) + parseFloat(cursor[i].cgst);
                        billdetail.pay = parseFloat(billdetail.pay) + parseFloat(cursor[i].pay);
                        var appendRaw = "<tr class='" + cursor[i].itemid + " grid-items'>";
                        appendRaw += "<td><span id='del-" + cursor[i].itemid + "' class='delete'><i class='fas fa-minus-circle'></i></span></td>"
                        appendRaw += "<td class='grid-slno'><input type='text' autocomplete='off' disabled value='" + (i + 1) + "'></td>";
                        appendRaw += "<td class='grid-id'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemid + "'></td>";
                        appendRaw += "<td class='grid-name'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemname + "'></td>";
                        appendRaw += "<td class='grid-cat'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemcatagory + "'></td>";
                        appendRaw += "<td class='grid-man'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemmanufactor + "'></td>";
                        appendRaw += "<td class='grid-price'><input type='text' autocomplete='off' disabled value='" + cursor[i].price + "'></td>";
                        appendRaw += "<td class='grid-hsn'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxhsn + "'></td>";
                        appendRaw += "<td class='grid-igst'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxigst + "'></td>";
                        appendRaw += "<td class='grid-sgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxsgst + "'></td>";
                        appendRaw += "<td class='grid-cgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxcgst + "'></td>";
                        appendRaw += "<td class='grid-total'><input type='text' autocomplete='off' disabled value='" + cursor[i].tot + "'></td>";
                        appendRaw += "<td class='grid-qnt'><input type='text' autocomplete='off' disabled value='" + cursor[i].qnt + "'></td>";
                        appendRaw += "<td class='grid-tax'><input type='text' autocomplete='off' disabled value='" + cursor[i].tax + "'></td>";
                        appendRaw += "<td class='grid-pay'><input type='text' autocomplete='off' disabled value='" + cursor[i].pay + "'></td>";
                        appendRaw += "</tr>";
                        jQuery("#grid-data").append(appendRaw);
                        //FOR DELETING ONE RAW
                        $('#del-' + cursor[i].itemid).click(function () {
                            var itemId = $(this).attr('id').split('-')[1];
                            ITEM_BOOLEAN = true;
                            deleteitem(itemId);
                        });
                    }
                    //FOR UPDATING
                    $('tr').click(function () {
                        if (ITEM_BOOLEAN) {
                            ITEM_BOOLEAN = false;
                        } else {
                            var itemIndex = $('table tr').index(this);
                            if (itemIndex > 0) {
                                var itemId = $('table tr:eq(' + itemIndex + ')').attr('class').split(' ')[0];
                                //showAddBoxWithUpdate(itemId); implement future
                            }
                        }
                    });
                    $("#pur-tax").val(billdetail.taxable);
                    $("#pur-taxi").val(billdetail.taxi);
                    $("#pur-taxc").val(billdetail.taxc);
                    $("#pur-taxs").val(billdetail.taxs);
                    $("#pur-igst").val(billdetail.igst);
                    $("#pur-cgst").val(billdetail.cgst);
                    $("#pur-sgst").val(billdetail.sgst);
                    $("#pur-pay").val(billdetail.pay);
                } else {
                    //alert("Failed To Load Previous Data");
                }
            }
        };
        fillDatagridRequest.open("GET", "php/fillDataGrid.php?q=" + billdetail.billId, true);
        fillDatagridRequest.send();
    }
}

function saveItems() {
    var vid = $("#cust-id").val();
    var vname = $("#cust-name").val();
    var vadd = $("#cust-address").val();
    var vgst = $("#gst-no").val();
    var vbid = $("#vbid").val();//VENDOR BILL ID
    if (vbid != "") {



        if (vgst == "") {
            vgst = "NA";
        }
        if (vadd == "") {
            vadd = "NA";
        }
        var vpho = $("#cust-phone").val();
        if (vpho == "") {
            vpho = "NA";
        }
        var date = $("#date").val();
        if (date == "") {
            var today = new Date();
            date = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
        }
        var tax = $("#pur-tax").val();
        var taxi = $("#pur-taxi").val();
        var taxc = $("#pur-taxc").val();
        var taxs = $("#pur-taxs").val();
        var igst = $("#pur-igst").val();
        var cgst = $("#pur-cgst").val();
        var sgst = $("#pur-sgst").val();
        var pay = $("#pur-pay").val();

        //START HERE
        var vendor = false;
        var proceed = true;
        if (vname == "") {
            proceed = confirm("Vendor Name Is Empty,do you want to Continue?");
            vid = "1";
            vendorHelp.id = "1";
            vname = "NA";
            vadd = "NA";
            vpho = "NA";
            vgst = "NA";
        }
        if (proceed) {
            if (vendorHelp.id == null || vendorHelp.id != vid) {
                proceed = confirm("This is a new Vendor,do you want to create?");
                vendor = true;
            }
            if (proceed) {
                if (billdetail.billId == null) {
                    alert("Can't Find The Bill Id");
                } else {
                    if (billdetail.pay == null || billdetail.pay <= 0) {
                        alert("Item Is Empty");
                    } else {
                        if (billdetail.pay != pay) {
                            var changeConf = confirm("You changed the total amount,So system need to reflect these changes.Do you want to continue?");
                            if (changeConf) {
                                adjustAllItems(pay);
                            } else {
                                alert("The System Will Set Back the Previous Value.Add again to save");
                                $("#pur-pay").val(billdetail.pay);
                            }

                        } else {
                            var sendDatas = {
                                billid: billdetail.billId,
                                vbid: vbid,
                                vendor: vendor,
                                vid: vid,
                                gst: vgst,
                                vname: vname,
                                vadd: vadd,
                                vpho: vpho,
                                date: date,
                                tax: tax,
                                taxi: taxi,
                                taxc: taxc,
                                taxs: taxs,
                                igst: igst,
                                cgst: cgst,
                                sgst: sgst,
                                pay: pay
                            };
                            saveBill(sendDatas);
                        }
                    }
                }
            }
        }
    }else{
        alert("Enter The Bill Id");
    }
}

function adjustAllItems(pay) {
    var datas = {
        id: billdetail.billId,
        old: billdetail.pay,
        new: pay
    };
    var adjustHTTP = new XMLHttpRequest();
    adjustHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                location.reload();
                alert("Items Are Successfully Adjusted.Add again to save");
            } else {
                alert("Failed To Adjust");
            }
        }
    };
    var myJSON = JSON.stringify(datas);
    adjustHTTP.open("GET", "php/adjustItems.php?q=" + myJSON, true);
    adjustHTTP.send();
}

function saveBill(datas) {
    var saveHTTP = new XMLHttpRequest();
    saveHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                alert("Succesfully Saved");
                window.location.href = "../";
            } else {
                alert("Failed To Save");
            }
        }
    };
    var myJSON = JSON.stringify(datas);
    saveHTTP.open("GET", "php/saveBill.php?q=" + myJSON, true);
    saveHTTP.send();
}

function discAdjust() {
    var discPer = $("#pur-dis-per").val();
    var disc = $("#pur-dis").val();
    var taxa = $("#pur-tax").val();
    if (discPer == "") {
        discPer = 0;
    }
    if (disc == "") {
        disc = 0;
    }

    if (taxa == "") {
        taxa = 0;
    }

    if (taxa > 0) {

        if (discPer > 0) {
            adjustDisc(discPer);
        } else {
            if (disc > 0) {
                discPer = (disc / taxa) * 100;
                adjustDisc(discPer);
            }
        }
    }
}

function adjustDisc(disc) {
    var datas = {
        id: billdetail.billId,
        disc: disc
    };
    var adjustHTTP = new XMLHttpRequest();
    adjustHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                location.reload();
                alert("Items Are Successfully Adjusted");
            } else {
                alert("Failed To Adjust");
            }
        }
    };
    var myJSON = JSON.stringify(datas);
    adjustHTTP.open("GET", "php/adjustDisc.php?q=" + myJSON, true);
    adjustHTTP.send();
}