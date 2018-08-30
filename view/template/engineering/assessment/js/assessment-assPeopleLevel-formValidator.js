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

	$("#levelName").formValidator({
		onshow : "������ȼ�����",
		onfocus : "�ȼ���������2���ַ�,���50���ַ�",
		oncorrect : "������ĵȼ����ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�ȼ��������߲����пշ���"
		},
		onerror : "������ĵȼ�����,��ȷ��"
	});
	$("#auditName").formValidator({
		onshow : "��ѡ��ָ�������",
		onfocus : "�������룬��ѡ��",
		oncorrect : "��ѡ���ָ���������Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ��ָ�������"
	});


	$("#ratio").formValidator({
		onshow : "������ϵ�������֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ,����Ĭ��Ϊ0"
	}).inputValidator({
		min :0,
		type : "value",
		onerrormin : "�������ֵ������ڵ���0,�粻����Ĭ��Ϊ0",
		onerror : "������ϵ��(����)"
	});
})