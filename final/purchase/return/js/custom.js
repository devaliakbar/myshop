$(document).ready(function () {
    loadDatagrid("");

    $('#ope-sele').on('change', function () {
        if (this.value == "5") {
            $(".dte-bet").show();
        } else {
            $(".dte-bet").hide();
            $("#date-max").val("");
            if ($("#date").val() != "") {
                searchItems();
            }
        }
    });

    $("#date").change(function () {
        if ($("#ope-sele").children("option:selected").val() == "5") {
            if ($("#date-max").val() != "") {
                searchItems();
            }
        } else {
            searchItems();
        }
    });

    $("#date-max").change(function () {
        if ($("#date").val() != "") {
            searchItems();
        }
    });

});

function searchItems() {
    var operation = $("#ope-sele").children("option:selected").val();
    var date = $("#date").val();
    var maxDate = $("#date-max").val();

    sendData = {
        operation: operation,
        date: date,
        maxDate: maxDate,
    };
    loadDatagrid(sendData);

}

function loadDatagrid(sendData) {
    var loadStockHTTP = new XMLHttpRequest();
    loadStockHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                jQuery("#grid-data").empty();
                for (var i = 0; i < cursor.length; i++) {
                    var appendRaw = "<tr class='" + cursor[i].id + " grid-items'>";
                    appendRaw += "<td class='grid-slno'><input type='text' autocomplete='off' disabled value='" + (i + 1) + "'></td>";
                    appendRaw += "<td class='grid-id'><input type='text' autocomplete='off' disabled value='" + cursor[i].id + "'></td>";
                    appendRaw += "<td class='grid-name'><input type='text' autocomplete='off' disabled value='" + cursor[i].name + "'></td>";
                    appendRaw += "<td class='grid-date'><input type='text' autocomplete='off' disabled value='" + cursor[i].dates + "'></td>";
                    appendRaw += "<td class='grid-amount'><input type='text' autocomplete='off' disabled value='" + cursor[i].amount + "'></td>";
                    appendRaw += "<td class='grid-c'><input type='text' autocomplete='off' disabled value='" + cursor[i].cgstper + "'></td>";
                    appendRaw += "<td class='grid-s'><input type='text' autocomplete='off' disabled value='" + cursor[i].sgstper + "'></td>";
                    appendRaw += "<td class='grid-i'><input type='text' autocomplete='off' disabled value='" + cursor[i].igstper + "'></td>";
                    appendRaw += "<td class='grid-sgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].sgst + "'></td>";
                    appendRaw += "<td class='grid-cgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].cgst + "'></td>";
                    appendRaw += "<td class='grid-igst'><input type='text' autocomplete='off' disabled value='" + cursor[i].igst + "'></td>";
                    appendRaw += "<td class='grid-tot'><input type='text' autocomplete='off' disabled value='" + cursor[i].total + "'></td>";

                    appendRaw += "</tr>";
                    jQuery("#grid-data").append(appendRaw);
                }
                $('table tr').click(function () {
                    var itemIndex = $('table tr').index(this);
                    if (itemIndex > 0) {
                        var itemId = $('table tr:eq(' + itemIndex + ')').attr('class').split(' ')[0];
                        var url = "single-item.php?q=" + itemId;
                        window.open(url, '_blank');
                    }
                });
            } else {
                alert("Data Is Empty");
                if (sendData != "") {
                    loadDatagrid("");
                }
                jQuery("#grid-data").empty();
            }
        }
    };
    if (sendData == "") {
        loadStockHTTP.open("GET", "php/loadBill.php", true);
    } else {
        var myJSON = JSON.stringify(sendData);
        loadStockHTTP.open("GET", "php/loadBill.php?q=" + myJSON, true);
    }
    loadStockHTTP.send();
}

function goRefresh() {
    location.reload();
}