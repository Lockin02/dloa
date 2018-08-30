$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		}
//		,
//		onsuccess : function() {
//			if (confirm("�༭�ɹ�,ȷ��������?")) {
//				return true;
//			} else {
//				return false;
//			}
//		}
	});
	$("#name").formValidator({
		onshow : "����������������",
		onfocus : "��������������2���ַ�,���50���ַ�",
		oncorrect : "����������������ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������������߲����пշ���"
		},
		onerror : "�����������������,��ȷ��"
	});


	$("#startDate").formValidator({
		onshow : "��ѡ����ʼ����",
		onfocus : "��ѡ������",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,����ʼ���ڲ���Ϊ��"
	}); // .defaultPassed();

	$("#endDate").formValidator({
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
		desid : "startDate",
		operateor : ">=",
		onerror : "�������ڲ���С����ʼ����"
	}); // .defaultPassed();

})