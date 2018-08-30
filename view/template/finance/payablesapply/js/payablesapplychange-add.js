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
		alert("审批付款日期并无改变！");
		return false;
	}else{
		return true;
	}
}