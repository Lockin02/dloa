$(function(){
	$("#thisMonth").val($("#sysMonth").val());
})

//�ж��Ƿ�����ͬ���ݣ��еĻ����ش���
function isCanCreate(){
	var isTrue = 0;
	if(confirm('ȷ��������')){
		$.ajax({
		    type: "POST",
		    url: "?model=finance_period_period&action=isExistPeriod",
		    data: {
		    	"year" : $("#thisYear").val(),
				"businessBelong" : $("#thisBusinessBelong").val()
		    },
		    async: false,
		    success: function(data){
		   	   if(data == 1){
		   	   		isTrue = 1;
					return false;
				}else{
					isTrue = 0;
					return false;
				}
			}
		});
		if(isTrue == 1){
			alert('���ô���,�Ѿ����ڲ���������');
			self.parent.tb_remove();
			return false;
		}
	}else{
		return false;
	}
}