$(document).ready(function() {

	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		nameCol : 'productCode',
		isDown : true,
		width : 635,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
				}
			}
		}
	});

	$("#protocolTypeCode").change(function (){
		if ($(this).val() != 'XYLXJTJG') {
			$("input[id^='itemTable_cmp_lowerNum']").removeClass("validate[required,custom[onlyNumber]]");
			$("input[id^='itemTable_cmp_ceilingNum']").removeClass("validate[required,custom[onlyNumber]]");
		} else {
			$("input[id^='itemTable_cmp_lowerNum']").addClass("validate[required,custom[onlyNumber]]");
			$("input[id^='itemTable_cmp_ceilingNum']").addClass("validate[required,custom[onlyNumber]]");
		}
	});

});

function checkData() {
	//���佻���ж�
	var i = $("input[id^='itemTable_cmp_lowerNum']").size(), j, a = new Array(), k ,b = new Array();
	var startDate = new Array(), endDate = new Array();
	if (i <= 1) {
		var n ,m ,start ,end;
		n = parseInt($("#itemTable_cmp_lowerNum0").val());
		m = parseInt($("#itemTable_cmp_ceilingNum0").val());
		start = $("#itemTable_cmp_startValidDate0").val();
		end = $("#itemTable_cmp_validDate0").val();
		if (n > m) {
			alert("�������޴����������ޣ�");
			return false;
		}
		if (start > end) {
			alert("��ʼ��Ч�ڴ��ڽ�����Ч�ڣ�");
			return false;
		}
		return true;
	}
	for (j = 1; j <= i; j++) { //ѭ��ȡֵ
		a[j] = parseInt($("#itemTable_cmp_lowerNum" + (j-1)).val());
		b[j] = parseInt($("#itemTable_cmp_ceilingNum" + (j-1)).val());
		startDate[j] = $("#itemTable_cmp_startValidDate" + (j-1)).val();
		endDate[j] = $("#itemTable_cmp_validDate" + (j-1)).val();
	}
	for (j = 1; j < i; j++) { //ѭ���ж�
		for (k = j+1; k <= i; k++) {
			if (a[j] >= a[k] && a[j] <= b[k]) {
				alert("�������޺������������ص���");
				return false;
			}
			if (b[j] >= a[k] && b[j] <= b[k]) {
				alert("�������޺������������ص���");
				return false;
			}
			if (a[j] > b[j]){
				alert("�������޴����������ޣ�");
				return false;
			}
			if (startDate[j] > endDate[j]) {
				alert("��ʼ��Ч�ڴ��ڽ�����Ч�ڣ�");
				return false;
			}
		}
	}

	return true;
}
