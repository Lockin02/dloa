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
	$("#planName").formValidator({
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


	$("#planStartTime").formValidator({
		onshow : "��ѡ��ƻ���ʼ����",
		onfocus : "��ѡ������",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,����ʼ���ڲ���Ϊ��"
	}); // .defaultPassed();

	$("#planEndTime").formValidator({
		// forcevalid:true,
		// triggerevent:"change",
		onshow : "��ѡ���������",
		onfocus : "��ѡ�����ڣ�����С�ڼƻ���ʼ����Ŷ",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,���������ڲ���Ϊ��"
	}).compareValidator({
		desid : "planStartTime",
		operateor : ">=",
		onerror : "�������ڲ���С����ʼ����"
	}); // .defaultPassed();


	$("#sortNo").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "����������ţ����֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ"
	}).inputValidator({
		min : 1,
		type : "value",
		onerrormin : "�������ֵ������ڵ���1",
		onerror : "�����������(����)"
	});// .defaultPassed();
})