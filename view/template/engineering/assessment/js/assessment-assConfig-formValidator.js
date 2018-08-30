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
		onshow : "��������Ŀ����",
		onfocus : "��Ŀ��������2���ַ�,���50���ַ�",
		oncorrect : "����������ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "��Ŀ�������߲����пշ���"
		},
		onerror : "���������Ŀ����,��ȷ��"
	});

	$("#score").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "�������ֵ�����֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ,����Ĭ��Ϊ0"
	}).inputValidator({
		min :0,
		type : "value",
		onerrormin : "�������ֵ������ڵ���0,�粻����Ĭ��Ϊ0",
		onerror : "�������ֵ(����)"
	});// .defaultPassed();

})