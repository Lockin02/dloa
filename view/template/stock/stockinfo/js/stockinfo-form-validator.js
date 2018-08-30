$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        onerror: function(msg) {
        }
    });

	/** 验证供应商名称 * */
	$("#stockName").formValidator({
		onshow : "请输入仓库名称",
		onfocus : "仓库名称不能为空",
		oncorrect : "仓库名称有效"
	}).inputValidator({
		min : 1,
		max : 50,
		onerror : "仓库名称不能为空，请重新输入"
	}).ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=stock_stockinfo_stockinfo&action=checkStockName",
		datatype : "json",
		success : function(data) {
			if (data == "1") {
				return true;
			} else {
				return false;
			}
		},
		buttons : $("#submitSave"),
		error : function() {
			alert("服务器没有返回数据，可能服务器忙，请重试");
		},
		onerror : "该仓库名称已重复，请更换",
		onwait : "正在对仓库名称进行合法性校验，请稍候..."
	});;

	/** 验证仓库代码 * */
	$("#stockCode").formValidator({
		onshow : "请输入仓库代码",
		onfocus : "仓库代码不能为空",
		oncorrect : "仓库代码有效"
	}).inputValidator({
		min : 1,
		max : 50,
		onerror : "仓库代码不能为空，请重新输入"
	}).ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=stock_stockinfo_stockinfo&action=checkStockCode",
		datatype : "json",
		success : function(data) {
			if (data == "1") {
				return true;
			} else {
				return false;
			}
		},
		buttons : $("#submitSave"),
		error : function() {
			alert("服务器没有返回数据，可能服务器忙，请重试");
		},
		onerror : "该仓库代码已重复，请更换",
		onwait : "正在对仓库代码进行合法性校验，请稍候..."
	});;
});

$(function(){

   //仓库类型
	invoiceTypeArr = getData('CKLX');
	    addDataToSelect(invoiceTypeArr, 'stockUse');
//		addDataToSelect(invoiceTypeArr, 'invoiceListType1');


});