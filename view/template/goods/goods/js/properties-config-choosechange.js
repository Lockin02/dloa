// ����ѡ������
function saveSetting() {

	// ��ȡֵ
	var goodsValue = getGoodsValue();

	// ���ݵ���̨������
	var dataJson = {
		id : $("#cacheId").val(),
		goodsId : $("#goodsId").val(),
		goodsName : $("#goodsName").val(),
		goodsCache : $("#settingInfo").formhtml(),
		goodsValue : $.obj2json(goodsValue)
	};

	$.ajax({
		type : "POST",
		url : "?model=goods_goods_goodscache&action=saveCache",
		data : {
			dataArr : dataJson
		},
		async : false,
		success : function(data) {
			if (data != 0) {
				data = strTrim(data);
				alert('����ɹ���');

				parent.opener.reloadCache(data, $("#componentId").val(), $("#rowNum").val());

				window.close();
			} else {
				alert('�������');
			}
		}
	});
}

$(function() {
	$("span.option").each(function() {
		var id = this.id;
		var parentId = $(this).attr('parentid');
		if (parentId != "none") {
			allSelect[id] = parentId;
		}
	});
});