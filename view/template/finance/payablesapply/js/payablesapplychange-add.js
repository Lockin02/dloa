$(function() {
	validate({
		"newAuditDate" : {
			required : true
		},
		"changeReason" : {
			required : true
		}
	})
});

function checkform(){
	if($("#newAuditDate").val() == $("#perAuditDate").val()){
		alert("�����������ڲ��޸ı䣡");
		return false;
	}else{
		return true;
	}
}