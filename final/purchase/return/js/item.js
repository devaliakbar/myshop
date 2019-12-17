var billid = null;
var vid = null;
itemArr = null;
$(document).ready(function () {
    billid = $("#id").val();
    getVendor();
    setUpDate();

    $("#rmv-qty").keyup(function () {
        var r_qty = $("#rmv-qty").val();
        var bal = $("#isbal").val();
        var qty = $("#iqty").val();

        if (r_qty > qty) {
            alert("This Bill Contain Only " + qty + " Qty.");
            $("#rmv-qty").val(0);
        } else {
            if (r_qty > bal) {
                alert("Their is only " + bal + " Left");
                $("#rmv-qty").val(0);
            }
        }
    });
});

function setUpDate() {
    var today = new Date();
    $("#re-date").val(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
}

function hideAddBox() {
    location.reload();
}

function getVendor() {
    var loadBillHTTP = new XMLHttpRequest();
    loadBillHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                vid = cursor[0].id;
                $("#name").val(cursor[0].name);
                loadBills();
            } else {
                alert("Can't Find Vendor");
            }
        }
    };
    loadBillHTTP.open("GET", "php/getVendor.php?q=" + billid, true);
    loadBillHTTP.send();
}

function loadBills() {
    var fillDatagridRequest = new XMLHttpRequest();
    fillDatagridRequest.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                itemArr = [""];
                for (var i = 0; i < cursor.length; i++) {
                    var appendRaw = "<tr class='" + cursor[i].itemid + " grid-items'>";
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
                    itemArr[i] = {
                        id: cursor[i].itemid,
                        name: cursor[i].itemname,
                        cat: cursor[i].itemcatagory,
                        man: cursor[i].itemmanufactor,
                        price: cursor[i].pay,
                        qty: cursor[i].qnt,
                        bal: cursor[i].bal
                    };
                }
                //FOR UPDATING
                $('tr').click(function () {
                    var itemIndex = $('table tr').index(this);
                    if (itemIndex > 0) {
                        var itemId = $('table tr:eq(' + itemIndex + ')').attr('class').split(' ')[0];
                        removeItems(itemIndex - 1);
                    }
                });
            } else {
                alert("Failed To Load  Data");
            }
        }
    };
    fillDatagridRequest.open("GET", "php/fillDataGrid.php?q=" + billid, true);
    fillDatagridRequest.send();

}

function removeItems(index) {
    $("#iid").val(itemArr[index].id);
    $("#iname").val(itemArr[index].name);
    $("#iman").val(itemArr[index].man);
    $("#icat").val(itemArr[index].cat);
    $("#iprice").val(itemArr[index].price);
    $("#iqty").val(itemArr[index].qty);
    $("#isbal").val(itemArr[index].bal);
    $(".something").show();
}

function remove() {
    var r_qty = parseInt($("#rmv-qty").val());
    if (!isNaN(r_qty) && r_qty > 0) {
        itemRemove(r_qty);
    } else {
        alert("Enter A QTY To Remove");
    }
}

function removeAll() {
    var bal = $("#isbal").val();
    var qty = $("#iqty").val();
    if (qty <= bal) {
        itemRemove(qty);
    } else {
        alert("Can't Remove The Whole Item.");
    }
}

function itemRemove(qtyForRemove) {
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                if (resobj.status == "ALREADY") {
                    alert("This Item Is Once Returned,Reduce Quantity and Try Again");
                } else {
                    alert("Succesfully Returned :)");
                }
            } else {
                alert("Failed To Deleted");
            }
            location.reload();
        }
    };
    $dateOfReturn = $("#re-date").val();

    if ($dateOfReturn == "") {
        var today = new Date();
        $dateOfReturn = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    }

    var send = {
        date: $dateOfReturn,
        iid: $("#iid").val(),
        qty: qtyForRemove,
        billid: billid,
        vid: vid
    };
    var myJSON = JSON.stringify(send);
    delteAllHTTP.open("POST", "php/returnItem.php?q=" + myJSON, true);
    delteAllHTTP.send();
}

function showReview() {
    $(".return-review").show();
    loadReview();
}

function loadReview() {
    var loadStockHTTP = new XMLHttpRequest();
    loadStockHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                jQuery("#r-grid-data").empty();
                for (var i = 0; i < cursor.length; i++) {
                    var appendRaw = "<tr class='" + cursor[i].id + " grid-items'>";
                    appendRaw += "<td><span id='del-" + cursor[i].id + "' class='delete'><i class='fas fa-minus-circle'></i></span></td>"
                    appendRaw += "<td class='r-grid-slno'><input type='text' autocomplete='off' disabled value='" + (i + 1) + "'></td>";
                    appendRaw += "<td class='r-grid-date'><input type='text' autocomplete='off' disabled value='" + cursor[i].date + "'></td>";

                    appendRaw += "<td class='r-grid-id'><input type='text' autocomplete='off' disabled value='" + cursor[i].id + "'></td>";

                    appendRaw += "<td class='r-grid-itemid'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemid + "'></td>";
                    appendRaw += "<td class='r-grid-name'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemname + "'></td>";
                    appendRaw += "<td class='r-grid-cat'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemcatagory + "'></td>";
                    appendRaw += "<td class='r-grid-man'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemmanufactor + "'></td>";
                    appendRaw += "<td class='r-grid-price'><input type='text' autocomplete='off' disabled value='" + cursor[i].price + "'></td>";
                    appendRaw += "<td class='r-grid-hsn'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxhsn + "'></td>";

                    appendRaw += "<td class='r-grid-igst'><input type='text' autocomplete='off' disabled value='" + cursor[i].igst + "'></td>";
                    appendRaw += "<td class='r-grid-sgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].sgst + "'></td>";
                    appendRaw += "<td class='r-grid-cgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].cgst + "'></td>";

                    appendRaw += "<td class='r-grid-total'><input type='text' autocomplete='off' disabled value='" + cursor[i].total + "'></td>";

                    appendRaw += "<td class='r-grid-qnt'><input type='text' autocomplete='off' disabled value='" + cursor[i].qty + "'></td>";
                    appendRaw += "<td class='r-grid-tax'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxable + "'></td>";
                    appendRaw += "<td class='r-grid-pay'><input type='text' autocomplete='off' disabled value='" + cursor[i].pay + "'></td>";
                    appendRaw += "</tr>";
                    jQuery("#r-grid-data").append(appendRaw);

                    $('#del-' + cursor[i].id).click(function () {
                        var itemId = $(this).attr('id').split('-')[1];
                        var proceed = confirm("Are You Sure,Do You Want To Delete");
                        if (proceed) {
                            deleteitem(itemId);
                        }
                    });
                }
            } else {
                jQuery("#grid-data").empty();
                alert("Data Is Empty");
            }
        }
    };
    loadStockHTTP.open("GET", "php/loadreview.php?q=" + billid, true);
    loadStockHTTP.send();
}

function deleteitem(itemId){
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                    alert("Successfully Deleted");
            } else {
                alert("Failed To Deleted");
            }
            location.reload();
        }
    };
    var today = new Date();
    datesForPayment = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    var sendDatas = {
        date: datesForPayment,
        id : itemId
    }
    var myJSON = JSON.stringify(sendDatas);
    delteAllHTTP.open("POST", "php/delete.php?q=" + myJSON, true);
    delteAllHTTP.send();
}