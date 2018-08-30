$(document).ready(function() {
	//面试官
	/*$("#interviewer").yxselect_user({
		hiddenId : 'interviewerId',
		formCode : 'interviewer'
	});
	//用人部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	//应聘职位
	$("#positionsName").yxcombogrid_position({
		hiddenId : 'positionsId',
		width:300
	});*/

	validate({
				"interviewEva" : {
					required : true
				},
				"interviewer" : {
					required : true
				}
			});

	$("#interviewer").yxcombogrid_interviewer({
		hiddenId : 'interviewerId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'parentId' : $("#invitationId").val()
			},
			event : {
				'row_dblclick' : function() {
					$.ajax({
						url : '?model=hr_recruitment_interviewer&action=listJson',
					    async: true,
						success : function (data){
							data = eval( "(" + data + ")" );
							//$.showDump(data);
							for(var i=0;i<data.length;i++){
								if(data[i].id==$("#interviewerId").val()){
									$("#interviewerId").val(data[i].interviewerId);
									$("#interviewerType").val(data[i].interviewerType);
								}
							}
						}
					});
//					alert($("#interviewerType").val());
					$.ajax({
						url : '?model=hr_recruitment_interviewComment&action=listJson',
						success : function(data){
							data = eval( "(" + data + ")" );
							$("#useWriteEva").val('');
							$("#interviewEva").val('');
							for(var i=0;i<data.length;i++){
								if($("#invitationId").val()==data[i].invitationId){
									if(data[i].interviewer==$("#interviewer").val()){
										$("#useWriteEva").val(data[i].useWriteEva);
										$("#interviewEva").val(data[i].interviewEva);
									}
								}
							}
						}
					});
				}
			}
		}
	});
})