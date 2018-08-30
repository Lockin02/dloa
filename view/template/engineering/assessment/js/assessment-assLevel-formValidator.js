$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("����ɹ�,ȷ��������?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	$("#name").formValidator({
		onshow : "������ȼ�����",
		onfocus : "������ȼ�����",
		oncorrect : "������ĵȼ����ƿ���"
	}).inputValidator({
		min : 1,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�ȼ��������߲����пշ���"
		},
		onerror : "������ĵȼ�����,��ȷ��"
	});

	$("#score").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "�������ֵ�����֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ,����Ĭ��Ϊ0"
	}).inputValidator({
		min : -50,
		type : "value",
		onerrormin : "�������ֵ������ڵ���0,�粻����Ĭ��Ϊ0",
		onerror : "�������ֵ(����)"
	});// .defaultPassed();

})