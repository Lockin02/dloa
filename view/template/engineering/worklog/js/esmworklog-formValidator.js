$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
		var result = $.ajax({
				url: "?model=engineering_worklog_esmworklog&action=couldAddLogDate&executionDate="+$("#executionDate").val(),
				async: false
				}).responseText;
		if(result=="no"){
			alert("�����ܱ��Ѿ��ύ��ˣ���������д��־��")
			$("#executionDate").focus();
				return false;
		}
		}
	});



	$("#executionDate").formValidator({
		onshow : "��ѡ������",
		onfocus : "��ѡ������",
		oncorrect : "����������ںϷ�"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "������Ϸ�������,�����ڲ���Ϊ��"
	}); // .defaultPassed();
})