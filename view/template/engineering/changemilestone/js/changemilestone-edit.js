$(document).ready(function(){
	//ǰ����̱�����
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
	//��ʼ����
	var actBeginDate = $("#actBeginDate");
	if(actBeginDate.val() == '0000-00-00' || actBeginDate.val() == ''){
		actBeginDate.val("");
		$("#planBeginDate").attr("class","txt").attr("readonly",false);
		$("#planBeginDateNeed").html("[*]");
	}else{
		//��̱�״̬
		var status = $("#status");
		status.attr('disabled',true).after("<input type='hidden' name='changemilestone[status]' value="+ status.val() + "/>");
	}

	//��������
	var actEndDate = $("#actEndDate");
	if(actEndDate.val() == '0000-00-00' || actEndDate.val() == ''){
		actEndDate.val("");
		$("#planEndDate").attr("class","txt").attr("readonly",false).bind("focus",function(){
			WdatePicker();
		});
		$("#planEndDateNeed").html("[*]");
	}
})