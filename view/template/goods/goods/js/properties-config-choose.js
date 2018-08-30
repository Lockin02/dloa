// 保存选择配置
function saveSetting() {

	// 获取值
	var goodsValue = getGoodsValue();

	// 传递到后台的数组
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
						alert('保存成功！');
						productInfoObj.val(data);
						closeFun();
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
	$(".tipTrigger").each(function() {
		if ($(this).attr("checked")) {
			$(this).click();
		}
	})
});