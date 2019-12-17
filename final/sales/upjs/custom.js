var UPDATE_FLAG = false;
var ITEM_BOOLEAN = false;//FOR AVOID COLLIDING DELETE AND SELECT ON DATA GRID
var UPDATE_VALUES = false;
var billdetail = {
    billId: null,
    taxable: null,
    taxi: null,
    taxs: null,
    taxc: null,
    igst: null,
    sgst: null,
    cgst: null,
    pay: null,

    mrp: null,
    disc: null,
    discper: null
};

var salesDetail = {
    id: null,
    name: null,
    address: null,
    phone: null,
    gst: null,
    date: null,
}

$(document).ready(function () {
    billdetail.billId = $("#pur-id").val();
    loadPreviousSaved();
    addItemReady();
});

function printBill() {
    var url = "bill-final.php?q=" + $("#pur-id").val();
    window.open(url, '_blank');
}

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


function deleteitem(idForDelete) {
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                if(resobj.status == "ONE"){
                    alert("This Item Can't Be Remove,Coz This The Only Item In This Bill");
                }else if(resobj.status =="RETURN"){
                    alert("This Item Can't Be Deleted");
                }
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
    delteAllHTTP.open("POST", "upphp/delete.php?q=" + myJSON, true);
    delteAllHTTP.send();
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

    billdetail.disc = 0;
    billdetail.discper = 0;
    billdetail.mrp = 0;
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

                        billdetail.disc = parseFloat(billdetail.disc) + parseFloat(cursor[i].disc);
                        billdetail.mrp = parseFloat(billdetail.mrp) + parseFloat(cursor[i].totmrp);

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

                        appendRaw += "<td class='grid-i'><input type='text' autocomplete='off' disabled value='" + cursor[i].igst + "'></td>";
                        appendRaw += "<td class='grid-s'><input type='text' autocomplete='off' disabled value='" + cursor[i].sgst + "'></td>";
                        appendRaw += "<td class='grid-c'><input type='text' autocomplete='off' disabled value='" + cursor[i].cgst + "'></td>";

                        appendRaw += "<td class='grid-mrp'><input type='text' autocomplete='off' disabled value='" + cursor[i].mrp + "'></td>";

                        appendRaw += "<td class='grid-total'><input type='text' autocomplete='off' disabled value='" + cursor[i].tot + "'></td>";
                        appendRaw += "<td class='grid-qnt'><input type='text' autocomplete='off' disabled value='" + cursor[i].qnt + "'></td>";
                        appendRaw += "<td class='grid-tax'><input type='text' autocomplete='off' disabled value='" + cursor[i].tax + "'></td>";

                        appendRaw += "<td class='grid-totmrp'><input type='text' autocomplete='off' disabled value='" + cursor[i].totmrp + "'></td>";
                        appendRaw += "<td class='grid-discper'><input type='text' autocomplete='off' disabled value='" + cursor[i].discper + "'></td>";
                        appendRaw += "<td class='grid-disc'><input type='text' autocomplete='off' disabled value='" + cursor[i].disc + "'></td>";

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

                    billdetail.discper = (billdetail.disc / billdetail.mrp) * 100;
                    if (isNaN(billdetail.discper)) {
                        billdetail.discper = 0;
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

                    $("#pur-mrp").val(billdetail.mrp);
                    $("#pur-disc").val(billdetail.disc);
                    $("#pur-disc-per").val(billdetail.discper);

                    $("#pur-igst").val(billdetail.igst);
                    $("#pur-cgst").val(billdetail.cgst);
                    $("#pur-sgst").val(billdetail.sgst);
                    $("#pur-pay").val(billdetail.pay);
                    getCustDetail();
                } else {
                    //alert("Failed To Load Previous Data");
                }
            }
        };
        fillDatagridRequest.open("GET", "upphp/fillDataGrid.php?q=" + billdetail.billId, true);
        fillDatagridRequest.send();
    }
}

function saveItems() {
    var vendor = true;
    var vid = salesDetail.id;
    var vname = $("#cust-name").val();
    var vpho = $("#cust-phone").val();
    var vaddr = $("#cust-addr").val();
    var vgst = $("#gst-no").val();
    if (vname == "") {
        vendor = false;
        vid = "1";
        vname = "NA";
        vpho = "NA";
        vaddr = "NA";
        vgst = "NA";
    }
    if (vpho == "") {
        vpho = "NA";
    }
    if (vaddr == "") {
        vaddr = "NA";
    }
    if (vgst == "") {
        vgst = "NA";
    }
    var date = $("#date").val();
    if (date == "") {
        var today = new Date();
        date = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    }


    var mrp = $("#pur-mrp").val();
    var dis = $("#pur-disc").val();
    var discper = $("#pur-disc-per").val();

    var taxi = billdetail.taxi;
    var taxc = billdetail.taxc;
    var taxs = billdetail.taxs;

    var tax = $("#pur-tax").val();
    var igst = $("#pur-igst").val();
    var cgst = $("#pur-cgst").val();
    var sgst = $("#pur-sgst").val();
    var pay = $("#pur-pay").val();

    var sendDatas = {
        billid: billdetail.billId,
        vendor: vendor,
        vid: vid,
        vname: vname,
        vaddr: vaddr,
        vgst: vgst,

        vpho: vpho,
        date: date,
        tax: tax,

        taxi: taxi,
        taxc: taxc,
        taxs: taxs,

        igst: igst,
        cgst: cgst,
        sgst: sgst,
        pay: pay,
        mrp: mrp,
        dis: dis,
        discper: discper
    };
    saveBill(sendDatas);
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
    adjustHTTP.open("GET", "upphp/adjustItems.php?q=" + myJSON, true);
    adjustHTTP.send();
}

function saveBill(datas) {
    var saveHTTP = new XMLHttpRequest();
    saveHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                if(UPDATE_VALUES){
                    UPDATE_VALUES = false;
                }else{
                    if(resobj.status == "RETURN"){
                        alert("This Bill Cant't Be Modified");
                    }else{
                        alert("Succesfully Saved");
                        var url = "bill-final.php?q=" + $("#pur-id").val();
                        window.open(url, '_blank');
                        window.close();
                    }
                }
            } else {
                alert("Failed To Save");
            }
        }
    };
    var myJSON = JSON.stringify(datas);
    saveHTTP.open("GET", "upphp/saveBill.php?q=" + myJSON, true);
    saveHTTP.send();
}

function getCustDetail() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                for (var i = 0; i < cursor.length; i++) {
                    salesDetail.name = cursor[i].name;
                    salesDetail.address = cursor[i].addrs;
                    salesDetail.phone = cursor[i].phone;
                    salesDetail.gst = cursor[i].gst;

                    salesDetail.id = cursor[i].id;

                    salesDetail.date = cursor[i].date;
                }

                $("#gst-no").val(salesDetail.gst);
                $("#cust-name").val(salesDetail.name);
                $("#cust-phone").val(salesDetail.phone);
                $("#cust-addr").val(salesDetail.address);
                $("#date").val(salesDetail.date);
                autosave();
            } else {
                alert("Failed To Fetch Vendor Detail");
            }
        }
    };
    xmlhttp.open("GET", "upphp/setCust.php?q=" + billdetail.billId, true);
    xmlhttp.send();
}

function autosave() {
    var date = $("#date").val();
    if (date == "") {
        var today = new Date();
        date = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    }


    var mrp = $("#pur-mrp").val();
    var dis = $("#pur-disc").val();
    var discper = $("#pur-disc-per").val();

    var taxi = billdetail.taxi;
    var taxc = billdetail.taxc;
    var taxs = billdetail.taxs;

    var tax = $("#pur-tax").val();
    var igst = $("#pur-igst").val();
    var cgst = $("#pur-cgst").val();
    var sgst = $("#pur-sgst").val();
    var pay = $("#pur-pay").val();

    var sendDatas = {
        billid: billdetail.billId,
        vendor: false,
        vid: salesDetail.id,
        date: date,
        tax: tax,

        taxi: taxi,
        taxc: taxc,
        taxs: taxs,

        igst: igst,
        cgst: cgst,
        sgst: sgst,
        pay: pay,
        mrp: mrp,
        dis: dis,
        discper: discper
    };
    UPDATE_VALUES = true;
    saveBill(sendDatas);
}