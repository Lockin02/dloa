$(function() {
	var modelArr = {
		sale : "销售与合同",
		delivery : "发货与邮寄",
		production : "生产",
		finance : "财务",
		stock : "仓存"
	};
	$.ajax({
		url : '?model=customer_customer_customer&action=getCustomerRelationArr',
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
				if (confirm("确认更新该客户选中关联的业务信息?执行此操作不可恢复,建议先做好数据库备份.")) {
					$("#customerForm").submit();
				}
			});

});


function check() {
	if ($('#customerName').val() == '') {
		$('#nameTip').html('客户名称不能为空！');
		return false;
	} else if ($('#Name').val() != '') {
		var param = {
			model : 'customer_customer_customer',
			action : 'checkRepeat',
			ajaxCusName : $('#customerName').val()
		};
		if ($("#customerId").val() != '') {
			param.id = $("#customerId").val();
		}
		var data = $.ajax({
					async : false,
					url : 'index1.php',
					data : param
				}).responseText;
		if (data == '1') {
			$('#nameTip').html('已存在的客户名称！');
			return false;
		} else {
			$('#nameTip').html('客户名称可用！');
		}
	}
	return true;
}
