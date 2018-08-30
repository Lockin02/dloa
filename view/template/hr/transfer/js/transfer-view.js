$(document).ready(function() {

	if($("#actType").val()!=""){
			$("#closebtn").hide();
	}

	$(window.parent.document.getElementById("sub")).bind("click",function(){
		if ($("#transferMessage").is(":hidden")) {
			$("#reportDate").val("");
			$("#handoverDate").val("");
			$("#handoverRemark").val("");
		}else {
			if (!$("#reportDate").val()) {
				alert("Ԥ�Ƶ��¸�λ�������ڲ���Ϊ�գ�");
				return false;
			}
			if (!$("#handoverDate").val()) {
				alert("Ԥ�ƹ�����ɹ����������ڲ���Ϊ�գ�");
				return false;
			}
			if (!$("#handoverRemark").val()) {
				alert("��Ҫ�������ݲ���Ϊ�գ�");
				return false;
			}
		}

		$.ajax({
            type: "POST",
            url: "?model=hr_transfer_transfer&action=updateData",
            data : {
            	'id' : $("#id").val(),
            	'reportDate' : $("#reportDate").val(),
            	'handoverDate' : $("#handoverDate").val(),
            	'handoverRemark' : $("#handoverRemark").val()
            	},
            async: false
        });
	});

	$(window.parent.document.getElementsByName("result")).change(function (){
		if ($(this).val()=='ok') {
			$('#transferMessage').show();
		} else {
			$('#transferMessage').hide();
		}
	});

	validate({
		"reportDate" : {
			required : true
		},
		"handoverDate" : {
			required : true
		},
		"handoverRemark" : {
			required : true
		}
	});

});