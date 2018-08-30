
  $(function() {
			//下达人选择
			$("#issuedUserName").yxselect_user({
						hiddenId : 'issuedUserCode'

					});


			//维修人员选择
			$("#repairUserName").yxselect_user({
						hiddenId : 'repairUserCode',
						isGetDept : [true,"repairDeptCode","repairDeptName"]

					});

			$("#repairDeptName").yxselect_dept({
						hiddenId : 'repairDeptCode'

					});

	/**
	 * 验证信息
	 */
	 validate({
	 "issuedUserName" : {
	 		required : true

	 },
	 "issuedTime" : {
	 		custom : ['date']
	 },
	 "repairDeptName" : {
	 		required : true

	 },
	 "repairUserName" : {
	 		required : true

	 }
	 });
});

	/**
	 * 动态添加从表数据
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
	oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png"  onclick="delItem(this);" title="删除行">';
	var oTL1 = oTR.insertCell(1);
	oTL1.innerHTML = mycount + 1;
	var oTL2 = oTR.insertCell(2);
	oTL2.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>'
			+ '<input type="hidden" name="repaircheck[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][productName]" id="productName' + mycount + '" class="readOnlyTxtNormal" readOnly/>';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][productType]" id="productType' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>';
	var oTL5 = oTR.insertCell(5);
	oTL5.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtShort"readOnly />';
	var oTL6 = oTR.insertCell(6);
	oTL6.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>';
	var oTL7 = oTR.insertCell(7);
	oTL7.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][serilnoName]" id="serilnoName' + mycount
			+ '" class="readOnlyTxtNormal" readOnly />';
	var oTL8 = oTR.insertCell(8);
	oTL8.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][fittings]" id="fittings' + mycount
			+ '" class="readOnlyTxtNormal"readOnly />';
	var oTL9 = oTR.insertCell(9);
	oTL9.innerHTML = '<input type="text" name="repaircheck[items][' + mycount
			+ '][troubleInfo]" id="troubleInfo' + mycount
			+ '" class="readOnlyTxtNormal" readOnly/>';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);

	reloadItemProduct();
	reloadItemCount();
}

	/**
	 * 删除行
	 */

function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		var itembody = document.getElementById('itembody');
		itembody.deleteRow(rowNo);
		$(obj).parent().append('<input type="hidden" name="accessorder[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo + '" />');
		reloadItemCount();
	}
}
	/**
	 * 初始化设置清单
	 */
function reloadItems() {
	var itemscount = $('#itemscount').val();
	$("#itembody").empty();
	$('#itemscount').val(0);
	addItems();
}

	/**
	 * 重新计算清单序列号
	 */
function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
		if ($(this).css("display") != "none") {
			$(this).children("td").eq(1).text(i);
			i++;

		}
	})
}


	/**
	 * 表单验证，验证从表数据是否为空，为空不能下达
	 */
function checkForm() {
	var table =document.getElementById("itembody");
	var rows = table.rows.length;

	$("#itembody").children("tr").each(function() {
		if ($(this).css("display") == "none") {
			rows--;
		}
	})
	if(rows == "0") {
		alert("没有设备信息不能下达");
		return false;
	}

}