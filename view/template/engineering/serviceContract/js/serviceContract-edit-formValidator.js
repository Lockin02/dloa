//����ύ���͡�Ϊ���ύ��ˡ����һ��ʶ�����app
function comitToApp(){
	document.getElementById('form1').action = "index1.php?model=engineering_serviceContract_serviceContract&action=addContract&act=app";
}


$(document).ready(function(){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){}
	});

	$("#name").formValidator({
		onshow : "����������ͬ����",
		onfocus : "�����ͬ��������2���ַ������50���ַ�",
		oncorrect : "OK"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�����ͬ�������߲����пշ���"
		}
//		onerror : "����������ķ����ͬ����"
	});
	});


function ajaxCode(){
	if ($('#orderCode').val() == '') {
//		$('#icon').html('����Ϊ�գ�');
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
						$('#icon').html('��ͬ���Ѵ��ڣ�');
						$("#orderCode").focus();
					} else {
						$('#icon').html('��');
					}
				})
	}
}

function ajaxTempCode(){
	if ($('#orderTempCode').val() == '') {
//		$('#icon1').html('����Ϊ�գ�');
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
						$('#icon1').html('��ͬ���Ѵ��ڣ�');
						$("#orderTempCode").focus();
					} else {
						$('#icon1').html('��');
					}
				})
	}
}