$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("������ɹ�,ȷ��������?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	$("#stockCode").formValidator({
		onshow : "������ֿ���",
		onfocus : "�ֿ�������2���ַ�,���50���ַ�",
		oncorrect : "������ȷ"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�ֿ������߲����пշ���"
		},
		onerror : "������Ĳֿ���,��ȷ��"
	});
	$("#dealUserName").formValidator({
		onshow : "��ѡ�񾭰���",
		onfocus : "�������룬��ѡ��",
		oncorrect : "��ѡ��ľ�������Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ�񾭰���"
	});
	$("#auditUserName").formValidator({
		onshow : "��ѡ�������",
		onfocus : "�������룬��ѡ��",
		oncorrect : "��ѡ����������Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ�������"
	});
	$("#stockName").formValidator({
		onshow : "��ѡ��ֿ�",
		onfocus : "��ѡ��,��������",
		oncorrect : "��ѡ��Ĳֿ���Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ��ֿ�"
	});
})