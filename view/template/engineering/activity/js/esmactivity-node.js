$(document).ready(function() {
	/**
	 * 验证信息
	 */
	validate({
		"activityName" : {
			required : true
		},
		"workRate" : {
			required : true,
			custom : ['percentageNum']
		}
	});
});

//验证最大
function checkform(){
	var workRate = $("#workRate").val();
	var canUseWorkRate = $("#canUseWorkRate").val();
	if(workRate* 1 > canUseWorkRate *1){
		alert('所填工作占比大于可用工作占比，请重新修改');
		return false;
	}
	return true;
}