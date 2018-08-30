function viewInTime() {
    showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');
}
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
                "?model=stock_outstock_stockout&action=add&actType=audit");
            if (checkForm()) {
                if (checkProNumIntime()) {
                    $("#docStatus").val("YSH");
                    $("#form1").submit();
                }
            }
        }
    }
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

function subTotalPrice1(el) {// �������Ͻ��_ʵ������
    var cost = parseInt($(el).parent().next().children().eq(0).val());
    if (cost >= 0) {
        $(el).parent().next().next().children().eq(0).val($(el).val() * cost);
    }
}
function subTotalPrice2(el) {// �������Ͻ�� _��λ�ɱ�
    var proNumVal = parseInt($(el).parent().prev().children().eq(0).val());
    if (proNumVal >= 0) {
        $(el).parent().next().children().eq(0).val($(el).val() * proNumVal);
    }
}

function checkRelDocType() {// ���ݺ���ɫ������Դ������
    reloadItems();
    if ($("#isRed").val() == 0) {
        $("#itembody").css("color", "blue");
        $(".main_head_title").html('<font color="blue">�������ⵥ</font>');
        $("#relDocType option").each(function () {
            if ($(this).val() == "QTCKQTCK") {
                $(this).remove();
            }
        });
    } else {
        $("#itembody").css("color", "red");
        $(".main_head_title").html('<font color="red">�������ⵥ</font>');
        var tlOption = false;
        $("#relDocType option").each(function () {
            if ($(this).val() == "QTCKQTCK") {
                tlOption = true;
            }
        });
        if (!tlOption) {
            $("#relDocType").append("<option value='QTCKQTCK'>��������</option>");
        }
    }
}


/**
 * ���¼��������嵥���к�
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
 * ���¼����װ���嵥���к�
 */
function reloadPackItemCount() {
    var i = 1;
    $("#packbody").children("tr").each(function () {
        $(this).children("td").eq(1).text(i);
        i++;
    });
}


/**
 * ��Ⱦ�����嵥���ϲֿ�combogrid
 */
function reloadItemStock() {
    var itemscount = $('#itemscount').val() * 1;
    for (var i = 0; i < itemscount; i++) {
        $("#stockName" + i).yxcombogrid_stockinfo('remove').yxcombogrid_stockinfo({
            hiddenId: 'stockId' + i,
            nameCol: 'stockName',
            gridOptions: {
                showcheckbox: false,
                model: 'stock_stockinfo_stockinfo',
                action: 'pageJson',
                event: {
                    'row_dblclick': function (i) {
                        return function (e, row, data) {
                            $('#stockCode' + i).val(data.stockCode);
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
    for (var i = 0; i < itemscount; i++) {// �����ϱ��
        if ($("#relDocId" + i).val() == "" || $("#relDocId" + i).val() == "0") {
            $("#productCode" + i).yxcombogrid_product("remove").yxcombogrid_product({
                hiddenId: 'productId' + i,
                nameCol: 'productCode',
                gridOptions: {
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
                            }
                        }(i)
                    }
                }
            });
            $("#productName" + i).yxcombogrid_product("remove").yxcombogrid_product({
                hiddenId: 'productId' + i,
                nameCol: 'productName',
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
                            }
                        }(i)
                    }
                }
            });
        }
    }
}

/**
 * ��ʼ�������嵥����
 */
function reloadItems() {
    var itemscount = $('#itemscount').val();
    for (var i = 0; i < itemscount; i++) {
        $("#productName" + i).yxcombogrid_product("remove");
        $("#productCode" + i).yxcombogrid_product("remove");
        $("#stockName" + i).yxcombogrid_stockinfo("remove");
    }
    $("#itembody").html("");
    $("#itemscount").val(0);
    addItems();
}
/**
 * ���¼��������嵥���к�
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
 * ��Ⱦ��װ��ֿ�����Ϣcombogrid
 */
function reloadPackProduct() {
    // ��ȡĬ�ϰ�װ��ֿ�
    var packStockId = null;
    var result = $.ajax({
        type: "POST",
        async: false,
        url: "?model=stock_stockinfo_systeminfo&action=getPackStockId",
        data: {
            id: 1
        }
    }).responseText;
    if (result != 0)
        packStockId = result;
    else {
        alert("ϵͳ�а�װ��Ĭ�ϲֿ�δ����!")
        return;
    }
    var itemscount = $('#packcount').val();
    for (var i = 0; i < itemscount; i++) {// �󶨰�װ���ϱ��
        $("#pproductCode" + i).yxcombogrid_inventory('remove').yxcombogrid_inventory({
            hiddenId: 'pproductId' + i,
            nameCol: 'productCode',
            valueCol: 'productId',
            checkParam: {
                'stockId': parseInt(packStockId)
            },
            event: {
                'clear': function (i) {
                    return function () {
                        if ($("#pproductName" + i).val() != "") {
                            $("#pproductName" + i).yxcombogrid_inventory('clearValue');
                        }
                    }
                }(i)
            },
            gridOptions: {
                param: {
                    'stockId': parseInt(packStockId)
                },
                showcheckbox: false,
                event: {
                    'row_dblclick': function (i) {
                        return function (e, row, data) {
                            $('#pproductId' + i).val(data.productId);
                            $('#pproductName' + i).val(data.productName);
                            $("#ppattern" + i).val(data.pattern);
                        }
                    }(i)
                }
            }
        });
        $("#pproductName" + i).yxcombogrid_inventory('remove').yxcombogrid_inventory({
            hiddenId: 'pproductId' + i,
            nameCol: 'productName',
            valueCol: 'productId',
            checkParam: {
                'stockId': parseInt(packStockId)
            },
            event: {
                'clear': function (i) {
                    return function () {
                        if ($("#pproductCode" + i).val() != "") {
                            $("#pproductCode" + i).yxcombogrid_inventory('clearValue');
                        }
                    }
                }(i)
            },
            gridOptions: {
                param: {
                    'stockId': parseInt(packStockId)
                },
                showcheckbox: false,
                event: {
                    'row_dblclick': function (i) {
                        return function (e, row, data) {
                            $('#pproductId' + i).val(data.productId);
                            $('#pproductCode' + i).val(data.productCode);
                            $("#ppattern" + i).val(data.pattern);
                        }
                    }(i)
                }
            }
        });
    }
}

/**
 * ѡ�����к�
 */
function chooseSerialNo(seNum) {
    var productIdVal = $("#productId" + seNum).val();
    var stockIdVal = $("#stockId" + seNum).val();
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
            if (stockIdVal != "" && parseInt($("#stockId" + seNum).val()) != 0) {
                showThickboxWin(
                    "?model=stock_serialno_serialno&action=toChooseFrame&serialnoId="
                    + serialnoId
                    + "&productId="
                    + productIdVal
                    + "&elNum="
                    + seNum
                    + "&stockId="
                    + stockIdVal
                    + "&isRed="
                    + $("#isRed").val()
                    + "&productCodeSeNum="
                    + productCodeSeNum
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600",
                    "ѡ�����к�")
            } else {
                alert("����ѡ��ֿ�!");
            }
        } else {
            alert("����ѡ������!");
        }
    }
}

/**
 * ��̬���Ӵӱ�����
 */
function addItems() {
    var mStockId = $("#stockId").val();
    var mStockCode = $("#stockCode").val();
    var mStockName = $("#stockName").val();

    var mycount = parseInt($("#itemscount").val());
    var itemtable = document.getElementById("itembody");
    i = itemtable.rows.length;
    oTR = itemtable.insertRow([i]);
    oTR.className = "TableData";
    oTR.align = "center";
    oTR.height = "28px";
    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����">';
    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = mycount + 1;
    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = ' <input type="text" name="stockout[items][' + mycount
    + '][productCode]" id="productCode' + mycount
    + '" class="txtshort" />'
    + '<input type="hidden" name="stockout[items][' + mycount
    + '][productId]" id="productId' + mycount + '"  />';
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = '<input type="text" name="stockout[items][' + mycount
        + '][proType]" id="proType' + mycount
        + '" class="readOnlyTxtShort" readonly="readonly"/>'
        + '<input type="hidden" name="stockout[items][' + mycount
        + '][proTypeId]" id="proTypeId' + mycount + '"/>';
    var oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = '<input type="text" name="stockin[items][' + mycount
        + '][k3Code]" id="k3Code' + mycount + '" class="readOnlyTxtShort" readonly="readonly"/>';
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = '<input type="text" name="stockout[items][' + mycount
        + '][productName]" id="productName' + mycount + '" class="txt" />';
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][pattern]" id="pattern' + mycount
    + '" class="readOnlyTxtItem" readonly="readonly"/>';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = ' <input type="text" name="stockout[items][' + mycount
    + '][unitName]" id="unitName' + mycount
    + '" class="readOnlyTxtItem" readonly="readonly"/>';
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][shouldOutNum]" id="shouldOutNum' + mycount
    + '" class="readOnlyTxtShort" readonly/>';
    var oTL9 = oTR.insertCell([9]);
    oTL9.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][actOutNum]" id="actOutNum' + mycount
    + '" class="txtshort" onfocus="exploreProTipInfo(' + mycount + ')" ondblclick="chooseSerialNo(' + mycount
    + ')" ondblclick="chooseSerialNo(' + mycount
    + ')" onblur="FloatMul(\'actOutNum' + mycount + '\',\'cost'
    + mycount + '\',\'subCost' + mycount + '\')"  />';
    var oTL10 = oTR.insertCell([10]);
    oTL10.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][stockName]" id="stockName' + mycount
    + '" class="txtshort" value="' + mStockName + '" />'
    + '<input type="hidden" name="stockout[items][' + mycount
    + '][stockId]" id="stockId' + mycount + '" value="' + mStockId
    + '" />' + '<input type="hidden" name="stockout[items][' + mycount
    + '][stockCode]" id="stockCode' + mycount + '" value="'
    + mStockCode + '" />'
    + '<input type="hidden" name="stockout[items][' + mycount
    + '][relDocId]" id="relDocId' + mycount + '" />'
    + '<input type="hidden" name="stockout[items][' + mycount
    + '][relDocName]" id="relDocName' + mycount + '" />'
    + '<input type="hidden" name="stockout[items][' + mycount
    + '][relDocCode]" id="relDocCode' + mycount + '" />';
    var oTL11 = oTR.insertCell([11]);
    oTL11.innerHTML = '<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('
    + mycount
    + ');" title="ѡ�����к�">'
    + '<input type="hidden" name="stockout[items]['
    + mycount
    + '][serialnoId]" id="serialnoId'
    + mycount
    + '"  />'
    + '<input type="text" name="stockout[items]['
    + mycount
    + '][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName'
    + mycount + '"  />';
    var oTL12 = oTR.insertCell([12]);
    oTL12.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][cost]" id="cost' + mycount
    + '" class="txtshort formatMoneySix"  onblur="FloatMul(\'cost'
    + mycount + '\',\'actOutNum' + mycount + '\',\'subCost' + mycount
    + '\')"   />';
    var oTL13 = oTR.insertCell([13]);
    oTL13.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][subCost]" id="subCost' + mycount
    + '" class="readOnlyTxtShort formatMoney" readonly="readonly"/>';
    var oTL14 = oTR.insertCell([14]);
    oTL14.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][salecost]" id="salecost' + mycount + '" class="txtshort formatMoney" '
    + ' "/>';
    var oTL15 = oTR.insertCell([15]);
    oTL15.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][saleSubCost]" id="saleSubCost' + mycount + '" class="txtshort" />';

    formateMoney();

    $("#itemscount").val(parseInt($("#itemscount").val()) + 1);
    reloadItemCount();
    reloadItemStock();
    reloadItemProduct();
}



/**
 * ��̬���Ӵӱ�����
 */
function addPackItems() {
    var mycount = parseInt($("#packcount").val());
    var itemtable = document.getElementById("packbody");
    i = itemtable.rows.length;
    oTR = itemtable.insertRow([i]);
    oTR.className = "TableData";
    oTR.align = "center";
    oTR.height = "28px";
    var oTL0 = oTR.insertCell([0]);
    oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delPackItem(this);" title="ɾ����">';
    var oTL1 = oTR.insertCell([1]);
    oTL1.innerHTML = mycount + 1;
    var oTL2 = oTR.insertCell([2]);
    oTL2.innerHTML = ' <input type="text" name="stockout[packitem][' + mycount
    + '][productCode]" id="pproductCode' + mycount + '" class="txt" />'
    + '<input type="hidden" name="stockout[packitem][' + mycount
    + '][productId]" id="pproductId' + mycount + '"  />';
    var oTL3 = oTR.insertCell([3]);
    oTL3.innerHTML = '<input type="text" name="stockout[packitem][' + mycount
    + '][productName]" id="pproductName' + mycount + '" class="txt" />';
    var oTL4 = oTR.insertCell([4]);
    oTL4.innerHTML = '<input type="text" name="stockout[packitem][' + mycount
    + '][outstockNum]" id="poutstockNum' + mycount + '" class="txt" />';
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = ' <input type="text" name="stockout[packitem][' + mycount
    + '][price]" id="pprice' + mycount + '" class="txt" />';
    $("#packcount").val(parseInt($("#packcount").val()) + 1);
    reloadPackProduct();
    reloadPackItemCount();
}


// ɾ��
function delItem(obj) {
    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowNo = obj.parentNode.parentNode.rowIndex - 2;
        $(obj).parent().parent().hide();
        $(obj).parent().append('<input type="hidden" name="stockout[items]['
        + rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
        + '" />');
        reloadItemCount();
    }
}


// ɾ����װ��
function delPackItem(obj) {
    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowNo = obj.parentNode.parentNode.rowIndex;
        packtable.deleteRow(rowNo);
        reloadPackItemCount();
    }
}


function checkForm() {// ����У��
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
                if ($("#stockId" + i).val() == ""
                    || parseInt($("#stockId" + i).val()) == 0) {
                    alert("�����ֿⲻ��Ϊ�գ���ѡ��...");
                    return false;
                }
                if ($("#relDocType").val() == "QTCKZCCK" && $("#isRed").val() == "0") {//�����ʲ���ɫ����
                    var actOutNum = $("#actOutNum" + i);
                    if (actOutNum.val()*1 > $("#shouldOutNum" + i).val()*1) {
                        alert("ʵ���������ܴ���Ӧ������");
                        actOutNum.focus();
                        return false;
                    }
                    if($("#productCode" + i).val() == '' || $("#productName" + i).val() == ''){//�ʲ�����ʱ����֤�Ƿ���δȷ�ϵ�����
                        alert("����δȷ�ϵ����ϣ���ȷ�Ϻ��ٽ��г���");
                        return false;
                    }
                }
            }
        }
    }
    // �������
    if($("#module").val() == ""){
    	alert("������鲻��Ϊ��");
    	return false;
    }
    return true;
}

/**
 * У�鼴ʱ���
 */
function checkProNumIntime() {
    var checkResult = true;
    var itemscount = $("#itemscount").val();
    for (var i = 0; i < itemscount; i++) {
        var stockObj = $("#stockId" + i);
        var productObj = $("#productId" + i);
        if (productObj.length > 0 && productObj.val() != "" && stockObj.val() != "" && parseInt(stockObj.val()) != "0" && $("#isDelTag" + i).val() != 1) {
            $.ajax({// ��ȡ��Ӧ�����Ϣ
                type: "POST",
                dataType: "json",
                async: false,
                url: "?model=stock_inventoryinfo_inventoryinfo&action=getInTimeObj",
                data: {
                    "productId": productObj.val(),
                    "stockId": stockObj.val()
                },
                success: function (result) {
                    if (isNum($("#actOutNum" + i).val())) {
                        if ($("#isRed").val() == "0") {// ��ɫ�����У��������
                            if (result != "0") {
                                if (result['exeNum'] < parseInt($("#actOutNum"
                                    + i).val())) {
                                    alert("��治��! " + $("#stockName" + i).val()
                                    + "�б��Ϊ\""
                                    + $("#productCode" + i).val()
                                    + "\"�����Ͽ�ִ��������" + result['exeNum']);
                                    checkResult = false;
                                }
                            } else {
                                alert("��治��!" + $("#stockName" + i).val()
                                + "�в����ڱ��Ϊ\""
                                + $("#productCode" + i).val() + "\"������");
                                checkResult = false;
                            }
                        }
                    } else {
                        alert("����������д����!");
                        checkResult = false;
                    }
                }
            });
            if (!checkResult) {
                return checkResult;
            }
        }
    }
    return true;
}

/**
 * �鿴��ͬ�������
 */
function viewContracAudit() {
    if ($("#contractId").val() == "") {
        alert("����ѡ����Ҫ�鿴�ĺ�ͬ");
    } else {
        showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_contract&pid='
        + $("#contractId").val()
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
    }
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
        });
    }
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
        });
    }
}