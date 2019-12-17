var DELETE_OVERLAP = false;


$(document).ready(function(){
    $("#id").keyup(function () {
        $("#name").val("");
        $("#phone").val("");
        searchItems();
    });

    $("#name").keyup(function () {
        $("#id").val("");
        searchItems();
    });

    $("#phone").keyup(function () {
        $("#id").val("");
        searchItems();
    });
   
   
    loadVendor("");
});

function searchItems() {
    var id = $("#id").val();
    var name = $("#name").val();
    var phone = $("#phone").val();
    if (id == "" && name == "" && phone == "") {
        loadVendor("");
    } else {
        sendData = {
            id: id,
            name: name,
            phone: phone,
        };
        loadVendor(sendData);
    }
}

function loadVendor(sendData){
    var loadVendorHTTP = new XMLHttpRequest();
    loadVendorHTTP.onreadystatechange = function () {
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
                    appendRaw += "<td class='grid-phone'><input type='text' autocomplete='off' disabled value='" + cursor[i].phone + "'></td>";
                    appendRaw += "<td class='grid-addr'><input type='text' autocomplete='off' disabled value='" + cursor[i].addr + "'></td>";
                    appendRaw += "<td class='grid-amt'><input type='text' autocomplete='off' disabled value='" + cursor[i].amount + "'></td>";
                    appendRaw += "<td class='grid-return'><input type='text' autocomplete='off' disabled value='" + cursor[i].return + "'></td>";
                    appendRaw += "<td class='grid-tot'><input type='text' autocomplete='off' disabled value='" + cursor[i].total + "'></td>";
                    appendRaw += "<td class='grid-pay'><input type='text' autocomplete='off' disabled value='" + cursor[i].pay + "'></td>";
                    appendRaw += "<td class='grid-bal'><input type='text' autocomplete='off' disabled value='" + cursor[i].bal + "'></td>";

                    appendRaw += "</tr>";

                    jQuery("#grid-data").append(appendRaw);

                    $('#del-' + cursor[i].id).click(function () {
                        DELETE_OVERLAP = true;
                        var itemId = $(this).attr('id').split('-')[1];
                        var proceed = confirm("Are You Sure,Do You Want To Delete");
                        if(proceed){
                            deleteitem(itemId);
                        }
                    });

                }

                $('table tr').click(function () {
                    if(DELETE_OVERLAP){
                        DELETE_OVERLAP = false;
                    }else{
                    var itemIndex = $('table tr').index(this);
                    if (itemIndex > 0) {
                        var itemId = $('table tr:eq(' + itemIndex + ')').attr('class').split(' ')[0];
                        window.location.href = "credit-detail.php?q=" + itemId;
                    }
                    }
                });

            } else {
                if(sendData == ""){
                    alert("Data Is Empty");
                }
                jQuery("#grid-data").empty();
            }
        }
    };
    if (sendData == "") {
        loadVendorHTTP.open("GET", "php/loadVendor.php", true);
    } else {
        var myJSON = JSON.stringify(sendData);
        loadVendorHTTP.open("GET", "php/loadVendor.php?q=" + myJSON, true);
    }
    loadVendorHTTP.send();
}

function  deleteitem(itemId){
    var delteAllHTTP = new XMLHttpRequest();
    delteAllHTTP.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                if(resobj.status == "USED"){
                    alert("Can't Delete This Vendor");
                }else{
                    alert("Successfully Deleted This Vendor");
                }
            } else {
                alert("Failed To Deleted");
            }
            refresh();
        }
    };
    delteAllHTTP.open("POST", "php/delete.php?q=" + itemId, true);
    delteAllHTTP.send();
}

function refresh(){
    $("#id").val("");
    $("#name").val("");
    $("#phone").val("");
    loadVendor("");
}