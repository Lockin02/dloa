$(document).ready(function() {

			/**
			 * ��֤��Ϣ
			 */
			validate({
						"applyCode" : {
							required : true

						},
						"applyUserName" : {
							required : true

						}
					});
		})
/**
 * ��̬��Ӵӱ�����
 */
function addItems() {
	var mycount = parseInt($("#itemscount").val());
	var itemtable = document.getElementById("itembody");
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
	oTL2.innerHTML = '<input type="text"  name="reduceapply[items][' + mycount
			+ '][productCode]" id="productCode' + mycount
			+ '" class="readOnlyTxtShort"  readOnly/>'
			+ '<input type="hidden" name="reduceapply[items][' + mycount
			+ '][productId]" id="productId' + mycount + '"  />';
	var oTL3 = oTR.insertCell(3);
	oTL3.innerHTML = '<input type="text" readOnly name="reduceapply[items]['
			+ mycount + '][productName]" id="productName' + mycount
			+ '" class="readOnlyText" readOnly/>';
	var oTL4 = oTR.insertCell(4);
	oTL4.innerHTML = '<input type="text" name="reduceapply[items][' + mycount
			+ '][productType]" id="productType' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>'
			+ '<input type="hidden" name="reduceapply[items][' + mycount
			+ '][productTypeId]" id="productTypeId' + mycount + '"  />';;
	var oTL5 = oTR.insertCell(5);
	oTL5.innerHTML = '<input type="text" readOnly name="reduceapply[items]['
			+ mycount + '][pattern]" id="pattern' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>';
	var oTL6 = oTR.insertCell(6);
	oTL6.innerHTML = '<input type="text" readOnly name="reduceapply[items]['
			+ mycount + '][unitName]" id="unitName' + mycount
			+ '" class="readOnlyTxtShort" readOnly/>';
	var oTL7 = oTR.insertCell(7);
	oTL7.innerHTML = '<input type="text" readOnly name="reduceapply[items]['
			+ mycount + '][serilnoName]" id="serilnoName' + mycount
			+ '" class="readOnlyText" readOnly  />';
	var oTL8 = oTR.insertCell(8);
	oTL8.innerHTML = '<input type="text" name="reduceapply[items][' + mycount
			+ '][fittings]" id="fittings' + mycount
			+ '" class="readOnlyText" readOnly  />';
	var oTL9 = oTR.insertCell(9);
	oTL9.innerHTML = '<input type="text" name="reduceapply[items][' + mycount
			+ '][cost]" id="cost' + mycount
			+ '" class="readOnlyTxtShort formatMoney" readOnly />';
	var oTL10 = oTR.insertCell(10);
	oTL10.innerHTML = '<input type="text" name="reduceapply[items][' + mycount
			+ '][reduceCost]" id="reduceCost' + mycount
			+ '" class="txtshort formatMoney"  />';
	$("#itemscount").val(parseInt($("#itemscount").val()) + 1);

	// ��� ǧ��λ����
	formateMoney();
	reloadItemCount();
}

/**
 * ɾ����
 */
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="reduceapply[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
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
}

/**
 * ��֤��
 */
function checkForm() {
	var subReduceCostAmount = 0;
	var itembody = $("#itembody tr").size();
	for (var i = 0; i < itembody; i++) {
		if ($("#productId" + i).val() == "" && $("#isDelTag" + i).val() != 1) {
			alert("������Ϣ����Ϊ�գ���ѡ��...");
			return false;
		}

		if ($("#reduceCost" + i).val() != "" && $("#isDelTag" + i).val() != 1) {
			subReduceCostAmount += parseInt($("#reduceCost" + i).val());
		}
		if ((parseInt($("#cost" + i).val()) < parseInt($("#reduceCost" + i)
				.val()))  && $("#isDelTag" + i).val() != 1) {
			alert("������ò��ܴ�����ȡ����");
			return false;
		}
	}

	$("#subReduceCost").val(subReduceCostAmount);
	return true;
}

/**
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		if (checkForm()) {
			$("#form1").attr("action","?model=service_reduce_reduceapply&action=add&actType=audit");
			$("#form1").submit();
		}

	} else {
		return false;
	}
}