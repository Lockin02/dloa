$(function(){
	
	validate({
		"beginDate" : {
			required : true
		},
		"endDate" : {
			required : true
		}
	});
	
});

//�����̻�������Ϣ
function exportExcel(){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var ids = $("#chanceId").val();
	if(beginDate && endDate != ""){
		if(beginDate > endDate){
			alert("��ʼʱ�䲻�ܴ��ڽ���ʱ��");
			return false;
		}
	}else{
		alert("ʱ�䲻��Ϊ��");
		return false;
	}
}