function readyTrans() {
    $('#t-ope-sele').on('change', function () {
        if (this.value == "5") {
            $(".dte-bet").show();
        } else {
            $(".dte-bet").hide();
            $("#t-date-max").val("");
            if ($("#t-date-min").val() != "") {
                tsearchItems();
            }
        }
    });

    $("#t-date-min").change(function () {
        if ($("#t-ope-sele").children("option:selected").val() == "5") {
            if ($("#t-date-max").val() != "") {
                tsearchItems();
            }
        } else {
            tsearchItems();
        }
    });

    $("#t-date-max").change(function () {
        if ($("#t-date-min").val() != "") {
            tsearchItems();
        }
    });
}

function loadTrans(sendData) {
    var loadTransHTTP = new XMLHttpRequest();
    loadTransHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                jQuery("#grid-trans").empty();
                for (var i = 0; i < cursor.length; i++) {
                    var appendRaw = "<tr class='grid-items'>";
                    appendRaw += "<td><span id='tdel-" + cursor[i].tid + "' class='delete-" + cursor[i].pay + "-" + cursor[i].id + "'><i class='fas fa-minus-circle'></i></span></td>"
                    appendRaw += "<td class='grid-slno-t'><input type='text' autocomplete='off' disabled value='" + (i + 1) + "'></td>";
                    appendRaw += "<td class='grid-id-t'><input type='text' autocomplete='off' disabled value='" + cursor[i].id + "'></td>";
                    appendRaw += "<td class='grid-date-t'><input type='text' autocomplete='off' disabled value='" + cursor[i].date + "'></td>";
                    appendRaw += "<td class='grid-pay-t'><input type='text' autocomplete='off' disabled value='" + cursor[i].pay + "'></td>";

                    appendRaw += "</tr>";

                    jQuery("#grid-trans").append(appendRaw);

                    $('#tdel-' + cursor[i].tid).click(function () {
                        var tid = $(this).attr('id').split('-')[1];
                        var payAmount = $(this).attr('class').split('-')[1];
                        var billId = $(this).attr('class').split('-')[2];
                        var sendData = {
                            vid: vendor.vid,
                            billId: billId,
                            paid: payAmount,
                            tid: tid
                        };
                        var proceed = confirm("Are You Sure,Do You Want To Delete?");
                        if (proceed) {
                            deleteitem(sendData);
                        }
                    });
                }
            } else {
                alert("Data Is Empty");
                jQuery("#grid-trans").empty();
                if (sendData != "") {
                    loadTrans("");
                }
            }
        }
    };
    if (sendData == "") {
        ssendData = {
            type: false,
            vid: vendor.vid
        };
        var myJSON = JSON.stringify(ssendData);
    }else{
        var myJSON = JSON.stringify(sendData);
    }
    loadTransHTTP.open("POST", "php/loadTrans.php?q=" + myJSON, true);
    loadTransHTTP.send();

}

function deleteitem(sendData) {
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                alert("Successfully Deleted This Payment");
            } else {
                alert("Failed To Delete");
            }
            hideAddBox();
        }
    };
    var myJSON = JSON.stringify(sendData);

    delteAllHTTP.open("POST", "php/deletePay.php?q=" + myJSON, true);
    delteAllHTTP.send();
}

function tgoRefresh() {
    loadTrans("");
}

function tsearchItems() {

    var operation = $("#t-ope-sele").children("option:selected").val();
    var date_min = $("#t-date-min").val();
    var maxDate = $("#t-date-max").val();

    sendData = {
        type: true,
        operation: operation,
        date: date_min,
        maxDate: maxDate,
        vid: vendor.vid
    };
    loadTrans(sendData);
}