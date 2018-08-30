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
	//区间交叉判断
	var i = $("input[id^='itemTable_cmp_lowerNum']").size(), j, a = new Array(), k ,b = new Array();
	var startDate = new Array(), endDate = new Array();
	if (i <= 1) {
		var n ,m ,start ,end;
		n = parseInt($("#itemTable_cmp_lowerNum0").val());
		m = parseInt($("#itemTable_cmp_ceilingNum0").val());
		start = $("#itemTable_cmp_startValidDate0").val();
		end = $("#itemTable_cmp_validDate0").val();
		if (n > m) {
			alert("数量下限大于数量上限！");
			return false;
		}
		if (start > end) {
			alert("开始有效期大于结束有效期！");
			return false;
		}
		return true;
	}
	for (j = 1; j <= i; j++) { //循环取值
		a[j] = parseInt($("#itemTable_cmp_lowerNum" + (j-1)).val());
		b[j] = parseInt($("#itemTable_cmp_ceilingNum" + (j-1)).val());
		startDate[j] = $("#itemTable_cmp_startValidDate" + (j-1)).val();
		endDate[j] = $("#itemTable_cmp_validDate" + (j-1)).val();
	}
	for (j = 1; j < i; j++) { //循环判断
		for (k = j+1; k <= i; k++) {
			if (a[j] >= a[k] && a[j] <= b[k]) {
				alert("数量下限和上限区间有重叠！");
				return false;
			}
			if (b[j] >= a[k] && b[j] <= b[k]) {
				alert("数量下限和上限区间有重叠！");
				return false;
			}
			if (a[j] > b[j]){
				alert("数量下限大于数量上限！");
				return false;
			}
			if (startDate[j] > endDate[j]) {
				alert("开始有效期大于结束有效期！");
				return false;
			}
		}
	}

	return true;
}
