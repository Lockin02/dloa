$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			return true;
		}
	});

	$("#name").formValidator({
		onshow : "������ƻ�����",
		onfocus : "�ƻ���������2���ַ�,���50���ַ�",
		oncorrect : "������ļƻ����ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�ƻ��������߲����пշ���"
		},
		onerror : "������ļƻ�����,��ȷ��"
	});
})