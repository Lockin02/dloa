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
	$("#name").formValidator({
		onshow : "��������������",
		onfocus : "������������2���ַ�,���100���ַ�",
		oncorrect : "��������������ƿ���"
	}).inputValidator({
		min : 2,
		max : 100,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�����������߲����пշ���"
		},
		onerror : "���������������,��ȷ��"
//	}).ajaxValidator({
//		type : "get",
//		url : "index1.php",
//		data : "model=rdproject_task_rdtask&action=ajaxTaskName",
//		datatype : "json",
//		success : function(data) {
//			if (data == "1") {
//				return true;
//			} else {
//				return false;
//			}
//		},
//		buttons : $("#submitSave"),
//		error : function() {
//			alert("������û�з������ݣ����ܷ�����æ��������");
//		},
//		onerror : "�����Ʋ����ã������",
//		onwait : "���ڶ���Ŀ���ƽ��кϷ���У�飬���Ժ�..."
	});
	$("#chargeName").formValidator({
		onshow : "��ѡ��������",
		onfocus : "�������룬��ѡ��",
		oncorrect : "��ѡ��ĸ�������Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ������"
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

	$("#appraiseWorkload").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "��������ƹ����������֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ"
	}).inputValidator({
		min : 1,
		type : "value",
		onerrormin : "�������ֵ������ڵ���1",
		onerror : "��������ƹ�����(����)"
	});// .defaultPassed();

	$("#nodeName1").formValidator({
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
	}).ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=rdproject_task_tknode&action=ajaxNodeName",
		datatype : "json",
		success : function(data) {
			if (data == "1") {
				return true;
			} else {
				return false;
			}
		},
		buttons : $("#submitSave"),
		error : function() {
			alert("������û�з������ݣ����ܷ�����æ��������");
		},
		onerror : "�����Ʋ����ã������",
		onwait : "���ڶԽڵ����ƽ��кϷ���У�飬���Ժ�..."
	});

	$("#charegeName").formValidator({
		onshow : "��ѡ��������",
		onfocus : "�������룬��ѡ��",
		oncorrect : "��ѡ��ĸ�������Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ������"
	});
	$("#informTime").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "������ȷ�Ϲ����������֣�",
		onfocus : "����������",
		oncorrect : "�������������ȷ"
	}).inputValidator({
		min : 1,
		type : "value",
		onerrormin : "�������ֵ������ڵ���1",
		onerror : "������ȷ�Ϲ�����(����)"
	});// .defaultPassed();



})