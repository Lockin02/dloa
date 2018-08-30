$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("������ɹ�,ȷ���ύ��?")) {
				return true;
			} else {
				return false;
			}
		}
	});
	$("#planBeginDate").formValidator({
		onshow : "��ѡ��ƻ���ʼ����",
		onfocus : "��ѡ������",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,���ƻ���ʼ���ڲ���Ϊ��"
	}); // .defaultPassed();

	$("#planEndDate").formValidator({
		// forcevalid:true,
		// triggerevent:"change",
		onshow : "��ѡ��ƻ��������",
		onfocus : "��ѡ�����ڣ�����С�ڼƻ���ʼ����Ŷ",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,���ƻ�������ڲ���Ϊ��"
	}).compareValidator({
		desid : "planBeginDate",
		operateor : ">=",
		onerror : "�ƻ�������ڲ���С�ڼƻ���ʼ����"
	}); // .defaultPassed();


})