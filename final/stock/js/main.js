var DELETE_OVERLAP = false;

$(document).ready(function () {
    loadStockOnDatagrid("");

    $("#stock-id").keyup(function () {
        $("#stock-name").val("");
        $("#stock-manuf").val("");
        $("#stock-categ").val("");
        searchItems();

    });

    $("#stock-name").keyup(function () {
        $("#stock-id").val("");



        searchItems();
    });

    $("#stock-manuf").keyup(function () {
        $("#stock-id").val("");



        searchItems();
    });

    $("#stock-categ").keyup(function () {
        $("#stock-id").val("");



        searchItems();
    });

});

function showEmptyStock() {
    sendData = {
        filter: "empty"
    };
    loadStockOnDatagrid(sendData);
}

function showFixPrice() {
    sendData = {
        filter: "not"
    };
    loadStockOnDatagrid(sendData);
}

function searchItems() {
    var idSearch = $("#stock-id").val();
    var nameSearch = $("#stock-name").val();
    var manufSearch = $("#stock-manuf").val();
    var catSearch = $("#stock-categ").val();
    if (idSearch == "" && nameSearch == "" && manufSearch == "" && catSearch == "") {
        loadStockOnDatagrid("");
    } else {
        sendData = {
            filter: "item",
            id: idSearch,
            name: nameSearch,
            manuf: manufSearch,
            cat: catSearch
        };
        loadStockOnDatagrid(sendData);
    }
}

function loadStockOnDatagrid(sendData) {
    var loadStockHTTP = new XMLHttpRequest();
    loadStockHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;
                jQuery("#grid-data").empty();
                for (var i = 0; i < cursor.length; i++) {
                    var appendRaw = "<tr class='" + cursor[i].itemid + " grid-items'>";
                    appendRaw += "<td><span id='del-" + cursor[i].itemid + "' class='delete'><i class='fas fa-minus-circle'></i></span></td>"
                    appendRaw += "<td class='grid-slno'><input type='text' autocomplete='off' disabled value='" + (i + 1) + "'></td>";
                    appendRaw += "<td class='grid-id'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemid + "'></td>";
                    appendRaw += "<td class='grid-name'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemname + "'></td>";
                    appendRaw += "<td class='grid-man'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemmanufactor + "'></td>";
                    appendRaw += "<td class='grid-cat'><input type='text' autocomplete='off' disabled value='" + cursor[i].itemcatagory + "'></td>";
                    appendRaw += "<td class='grid-qnt'><input type='text' autocomplete='off' disabled value='" + cursor[i].left + "'></td>";
                    appendRaw += "<td class='grid-sold'><input type='text' autocomplete='off' disabled value='" + cursor[i].sold + "'></td>";
                    appendRaw += "<td class='grid-price'><input type='text' autocomplete='off' disabled value='" + cursor[i].price + "'></td>";
                    appendRaw += "<td class='grid-hsn'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxhsn + "'></td>";
                    appendRaw += "<td class='grid-igstp'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxigst + "'></td>";
                    appendRaw += "<td class='grid-sgstp'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxsgst + "'></td>";
                    appendRaw += "<td class='grid-cgstp'><input type='text' autocomplete='off' disabled value='" + cursor[i].taxcgst + "'></td>";
                    appendRaw += "<td class='grid-igst'><input type='text' autocomplete='off' disabled value='" + cursor[i].igst + "'></td>";
                    appendRaw += "<td class='grid-sgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].sgst + "'></td>";
                    appendRaw += "<td class='grid-cgst'><input type='text' autocomplete='off' disabled value='" + cursor[i].cgst + "'></td>";
                    appendRaw += "<td class='grid-total'><input type='text' autocomplete='off' disabled value='" + cursor[i].total + "'></td>";
                    appendRaw += "</tr>";
                    jQuery("#grid-data").append(appendRaw);

                    $('#del-' + cursor[i].itemid).click(function () {
                        DELETE_OVERLAP = true;
                        var itemId = $(this).attr('id').split('-')[1];
                        var proceed = confirm("Are You Sure,Do You Want To Delete");
                        if (proceed) {
                            deleteitem(itemId);
                        }
                    });
                }
                $('table tr').click(function () {
                    if (DELETE_OVERLAP) {
                        DELETE_OVERLAP = false;
                    }else{
                    var itemIndex = $('table tr').index(this);
                    if (itemIndex > 0) {
                        var itemId = $('table tr:eq(' + itemIndex + ')').attr('class').split(' ')[0];
                        //$("table tr").removeClass("selected-raw-datagrid"); //WHEN WE NEED TO SPECIFY THE SELECTED RAW
                        //$("." + itemId).addClass("selected-raw-datagrid"); //THIS IS NOT NEED
                        // window.location.href = "viewitem.php?q=" + itemId;
                         var url = "viewitem.php?q=" + itemId;
                         window.open(url, '_blank');
                    }
                }
                });
            
            } else {
                jQuery("#grid-data").empty();
                if(sendData != ""){
                    if(sendData.filter == "not"){
                        alert("Their Is No Item To Fix The Price");
                        loadStockOnDatagrid("");
                    }else if(sendData.filter == "empty"){
                        alert("Their Is No Empty Item");
                        loadStockOnDatagrid("");
                    }
                }else{
                    alert("Data Is Empty");
                }
            }
        }
    };
    if (sendData == "") {
        loadStockHTTP.open("GET", "php/loadStock.php", true);
    } else {
        var myJSON = JSON.stringify(sendData);
        loadStockHTTP.open("GET", "php/loadStock.php?q=" + myJSON, true);
    }
    loadStockHTTP.send();
}

function deleteitem(idItem) {
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText)
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                if(resobj.status == "USING"){
                    alert("This Item Can't Be Delete");
                }else{
                    alert("Succefully Deleted Bill");
                }
                location.reload();
            } else {
                    alert("Failed To Deleted");
                    location.reload();    
            }
        }
    };
    delteAllHTTP.open("POST", "php/delete.php?q=" + idItem, true);
    delteAllHTTP.send();
}  

function billSearch(){
    var billId = $("#bill-id").val();
    sendData = {
        filter: "bill",
        id: billId
    };
    loadStockOnDatagrid(sendData);
}

function venSearch(){
    var vname = $("#vendor").val();
    sendData = {
        filter: "ven",
        name: vname
    };
    loadStockOnDatagrid(sendData);
}