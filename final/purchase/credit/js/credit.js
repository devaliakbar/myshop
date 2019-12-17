var billBal = null;
var vendor = {
    vid: null,
    name: null,
    address: null,
    phone: null,
    gst: null,

    amt: null,
    ret: null,
    tot: null,
    paid: null,
    bal: null
};
$(document).ready(function () {
    vendor.vid = $("#vid").val();
    readyPay();
    readyTrans();
    loadVendor();

});

function loadVendor() {
    var loadVendorHTTP = new XMLHttpRequest();
    loadVendorHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                for (var i = 0; i < cursor.length; i++) {
                    vendor.name = cursor[i].name;
                    vendor.address = cursor[i].addr;
                    vendor.gst = cursor[i].gst;
                    vendor.phone = cursor[i].phone;

                    vendor.amt = cursor[i].amt;
                    vendor.ret = cursor[i].ret;
                    vendor.tot = cursor[i].tot;
                    vendor.paid = cursor[i].paid;
                    vendor.bal = cursor[i].bal;

                    $("#vname").val(vendor.name);
                    $("#vaddr").val(vendor.address);
                    $("#vphone").val(vendor.phone);
                    $("#gst-no").val(vendor.gst);

                    $("#vamount").val(vendor.amt);
                    $("#vreturn").val(vendor.ret);
                    $("#vtotal").val(vendor.tot);
                    $("#vpaid").val(vendor.paid);
                    $("#vbal").val(vendor.bal);
                }
                loadBills("");
            } else {
                alert("Data Is Empty");
            }
        }
    };
    loadVendorHTTP.open("GET", "php/getVendor.php?q=" + vendor.vid, true);
    loadVendorHTTP.send();
}

function loadBills(sendData) {
    var loadBillHTTP = new XMLHttpRequest();
    loadBillHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                jQuery(".bills-com").empty();
                billBal = [""];
                for (var i = 0; i < cursor.length; i++) {
                    var appendRaw = "<tr class='" + cursor[i].id + " grid-items'>";
                    appendRaw += "<td class='grid-slno'><input type='text' autocomplete='off' disabled value='" + (i + 1) + "'></td>";
                    appendRaw += "<td class='grid-id'><input type='text' autocomplete='off' disabled value='" + cursor[i].id + "'></td>";
                    appendRaw += "<td class='grid-date'><input type='text' autocomplete='off' disabled value='" + cursor[i].date + "'></td>";
                    appendRaw += "<td class='grid-amt'><input type='text' autocomplete='off' disabled value='" + cursor[i].amount + "'></td>";
                    appendRaw += "<td class='grid-return'><input type='text' autocomplete='off' disabled value='" + cursor[i].return+"'></td>";
                    appendRaw += "<td class='grid-tot'><input type='text' autocomplete='off' disabled value='" + cursor[i].total + "'></td>";
                    appendRaw += "<td class='grid-pay'><input type='text' autocomplete='off' disabled value='" + cursor[i].pay + "'></td>";
                    appendRaw += "<td class='grid-bal'><input type='text' autocomplete='off' disabled value='" + cursor[i].bal + "'></td>";
                    billBal[i] = {
                        bal: cursor[i].bal,
                        pay: cursor[i].pay
                    };
                    appendRaw += "</tr>";
                    jQuery(".bills-com").append(appendRaw);
                }

                $('.add-products-page table tr').click(function () {
                    var itemIndex = $('table tr').index(this);
                    if ((itemIndex - (cursor.length + 2)) >= 0) {
                        var itemId = $('table tr:eq(' + itemIndex + ')').attr('class').split(' ')[0];
                        var amountPay = $("#amount-pay").val();
                        if (amountPay > 0 && !isNaN(amountPay)) {
                            //      if (parseFloat(amountPay) <= parseFloat($("#vbal").val())) {
                            if (billBal[itemIndex - (cursor.length + 2)].bal >= parseFloat(amountPay)) {
                                applyPay(itemId, amountPay, itemIndex - (cursor.length + 2));
                            } else {
                                alert("This Amount Is Greater Than The Bill Balance");
                            }
                            //  }
                        }
                    }
                });

            } else {
                alert("Data Is Empty");
                jQuery(".bills-com").empty();
                if (sendData != "") {
                    loadBills("");
                }
            }
        }
    };
    if (sendData == "") {
        sendData = {
            type: false,
            vid: vendor.vid
        };
    }
    var myJSON = JSON.stringify(sendData);
    loadBillHTTP.open("GET", "php/loadBill.php?q=" + myJSON, true);


    loadBillHTTP.send();
}

function payNow() {
    $(".add-products-page").show();
}

function hideAddBox() {
    location.reload();
}

function showTrans() {
    $(".transation-history").show();
    loadTrans("");
}

function updateVendor() {
    var updateVendorHTTP = new XMLHttpRequest();
    updateVendorHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                alert("Successfully Updated Vendor");
            } else {
                alert("Failed To Updated");
            }
            hideAddBox();
        }
    };

    var proceed = confirm("Are You Sure,Do You Want To Update?");
    if (proceed) {
        var upvid = $("#vid").val();
        var upgst = $("#gst-no").val();
        var upvname = $("#vname").val();
        var upaddress = $("#vaddr").val();
        var upphone = $("#vphone").val();


        if(upgst == ""){
            upgst = "NA";
        }

        if(upvname == ""){
            upvname = "NA";
        }

        if(upaddress == ""){
            upaddress = "NA";
        }

        if(upphone == ""){
            upphone = "NA";
        }
        

        var sendData = {
            vid: upvid,
            gst: upgst,
            name: upvname,
            addrs: upaddress,
            phone: upphone
        }
        var myJSON = JSON.stringify(sendData);
        updateVendorHTTP.open("POST", "php/updateVendor.php?q=" + myJSON, true);
        updateVendorHTTP.send();
    }
}