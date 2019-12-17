var item = {
    name: null,
    man: null,
    cat: null
};

var totalPrice = null;

$(document).ready(function () {
    totalPrice = $("#amnt-n-tax").val();
    item.name = $("#name").val();
    item.man = $("#manufact").val();
    item.cat = $("#categ").val();

    $("#amnt-n-tax").keyup(function () {
        var total = $("#amnt-n-tax").val();
        var per = $("#igst-per").val();
        var cper = $("#cgst-per").val();
        var sper = $("#sgst-per").val();

        if (total == "" || isNaN(total)) { total = 0; }

        var c, i, s, price;
        if (total > 0) {
            var yy = per / 100;
            price = total / (1 + yy);

            c = Number(((cper / 100) * price));
            s = Number(((sper / 100) * price));
            i = Number((c + s));

        } else {
            c = 0;
            i = 0;
            s = 0;
            price = 0;
        }
        if (total < 0) {
            total = 0;
            $("#amnt-n-tax").val(total);
        }
        $("#cgst").val(c);
        $("#igst").val(i);
        $("#sgst").val(s);
        $("#amount").val(price);

    });
});

function cancel() {
    window.location.href = "index.html";
}

function update() {
    var id = $("#id").val();
    var name = $("#name").val();
    var man = $("#manufact").val();
    var cat = $("#categ").val();
    if (name != item.name || man != item.man || cat != item.cat) {
        var proceed = confirm("Are You Sure Do You Want To Change Item Details?");
        if (proceed) {
            var data = {
                id: id,
                tbl: 'item',
                name: name,
                man: man,
                cat: cat
            };
            updateItem(data);
        }
    }

    var c = $("#cgst").val();
    var s = $("#sgst").val();
    var i = $("#igst").val();
    var price = $("#amount").val();
    var tot = $("#amnt-n-tax").val();
    if (tot == "") {
        tot = 0;
    }

    if (tot != totalPrice) {
        var proceed = confirm("Are You Sure Do You Want To Change This Item Sale Price?");
        if (proceed) {
            var data = {
                id: id,
                tbl: 'amt',
                c: c,
                s: s,
                i: i,
                price: price,
                tot: tot
            };
            updateItem(data);
        }
    }
}

function updateItem(updateData) {
    var updatehttp = new XMLHttpRequest();
    updatehttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                alert("Succesfully Updated");
            } else {
                alert("Failed To Update");
            }
        }
    };
    var myJSON = JSON.stringify(updateData);
    updatehttp.open("GET", "php/updateItems.php?q=" + myJSON, true);
    updatehttp.send();
}

function viewBarcode() {
    ids = $("#id").val()
    var url = "php/barcode.php?q=" + ids;
    window.open(url, '_blank');
}