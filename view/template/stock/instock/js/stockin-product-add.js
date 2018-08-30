function redBlueClick(el) {// ����ɫ��ѡ���¼�
    $("#isRed").val($(el).val());
    checkRelDocType();
    reloadRelDocType();
}

function confirmAudit() {// ���ȷ��
    var auditDate = $("#auditDate").val();
    if (couldAudit(auditDate)) {
        if (confirm("��˺󵥾ݽ������޸ģ���ȷ��Ҫ�����?")) {
            $("#form1").attr("action",
                "?model=stock_instock_stockin&action=add&actType=audit");
            if (checkForm()) {
                if (checkProNumIntime() && checkSerioNum()) {
                    $("#docStatus").val("YSH");
                    $("#form1").submit();
                }
            }
        }
    }
}
/**
 * У����Ҫ�������кŵĸ���
 */
function checkSerioNum() {
    var checkResult = true;
    // У����Ҫ�������кŵ����ϲ���Ϊһ����
    var itemscount = $("#itemscount").val();
    for (var i = 0; i < itemscount; i++) {
        var serialnoNameStr = $("#serialSequence" + i).val();
        if ($("#isDelTag" + i).val() != 1 && serialnoNameStr != "") {
            if (serialnoNameStr.split(",").length < $("#actNum" + i).val()) {
                alert($("#productName" + i).val() + "��¼������к�С��ʵ��������")
                checkResult = false;
                break;
            }
        }
    }
    return checkResult;
}

function couldAudit(auditDate) {// �����Ƿ��ѹ���,�������ڲ��������Ƿ��ѽ����ж�
    var resultTrue = true;
    $.ajax({
        type: "POST",
        async: false,
        url: "?model=finance_period_period&action=isClosed",
        data: {},
        success: function (result) {
            if (result == 1) {
                alert("�����ѹ��ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
                resultTrue = false;
            }
        }
    });
    $.ajax({
        type: "POST",
        async: false,
        url: "?model=finance_period_period&action=isLaterPeriod",
        data: {
            thisDate: auditDate
        },
        success: function (result) {
            if (result == 0) {
                alert("�������ڲ��������Ѿ����ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
                resultTrue = false;
            }
        }
    });
    return resultTrue;
}

function subTotalPrice1(el) {// �������Ͻ��
    var proNumVal = parseInt($(el).parent().next().children().eq(0).val());
    if (proNumVal >= 0) {
        $(el).parent().next().next().children().eq(0).val($(el).val()
        * proNumVal);
    }
}

function subTotalPrice2(el) {// �������Ͻ��
    var proNumVal = parseInt($(el).parent().prev().children().eq(0).val());
    if (proNumVal >= 0) {
        $(el).parent().next().children().eq(0).val($(el).val() * proNumVal);
    }
}

function checkRelDocType() {// ���ݺ���ɫ������Դ������
    reloadItems();
    if ($("#isRed").val() == 0) {
        $("#itembody").css("color", "blue");
        $(".main_head_title").html('<font color="blue">��Ʒ��ⵥ</font>');
        var slOption = false;
        $("#relDocType option").each(function () {
            if ($(this).val() == "RCPRK") {
                $(this).remove();
            }
            if ($(this).val() == "RCPJYD") {
                slOption = true;
            }
        });
        if (!slOption) {
            $("#relDocType").append("<option value='RCPJYD'>��Ʒ���鵥</option>")
        }
    } else {
        $("#itembody").css("color", "red");
        $(".main_head_title").html('<font color="red">��Ʒ��ⵥ</font>');
        var tlOption = false;
        $("#relDocType option").each(function () {
            if ($(this).val() == "RCPJYD") {
                $(this).remove();
            }
            if ($(this).val() == "RCPRK") {
                tlOption = true;
            }
        });
        if (!tlOption) {
            $("#relDocType").append("<option value='RCPRK'>��Ʒ���</option>");
        }
    }
}
/**
 * ���¼����嵥���к�
 */
function reloadItemCount() {
    var i = 1;
    $("#itembody").children("tr").each(function () {
        if ($(this).css("display") != "none") {
            $(this).children("td").eq(1).text(i);
            i++;
        }
    });
}

/**
 * ��Ⱦ�����嵥���ϲֿ�combogrid
 */
function reloadItemStock() {
    var itemscount = $('#itemscount').val();
    for (var i = 0; i < itemscount; i++) {
        $("#inStockName" + i).yxcombogrid_stockinfo("remove");
        $("#inStockName" + i).yxcombogrid_stockinfo({
            hiddenId: 'inStockId' + i,
            nameCol: 'stockName',
            gridOptions: {
                showcheckbox: false,
                model: 'stock_stockinfo_stockinfo',
                action: 'pageJson',
                event: {
                    'row_dblclick': function (i) {
                        return function (e, row, data) {
                            $('#inStockCode' + i).val(data.stockCode);
                        }
                    }(i)
                }
            }
        });
    }
}

/**
 * ��Ⱦ�����嵥������Ϣcombogrid
 */
function reloadItemProduct() {
    var itemscount = $('#itemscount').val();
    for (var i = 0; i < itemscount; i++) {
        if ($("#relDocId" + i).val() == "" || $("#relDocId" + i).val() == "0") {
            $("#productCode" + i).yxcombogrid_product("remove").yxcombogrid_product({// �����ϱ��
                hiddenId: 'productId' + i,
                nameCol: 'productCode',
                isDown: true,
                height: 250,
                // width : 730,
                gridOptions: {
                    // isTitle : true,
                    showcheckbox: false,
                    event: {
                        'row_dblclick': function (i) {
                            return function (e, row, data) {
                                var proType=getParentProType(data.proTypeId);
                                $('#proType' + i).val(proType);
                                $('#productName' + i).val(data.productName);
                                $('#k3Code' + i).val(data.ext2);
                                $("#pattern" + i).val(data.pattern);
                                $("#unitName" + i).val(data.unitName);
                                $("#warranty" + i).val(data.warranty);
                            }
                        }(i)
                    }
                }
            });
            $("#productName" + i).yxcombogrid_product("remove").yxcombogrid_product({
                hiddenId: 'productId' + i,
                nameCol: 'productName',
                height: 250,
                isDown: true,
                gridOptions: {
                    showcheckbox: false,
                    event: {
                        'row_dblclick': function (i) {
                            return function (e, row, data) {
                                var proType=getParentProType(data.proTypeId);
                                $('#proType' + i).val(proType);
                                $('#productCode' + i).val(data.productCode);
                                $('#k3Code' + i).val(data.ext2);
                                $("#pattern" + i).val(data.pattern);
                                $("#unitName" + i).val(data.unitName);
                                $("#warranty" + i).val(data.warranty);
                            }
                        }(i)
                    }
                }
            });
        }
    }
}

/**
 * ��ʼ�������嵥��
 */
function reloadItems() {
    var itemscount = $('#itemscount').val();
    for (var i = 0; i < itemscount; i++) {
        $("#productName" + i).yxcombogrid_product("remove");
        $("#productCode" + i).yxcombogrid_product("remove");
        $("#inStockName" + i).yxcombogrid_stockinfo("remove");
    }
    $("#itembody").empty();
    $('#itemscount').val(0);
    addItems();
}

/**
 * ��̬��Ӵӱ�����
 */
function addItems() {
    var mStockId = $("#inStockId").val();
    var mStockCode = $("#inStockCode").val();
    var mStockName = $("#inStockName").val();

    var mycount = parseInt($("#itemscount").val());
    var itemtable = document.getElementById("itembody");
    i = itemtable.rows.length;
    oTR = itemtable.insertRow([i]);
    oTR.className = "TableData";
    oTR.align = "center";
    oTR.height = "28px";
    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delItem(this);" title="ɾ����">';
    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = 1;
    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = '<input type="hidden" name="stockin[items][' + mycount
    + '][serialSequence]" id="serialSequence' + mycount
    + '" /><input type="hidden" name="stockin[items][' + mycount
    + '][serialRemark]" id="serialRemark' + mycount + '"/>'
    + '<input type="text" name="stockin[items][' + mycount
    + '][productCode]" id="productCode' + mycount
    + '" class="txtshort"/>'
    + '<input type="hidden" name="stockin[items][' + mycount
    + '][productId]" id="productId' + mycount + '"/>';
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = '<input type="text" name="stockin[items][' + mycount
        + '][proType]" id="proType' + mycount
        + '" class="readOnlyTxtShort" readonly="readonly"/>'
        + '<input type="hidden" name="stockin[items][' + mycount
        + '][proTypeId]" id="proTypeId' + mycount + '"/>';
    var oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = '<input type="text" name="stockin[items][' + mycount
    + '][k3Code]" id="k3Code' + mycount
    + '" class="readOnlyTxtShort" readonly="readonly"/>';
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = '<input type="text" name="stockin[items][' + mycount
    + '][productName]" id="productName' + mycount + '" class="txt"/>';
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = '<input type="text" name="stockin[items][' + mycount
    + '][pattern]" id="pattern' + mycount
    + '" class="readOnlyTxtShort" readonly="readonly"/>';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = ' <input type="text" name="stockin[items][' + mycount
    + '][unitName]" id="unitName' + mycount
    + '" class="readOnlyTxtMin" readonly="readonly"/>';
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = ' <input type="text" name="stockin[items][' + mycount
    + '][batchNum]" id="batchNum' + mycount + '" class="txtshort" />';
    var oTL9 = oTR.insertCell([9]);
    oTL9.innerHTML = '<input type="text" name="stockin[items][' + mycount
    + '][storageNum]" id="storageNum' + mycount
    + '" class="readOnlyTxtShort" readonly="readonly"/>';
    var oTL10 = oTR.insertCell([10]);
    oTL10.innerHTML = '<input type="text" name="stockin[items][' + mycount
    + '][actNum]" id="actNum' + mycount
    + '" class="txtshort" onfocus="exploreProTipInfo(' + mycount + ')" ondblclick="serialNoDeal(this,' + mycount
    + ')" onblur="FloatMul(\'actNum' + mycount + '\',\'price' + mycount
    + '\',\'subPrice' + mycount + '\')" />'
    + '<input type="hidden" name="stockin[items][' + mycount
    + '][serialnoId]" id="serialnoId' + mycount + '"  />'
    + '<input type="hidden" name="stockin[items][' + mycount
    + '][serialnoName]" id="serialnoName' + mycount + '"  />';
    var oTL11 = oTR.insertCell([11]);
    oTL11.innerHTML = '<input type="text" name="stockin[items][' + mycount
    + '][inStockName]" id="inStockName' + mycount
    + '" class="txtshort" value="' + mStockName + '" />'
    + '<input type="hidden" name="stockin[items][' + mycount
    + '][inStockId]" id="inStockId' + mycount + '" value="' + mStockId
    + '" />' + '<input type="hidden" name="stockin[items][' + mycount
    + '][inStockCode]" id="inStockCode' + mycount + '" value="'
    + mStockCode + '" />'
    + '<input type="hidden" name="stockin[items][' + mycount
    + '][relDocId]" id="relDocId' + mycount + '" />'
    + '<input type="hidden" name="stockin[items][' + mycount
    + '][relDocName]" id="relDocName' + mycount + '" />'
    + '<input type="hidden" name="stockin[items][' + mycount
    + '][relDocCode]" id="relDocCode' + mycount + '" />';
    var oTL12 = oTR.insertCell([12]);
    oTL12.innerHTML = '******<input type="hidden" name="stockin[items][' + mycount
    + '][price]" id="price' + mycount
    + '" class="txtshort formatMoneySix" onblur="FloatMul(\'price'
    + mycount + '\',\'actNum' + mycount + '\',\'subPrice' + mycount
    + '\')" />';
    var oTL13 = oTR.insertCell([13]);
    oTL13.innerHTML = '******<input type="hidden" name="stockin[items][' + mycount
    + '][subPrice]" id="subPrice' + mycount
    + '" class="readOnlyTxtShort formatMoney" readonly="readonly"/>';
    var oTL14 = oTR.insertCell([14]);
    oTL14.innerHTML = '<input type="text" name="stockin[items][' + mycount
    + '][warranty]" id="warranty' + mycount
    + '" class="readOnlyTxtShort" readonly="readonly"/>';

    // ��� ǧ��λ����
    formateMoney();
    $("#itemscount").val(parseInt($("#itemscount").val()) + 1);

    reloadItemStock();
    reloadItemProduct();
    reloadItemCount();
}

// ɾ��
function delItem(obj) {
    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowNo = obj.parentNode.parentNode.rowIndex - 2;
        $(obj).parent().parent().hide();
        $(obj).parent().append('<input type="hidden" name="stockin[items]['
        + rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
        + '" />');
        reloadItemCount();
    }
}

// ���кŴ���
function serialNoDeal(el, elNum) {
    if ($("#isRed").val() == 0) {
        toAddSerialNo(el, elNum);
    } else {
        chooseSerialNo(elNum);
    }
}

// ��ɫ��¼�����к�
function toAddSerialNo(el, elNum) {
    var productNum = $(el).val();
    var productId = $("#productId" + elNum).val();
    var productCode = $("#productCode" + elNum).val();
    var productName = $("#productName" + elNum).val();

    var inStockId = $("#inStockId" + elNum).val();
    var inStockCode = $("#inStockCode" + elNum).val();
    var inStockName = $("#inStockName" + elNum).val();
    var pattern = $("#pattern" + elNum).val();
    var serialSequence = $("#serialSequence" + elNum).val();
    var serialRemark = $("#serialRemark" + elNum).val();

    var cacheResult = false;
    var productCodeSeNum = productCode + "_" + elNum;
    $.ajax({// �������к�
        type: "POST",
        async: false,
        url: "?model=stock_serialno_serialno&action=cacheSerialno",
        data: {
            "serialSequence": serialSequence,
            "productCodeSeNum": productCodeSeNum
        },
        success: function (result) {
            if (result == 1) {
                cacheResult = true;
            }

        }
    });
    if (cacheResult) {
        var url = "index1.php?model=stock_serialno_serialno&action=toAdd&productId="
            + productId
            + "&productCode="
            + productCode
            + "&productName="
            + productName
            + "&inStockId="
            + inStockId
            + "&inStockCode="
            + inStockCode
            + "&inStockName="
            + inStockName
            + "&productNum="
            + productNum
            + "&pattern="
            + pattern
            + "&elNum="
            + elNum
            + "&serialRemark="
            + serialRemark
            + "&productCodeSeNum="
            + productCodeSeNum;
        if (productId != "" && inStockId != "")
            showThickboxWin(url
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
        else
            alert("��������������Ϣ�����ϲֿ���Ϣ!");
    }
}

/**
 * ��ɫ��ѡ�����к�
 */
function chooseSerialNo(seNum) {
    var productIdVal = $("#productId" + seNum).val();
    var serialnoId = $("#serialnoId" + seNum).val();
    var serialnoName = $("#serialnoName" + seNum).val();

    var cacheResult = false;
    var productCodeSeNum = $("#productCode" + seNum).val() + "_" + seNum;
    $.ajax({// �������к�
        type: "POST",
        async: false,
        url: "?model=stock_serialno_serialno&action=cacheSerialno",
        data: {
            "serialSequence": serialnoName,
            "productCodeSeNum": productCodeSeNum
        },
        success: function (result) {
            if (result == 1) {
                cacheResult = true;
            }
        }
    });

    if (cacheResult) {
        if (productIdVal != "") {
            showThickboxWin("?model=stock_serialno_serialno&action=toChooseFrame&serialnoId="
            + serialnoId
            + "&productId="
            + productIdVal
            + "&elNum="
            + seNum
            + "&productCodeSeNum="
            + productCodeSeNum
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=650")
        } else {
            alert("����ѡ������!");
        }
    }
}

function checkForm() {// ��У��
    var itemscount = $("#itemscount").val();
    var deleteCount = 0;
    for (var i = 0; i < itemscount; i++) {
        if ($("#isDelTag" + i).val() == 1) {
            deleteCount = deleteCount + 1;
        }

    }
    if (deleteCount == itemscount) {
        alert("������������Ϣ...");
        return false;
    }
    if (itemscount < 1) {
        alert("��ѡ��������Ϣ...");
        return false;
    } else {
        for (var i = 0; i < itemscount; i++) {
            if ($("#isDelTag" + i).val() != 1) {
                var productIdObj = $("#productId" + i);
                if (productIdObj.length == 0) continue;
                if (productIdObj.val() == "") {
                    alert("������Ϣ����Ϊ�գ���ѡ��...");
                    return false;
                }
                if ($("#inStockId" + i).val() == ""
                    || parseInt($("#inStockId" + i).val()) == 0) {
                    alert("���ϲֿⲻ��Ϊ�գ���ѡ��...");
                    return false;
                }
                if (!isNum($("#actNum" + i).val())) {// �ж�����
                    alert("ʵ������" + "<" + $("#actNum" + i).val() + ">" + "��д����!");
                    $("#actNum" + i).focus();
                    return false;
                }
            }
        }
    }
    return true;
}
/**
 * У�鼴ʱ���
 */
function checkProNumIntime() {
    var checkResult = true;
    var itemscount = $("#itemscount").val();

    if ($("#isRed").val() == "1") {
        for (var i = 0; i < itemscount; i++) {
            if ($("#productId" + i).val() != ""
                && $("#inStockId" + i).val() != ""
                && parseInt($("#inStockId" + i).val()) != "0"
                && $("#isDelTag" + i).val() != 1) {
                $.ajax({// ��ȡ��Ӧ�����Ϣ
                    type: "POST",
                    dataType: "json",
                    async: false,
                    url: "?model=stock_inventoryinfo_inventoryinfo&action=getInTimeObj",
                    data: {
                        "productId": $("#productId" + i).val(),
                        "stockId": $("#inStockId" + i).val()
                    },
                    success: function (result) {
                        if (isNum($("#actNum" + i).val())) {
                            if (result != "0") {
                                if (result['exeNum'] < parseInt($("#actNum" + i)
                                        .val())) {
                                    alert("��治��! "
                                    + $("#inStockName" + i).val()
                                    + "�б��Ϊ\""
                                    + $("#productCode" + i).val()
                                    + "\"�����Ͽ�ִ��������" + result['exeNum']);
                                    checkResult = false;
                                }

                            } else {
                                alert("��治��!" + $("#inStockName" + i).val()
                                + "�в����ڱ��Ϊ\""
                                + $("#productCode" + i).val() + "\"������");
                                checkResult = false;
                            }
                        } else {
                            alert("ʵ��������д����!");
                            checkResult = false;
                        }
                    }
                })
                if (!checkResult) {
                    return checkResult;
                }
            }

        }
    }
    return true;
}

/**
 * �������Ͽ�����
 *
 * @param productId
 */
function exploreProTipInfo(mycount) {
    if ($("#productId" + mycount).val() != "" && $("#productId" + mycount).val() != "0") {
        $.ajax({// �������к�
            type: "POST",
            async: false,
            url: "?model=stock_inventoryinfo_inventoryinfo&action=getJsonByProductId",
            dataType: "json",
            data: {
                "productId": $("#productId" + mycount).val()
            },
            success: function (result) {
                var tipStr = "<" + $("#productName" + mycount).val() + ">  :  ";
                for (var i = 0; i < result.length; i++) {
                    tipStr += result[i]['stockName'] + "(" + result[i]['actNum'] + ")   ";
                }
                $("#proTipInfo").text(tipStr);
            }
        })
    }
}