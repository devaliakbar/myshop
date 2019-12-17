
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

    var idSearch = $("#id").val();
    var nameSearch = $("#name").val();
    var manufSearch = $("#manuf").val();
    var catSearch = $("#categ").val();
    if (idSearch == "" && nameSearch == "" && manufSearch == "" && catSearch == "" && date == "" && maxDate == "") {
        loadDatagrid("");
    } else {
        sendData = {
            operation: operation,
            date: date,
            maxDate: maxDate,

            id: idSearch,
            name: nameSearch,
            manuf: manufSearch,
            cat: catSearch
        };
        loadDatagrid(sendData);
    }
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
                    appendRaw += "<td><span id='del-" + cursor[i].id + "' class='delete'><i class='fas fa-minus-circle'></i></span></td>"
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

                    appendRaw += "<td class='grid-disc'><input type='text' autocomplete='off' disabled value='" + cursor[i].discount + "'></td>";
                    appendRaw += "<td class='grid-disc-per'><input type='text' autocomplete='off' disabled value='" + cursor[i].discountper + "'></td>";
                    appendRaw += "<td class='grid-pay'><input type='text' autocomplete='off' disabled value='" + cursor[i].pay + "'></td>";

                    appendRaw += "</tr>";
                    jQuery("#grid-data").append(appendRaw);
                    $('#del-' + cursor[i].id).click(function () {
                        var itemId = $(this).attr('id').split('-')[1];
                        var proceed = confirm("Are You Sure,Do You Want To Delete");
                        if(proceed){
                            deleteitem(itemId);
                        }
                    });
                }
            } else {
                if(sendData == ""){
                    alert("Data Is Empty");
                }
                jQuery("#grid-data").empty();
            }
        }
    };
    if (sendData == "") {
        loadStockHTTP.open("GET", "reviewphp/loadStock.php", true);
    } else {
        var myJSON = JSON.stringify(sendData);
        loadStockHTTP.open("GET", "reviewphp/loadStock.php?q=" + myJSON, true);
    }
    loadStockHTTP.send();
}

function deleteitem(idForDelete) {
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                alert("Succefully Deleted Bill");
            } else {
                alert("Failed To Deleted");
            }
            location.reload();
        }
    };
    delteAllHTTP.open("POST", "reviewphp/delete.php?q=" + idForDelete, true);
    delteAllHTTP.send();
}