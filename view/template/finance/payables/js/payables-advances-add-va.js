$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onsuccess : function() {
			if (confirm("������ɹ�,ȷ���ύ��?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	/** ��Ӧ����֤* */
	$("#supplierName").formValidator({
		empty : false,
		onshow : "��ѡ��Ӧ��",
		onfocus : "ѡ��Ӧ��",
		oncorrect : "��ѡ���˹�Ӧ��"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ��Ӧ��"
	});

});