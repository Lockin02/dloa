var allSelect = {};// ����ѡ���� key��id value��parentId
var seletedItem = {};// ����ѡ���� radio key:name checkbox key:id value:id
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
Array.prototype.in_array = function(e) { // �ж�һ��Ԫ���Ƿ�����������
	for (i = 0; i < this.length; i++) {
		if (this[i] == e)
			return true;
	}
	return false;
}

/**
 * ѡ��ѡ��Ŀ
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
	// ������ʾ���� ����parentId��ѡ�е��������
	for (var key in allSelect) {// ѭ������ѡ����ж���parentId�Ƿ���ѡ������
		var parentId = allSelect[key];
		var parentIdArr = parentId.split("_");// or �Ĺ�ϵ

		for (var i = 0; i < parentIdArr.length-1; i++) {
			var showNum = 0;
			var pArr = parentIdArr[i].split("/");// and �Ĺ�ϵ
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
 * �鿴��ϸ
 */
function toViewTip(id) {
	showThickboxWin("?model=goods_goods_properties&action=toViewTip&id=" + id
			// + "&docType="
			// + row.docType
			// + "&skey="
			// + row['skey_']
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");

}
