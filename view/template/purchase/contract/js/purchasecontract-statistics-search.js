$(function () {
	var logicArr = $("#logic").val().split(",");
	var fieldArr = $("#field").val().split(",");
	var relationArr = $("#relation").val().split(",");
	var valuesArr = $("#values").val().split(",");

	if (logicArr.length > 0) {
		var j;
		for (var i = 0; i < logicArr.length; i++) {
			dynamic_add("invbody", "invnumber");
			j = i + 1;

			$("#logic" + j).val(logicArr[i]);
			$("#field" + j).val(fieldArr[i]);
			$("#relation" + j).val(relationArr[i]);
			$("#values" + j).val(valuesArr[i]);
		}
	}
});
/**********************删除动态表单*************************/
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (var i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i - 1;
		}
	}
}
/**********************条目列表*************************/
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i + 1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = '<select id="logic' + mycount + '" class="selectshort logic"  name="contract[' + mycount + '][logic]">' +
		'<option value="and">并且</option>' +
		'<option value="or">或者</option></select>';
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<select id="field' + mycount + '" class="selectmiddel field" name="contract[' + mycount + '][field]"> ' +
		'<option value="suppName" selected>供应商</option>' +
		'<option value="productName">物料名称</option>' +
		'<option value="productNumb">物料代码</option>' +
		'<option value="purchType">采购类型</option>' +
		'<option value="createTime">单据日期</option>' +
		'<option value="moneyAll">订单金额</option>' +
		'<option value="sendName">业务员</option>' +
		'<option value="hwapplyNumb">订单编号</option>' +
		'<option value="batchNumb">批次号</option>' +
		'<option value="sourceNumb">鼎利合同</option>' +
		'</select>';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<select id="relation' + mycount + '" class="selectshort relation" name="contract[' + mycount + '][relation]"> ' +
		'<option value="in">包含</option>' +
		'<option value="equal">等于</option>' +
		'<option value="notequal">不等于</option>' +
		'<option value="greater">大于</option>' +
		'<option value="less">小于</option>' +
		'<option value="notin">不包含</option>' +
		'</select>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<div  id="type' + mycount + '"><input type="text" class="txt values" id="values' + mycount + '" name="contract[' + mycount + '][values]" onblur="trimSpace(this);"></div>';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<img title="删除行" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
		* 1 + 1;
	//查询字段选择绑定事件
	$("#field" + mycount).bind("change", function (mycount) {
		return function () {
			if ($(this).val() == "purchType") {//判断查询字段是否为“采购类型”，如果是则追加选择框
				var tdHtml = '<select id="values' + mycount + '" class="select values"  name="contract[' + mycount + '][values]">' +
					'<option value="HTLX-XSHT">销售合同采购</option>' +
					'<option value="HTLX-FWHT">服务合同采购</option>' +
					'<option value="HTLX-ZLHT">租赁合同采购</option>' +
					'<option value="HTLX-YFHT">研发合同采购</option>' +
					'<option value="stock">补库采购</option>' +
					'<option value="assets">资产采购</option>' +
					'<option value="rdproject">研发采购</option>' +
					'<option value="produce">生产采购</option>' +
					'</select>';
				$("#type" + mycount).html("").html(tdHtml);
				$("#relation" + mycount).val("equal");
			} else {
				var tdHtml = '<input type="text" id="values' + mycount + '" class="txt values"  name="contract[' + mycount + '][values]" value="" onblur="trimSpace(this);"/>';
				$("#type" + mycount).html(tdHtml);
				$("#relation" + mycount).val("in");
			}
		};
	}(mycount));

}
//根据查询条件进行查询
function toSupport() {
	var checkSup = true;
	$.each($('.values'), function () {
		if ($(this).val() == "") {
			alert("请输入查询值");
			$(this).focus();
			checkSup = false;
			return false;
		}
	});

	if (!checkSup) {
		return false;
	}

	var logicArr = [];
	var fieldArr = [];
	var relationArr = [];
	var valuesArr = [];

	// 行数量
	var invnumber = $("#invnumber").val() * 1;
	for (var i = 0; i <= invnumber; i++) {
		var logicObj = $("#logic" + i);
		if (logicObj.length == 1) {
			logicArr.push(logicObj.val());
			fieldArr.push($("#field" + i).val());
			relationArr.push($("#relation" + i).val());
			valuesArr.push($("#values" + i).val());
		}
	}
	this.opener.location = "?model=purchase_contract_purchasecontract&action=toStatistics" +
		"&logic=" + logicArr.toString() +
		"&field=" + fieldArr.toString() +
		"&relation=" + relationArr.toString() +
		"&values=" + valuesArr.toString()
	;
	this.close();
}
//去除前后空格
function trimSpace(obj) {
	var newVal = $.trim($(obj).val());
	$(obj).val(newVal);
}

