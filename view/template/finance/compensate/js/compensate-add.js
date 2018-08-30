$(function() {
	//负责人
	$("#chargerName").yxselect_user({
		hiddenId : 'chargerId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'compensate',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					if($("#dutyType").val() == "PCZTLX-01"){
						$("#dutyObjName").val($("#chargerName").val());
						$("#dutyObjId").val($("#chargerId").val());
					}else{
						$("#dutyObjName").val($("#deptName").val());
						$("#dutyObjId").val($("#deptId").val());
					}
				}
			}
		}
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//申请类型
	var applyType = $("#applyType").val();
	if(applyType == "JYGHSQLX-02"){
		var isAddAndDel = false;
	}else{
		var isAddAndDel = true;
	}

	var detailObj = $("#detail");
	// 产品清单
	detailObj.yxeditgrid({
		objName : 'compensate[detail]',
		url : '?model=finance_compensate_compensate&action=businessGetDetail',
		tableClass : 'form_in_table',
		isAdd : false,
		isAddAndDel : isAddAndDel,
		title : "物料清单",
		param : {
			'relDocId' : $("#relDocId").val(),
			'relDocType' : $("#relDocType").val(),
			'applyType' : applyType
		},
		event : {
			'reloadData': function(e,g,data) {
				if(!data || data.length == 0){
//					alert('暂无需要赔偿的物料');
//					closeFun();
				}
			},
			'removeRow': function(removeRow,rowNum) {
				updateTotalCount(rowNum,1);
			}
		},
		colModel : [{
			display : 'returnequId',
			name : 'returnequId',
			type : 'hidden'
		}, {
			display : 'borrowequId',
			name : 'borrowequId',
			type : 'hidden'
		}, {
			display : 'qualityequId',
			name : 'qualityequId',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productNo',
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			width : 80
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 120
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productModel',
			display : '规格型号',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '赔偿数量',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '预计维修金额',
			name : 'money',
			type : 'money',
			width : 80,
			validation : {
				required : true
			},
			event : {
				blur : function(){
					countMoney($(this).data('rowNum'));
					countForm();
				}
			}
		}, {
			display : '单价',
			name : 'unitPrice',
			type : 'money',
			width : 80,
			validation : {
				required : true
			},
			process: function (v) {
				var totalUnitPrice = ($("#totalUnitPrice_v").val() == '')? 0 : $("#totalUnitPrice_v").val();
				totalUnitPrice = accAdd(Number(totalUnitPrice),$(v).val(),2);
				$("#totalUnitPrice_v").val(totalUnitPrice);
				return ($(v).val() <= 0)? $(v).val('') : $(v).val();
			},
			event : {
				blur : function(){
					var priceVal = $(this).val();
					updatePrice($(this).data('rowNum'),priceVal.replaceAll(",",""));
					checkPrice($(this).data('rowNum'));
					updateTotalCount($(this).data('rowNum'));
				}
			}
		}, {
			display : '净值',
			name : 'price',
			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			validation : {
				required : true
			},
			process: function (v) {
				var totalPrice = ($("#totalPrice_v").val() == '')? 0 : $("#totalPrice_v").val();
				totalPrice = accAdd(Number(totalPrice),$(v).val(),2);
				$("#totalPrice_v").val(totalPrice);
			},
		}, {
			display : '赔偿金额',
			name : 'compensateMoney',
			type : 'hidden'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txtmiddle'
		}, {
			name : 'serialNos',
			display : '序列号',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}]
	});

	//加上合计
	if(applyType == "JYGHSQLX-02"){// 解决合计错位问题
		var tdPlus = '<td colspan="1"></td>';
	}else{
		var tdPlus = '<td colspan="2"></td>';
	}
	detailObj.find('tbody').after("<tr class='tr_count'>" +tdPlus+
	"<td>合计</td><td colspan='4'></td>" +
	"<td>" +
	"<input id='formMoney_v' style='width:70px;' class='readOnlyTxtShortCount' readonly='readonly'/>" +
	"</td>"+"<td>" +
	"<input id='totalUnitPrice_v' style='width:70px;' class='readOnlyTxtShortCount' readonly='readonly'/>" +
	"</td>"+"<td>" +
	"<input id='totalPrice_v' style='width:70px;' class='readOnlyTxtShortCount' readonly='readonly'/>" +
	"</td>"+
	"<td colspan='2'></td>"+
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
		}else if($(this).val() != ''){
			$("#dutyObjName").val($("#deptName").val());
			$("#dutyObjId").val($("#deptId").val());
		}
	}).change();

	//显示质检情况
	$("#showQualityReport").showQualityDetail({
		param : {
			"objId" : $("#relDocId").val(),
			"objType" : $("#qualityObjType").val()
		}
	});

	//表单验证绑定
	$("form").bind("submit",function(){
		var moneyArr = $("#detail").yxeditgrid('getCmpByCol','money');//判断有没有赔偿清单
		if(moneyArr.length == 0){
			alert('没有赔偿物料清单');
			return false;
		}
		return true;
	});
});

//金额调整
function countMoney(rowNum){
	var detailObj = $("#detail");
	//赋值
	var money = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"money",true).val();
//	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val(money);
	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"compensateMoney").val(money);
}

// 净值调整 （净值 = 单价*数量）
function updatePrice(rowNum,unitPrice){
	var detailObj = $("#detail");
	var number = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"number",true).val();
	console.log(Number(number));
	console.log(Number(unitPrice));
	var newPrice = moneyFormat2(Number(number) * Number(unitPrice));
	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val(newPrice);
	$("#detail_cmp_price"+rowNum).val(newPrice);
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
	$("#compensateMoney").val(formMoney);
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
// 统计各合计金额
function updateTotalCount(rowNum,isDel){
	var detailObj = $("#detail");

	var totalMoney = 0;
	var totalUnitPric = 0;
	var totalPrice = 0;
	var moneyArr = detailObj.yxeditgrid("getCmpByCol", "money");
	var unitPriceArr = detailObj.yxeditgrid("getCmpByCol", "unitPrice");
	var priceArr = detailObj.yxeditgrid("getCmpByCol", "price");

	moneyArr.each(function(){
		totalMoney = accAdd(totalMoney,$(this).val(),2);
	});
	unitPriceArr.each(function(){
		totalUnitPric = accAdd(totalUnitPric,$(this).val(),2);
	});
	priceArr.each(function(){
		totalPrice = accAdd(totalPrice,$(this).val(),2);
	});

	if(isDel == 1){
		var delMoney = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum, "money").val();
		var delUnitPrice = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum, "unitPrice").val();
		var delPrice = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum, "price").val();
		totalMoney = accSub(totalMoney,delMoney,2);
		totalUnitPric = accSub(totalUnitPric,delUnitPrice,2);
		totalPrice = accSub(totalPrice,delPrice,2);
	}
	console.log("totalMoney: "+totalMoney+"; totalUnitPric: "+totalUnitPric+"; totalPrice:"+totalPrice);
	$("#totalUnitPrice_v").val(moneyFormat2(totalUnitPric));
	$("#totalPrice_v").val(moneyFormat2(totalPrice));
	if(totalMoney > 0){
		$("#formMoney_v").val(moneyFormat2(totalMoney));
	}
}