$(document).ready(function(){
	$("#createName").yxselect_user({
		formCode : 'audit'
	});
})

function CheckForm(){
	if($("#beginDate").val() == ""){
		alert("��ʼʱ�䲻��Ϊ��");
		return false;
	}
	if($("#endDate").val() == ""){
		alert("����ʱ�䲻��Ϊ��");
		return false;
	}
	if($("#createName").val() == ""){
		alert("��д�˲���Ϊ��");
		return false;
	}
	if(confirm('ȷ��ȡ�������?')){
		return true;
	}
	else{
		return false;
	}
}
