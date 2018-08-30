// 保存选择配置
function saveSetting() {
	//产品必选验证
	var checkResult = checkForm();
	if(checkResult == false){
		return false;
	}
	// 获取值
	var goodsValue = getGoodsValue();
	var goodsValueChanged = $.obj2json(goodsValue);

	if(goodsValueChanged == '{}'){
		alert('至少选中一项');
		return false;
	}

	//获取页面
	var goodsHtml = $("#settingInfo").formhtml();

	// 传递到后台的数组
	var dataJson = {
		goodsId : $("#goodsId").val(),
		goodsName : $("#goodsName").val(),
		goodsCache : goodsHtml,
		id : $("#cacheId").val(),
		goodsValue : goodsValueChanged
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