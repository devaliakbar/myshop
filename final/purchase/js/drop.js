var mouse = false;

var cust_index = -1;

var vendorHelp = {
    id: null,
    name:null
};
function dropReady() {
    //TO PREVENT AUTO INCREMENT WHILE USING ARROW FOR NUMBER AND PREVENT SUBMIT WHEN PRESS ENTER
    $("input[type=number]").on("focus", function () {
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40 || event.keyCode === 13) {
                event.preventDefault();
            }
        });
    });

    //ESC FOR REMOVE SUGGESSION
    $(document).keyup(function (e) {
        if (e.keyCode == 27) {
            cust_index = -1;
            $(".list").html("");
        }
    });

    //WHEN FOCUS LOST,REMOVE SUGGESION
    $(".use-list").focusout(function () {
        if (!$(".selected").attr('id')) {
            cust_index = -1;
            $(".list").html("");
        }
    });

    //WHEN ID FOCUS LOST TRACK IT'S VALUE
    $("#cust-id").focusout(function () {
        if (vendorHelp.id == null) {
            $("#cust-id").val("");
            $("#cust-name").val("");
            $("#gst-no").val("");
        } else {
            if (vendorHelp.id != $("#cust-id").val()) {
                $("#cust-id").val("");
                $("#cust-name").val("");
                $("#gst-no").val("");
            }
        }
    });

    //WHEN NAME FOCUS LOST TRACK IT'S VALUE
    $("#cust-name").focusout(function () {
        if (vendorHelp.id == null) {
            $("#cust-id").val("");
        } else {
            if (vendorHelp.name != $("#cust-name").val()) {
                $("#cust-id").val("");
            }
        }
    });

    //FOR NAME
    var $input = $('#cust-name');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (cust_index < count - 1) {
                if (!mouse) {
                    cust_index++;
                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * cust_index }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            cust_index = -1;
            $(".list").html("");
        }
        else if (e.keyCode == 38) {
            if (cust_index > 0) {
                if (!mouse) {

                    cust_index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * cust_index) - 72 }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                selectedHint($(".selected").attr('id'));
            }
        } else {
            showHint($('#cust-name').val(), "cust-name");
        }
    });

    //FOR ID
    var $input = $('#cust-id');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (cust_index < count - 1) {
                if (!mouse) {
                    cust_index++;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * cust_index }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            cust_index = -1;
            $(".list").html("");
        }

        else if (e.keyCode == 38) {
            if (cust_index > 0) {
                if (!mouse) {

                    cust_index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * cust_index) - 72 }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                selectedHint($(".selected").attr('id'));
            }


        } else {
            showHint($('#cust-id').val(), "cust-id");
        }
    });


    //FOR VENDOR PHONE
    var $input = $('#cust-phone');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (cust_index < count - 1) {
                if (!mouse) {

                    cust_index++;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * cust_index }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            cust_index = -1;
            $(".list").html("");
        }

        else if (e.keyCode == 38) {
            if (cust_index > 0) {
                if (!mouse) {

                    cust_index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * cust_index) - 72 }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                selectedHint($(".selected").attr('id'));
            }


        } else {
            showHint($('#cust-phone').val(), "cust-phone");
        }
    });

    //FOR VENDOR ADDRESS
    var $input = $('#cust-address');
    $input.bind('keyup', function (e) {
        if (e.keyCode == 40) {
            var count = $(".items").length;
            if (cust_index < count - 1) {
                if (!mouse) {

                    cust_index++;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: 72 * cust_index }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 27) {
            cust_index = -1;
            $(".list").html("");
        }

        else if (e.keyCode == 38) {
            if (cust_index > 0) {
                if (!mouse) {

                    cust_index--;

                    //SCROLL DOWN EFFECT
                    $('.list').animate({ scrollTop: (72 * cust_index) - 72 }, 100);

                    removeAll();
                    appendClassName();
                }
            }
        } else if (e.keyCode == 13) {
            if ($(".selected").length > 0) {
                selectedHint($(".selected").attr('id'));
            }


        } else {
            showHint($('#cust-address').val(), "cust-address");
        }
    });
}

function appendClassName() {
    var a = $('ul li').eq(cust_index).attr('id'); 
    $("#" + a).addClass("selected");
}

function removeAll() {
    $(".items").removeClass("selected");
}

function showHint(str, idOfdiv) {
    cust_index = -1;
    $(".list").html("");
    if (str.length != 0) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var resobj = JSON.parse(this.responseText);
                if (resobj.success) {
                    var cursor = resobj.cursor;
                    for (var i = 0; i < cursor.length; i++) {
                        $("." + idOfdiv).append("<li id='" + cursor[i].id + "' class='items'> | ID:" + cursor[i].id + " | NAME:" + cursor[i].name + 
                        " | PHONE:" + cursor[i].phone + " | ADDRESS:" + cursor[i].addrs + "</li>");
                    }
                    $('.items').hover(function () {
                        mouse = true;
                        cust_index = $(this).index();
                        removeAll();
                        appendClassName();
                    }, function () {
                        mouse = false;
                    });
                    $(".items").click(function () {
                        selectedHint(this.id);
                    });

                }
            }
        };

        var filterItemName = "";
        if (idOfdiv == "cust-id") {
            filterItemName = "id";
        } else if (idOfdiv == "cust-phone") {
            filterItemName = "phone";
        } else if(idOfdiv == "cust-address"){
            filterItemName = "address";
        }else {
            filterItemName = "name";
        }
        var sendObj = {
            filter: filterItemName,
            value: str
        };
        var myJSON = JSON.stringify(sendObj);
        xmlhttp.open("GET", "php/search.php?q=" + myJSON, true);
        xmlhttp.send();
    }
}

function selectedHint(idOfItem) {
    cust_index = -1;
    $(".list").html("");
    var fillRequest = new XMLHttpRequest();
    fillRequest.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var resobj = JSON.parse(this.responseText);
            if (resobj.success) {
                var cursor = resobj.cursor;

                //FOR ID HELP
                vendorHelp.id = cursor[0].id;
                vendorHelp.name = cursor[0].name;

                var ids = cursor[0].id;
                var name = cursor[0].name;
                var addrs = cursor[0].addrs;
                var phone = cursor[0].phone;
                var gstnum = cursor[0].gst;

                $("#gst-no").val(gstnum);

                $("#cust-id").val(ids);
                $("#cust-name").val(name);
                $("#cust-phone").val(phone);
                $("#cust-address").val(addrs); 

                $("#date").focus();
            }
        }
    };
    fillRequest.open("POST", "php/getvendorInfo.php?q=" + idOfItem, true);
    fillRequest.send();
}