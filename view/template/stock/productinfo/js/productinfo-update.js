$(function() {
	var modelArr = {
		sale : "�������ͬ",
		delivery : "�������ʼ�",
		production : "����",
		finance : "����",
		stock : "�ִ�",
		purch : "�ɹ�"
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
					alert("������ѡ��һ��ҵ�����.");
					return false;
				}
				if (confirm("ȷ�ϸ��¸�����ѡ�й�����ҵ����Ϣ?ִ�д˲������ɻָ�,�������������ݿⱸ��.")) {
					$("#productinfoForm").submit();
				}
			});

});

function check() {
	if ($('#productName').val() == '') {
		$('#nameTip').html('�������Ʋ���Ϊ�գ�');
		return false;
	} else if ($('#productCode').val() == '') {
		$('#codeTip').html('���ϱ��벻��Ϊ�գ�');
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
			$('#codeTip').html('�Ѵ��ڵ����ϱ��룡');
			return false;
		} else {
			$('#codeTip').html('���ϱ�����ã�');
		}
	}
	return true;
}