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
/**********************ɾ����̬��*************************/
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (var i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i - 1;
		}
	}
}
/**********************��Ŀ�б�*************************/
function dynamic_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i + 1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = '<select id="logic' + mycount + '" class="selectshort logic"  name="contract[' + mycount + '][logic]">' +
		'<option value="and">����</option>' +
		'<option value="or">����</option></select>';
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = '<select id="field' + mycount + '" class="selectmiddel field" name="contract[' + mycount + '][field]"> ' +
		'<option value="suppName" selected>��Ӧ��</option>' +
		'<option value="productName">��������</option>' +
		'<option value="productNumb">���ϴ���</option>' +
		'<option value="purchType">�ɹ�����</option>' +
		'<option value="createTime">��������</option>' +
		'<option value="moneyAll">�������</option>' +
		'<option value="sendName">ҵ��Ա</option>' +
		'<option value="hwapplyNumb">�������</option>' +
		'<option value="batchNumb">���κ�</option>' +
		'<option value="sourceNumb">������ͬ</option>' +
		'</select>';
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<select id="relation' + mycount + '" class="selectshort relation" name="contract[' + mycount + '][relation]"> ' +
		'<option value="in">����</option>' +
		'<option value="equal">����</option>' +
		'<option value="notequal">������</option>' +
		'<option value="greater">����</option>' +
		'<option value="less">С��</option>' +
		'<option value="notin">������</option>' +
		'</select>';
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = '<div  id="type' + mycount + '"><input type="text" class="txt values" id="values' + mycount + '" name="contract[' + mycount + '][values]" onblur="trimSpace(this);"></div>';
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
		* 1 + 1;
	//��ѯ�ֶ�ѡ����¼�
	$("#field" + mycount).bind("change", function (mycount) {
		return function () {
			if ($(this).val() == "purchType") {//�жϲ�ѯ�ֶ��Ƿ�Ϊ���ɹ����͡����������׷��ѡ���
				var tdHtml = '<select id="values' + mycount + '" class="select values"  name="contract[' + mycount + '][values]">' +
					'<option value="HTLX-XSHT">���ۺ�ͬ�ɹ�</option>' +
					'<option value="HTLX-FWHT">�����ͬ�ɹ�</option>' +
					'<option value="HTLX-ZLHT">���޺�ͬ�ɹ�</option>' +
					'<option value="HTLX-YFHT">�з���ͬ�ɹ�</option>' +
					'<option value="stock">����ɹ�</option>' +
					'<option value="assets">�ʲ��ɹ�</option>' +
					'<option value="rdproject">�з��ɹ�</option>' +
					'<option value="produce">�����ɹ�</option>' +
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
//���ݲ�ѯ�������в�ѯ
function toSupport() {
	var checkSup = true;
	$.each($('.values'), function () {
		if ($(this).val() == "") {
			alert("�������ѯֵ");
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

	// ������
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
//ȥ��ǰ��ո�
function trimSpace(obj) {
	var newVal = $.trim($(obj).val());
	$(obj).val(newVal);
}

