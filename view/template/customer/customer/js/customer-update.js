$(function() {
	var modelArr = {
		sale : "�������ͬ",
		delivery : "�������ʼ�",
		production : "����",
		finance : "����",
		stock : "�ִ�"
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
					alert("������ѡ��һ��ҵ�����.");
					return false;
				}
				if (confirm("ȷ�ϸ��¸ÿͻ�ѡ�й�����ҵ����Ϣ?ִ�д˲������ɻָ�,�������������ݿⱸ��.")) {
					$("#customerForm").submit();
				}
			});

});


function check() {
	if ($('#customerName').val() == '') {
		$('#nameTip').html('�ͻ����Ʋ���Ϊ�գ�');
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
			$('#nameTip').html('�Ѵ��ڵĿͻ����ƣ�');
			return false;
		} else {
			$('#nameTip').html('�ͻ����ƿ��ã�');
		}
	}
	return true;
}
