$(function() {
	var modelArr = {
		goodsRequired : "产品需求",
		delivery : "发货与邮寄",
		production : "生产",
		finance : "财务",
		stock : "仓存",
		purch : "采购"
	};
	$
			.ajax({
				url : '?model=goods_goods_goodsbaseinfo&action=getGoodsRelationArr',
				success : function(data) {
					data = eval("(" + data + ")");
					for ( var key in data) {
						var dataArr = data[key];
						$("#objArr").append("</br>");
						$checkbox = $("<input type='checkbox' name=" + key
								+ "/>");
						$checkbox.click(function(key) {
							return function() {
								var checked = $(this).attr('checked');
								$("input[id^='" + key + "']").attr('checked',
										checked);
							}
						}(key));
						$("#objArr").append($checkbox);
						$checkbox.after("&nbsp;<b>" + modelArr[key]
								+ "&nbsp;&nbsp;</br>&nbsp;&nbsp;&nbsp;&nbsp;");
						for ( var k in dataArr) {
							$checkbox = $("<input type='checkbox' name='checked[]' id='"
									+ key + "_" + k + "' value='" + k + "'/>");

							$("#objArr").append($checkbox);
							$checkbox.after("&nbsp;" + dataArr[k][0]
									+ "&nbsp;&nbsp;");
						}
					}
				}
			});

	$("#saveButton").click(function() {
		if (!check()) {
			return false;
		}
		if ($("input:checked").size() == 0) {
			if (confirm("没有选择关联更新业务，是否确认更新？")) {
				$("#goodsForm").submit();
			} else {
				return false;

			}
		}
		if (confirm("确认更新该产品选中关联的业务信息?执行此操作不可恢复,建议先做好数据库备份.")) {
			$("#goodsForm").submit();
		}
	});

});


function check() {
	if ($('#goodsName').val() == '') {
		$('#nameTip').html('产品名称不能为空！');
		return false;
	} else if ($('#goodsName').val() != '') {
		var param = {
			model : 'goods_goods_goodsbaseinfo',
			action : 'checkRepeat',
			goodsNameEq : $('#goodsName').val()
		};
		if ($("#goodsId").val() != '') {
			param.id = $("#goodsId").val();
		}
		var data = $.ajax({
			async : false,
			url : 'index1.php',
			data : param
		}).responseText;
		if (data == '1') {
			$('#nameTip').html('已存在的产品名称！');
			return false;
		} else {
			$('#nameTip').html('产品名称可用！');
		}
	}
	return true;
}