//标记提交类型。为“提交审核”添加一个识别参数app
function comitToApp(){
	document.getElementById('form1').action = "index1.php?model=engineering_serviceContract_serviceContract&action=addContract&act=app";
}


$(document).ready(function(){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){}
	});

	$("#name").formValidator({
		onshow : "请输入服务合同名称",
		onfocus : "服务合同名称至少2个字符，最多50个字符",
		oncorrect : "OK"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "服务合同名称两边不能有空符号"
		}
//		onerror : "请检查您输入的服务合同名称"
	});
	});


function ajaxCode(){
	if ($('#orderCode').val() == '') {
//		$('#icon').html('不能为空！');
//		$("#orderCode").focus();
	} else if ($('#orderCode').val() != '') {
		var param = {
			model : 'engineering_serviceContract_serviceContract',
			action : 'ajaxCode',
			ajaxOrderCode : $('#orderCode').val()
		};
		if ($("#contractID").val() != '') {
			param.id = $("#contractID").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon').html('合同号已存在！');
						$("#orderCode").focus();
					} else {
						$('#icon').html('√');
					}
				})
	}
}

function ajaxTempCode(){
	if ($('#orderTempCode').val() == '') {
//		$('#icon1').html('不能为空！');
//		$("#orderTempCode").focus();
	} else if ($('#orderTempCode').val() != '') {
		var param = {
			model : 'engineering_serviceContract_serviceContract',
			action : 'ajaxTempCode',
			ajaxOrderTempCode : $('#orderTempCode').val()
		};
		if ($("#contractID").val() != '') {
			param.id = $("#contractID").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon1').html('合同号已存在！');
						$("#orderTempCode").focus();
					} else {
						$('#icon1').html('√');
					}
				})
	}
}