// ����ѡ������
function saveSetting() {
	//��Ʒ��ѡ��֤
	var checkResult = checkForm();
	if(checkResult == false){
		return false;
	}
	// ��ȡֵ
	var goodsValue = getGoodsValue();
	var goodsValueChanged = $.obj2json(goodsValue);

	if(goodsValueChanged == '{}'){
		alert('����ѡ��һ��');
		return false;
	}

	//��ȡҳ��
	var goodsHtml = $("#settingInfo").formhtml();

	// ���ݵ���̨������
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