// 保存选择配置
function saveSetting() {

	// 获取值
	var goodsValue = getGoodsValue();

	// 传递到后台的数组
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
				alert('保存成功！');

				parent.opener.reloadCache(data, $("#componentId").val(), $("#rowNum").val());

				window.close();
			} else {
				alert('保存错误！');
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