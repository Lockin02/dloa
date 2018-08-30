$(function() {
	var modelArr = {
		sale : "销售与合同",
		delivery : "发货与邮寄",
		production : "生产",
		finance : "财务",
		stock : "仓存",
		purch : "采购"
	};
	$.ajax({
		url : '?model=stock_productinfo_productinfo&action=getProductInfoRelationArr',
		success : function(data) {
			data = eval("(" + data + ")");
			for (var key in data) {
				var dataArr = data[key];
				$("#objArr").append("</br>");
				$checkbox = $("<input type='checkbox' name=" + key + "/>");
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
				for (var k in dataArr) {
					$checkbox = $("<input type='checkbox' name='checked[]' id='"
							+ key + "_" + k + "' value='" + k + "'/>");

					$("#objArr").append($checkbox);
					$checkbox.after("&nbsp;" + dataArr[k][0] + "&nbsp;&nbsp;");
				}
			}
		}
	});

	$("#saveButton").click(function() {
				if(!check()){
					return false;
				}
				if ($("input:checked").size() == 0) {
					alert("请至少选择一个业务对象.");
					return false;
				}
				if (confirm("确认更新该物料选中关联的业务信息?执行此操作不可恢复,建议先做好数据库备份.")) {
					$("#productinfoForm").submit();
				}
			});

});

function check() {
	if ($('#productName').val() == '') {
		$('#nameTip').html('物料名称不能为空！');
		return false;
	} else if ($('#productCode').val() == '') {
		$('#codeTip').html('物料编码不能为空！');
		return false;
	} else if ($('#productCode').val() != '') {
		var param = {
			model : 'stock_productinfo_productinfo',
			action : 'checkRepeat',
			productCode : $('#productCode').val()
		};
		if ($("#productId").val() != '') {
			param.id = $("#productId").val();
		}
		var data = $.ajax({
					async : false,
					url : 'index1.php',
					data : param
				}).responseText;
		if (data == '1') {
			$('#codeTip').html('已存在的物料编码！');
			return false;
		} else {
			$('#codeTip').html('物料编码可用！');
		}
	}
	return true;
}