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
		onshow : "����������",
		onfocus : "��������2���ַ�,���50���ַ�",
		oncorrect : "����������ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������߲����пշ���"
		},
		onerror : "�����������,��ȷ��"
	});

	$("#sortNo").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "����������ţ����֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ"
	}).inputValidator({
		min : 0,
		type : "value",
		onerrormin : "�������ֵ������ڵ���1",
		onerror : "�����������(����)"
	});// .defaultPassed();
})