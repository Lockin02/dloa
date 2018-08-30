$(function() {
	var modelArr = {
		goodsRequired : "��Ʒ����",
		delivery : "�������ʼ�",
		production : "����",
		finance : "����",
		stock : "�ִ�",
		purch : "�ɹ�"
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
			if (confirm("û��ѡ���������ҵ���Ƿ�ȷ�ϸ��£�")) {
				$("#goodsForm").submit();
			} else {
				return false;

			}
		}
		if (confirm("ȷ�ϸ��¸ò�Ʒѡ�й�����ҵ����Ϣ?ִ�д˲������ɻָ�,�������������ݿⱸ��.")) {
			$("#goodsForm").submit();
		}
	});

});


function check() {
	if ($('#goodsName').val() == '') {
		$('#nameTip').html('��Ʒ���Ʋ���Ϊ�գ�');
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
			$('#nameTip').html('�Ѵ��ڵĲ�Ʒ���ƣ�');
			return false;
		} else {
			$('#nameTip').html('��Ʒ���ƿ��ã�');
		}
	}
	return true;
}