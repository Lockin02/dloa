$(function() {
			// var assItemArr = $("#assitem").val().split(",");
			// for (var i = 0; i < assItemArr.length; i++) {
			// if (assItemArr[i] != "") {
			// $("#" + assItemArr[i]).attr("checked", true);
			// }
			//
			// }

		});

/**
 * 选中属性
 *
 * @param {}
 *            elId
 */
function checkAssPro(elId) {
	if ($(this).attr("chekced")) {
		$("#" + elId + "_body").show();
	} else {
		$("#" + elId + "_body").hide();
	}
}

/**
 * 动态添加选中数据
 */
function addItem() {
	var checkIdStr = "";
	var checkTipStr = "";
	$("input[type=checkbox]").each(function() {
				if ($(this).attr("checked")) {
					checkIdStr += $(this).attr("id") + "/";
					checkTipStr += $(this).attr("tip") + "/";
					$(this).attr("checked", false);
				}
			})
	if (checkIdStr != "") {
		var mycount = parseInt($("#coutNumb").val() * 1);
		var itemtable = document.getElementById("itemtable");
		i = itemtable.rows.length;
		oTR = itemtable.insertRow([i]);
		oTR.className = "TableData";
		oTR.align = "center";
		oTR.height = "28px";
		var oTL0 = oTR.insertCell([0]);
		oTL0.innerHTML = '<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">';
		var oTL1 = oTR.insertCell([1]);
		oTL1.innerHTML = checkTipStr
				+ '<input type="hidden" class="txtshort" tip="' + checkTipStr
				+ '" value="' + checkIdStr + '"  id="checkId' + mycount + '"/>';
		var oTL2 = oTR.insertCell([2]);
	} else {
		alert("请失少选中一项!")
	}

}

// 删除
function delItem(obj) {
	if (confirm('确定要删除这一选择？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().remove();
		// $(obj).parent().append('<input type="hidden" name="stockin[items]['
		// + rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo
		// + '" />');

	}
}
