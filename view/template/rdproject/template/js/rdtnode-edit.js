$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			return true;
		}
	});

	$("#nodeName").formValidator({
		onshow : "������ڵ�����",
		onfocus : "�ڵ���������2���ַ�,���50���ַ�",
		oncorrect : "������Ľڵ����ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�ڵ��������߲����пշ���"
		},
		onerror : "������Ľڵ�����,��ȷ��"
	});
})