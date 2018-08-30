function toApp() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_exchange_exchange&action=add&act=app";
}
function toSave() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_exchange_exchange&action=add";

}
//用于新增页面，编辑页面无须额外加载数据字典项
$(function() {
	if($("#exchangeId").val() == undefined){
		// 换货类型
		exchangeTypeArr = getData('HHLX');
		addDataToSelect(exchangeTypeArr, 'exchangeType');
	}
});

$(function() {
	// 所有合同权限不同，过滤参数param不同
	var param = '';
	if($("#allContract").val() == 1){
		param = {
			'states' : '2,3,4,7',
			'ExaStatus' : '完成'
		};
	}else{
		param = {
			'states' : '2,3,4,7',
			'ExaStatus' : '完成',
			'prinvipalId' : $("#userId").val()//只能查看个人合同
		};
	}
	// 选择合同源单
	$("#contractCode").yxcombogrid_allcontract({
		hiddenId : 'contractId',
		width : 980,
		height : 300,
		searchName : 'contractCode',
		event : {
			'clear' : function(e, row, data) {
				$("#contractName").val("");
				$("#customerName").val("");
				$("#customerId").val("");
			}
		},
		gridOptions : {
			showcheckbox : false,
			param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#contractName").val(data.contractName);
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					loadEquinfo(data.id);
				}
			}
		}
	});
	//由合同下推过来的单据，需要带出物料
	if($("#contractId").val() != ''){
		loadEquinfo($("#contractId").val());
	}
});

/**
 *选择序列号
 */
function chooseSerialNo(seNum) {
	var productIdVal = $("#productId" + seNum).val();
	var serialnoId = $("#serialnoId" + seNum).val();
	var serialnoName = $("#serialnoName" + seNum).val();

	if (productIdVal != "") {
		showThickboxWin("?model=stock_serialno_serialno&action=toChooseFrame&serialnoId="
				+ serialnoId
				+ "&serialnoName="
				+ serialnoName
				+ "&productId="
				+ productIdVal
				+ "&elNum="
				+ seNum
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=650")
	} else {
		alert("请先选择物料!");
	}

}

//设置设备
function initEqu(thisObj, tableId) {
	var tableObj = $('#' + tableId);
	var objLength = thisObj.length;

	var inStr = "";
	var j = 0;
	for (var i = 1; i <= objLength; i++) {
		inStr = "<tr><td>"
				+ i
				+ "</td>"
				+ "<td><input class='txtshort' type='text' name='return[equipment]["
				+ i
				+ "][productNo]' id='productNo"
				+ i
				+ "' value='"
				+ thisObj[j].productNo
				+ "'/></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='return[equipment]["
				+ i
				+ "][productName]' id='productName"
				+ i
				+ "' value='"
				+ thisObj[j].productName
				+ "'>"
				+ "<input class='txtmiddle' type='hidden' name='return[equipment]["
				+ i
				+ "][productId]' id='productId"
				+ i
				+ "' value='"
				+ thisObj[j].productId
				+ "'></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='return[equipment]["
				+ i
				+ "][productModel]' id='productModel"
				+ i
				+ "' value='"
				+ thisObj[j].productModel
				+ "'></td>"
				+ "<td><input class='txtshort' type='text' name='return[equipment]["
				+ i
				+ "][number]' id='number"
				+ i
				+ "' ondblclick='chooseSerialNo("
				+ i
				+ ")' value='"
				+ thisObj[j].number
				+ "' title='双击输入框选择输入序列号' >"
				+ "<input type='hidden' name='return[equipment]["
				+ i
				+ "][serialnoName]' id='serialnoName"
				+ i
				+ "'>"
				+ "<input type='hidden' name='return[equipment]["
				+ i
				+ "][serialnoId]' id='serialnoId"
				+ i
				+ "'></td>"
				+ "<td><input class='readOnlyTxtItem' type='text' name='return[equipment]["
				+ i
				+ "][unitName]' id='unitName"
				+ i
				+ "' value='"
				+ thisObj[j].unitName
				+ "'></td>"
				+ "<td><input class='txtshort' type='text' name='return[equipment]["
				+ i
				+ "][price]' id='price"
				+ i
				+ "' value='"
				+ thisObj[j].price
				+ "'></td>"
				+ "<td><input class='txtshort' type='text' name='return[equipment]["
				+ i + "][money]' id='money" + i + "' value='"
				+ thisObj[j].money + "'></td>"
				+ "<td><img src='images/closeDiv.gif' onclick='mydel(this,\""
				+ tableId + "\")' title='删除行'></td>"
		"</tr>";
		//插入元素
		tableObj.append(inStr);
		j = i;
	}
	$('#EquNum').val(j);
	//	return inStr;
}

/*
 * 双击查看产品清单 物料 配置信息
 */
function conInfo(productId) {
	var proId = $("#" + productId).val();
	if (proId == '') {
		alert("【请选择物料】");
	} else {
		showThickboxWin('?model=stock_productinfo_configuration&action=viewConfig&productId='
				+ proId
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600');
	}

}

//渲染物料清单
function loadEquinfo(contractId){
	var returnValue = $.ajax({
		type : 'POST',
		url : "?model=contract_contract_equ&action=listJson",
		data : {
			contractId : contractId,
			isDel : '0'
		},
		async : false
	}).responseText;
	returnValue = eval("(" + returnValue + ")");
	if (returnValue) {
		var g = $("#backequinfo").data("yxeditgrid");
		var p = $("#equinfo").data("yxeditgrid");
		g.removeAll();
		p.removeAll();
		//循环拆分数组
		for (var i = 0; i < returnValue.length; i++) {
		    var maxNum = returnValue[i].actNum;
			if (maxNum > 0) {
				outJson = {
					"productId" : returnValue[i].productId,
					"productCode" : returnValue[i].productCode,
					"productName" : returnValue[i].productName,
					"productModel" : returnValue[i].productModel,
					"maxNum" : maxNum,
					"number" : maxNum,
					"contractId" : returnValue[i].contractId,
					"contractequId" : returnValue[i].id,
					"executedNum" : "0"
				};
				//插入数据
				g.addRow(i, outJson);
				p.addRow(i, outJson);
			}
		}
	}
}

$(function() {
	/**
	 * 验证信息
	 */
	validate({
		"contractCode" : {
			required : true
		},
		"exchangeType" : {
			required : true
		},
		"deliveryCondition" : {
			required : true
		}
		,
		"arrivalDate" : {
			required : true
		},
		"expectedDate" : {
			required : true
		},
		"reason" : {
			required : true
		}
	});
});