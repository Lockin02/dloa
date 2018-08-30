$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        onerror: function(msg) {
        }
    });

	/** ��֤��Ӧ������ * */
	$("#stockName").formValidator({
		onshow : "������ֿ�����",
		onfocus : "�ֿ����Ʋ���Ϊ��",
		oncorrect : "�ֿ�������Ч"
	}).inputValidator({
		min : 1,
		max : 50,
		onerror : "�ֿ����Ʋ���Ϊ�գ�����������"
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
			alert("������û�з������ݣ����ܷ�����æ��������");
		},
		onerror : "�òֿ��������ظ��������",
		onwait : "���ڶԲֿ����ƽ��кϷ���У�飬���Ժ�..."
	});;

	/** ��֤�ֿ���� * */
	$("#stockCode").formValidator({
		onshow : "������ֿ����",
		onfocus : "�ֿ���벻��Ϊ��",
		oncorrect : "�ֿ������Ч"
	}).inputValidator({
		min : 1,
		max : 50,
		onerror : "�ֿ���벻��Ϊ�գ�����������"
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
			alert("������û�з������ݣ����ܷ�����æ��������");
		},
		onerror : "�òֿ�������ظ��������",
		onwait : "���ڶԲֿ������кϷ���У�飬���Ժ�..."
	});;
});

$(function(){

   //�ֿ�����
	invoiceTypeArr = getData('CKLX');
	    addDataToSelect(invoiceTypeArr, 'stockUse');
//		addDataToSelect(invoiceTypeArr, 'invoiceListType1');


});