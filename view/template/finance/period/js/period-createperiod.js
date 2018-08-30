$(function(){
	$("#thisMonth").val($("#sysMonth").val());
})

//判断是否有相同数据，有的话返回错误
function isCanCreate(){
	var isTrue = 0;
	if(confirm('确定启用吗？')){
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
			alert('设置错误,已经存在财务会计周期');
			self.parent.tb_remove();
			return false;
		}
	}else{
		return false;
	}
}