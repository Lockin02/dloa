 function checkData(){
	if($("#weekEndDate").val()!=''&&$("#weekBeginDate").val()!='')
    if($("#weekEndDate").val()<$("#weekBeginDate").val()){
    	alert("开始时间必须小于介绍时间");
    	$("#weekEndDate").val("");
    	return false;
    }
 }
$(function(){
	$("#projectName").yxcombogrid_esmproject({
		hiddenId : 'projectId',
		nameCol : 'projectName',
		isShowButton : false,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);
				}
			}
		}
	});
});