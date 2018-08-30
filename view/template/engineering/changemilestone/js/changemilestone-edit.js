$(document).ready(function(){
	//前置里程碑设置
	$("#preMilestoneName").yxcombogrid_milestonechange({
		hiddenId : 'changePreId',
		gridOptions : {
			param  : {
				'projectId' : $("#projectId").val(),
				'changeId' : $("#changeId").val()
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#changePreId").val(data.milestoneId)
				}
			}
		}
	});
	//开始日期
	var actBeginDate = $("#actBeginDate");
	if(actBeginDate.val() == '0000-00-00' || actBeginDate.val() == ''){
		actBeginDate.val("");
		$("#planBeginDate").attr("class","txt").attr("readonly",false);
		$("#planBeginDateNeed").html("[*]");
	}else{
		//里程碑状态
		var status = $("#status");
		status.attr('disabled',true).after("<input type='hidden' name='changemilestone[status]' value="+ status.val() + "/>");
	}

	//结束日期
	var actEndDate = $("#actEndDate");
	if(actEndDate.val() == '0000-00-00' || actEndDate.val() == ''){
		actEndDate.val("");
		$("#planEndDate").attr("class","txt").attr("readonly",false).bind("focus",function(){
			WdatePicker();
		});
		$("#planEndDateNeed").html("[*]");
	}
})