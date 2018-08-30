var allSelect = {};// 所有选择项 key：id value：parentId
var seletedItem = {};// 所有选中项 radio key:name checkbox key:id value:id
$(function() {

			$("span.option").each(function() {
						var id = this.id;
						var parentId = $(this).attr('parentid');
						if (parentId != "none") {
							allSelect[id] = parentId;
						}
					});
			$(".tipTrigger").each(function() {
						// if($(this).attr("checked"))
						// alert($(this).attr("checked"))
						if ($(this).attr("checked")) {
							$(this).trigger("click");
						}
					});

		});
Array.prototype.in_array = function(e) { // 判断一个元素是否在数组里面
	for (i = 0; i < this.length; i++) {
		if (this[i] == e)
			return true;
	}
	return false;
}

/**
 * 选择选项目
 */
function checkedItem(el, elId, ckType) {

	// radio
	var group = $(el).parent("span").attr("group");
	$("span.option[parentId!='none'][group!='"+group+"']").hide();
	if (ckType == "0") {
		delete seletedItem[seletedItem[el.name]];
		seletedItem[el.name] = elId;
		seletedItem[elId] = elId;

		// checkbox
	} else {
		if ($(el).attr("checked")) {
			seletedItem[elId] = elId;
			// $("span[parentId*='-" + elId + "_,']").show();
		} else {
			delete seletedItem[elId];
			// $("span[parentId*='-" + elId + "_,']").hide();
		}

	}
	// 进行显示处理 规则：parentId在选中的项组合中
	for (var key in allSelect) {// 循环所有选择项，判断其parentId是否在选中项中
		var parentId = allSelect[key];
		var parentIdArr = parentId.split("_");// or 的关系

		for (var i = 0; i < parentIdArr.length-1; i++) {
			var showNum = 0;
			var pArr = parentIdArr[i].split("/");// and 的关系
			for (var j = 0; j < pArr.length-1; j++) {
				var p = pArr[j];

				if (seletedItem[p]) {
					showNum++;
				}
			}
			if (showNum == pArr.length-1) {
				$("#"+key).show();
				continue;
			}
		}

	}

}

/**
 * 查看明细
 */
function toViewTip(id) {
	showThickboxWin("?model=goods_goods_properties&action=toViewTip&id=" + id
			// + "&docType="
			// + row.docType
			// + "&skey="
			// + row['skey_']
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");

}
