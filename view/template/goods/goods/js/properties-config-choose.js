// ����ѡ������
function saveSetting() {

	// ��ȡֵ
	var goodsValue = getGoodsValue();

	// ���ݵ���̨������
	dataJson = {
		"goodsId" : $("#goodsId").val(),
		"goodsName" : $("#goodsName").val(),
		// "goodsCache" : $("#settingInfo").formhtml(),
		"goodsValue" : $.obj2json(goodsValue)
	};

	$.showDump(dataJson);

	var productInfoObj = parent.$("#" + $("#productInfoId").val());

	$.ajax({
				type : "POST",
				url : "?model=goods_goods_goodscache&action=saveCache",
				data : {
					"dataArr" : dataJson
				},
				async : false,
				success : function(data) {
					if (data != 0) {
						alert('����ɹ���');
						productInfoObj.val(data);
						closeFun();
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
	$(".tipTrigger").each(function() {
		if ($(this).attr("checked")) {
			$(this).click();
		}
	})
});