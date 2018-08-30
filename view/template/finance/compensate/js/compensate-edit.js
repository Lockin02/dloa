$(function() {
	//负责人
	$("#chargerName").yxselect_user({
		hiddenId : 'chargerId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'compensate'
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	var totalPrice = totalUnitPrice = 0;
	var detailObj = $("#detail");
	// 产品清单
	detailObj.yxeditgrid({
		objName : 'compensate[detail]',
        url: "?model=finance_compensate_compensatedetail&action=listJson",
        param : {
            'mainId' : $("#id").val()
        },
		tableClass : 'form_in_table',
		title : "物料清单",
        isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'borrowequId',
			name : 'borrowequId',
			type : 'hidden'
		}, {
			display : 'returnequId',
			name : 'returnequId',
			type : 'hidden'
		}, {
            display : '物料Id',
            name : 'productId',
            type : 'hidden'
        }, {
			display : '物料编号',
			name : 'productNo',
            tclass : 'readOnlyTxtMiddle',
			width : 90
		}, {
			display : '物料名称',
			name : 'productName',
			readonly : true,
			tclass : 'readOnlyTxtNormal'
		}, {
            display : '规格型号',
            name : 'productModel',
            readonly : true,
            tclass : 'readOnlyTxtNormal'
        }, {
			display : '单位',
			name : 'unitName',
			width : 50,
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '数量',
			name : 'number',
			width : 50,
            tclass : 'readOnlyTxtShort'
		}, {
			display : '预计维修金额',
			name : 'money',
			width : 80,
			type : 'money',
            readonly : true,
            tclass : 'readOnlyTxtShort'
		}, {
            display : '单价',
            name : 'unitPrice',
            width : 70,
            type : 'money',
            validation : {
                required : true
            },
			process : function (v, row) {
				totalUnitPrice = accAdd(totalUnitPrice, Number(row.unitPrice), 2);
				totalUnitPrice = moneyFormat2(totalUnitPrice);
				$("#formUnitPrice_v").val(totalUnitPrice);
				return ($(v).val() <= 0)? $(v).val('') : $(v).val();
			},
			event : {
				blur : function(v,row){
					var priceVal = $(this).val();
					updatePrice($(this).data('rowNum'),priceVal.replaceAll(",",""));
					checkPrice($(this).data('rowNum'));
					updateTotalCount();
				}
			}
        }, {
            display : '净值',
            name : 'price',
            width : 70,
            type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
            validation : {
                required : true
            },
			process : function (v, row) {
				totalPrice = accAdd(totalPrice, Number(row.price), 2);
				totalPrice = moneyFormat2(totalPrice);
            	$("#formPrice_v").val(totalPrice);
			}
        }, {
//			display : '赔偿类型',
//			name : 'compensateType',
//			type : 'select',
//			width : 80,
//			datacode : 'PCFSX',
//			validation : {
//				required : true
//			}
//		}, {
//			display : '赔偿金额',
//			name : 'compensateMoney',
//			width : 80,
//			type : 'money',
//			event : {
//				blur : function(){
//					countForm();
//				}
//			}
//		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txtmiddle',
			width : 90
		}, {
			display : '序列号',
			name : 'serialNos',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 90
		}]
	});

	detailObj.find('tbody').after("<tr class='tr_count'>" +
		"<td colspan='2'></td><td>合计</td><td colspan='3'></td>" +
		"<td><input id='formMoney_v' style='width:80px;' class='readOnlyTxtShortCount' readonly='readonly' value='" +
			moneyFormat2($("#formMoney").val()) + "'/>" +
		"</td>" +
		"<td><input id='formUnitPrice_v' style='width:80px;' class='readOnlyTxtShortCount' readonly='readonly' value=''/>" +
		"</td>" +
		"<td><input id='formPrice_v' style='width:80px;' class='readOnlyTxtShortCount' readonly='readonly' value=''/>" +
		"</td>" +
		"<td colspan='2'></td>" +
		"</tr>");

	//表单验证
	validate({
		"formDate" : {
			required : true
		},
		"chargerName" : {
			required : true
		},
		"deptName" : {
			required : true
		}
		// "dutyObjName" : {
		// 	required : true
		// }
	});

	$("#dutyType").change(function(){
		if($(this).val() == "PCZTLX-01"){
			$("#dutyObjName").val($("#chargerName").val());
			$("#dutyObjId").val($("#chargerId").val());
		}else if($(this).val() != ""){
			$("#dutyObjName").val($("#deptName").val());
			$("#dutyObjId").val($("#deptId").val());
		}
	}).change();

    //显示费用分摊明细
//    $("#costshareGrid").costshareGrid({
//        objName : 'compensate[costshare]',
//        url : "?model=finance_cost_costshare&action=listjson",
//        param : {'objType' : 1 ,'objId' : $("#id").val()}
//    });

	//显示质检情况
	$("#showQualityReport").showQualityDetail({
		tableClass : 'form_in_table',
		param : {
			"objId" : $("#relDocId").val(),
			"objType" : $("#qualityObjType").val()
		}
	});
});

//金额调整
function countMoney(rowNum){
	var detailObj = $("#detail");
	//赋值
	var money = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"money").val();
	//如果是正常赔偿
	var compensateType = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"compensateType").val();//赔偿方式
    var compensateRate = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"compensateRate").val();

	var compensateMoney = accDiv(accMul(money,compensateRate,2),100,2);
	detailObj.yxeditgrid("setRowColValue",rowNum,"compensateMoney",compensateMoney,true);
}

//净值验证
function checkPrice(rowNum){
    var detailObj = $("#detail");
    //赋值
    var price = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val();
    if(price==0){
        alert("净值不允许为0");
        detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val("");
		detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"unitPrice").val("");
    }
}

//单据金额计算方法
function countForm(){
	var detailObj = $("#detail");

	//计算单据金额
	var moneyArr = detailObj.yxeditgrid("getCmpByCol", "money");
	var formMoney = 0;
	moneyArr.each(function(){
		formMoney = accAdd(formMoney,$(this).val(),2);
	});
	setMoney('formMoney',formMoney);

	//计算赔偿金额
	var compensateMoneyArr = detailObj.yxeditgrid("getCmpByCol", "compensateMoney");
	var compensateMoney = 0;
	compensateMoneyArr.each(function(){
		compensateMoney = accAdd(compensateMoney,$(this).val(),2);
	});
	setMoney('compensateMoney',compensateMoney);
}

//表单提交审批
function audit(thisVal){
	$("#isSubAudit").val(thisVal);
}

// 选择序列号
function serialNum(rowNum,serialIds,serialNos,returnequId,number,detailId) {
	showThickboxWin('?model=finance_compensate_compensate&action=toSerialNos'
		+ '&relDocId=' + $("#relDocId").val()
		+ '&relDocType=' + $("#relDocType").val()
		+ '&rowNum=' + rowNum
		+ '&serialIds=' + serialIds
		+ '&serialNos=' + serialNos
		+ '&returnequId=' + returnequId
		+ '&number=' + number
		+ '&id=' + $("#id").val()
		+ '&detailId='
		+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=350");
}

// 净值调整 （净值 = 单价*数量）
function updatePrice(rowNum,unitPrice){
	var detailObj = $("#detail");
	var number = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"number",true).val();
	var newPrice = moneyFormat2(Number(number) * Number(unitPrice));
	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val(newPrice);
	$("#detail_cmp_price"+rowNum).val(newPrice);
}

// 统计单价以及净值
function updateTotalCount(){
	var detailObj = $("#detail");

	var totalUnitPric = totalPrice = 0;
	var unitPriceArr = detailObj.yxeditgrid("getCmpByCol", "unitPrice");
	var priceArr = detailObj.yxeditgrid("getCmpByCol", "price");

	unitPriceArr.each(function(){
		totalUnitPric = accAdd(totalUnitPric,$(this).val(),2);
	});
	priceArr.each(function(){
		totalPrice = accAdd(totalPrice,$(this).val(),2);
	});

	// console.log("totalUnitPric: "+totalUnitPric+"; totalPrice:"+totalPrice);
	$("#formUnitPrice_v").val(moneyFormat2(totalUnitPric));
	$("#formPrice_v").val(moneyFormat2(totalPrice));
}