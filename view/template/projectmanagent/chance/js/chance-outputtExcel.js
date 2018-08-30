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

//导出商机跟踪信息
function exportExcel(){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var ids = $("#chanceId").val();
	if(beginDate && endDate != ""){
		if(beginDate > endDate){
			alert("开始时间不能大于结束时间");
			return false;
		}
	}else{
		alert("时间不能为空");
		return false;
	}
}