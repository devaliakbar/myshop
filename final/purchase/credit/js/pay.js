function readyPay() {
    setUpDate();
    $('#ope-sele').on('change', function () {
        if (this.value == "5") {
            $(".dte-bet").show();
        } else {
            $(".dte-bet").hide();
            $("#date-max").val("");
            if ($("#date-min").val() != "") {
                searchItems();
            }
        }
    });

    $("#date-min").change(function () {
        if ($("#ope-sele").children("option:selected").val() == "5") {
            if ($("#date-max").val() != "") {
                searchItems();
            }
        } else {
            searchItems();
        }
    });

    $("#date-max").change(function () {
        if ($("#date-min").val() != "") {
            searchItems();
        }
    });
}

function searchItems() {
    var operation = $("#ope-sele").children("option:selected").val();
    var date_min = $("#date-min").val();
    var maxDate = $("#date-max").val();

    sendData = {
        type: true,
        operation: operation,
        date: date_min,
        maxDate: maxDate,
        vid: vendor.vid
    };
    loadBills(sendData);
}


function setUpDate() {
    var today = new Date();
    $("#date-pay").val(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
}

function applyPay(payBillid, amountPay, billIndex) {
    var dateOfPay = $("#date-pay").val();
    if(dateOfPay == ""){
        var today = new Date();
        dateOfPay = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    }
    var proceed = confirm("Do You Want To Apply This Amount To Bill Id :" + payBillid + " ?");
    if (proceed) {
        var upPay = parseFloat(amountPay) + parseFloat(billBal[billIndex].pay);
        var upBal = parseFloat(billBal[billIndex].bal) - parseFloat(amountPay);

        var VupPay = parseFloat(amountPay) + parseFloat($("#vpaid").val());
        var VupBal = parseFloat($("#vbal").val()) - parseFloat(amountPay);

        var send = {
            date : dateOfPay,
            vid : $("#vid").val(),
            billId : payBillid,
            amount: amountPay,
            upPay: upPay,
            upBal: upBal,
            VupPay : VupPay,
            VupBal : VupBal
        };
        settlePrice(send);
    }

}

function goRefresh() {
    hideAddBox();

}

function settlePrice(dataForUpdate) {
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                alert("Successfully Updated");
            } else {
                alert("Failed To Update");
            }
            hideAddBox();
        }
    };
    var myJSON = JSON.stringify(dataForUpdate);
    delteAllHTTP.open("POST", "php/update.php?q=" + myJSON, true);
    delteAllHTTP.send();
}
