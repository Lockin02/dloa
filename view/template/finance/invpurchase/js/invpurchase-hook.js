/**
 * 获取对应外购入库单
 * @param {} arg1
 * @param {} arg2
 */
$(function () {
//	$.ajax({
//	    type: "POST",
//	    url: "?model=stock_instock_stockin&action=getPurchaseOutstock",
//	    data: {"supplierId" : $("#supplierId").val()},
//	    async: false,
//	    success: function(data){
//	   		if(data != ""){
//				$("#storageTable").html(data);
//				formateMoney();
//	   	    }
//		}
//	});
});


/**
 * 验证文本框
 * @param {} data
 */
function checkInput(arg1, arg2) {
    $obj1 = $('#' + arg1);
    $obj2 = $('#' + arg2);
    if (isNaN($obj1.val())) {
        alert('请输入数字！');
        $obj1.val($obj2.val());
    } else {
        if ($obj2.val() < 0) {
            if ($obj1.val() > 0) {
                $obj1.val($obj2.val());
            } else if (Math.abs($obj1.val()) > Math.abs($obj2.val())) {
                $obj1.val($obj2.val());
            }
        } else {
            if ($obj1.val() < 0) {
                $obj1.val($obj2.val());
            } else if ($obj1.val() * 1 > $obj2.val() * 1) {
                alert('本次钩稽数不能大于未钩稽数');
                $obj1.val($obj2.val());
            }
        }
    }
}

/**
 * 添加入库申请单
 * @param {} data
 */
function addStorage(data) {
    var storageTable = $('#storageTable');
    var obj = eval("(" + data + ")");
    var productsLength = 0;
    var str = '';
    var objStorage = $('#storageCount');
    var storageCount = $('#storageCount').val();
    var thisCode = '';
    if ($('.storageList_' + obj.id).length > 0) {
//		$('.storageList_' + obj.id).remove();
    } else {
        productsLength = obj.products.length;
        for (var i = 1; i <= productsLength; i++) {
            storageCount++;
            objStorage.val(storageCount);
            $classCss = ((storageCount % 2) == 0) ? "tr_even" : "tr_odd";
            j = i - 1;
            if (obj.isRed == 0) {
                thisCode = obj.docCode;
            } else {
                thisCode = '<font color="red">' + obj.docCode + '</font>';
            }
            str += "<tr class='storageList_" + obj.id + ' ' + $classCss + "'>" +
                "<td>" +
                "<input type='text' name='storage[" + storageCount + "][number]' id='stonumber" + storageCount + "' value='" + obj.products[j].unHookNumber + "' class='txtshort' onblur='checkInput(\"stonumber" + storageCount + "\",\"oldpronumber" + storageCount + "\");'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][hookMainId]' id='stoHookManId" + obj.id + "' value='" + obj.id + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][hookId]' value='" + obj.products[j].id + "'/>" +
                "<input type='hidden' id='oldpronumber" + storageCount + "' value='" + obj.products[j].unHookNumber + "' />" +
                "<input type='hidden' name='storage[" + storageCount + "][hookObjCode]' value='" + obj.docCode + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][formDate]' id='stoFormDate" + storageCount + "' value='" + obj.auditDate + "'/>" +
                "<input type='hidden' id='storageId_" + storageCount + "' value='" + obj.inStockId + "' />" +
                "<input type='hidden' id='isRed" + storageCount + "' value='" + obj.isRed + "' />" +
                "</td>";
            if (i != 1) {
                str += "<td colspan='4'>" +
                    "</td>"
            } else {
                str += "<td>" +
                    obj.auditDate +
                    "</td>" +
                    "<td>" +
                    thisCode +
                    " <img src='images/closeDiv.gif' onclick='delStorage(" + obj.id + ")' title='删除行'/>" +
                    "</td>" +
                    "<td>" +
                    obj.inStockName +
                    "</td>" +
                    "<td>" +
                    obj.catchStatus +
                    "</td>";
            }
            str +=
                "<td>" +
                obj.products[j].productCode +
                "<input type='hidden' name='storage[" + storageCount + "][unHookNumber]' value='" + obj.products[j].unHookNumber + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][hookNumber]' value='" + obj.products[j].hookNumber + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][productName]' value='" + obj.products[j].productName + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][productNo]' value='" + obj.products[j].productCode + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][productId]' id='storagePN" + storageCount + "' value='" + obj.products[j].productId + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][cost]' value='" + obj.products[j].price + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][subCost]' value='" + obj.products[j].subPrice + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][stockId]' value='" + obj.products[j].inStockId + "'/>" +
                "<input type='hidden' name='storage[" + storageCount + "][stockName]' value='" + obj.products[j].inStockName + "'/>" +
                "</td>" +
                "<td>" +
                obj.products[j].productName +
                "</td>" +
                "<td>" +
                obj.products[j].actNum +
                "</td>" +
                "<td>" +
                obj.products[j].hookNumber +
                "</td>" +
                "<td>" +
                obj.products[j].hookAmount +
                "</td>" +
                "<td>" +
                obj.products[j].unHookNumber +
                "</td>" +
                "<td>" +
                moneyFormat2(obj.products[j].unHookAmount, 2, 2) +
                "</td>" +
                "</tr>";
        }
        storageTable.append(str);
    }
}

/**
 * 下拉选择添加发票
 * @param {} data
 */
function invAdd(data) {
    var invtable = $('#invTable');
    var obj = eval("(" + data + ")");
    var str = '';
    var objInv = $('#invCount');
    var invCount = $('#invCount').val();
    if ($('.invList_' + obj.id).length > 0) {
//		$('.invList_' + obj.id).remove();
    } else {
        productsLength = obj.products.length;
        for (var i = 1; i <= productsLength; i++) {
            invCount++;
            objInv.val(invCount);
            j = i - 1;
            if (obj.products[j].hookNumber == 0) {
                $unHookNumber = obj.products[j].number;
                $unHookAmount = obj.products[j].amount;
            } else {
                $unHookNumber = obj.products[j].unHookNumber;
                $unHookAmount = obj.products[j].unHookAmount;
            }
            if (obj.products[j].formType == "blue") {
                $price = obj.products[j].price;
            } else {
                $price = -obj.products[j].price;
            }
            $classCss = ((invCount % 2) == 0) ? "tr_even" : "tr_odd";
            str += "<tr class='invList_" + obj.id + " " + $classCss + "' title='" + obj.id + "'>" +
                "<td>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][hookMainId]' id='hookMainId" + obj.id + "' value='" + obj.id + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][hookId]' value='" + obj.products[j].id + "'/>" +
                "<input type='text' name='invpurdetail[" + invCount + "][number]' id='number" + invCount + "' readonly='readonly' class='readOnlyTxtShort' onblur='checkInput(\"number" + invCount + "\",\"oldnumber" + invCount + "\");FloatMul(\"number" + invCount + "\",\"price" + invCount + "\",\"amount" + invCount + "\",1)' value='" + $unHookNumber + "'/>" +
                "<input type='hidden' id='oldnumber" + invCount + "' value='" + $unHookNumber + "' />" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][hookObjCode]' value='" + obj.objCode + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][formDate]' id='invFormDate" + invCount + "' value='" + obj.formDate + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][formType]' id='formType" + invCount + "' value='" + obj.formType + "'/>" +
                "</td>";
            if (i != 1) {
                str += "<td colspan='3'>" +
                    "</td>"
            } else {
                if (obj.formType == 'blue') {
                    thisCode = obj.objCode;
                } else {
                    thisCode = '<font color="red">' + obj.objCode + '</font>';
                }
                str += "<td>" +
                    obj.formDate +
                    "</td>" +
                    "<td align='left' width='12%'>" +
                    thisCode +
                    " <img src='images/closeDiv.gif' onclick='delInvPur(" + obj.id + ")' title='删除行'/>" +
                    "</td>" +
                    "<td width='10%'>" +
                    obj.status +
                    "</td>";
            }
            str +=
                "<td>" +
                obj.products[j].productNo +
                "<input type='hidden' name='invpurdetail[" + invCount + "][hookNumber]' value='" + obj.products[j].hookNumber + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][hookAmount]' value='" + obj.products[j].hookAmount + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][unHookNumber]' value='" + $unHookNumber + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][unHookAmount]' value='" + $unHookAmount + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][productNo]' value='" + obj.products[j].productNo + "'/>" +
                "</td>" + "<td>" +
                obj.products[j].productName +
                "<input type='hidden' name='invpurdetail[" + invCount + "][productName]' value='" + obj.products[j].productName + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][productId]' id='invpurPN" + invCount + "' value='" + obj.products[j].productId + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][price]' id='price" + invCount + "' value='" + $price + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][amount]' id='amount" + invCount + "' value='" + $unHookAmount + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][objId]' value='" + obj.products[j].objId + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][contractId]' value='" + obj.products[j].contractId + "'/>" +
                "<input type='hidden' name='invpurdetail[" + invCount + "][contractCode]' value='" + obj.products[j].contractCode + "'/>" +
                "</td>" +
                "<td>" +
                obj.products[j].number +
                "</td>" +
                "<td class='formatMoney'>" +
                moneyFormat2(obj.products[j].hookNumber) +
                "</td>" +
                "<td class='formatMoney'>" +
                moneyFormat2(obj.products[j].hookAmount) +
                "</td>" +
                "<td class='formatMoney'>" +
                moneyFormat2($unHookNumber) +
                "</td>" +
                "<td class='formatMoney'>" +
                moneyFormat2($unHookAmount) +
                "</td>" +
                "<td class='formatMoney'>" +
                obj.products[j].dObjCode +
                "</td>" +
                "</tr>";
        }
        invtable.append(str);
    }
}

/**
 * 添加费用发票
 * @param {} data
 */
function invCostAdd(data) {
    var costTable = $('#costTable');
    var obj = data.listStr;
    var str = '';
    var objCost = $('#invCostCount');
    var invCount = $('#invCostCount').val();
    if ($('.costList_' + obj.id).length > 0) {
        $('.costList_' + obj.id).remove();
    } else {
        invCount++;
        objCost.val(invCount);
        str += "<tr height='30px' class='costList_" + obj.id + "'>" +
            "<td>" +
            obj.objCode +
            "<input type='hidden' name='invCost[" + invCount + "][hookMainId]' value='" + obj.id + "'/>" +
            "<input type='hidden' name='invCost[" + invCount + "][hookObjCode]' value='" + obj.objCode + "'/>" +
            "<input type='hidden' name='invCost[" + invCount + "][amount]' id='amount" + invCount + "' value='" + obj.amount + "'/>" +
            "<input type='hidden' name='invCost[" + invCount + "][hookAmount]' value='" + obj.amount + "'/>" +
            "<input type='hidden' name='invCost[" + invCount + "][unHookAmount]' value='0'/>" +
            "<input type='hidden' name='invCost[" + invCount + "][formDate]' value='" + formatDate(obj.createTime) + "'/>" +
            "</td>" +
            "<td>" +
            obj.supplierName +
            "</td>" +
            "<td>" +
            formatDate(obj.createTime) +
            "</td>" +
            "<td>" +
            moneyFormat2(obj.amount) +
            "</td>" +
            "<td></td>" +
            "</tr>";
        costTable.append(str);
    }
}

/**
 * 删除表单行
 */
function delInvPur(id) {
    if (confirm('确认删除？')) {
        $('.invList_' + id).remove();
        reload_cardTb();
    }
}

function delStorage(id) {
    if (confirm('确认删除？')) {
        $('.storageList_' + id).remove();
    }
}

/**
 * 加载发票下拉选择
 */
$(function () {
    //渲染tab
    $("ul.tabs").tabs("div.panes > div");
    //渲染费用发票下拉表格
//	$("#invCost").yxcombogrid_invCost({
//		isShowName:false,
//		gridOptions : {
//			action : 'pageJsonGrid',
//			showcheckbox : true,
//			param : {
//				"supplierId" : $('#supplierId').val(),
//				"status" : "CGFPZT-WGJ"
//			},colModel : [{
//				display : 'id',
//				name : 'id',
//				hide : true
//			}, {
//				display : '发票编号',
//				name : 'objCode'
//			}, {
//				display : '发票号码',
//				name : 'objNo'
//			},{
//				display : '供应商名称',
//				name : 'supplierName',
//				width : 150
//			}, {
//				display : '日期',
//				name : 'createTime',
//				process:function (v){
//					return v.substr(0,10);
//				},
//				width : 80
//			},{
//				display : '金额',
//				name : 'amount',
//				process :function(v){
//					return moneyFormat2(v);
//				},
//				width : 80
//			}, {
//				display : '列表内容',
//				name : 'listStr',
//				hide : true
//			}],
//			event : {
//				'row_click' : function(e, row, data) {
//					invCostAdd(data);
//				}
//			}
//		}
//	});
})

//选择采购发票页面
function toInvList() {
    toInvArr = new Array();
    thisStr = "";
    $.each($("input[id^='hookMainId']"), function (i, n) {
        if (inArr($(this).val(), toInvArr) == true) {
            toInvArr.push($(this).val());
        }
    });
    if (toInvArr.length != 0) {
        thisStr = toInvArr.toString();
    }
    url = "?model=finance_invpurchase_invpurchase&action=instockHookPage"
        + "&supplierId=" + $("#supplierId").val()
        + "&supplierName=" + $("#supplierName").val()
        + "&hookMainIds=" + thisStr
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=600"
        + "&width=1000"
    ;
    showThickboxWin(url);
}

//选择外购出库页面
function toStockInList() {
    //生成出库单id串
    toStoArr = new Array();
    var thisStr = "";
    $.each($("input[id^='stoHookManId']"), function (i, n) {
        if (inArr($(this).val(), toStoArr) == true) {
            toStoArr.push($(this).val());
        }
    });
    if (toStoArr.length != 0) {
        thisStr = toStoArr.toString();
    }

    //生成订单id串
    orderIdArr = new Array();
    var orderIds = "";
    $.each($("input[id^='contractId']"), function (i, n) {
        if (inArr($(this).val(), orderIdArr) == true && $(this).val() * 1 != 0) {
            orderIdArr.push($(this).val());
        }
    });
    if (orderIdArr.length != 0) {
        orderIds = orderIdArr.toString();
    }
    url = "?model=stock_instock_stockinitem&action=instockHookPage"
        + "&supplierId=" + $("#supplierId").val()
        + "&supplierName=" + $("#supplierName").val()
        + "&hookMainIds=" + thisStr
        + "&orderIds=" + orderIds
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=600"
        + "&width=1000"
    ;
//	showThickboxWin(url);
    window.open(url, '', "width=1000,height=600,top=50,left=50,toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,status=yes");
}

//清空表格
function clearTable(objId) {
    $("#" + objId).empty();
}

//判断字符串是否在数组内
//是返回false
//否返回true
function inArr(thisV, thisArr) {
    var mark = true;
    for (var i = 0; i < thisArr.length; i++) {
        if (thisV == thisArr[i]) {
            mark = false;
        }
    }
    return mark;
}

/**
 * 验证勾稽物料的数量与卡片数量是否一致
 * ID2209 2016-12-09
 * @return {Boolean}
 */
function chkInvProductNum(invProCount) {
    var checked = $("input[type=checkbox][checkCards[]][checked]");//选中的卡片
    var productCodeArr = $('#productCodeStr').val().split(',');
    var invProducArr = new Array();
    var chkInvProducArr = new Array();//用于对比的勾稽物料数量组和选中卡片对应物料数量组

    // 检查选择的卡片数量是否对应相应的勾稽的物料数量
    $.each(productCodeArr, function (i, item) {// 勾稽单据内对应物料数组
        var itemArr = item.split(':');
        invProducArr[itemArr[0]] = itemArr[1];
    });
    $.each(checked, function () {// 选中卡片内对应物料数组
        var arr = $(this).val().split(',');
        var index = arr[2] + '_' + arr[1];
        if (chkInvProducArr[index]) {
            chkInvProducArr[index] += 1;
        } else {
            chkInvProducArr[index] = 1;
        }
    });

    // 对比数量
    var chk_result = 0;
    for (key in chkInvProducArr) {
        if (invProducArr[key][0] != undefined) {
            if (parseInt(chkInvProducArr[key]) == parseInt(invProducArr[key][0])) {
                chk_result += parseInt(chkInvProducArr[key]);
            } else {
                chk_result += 0;
            }
        }
    }
    var boolean = (parseInt(invProCount) == parseInt(chk_result));
    return boolean;
}

/**
 * 表单验证(资产采购发票勾稽用,暂时只验证勾稽物料的数量与卡片数量是否一致)
 * ID2209 2016-12-06
 * @return {Boolean}
 */
function checkform_forasset() {
    var invProCount = $('#productCount').val();
    var checked = $("input[type=checkbox][checkCards[]][checked]");
    // 检查选择的卡片数量是否对应相应的勾稽的物料数量
    var checkedCardsNum = chkInvProductNum(invProCount);
    var checked = $("input[type=checkbox][checkCards[]][checked]");//选中的卡片
    var checkedLen = checked.length;

    if (invProCount == 0) {
        alert('请选择采购发票');
        return false;
    } else if (checkedLen == 0) {
        alert('请选择对应卡片');
        return false;
    }
    else if (parseInt(invProCount) != checkedLen) {
        alert('发票选择数量和卡片选择数量不相等，不能进行钩稽');
        return false;
    } else if (!checkedCardsNum) {
        alert('未选择单据或者钩稽产品不相同，不能完成钩稽');
        return false;
    } else {
        return true;
    }
}

/**
 * 添加或删除发票后重新加载可勾稽卡片信息
 * ID2209 2016-12-06
 */
function reload_cardTb() {
    if ($('#purchType') && $('#purchType').val() == 'assets') {
        var idsArr = [];
        var ids = '';
        $.each($('#invTable').children(), function () {
            if (jQuery.inArray($(this).attr('title'), idsArr) < 0) {
                idsArr.push($(this).attr('title'));
                ids += "'" + $(this).attr('title') + "',";
            }
        });
        $.ajax({
            type: "POST",
            url: "?model=finance_invpurchase_invpurchase&action=getCardsToHookJson",
            data: {"ids": ids},
            async: false,
            dataType: 'json',
            success: function (data) {
                var countNum = (data.data.length > 0) ? data.totalNum : 0;
                var productCodeStr = (data.data.length > 0) ? data.productCodeStr : '';
                $('#productCount').val(countNum);
                $('#productCodeStr').val(productCodeStr);
                if (data.msg == "ok") {
                    $('#cartsTable').html(data.data);
                } else {
                    $('#cartsTable').html('');
                }
            }
        });
    }
}

/**
 * 表单验证
 * @return {Boolean}
 */
function checkform() {

    /****************************************/
    //验证是否存在采购发票和入库清单
    if ($("input[id^='invpurPN']").length == 0) {
        alert('请选择采购发票');
        return false;
    } else if ($("input[id^='storagePN']").length == 0) {
        alert('请选择外购入库单');
        return false;
    }

    /****************************************/
    //判断单据颜色是否一致 红蓝字
    var hasBlue = 0;
    var hasRed = 0;

    $.each($("input[id^='formType']"), function (i, n) {
        if ($(this).val() == 'red') {
            hasRed = 1;
        } else {
            hasBlue = 1;
        }
    });

    if (hasBlue == hasRed) {
        alert('红蓝单据不能互相钩稽');
        return false;
    }

    $.each($("input[id^='isRed']"), function (i, n) {
        if ($(this).val() == '1') {
            hasRed = 1;
        } else {
            hasBlue = 1;
        }
    });

    if (hasBlue == hasRed) {
        alert('红蓝单据不能互相钩稽');
        return false;
    }

    //判断物料数量是否一致
    allNumber = 0;
    $.each($("input[id^='number']"), function (i, n) {
        allNumber = accAdd(allNumber, n.value * 1, 2);
    });

    if (allNumber == 0) {
        alert('发票选择数量不能为0');
        return false;
    }

    thisInvArr = new Array();
    $.each($("input[id^='stonumber']"), function (i, n) {
        allNumber = accSub(allNumber, n.value * 1, 2);
    });
    if (allNumber != 0) {
        alert('发票选择数量和入库选择数量不相等，不能进行钩稽');
        return false;
    }

    //判断产品是否对应  原理  1^1 = 0
    /****************************************/
    var markKey = 0;
    var thisInvArr = new Array();

    $.each($("input[id^='invpurPN']"), function (i, n) {
        if (thisInvArr.length != 0 && inArr($(this).val(), thisInvArr)) {
            thisInvArr[i] = $(this).val();
        } else if (inArr($(this).val(), thisInvArr)) {
            thisInvArr[i] = $(this).val();
        }
    });

    for (var i = 0; i < thisInvArr.length; i++) {
        markKey ^= thisInvArr[i] * 1;
    }
    var thisStoArr = new Array();
    $.each($("input[id^='storagePN']"), function (i, n) {
//		alert($("input[id^='stonumber']").eq(i).val())
        if ($("input[id^='stonumber']").eq(i).val() * 1 != 0) {
            if (thisStoArr.length != 0 && inArr($(this).val(), thisStoArr)) {
                thisStoArr[i] = $(this).val();
            } else if (inArr($(this).val(), thisStoArr)) {
                thisStoArr[i] = $(this).val();
            }
        }
    });
    for (var i = 0; i < thisStoArr.length; i++) {
        markKey ^= thisStoArr[i] * 1;
    }

    if (markKey != 0) {
        alert('未选择单据或者钩稽产品不相同，不能完成钩稽');
        return false;
    }


    //判断单据是否属于不同的财务期间
    /***************************************/
    var tempStr = "";  //临时字符串,存储表单日期

    //获取系统时间
    var sysYear = $("#sysYear").val() * 1;
    var sysMonth = $("#sysMonth").val() * 1;

    /**part -1 采购发票部分**/
    var invThisYear = 0;
    var invThisMonth = 0;
    var invIsDiff = false;

    $.each($("input[id^='invFormDate']"), function (i, n) {
        tempStr = $(this).val();
        if (invThisYear != 0) {
            if (invThisYear != tempStr.substring(0, 4) * 1 || invThisMonth != tempStr.substring(5, 7) * 1) {
                isDiff = true;
            }
        } else {
            invThisYear = tempStr.substring(0, 4) * 1;
            invThisMonth = tempStr.substring(5, 7) * 1;
        }
    });

//	if(invIsDiff == true){
//		alert('不同年和月的采购发票不能一同钩稽');
//		return false;
//	}

    /**part -2 外购入库部分**/

    var stoThisYear = 0;
    var stoThisMonth = 0;
    var stoIsDiff = false;

    $.each($("input[id^='stoFormDate']"), function (i, n) {
        tempStr = $(this).val();
        if (stoThisYear != 0) {
            if (stoThisYear != tempStr.substring(0, 4) * 1 || stoThisMonth != tempStr.substring(5, 7) * 1) {
                isDiff = true;
            }
        } else {
            stoThisYear = tempStr.substring(0, 4) * 1;
            stoThisMonth = tempStr.substring(5, 7) * 1;

            //判断是否要进入补差流程
            if (sysYear > stoThisYear && $("#isHook").val() * 1 == 1) {
                $("#isHook").val(0);
            } else if (sysYear == stoThisYear) {
                if (sysMonth > stoThisMonth) {
                    $("#isHook").val(0);
                } else {
                    $("#isHook").val(1);
                }
            } else {
                $("#isHook").val(1);
            }
        }
    });

//	if(invIsDiff == true){
//		alert('不同年和月的外购入库不能一同钩稽');
//		return false;
//	}

    /**part - 3 确定是正常钩稽还是补差**/
//	if(invThisYear != stoThisYear || invThisMonth != stoThisMonth){
//		$("#isHook").val(0);
//		if(confirm('不同财务期的单据钩稽会生成补差单，确定进行钩稽吗？')){
//			return true;
//		}else{
//			return false;
//		}
//	}else{
//		$("#isHook").val(1);
//	}
    /********************************************/
    //验证结束

    $("input[type='submit']").attr('disabled', true);

//	return false;
    return true;
}
