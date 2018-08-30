function viewInTime() {
    showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');
}
function redBlueClick(el) {// 红蓝色单选中事件
    $("#isRed").val($(el).val());
    checkRelDocType();
    reloadRelDocType();
}

function confirmAudit() {// 审核确认
    var auditDate = $("#auditDate").val();
    if (couldAudit(auditDate)) {
        if (confirm("审核后单据将不可修改，你确定要审核吗?")) {
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

function couldAudit(auditDate) {// 财务是否已关账,单据所在财务周期是否已结账判断
    var resultTrue = true;
    $.ajax({
        type: "POST",
        async: false,
        url: "?model=finance_period_period&action=isClosed",
        data: {},
        success: function (result) {
            if (result == 1) {
                alert("财务已关账，不能进行审核，请联系财务人员进行反关账！")
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
                alert("单据所在财务周期已经结账，不能进行审核，请联系财务人员进行反结账！")
                resultTrue = false;
            }
        }
    });
    return resultTrue;
}

function subTotalPrice1(el) {// 计算物料金额_实发数量
    var cost = parseInt($(el).parent().next().children().eq(0).val());
    if (cost >= 0) {
        $(el).parent().next().next().children().eq(0).val($(el).val() * cost);
    }
}
function subTotalPrice2(el) {// 计算物料金额 _单位成本
    var proNumVal = parseInt($(el).parent().prev().children().eq(0).val());
    if (proNumVal >= 0) {
        $(el).parent().next().children().eq(0).val($(el).val() * proNumVal);
    }
}

function checkRelDocType() {// 根据红篮色单控制源单类型
    reloadItems();
    if ($("#isRed").val() == 0) {
        $("#itembody").css("color", "blue");
        $(".main_head_title").html('<font color="blue">其他出库单</font>');
        $("#relDocType option").each(function () {
            if ($(this).val() == "QTCKQTCK") {
                $(this).remove();
            }
        });
    } else {
        $("#itembody").css("color", "red");
        $(".main_head_title").html('<font color="red">其他出库单</font>');
        var tlOption = false;
        $("#relDocType option").each(function () {
            if ($(this).val() == "QTCKQTCK") {
                tlOption = true;
            }
        });
        if (!tlOption) {
            $("#relDocType").append("<option value='QTCKQTCK'>其他出库</option>");
        }
    }
}


/**
 * 重新计算物料清单序列号
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
 * 重新计算包装物清单序列号
 */
function reloadPackItemCount() {
    var i = 1;
    $("#packbody").children("tr").each(function () {
        $(this).children("td").eq(1).text(i);
        i++;
    });
}


/**
 * 渲染物料清单收料仓库combogrid
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
 * 渲染物料清单物料信息combogrid
 */
function reloadItemProduct() {
    var itemscount = $('#itemscount').val();
    for (var i = 0; i < itemscount; i++) {// 绑定物料编号
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
 * 初始化物料清单表单
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
 * 重新计算物料清单序列号
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
 * 渲染包装物仓库库存信息combogrid
 */
function reloadPackProduct() {
    // 获取默认包装物仓库
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
        alert("系统中包装物默认仓库未设置!")
        return;
    }
    var itemscount = $('#packcount').val();
    for (var i = 0; i < itemscount; i++) {// 绑定包装物料编号
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
 * 选择序列号
 */
function chooseSerialNo(seNum) {
    var productIdVal = $("#productId" + seNum).val();
    var stockIdVal = $("#stockId" + seNum).val();
    var serialnoId = $("#serialnoId" + seNum).val();
    var serialnoName = $("#serialnoName" + seNum).val();

    var cacheResult = false;
    var productCodeSeNum = $("#productCode" + seNum).val() + "_" + seNum;
    $.ajax({// 缓存序列号
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
                    "选择序列号")
            } else {
                alert("请先选择仓库!");
            }
        } else {
            alert("请先选择物料!");
        }
    }
}

/**
 * 动态添加从表数据
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
    oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">';
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
    + '" class="readOnlyTxtShort" readonly="readonly"/>';
    var oTL7 = oTR.insertCell([7]);
    oTL7.innerHTML = ' <input type="text" name="stockout[items][' + mycount
    + '][unitName]" id="unitName' + mycount
    + '" class="readOnlyTxtMin" readonly="readonly"/>';
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
    + ');" title="选择序列号">'
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
        + '][remark]" id="remark' + mycount + '" class="txtshort " '
        + ' "/>';
    var oTL15 = oTR.insertCell([15]);
    oTL15.innerHTML = '<input type="text" name="stockout[items][' + mycount
        + '][prodDate]" id="prodDate' + mycount + '" class="txtshort" />';
    var oTL16 = oTR.insertCell([16]);
    oTL16.innerHTML = '<input type="text" name="stockout[items][' + mycount
        + '][shelfLife]" id="shelfLife' + mycount + '" class="txtshort" />';
    formateMoney();

    $("#itemscount").val(parseInt($("#itemscount").val()) + 1);
    reloadItemCount();
    reloadItemStock();
    reloadItemProduct();
}



/**
 * 动态添加从表数据
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
    oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delPackItem(this);" title="删除行">';
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


// 删除
function delItem(obj) {
    if (confirm('确定要删除该行？')) {
        var rowNo = obj.parentNode.parentNode.rowIndex - 2;
        $(obj).parent().parent().hide();
        $(obj).parent().append('<input type="hidden" name="stockout[items]['
        + rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
        + '" />');
        reloadItemCount();
    }
}


// 删除包装物
function delPackItem(obj) {
    if (confirm('确定要删除该行？')) {
        var rowNo = obj.parentNode.parentNode.rowIndex;
        packtable.deleteRow(rowNo);
        reloadPackItemCount();
    }
}


function checkForm() {// 表单校验
    var itemscount = $("#itemscount").val();
    var deleteCount = 0;
    for (var i = 0; i < itemscount; i++) {
        if ($("#isDelTag" + i).val() == 1) {
            deleteCount = deleteCount + 1;
        }
    }
    if (deleteCount == itemscount) {
        alert("请新增物料信息...");
        return false;
    }
    if (itemscount < 1) {
        alert("请选择物料信息...");
        return false;
    } else {
        for (var i = 0; i < itemscount; i++) {
            if ($("#isDelTag" + i).val() != 1) {
                var productIdObj = $("#productId" + i);
                if (productIdObj.length == 0) continue;
                if (productIdObj.val() == "") {
                    alert("物料信息不能为空，请选择...");
                    return false;
                }
                if ($("#stockId" + i).val() == ""
                    || parseInt($("#stockId" + i).val()) == 0) {
                    alert("发货仓库不能为空，请选择...");
                    return false;
                }
                if ($("#relDocType").val() == "QTCKZCCK" && $("#isRed").val() == "0") {//用于资产蓝色出库
                    var actOutNum = $("#actOutNum" + i);
                    if (actOutNum.val()*1 > $("#shouldOutNum" + i).val()*1) {
                        alert("实发数量不能大于应发数量");
                        actOutNum.focus();
                        return false;
                    }
                    if($("#productCode" + i).val() == '' || $("#productName" + i).val() == ''){//资产出库时，验证是否有未确认的物料
                        alert("存在未确认的物料，请确认后再进行出库");
                        return false;
                    }
                }
            }
        }
    }
    // 所属板块
    if($("#module").val() == ""){
    	alert("所属板块不能为空");
    	return false;
    }
    return true;
}

/**
 * 校验即时库存
 */
function checkProNumIntime() {
    var checkResult = true;
    var itemscount = $("#itemscount").val();
    for (var i = 0; i < itemscount; i++) {
        var stockObj = $("#stockId" + i);
        var productObj = $("#productId" + i);
        if (productObj.length > 0 && productObj.val() != "" && stockObj.val() != "" && parseInt(stockObj.val()) != "0" && $("#isDelTag" + i).val() != 1) {
            $.ajax({// 获取对应库存信息
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
                        if ($("#isRed").val() == "0") {// 蓝色出库才校验库存数量
                            if (result != "0") {
                                if (result['exeNum'] < parseInt($("#actOutNum"
                                    + i).val())) {
                                    alert("库存不足! " + $("#stockName" + i).val()
                                    + "中编号为\""
                                    + $("#productCode" + i).val()
                                    + "\"的物料可执行数量是" + result['exeNum']);
                                    checkResult = false;
                                }
                            } else {
                                alert("库存不足!" + $("#stockName" + i).val()
                                + "中不存在编号为\""
                                + $("#productCode" + i).val() + "\"的物料");
                                checkResult = false;
                            }
                        }
                    } else {
                        alert("发货数量填写有误!");
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
 * 查看合同审批情况
 */
function viewContracAudit() {
    if ($("#contractId").val() == "") {
        alert("请先选择需要查看的合同");
    } else {
        showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_contract&pid='
        + $("#contractId").val()
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
    }
}

/**
 * 解释物料库存情况
 *
 * @param productId
 */
function exploreProTipInfo(mycount) {
    if ($("#productId" + mycount).val() != "" && $("#productId" + mycount).val() != "0") {
        $.ajax({// 缓存序列号
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
 * 解释物料库存情况
 *
 * @param productId
 */
function exploreProTipInfo(mycount) {
    if ($("#productId" + mycount).val() != "" && $("#productId" + mycount).val() != "0") {
        $.ajax({// 缓存序列号
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