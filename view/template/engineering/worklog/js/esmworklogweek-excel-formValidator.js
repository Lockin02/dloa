$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("������ɹ�,ȷ������Excel��?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	$("#chargeName").formValidator({
		onshow : "��ѡ��������",
		onfocus : "�������룬��ѡ��",
		oncorrect : "��ѡ��ĸ�������Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ������"
	});

	$("#beginTime").formValidator({
		onshow : "��ѡ��ʼ����",
		onfocus : "��ѡ������",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,����ʼ���ڲ���Ϊ��"
	}); // .defaultPassed();

	$("#endTime").formValidator({
		// forcevalid:true,
		// triggerevent:"change",
		onshow : "��ѡ���������",
		onfocus : "��ѡ�����ڣ�����С�ڿ�ʼ����Ŷ",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,���������ڲ���Ϊ��"
	}).compareValidator({
		desid : "beginTime",
		operateor : ">=",
		onerror : "�������ڲ���С�ڿ�ʼ����"
	}); // .defaultPassed();

		$("#officeName").formValidator({
		onshow : "��ѡ����´�",
		onfocus : "��ѡ��",
		oncorrect : "��ѡ��İ��´���Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ����´�"
	});
})