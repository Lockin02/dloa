function viewInTime() {
    showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');
}
function confirmAudit() {// ���ȷ��
    var auditDate = $("#auditDate").val();
    if (couldAudit(auditDate)) {
        if (confirm("��˺󵥾ݽ������޸ģ���ȷ��Ҫ�����?")) {
            $("#form1").attr("action",
                "?model=stock_outstock_stockout&action=edit&actType=audit");
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

/**
 * ��Ⱦ�����嵥���ϲֿ�combogrid
 */
function reloadItemStock() {
    var itemscount = $('#itemscount').val();
    for (var i = 0; i < itemscount; i++) {
        $("#stockName" + i).yxcombogrid_stockinfo('remove');
        $("#stockName" + i).yxcombogrid_stockinfo({
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
 * ���¼��������嵥���к�
 */
function reloadItemCount() {
    var i = 1;
    $("#itembody").children("tr").each(function () {
        if ($(this).css("display") != "none") {
            $(this).children("td").eq(1).text(i);
            i++;
        }
    })
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
 * ��̬��Ӵӱ�����
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
    oTL4.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][k3Code]" id="k3Code' + mycount
    + '" class="readOnlyTxtShort" readonly="readonly"/>';
    var oTL5 = oTR.insertCell([5]);
    oTL5.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][productName]" id="productName' + mycount + '" class="txt" />';
    var oTL6 = oTR.insertCell([6]);
    oTL6.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][pattern]" id="pattern' + mycount
    + '" class="readOnlyTxtShort" readonly="readonly"/>';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = ' <input type="text" name="stockout[items][' + mycount
    + '][unitName]" id="unitName' + mycount
    + '" class="readOnlyTxtMin" readonly="readonly"/>';
    var oTL8 = oTR.insertCell([8]);
    oTL8.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][actOutNum]" id="actOutNum' + mycount
    + '" class="txtshort" onfocus="exploreProTipInfo(' + mycount + ')" ondblclick="chooseSerialNo(' + mycount
    + ')" ondblclick="chooseSerialNo(' + mycount
    + ')" onblur="FloatMul(\'actOutNum' + mycount + '\',\'cost'
    + mycount + '\',\'subCost' + mycount + '\')"  />';
    var oTL9 = oTR.insertCell([9]);
    oTL9.innerHTML = '<input type="text" name="stockout[items][' + mycount
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
    var oTL10 = oTR.insertCell([10]);
    oTL10.innerHTML = '<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('
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
    var oTL11 = oTR.insertCell([11]);
    oTL11.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][cost]" id="cost' + mycount
    + '" class="txtshort formatMoneySix"  onblur="FloatMul(\'cost'
    + mycount + '\',\'actOutNum' + mycount + '\',\'subCost' + mycount
    + '\')"   />';
    var oTL12 = oTR.insertCell([12]);
    oTL12.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][subCost]" id="subCost' + mycount
    + '" class="readOnlyTxtShort formatMoney" readonly="readonly"/>';
    var oTL13 = oTR.insertCell([13]);
    oTL13.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][remark]" id="remark' + mycount + '" class="txtshort" />';
    var oTL14 = oTR.insertCell([14]);
    oTL14.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][prodDate]" id="prodDate' + mycount
    + '" class="txtshort" onfocus="WdatePicker()"  />';
    var oTL15 = oTR.insertCell([15]);
    oTL15.innerHTML = '<input type="text" name="stockout[items][' + mycount
    + '][shelfLife]" id="shelfLife' + mycount + '" class="txtshort" />';

    formateMoney();

    $("#itemscount").val(parseInt($("#itemscount").val()) + 1);
    reloadItemCount();
    reloadItemStock();
    reloadItemProduct();
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
                if ($("#productId" + i).val() == "") {
                    alert("������Ϣ����Ϊ�գ���ѡ��...");
                    return false;
                }
                if ($("#stockId" + i).val() == ""
                    || parseInt($("#stockId" + i).val()) == 0) {
                    alert("�����ֿⲻ��Ϊ�գ���ѡ��...");
                    return false;
                }
                // �����������Ƿ�Ϸ�
                var actOutNum = $("#actOutNum" + i);
                if (!isNum(actOutNum.val())) {// �ж�����
                    alert("����" + "<" + actOutNum.val() + ">" + "��д����!");
                    actOutNum.focus();
                    return false;
                }
                if($("#relDocType").val() == "QTCKZCCK" && $("#relDocId" + i).val() != ''
                		&& isNum($("#relDocId" + i).val()) && $("#isRed").val() == "0"){//�����ʲ���ɫ����,��OA���ݲ���У��
                	var num = 0;
                    $.ajax({//��ȡ������ʵ�ʿɳ�������
                        type: "POST",
                        async: false,
                        url: "?model=asset_require_requireinitem&action=getNumAtInStock",
                        data: {
                            "id": $("#relDocId" + i).val()
                        },
                        success: function (data) {
                        	num = data;
                        }
                    });
                    if(num <= 0){
                        alert("����Ϊ" + "<" + $("#productName" + i).val() + ">" + "�������ѳ�����ϣ������ٴ��ύ");
                        return false;
                    }
                    if(actOutNum.val()*1 > num){
                        alert("����Ϊ" + "<" + $("#productName" + i).val() + ">" + "�����ϵ��������ܳ���" + num);
                        actOutNum.focus();
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
        if ($("#productId" + i).val() != "" && $("#stockId" + i).val() != ""
            && parseInt($("#stockId" + i).val()) != "0"
            && $("#isDelTag" + i).val() != 1) {
            $.ajax({// ��ȡ��Ӧ�����Ϣ
                type: "POST",
                dataType: "json",
                async: false,
                url: "?model=stock_inventoryinfo_inventoryinfo&action=getInTimeObj",
                data: {
                    "productId": $("#productId" + i).val(),
                    "stockId": $("#stockId" + i).val()
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
            })
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
        })
    }
}