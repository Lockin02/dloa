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
	$("#assessmentName").formValidator({
		onshow : "��ѡ�񿼺���",
		onfocus : "�������룬��ѡ��",
		oncorrect : "��ѡ��Ŀ�������Ч"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ�񿼺���"
	});

})