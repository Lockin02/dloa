$(document).ready(function() {

	$.formValidator.initConfig({
				theme : "Default",
				submitOnce : true,
				formID : "form1",
				onError : function(msg, obj, errorlist) {
					alert(msg);
				}
			});
 })
 
 /**
 * ��̬��Ӵӱ�����
 */
function addItems() {
	var mycount = parseInt($("#itemscount").val());
	var itemtable = document.getElementById("itemtable");
	i = itemtable.rows.length;
	oTR = itemtable.insertRow(i);
	oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "28px";
	var oTL0 = oTR.insertCell(0);
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delItem(this);" title="ɾ����">';
	var oTL1 = oTR.insertCell(1);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell(2);
	oTL2.innerHTML = '<select name="assproperties[items][' + mycount
			+ '][columnName]" id="columnName' + mycount
			+ '" class="select" ></select>';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" name="assproperties[items][' + mycount
			+ '][columnText]" id="columnText' + mycount + '" class="txt" />';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="text" name="assproperties[items][' + mycount
			+ '][columnDataType]" id="columnDataType' + mycount
			+ '" class="txt" />';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);
}

// ɾ��
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="assproperties[items]['
				+ rowNo + ']isDelTag" value="1" id="isDelTag' + rowNo
				+ '" />');
		reloadItemCount();
	}
}
/**
 * ��ʼ�������嵥
 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
	$("#itembody").empty();
	$('#itemscount').val(0);
	addItems();
}

/**
 * ���¼����嵥���к�
 */
function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
				if ($(this).css("display") != "none") {
					$(this).children("td").eq(1).text(i);
					i++;

				}
			})