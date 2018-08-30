function toMilestone(){
	var projectId = $("#projectId").val();
	var changeId = $("#changeId").val();
	var versionNo = $("#versionNo").val();
	if(changeId == ""){
		if(confirm('没有对应的变更申请单,在对里程碑变更前系统将自动生成一张申请单,确认要变更里程碑吗?')){
			//动态添加变更申请单 - 获取项目中的里程碑，存入变更表中
		    $.ajax({
				type : 'POST',
				url : '?model=engineering_changemilestone_changemilestone&action=addMileAndChange',
				data : {
					'projectId' : projectId
				},
				async : false,
				success : function(data) {
					var rtArr = eval( "(" + data + ")" );
					location.href = "?model=engineering_changemilestone_changemilestone&action=toChange&projectId=" + projectId
						+ "&changeId=" + rtArr.id
						+ "&versionNo=" + rtArr.versionNo
						;
//					self.location.reload();
					//清空预算的路径，用于重新刷新
					self.parent.$("#iframe1").attr('src','');
				}
			});
		}else{
			$("#milestoneChange").attr('checked',false);
		}
	}else{
		//获取项目中的里程碑，存入变更表中
	    $.ajax({
			type : 'POST',
			url : '?model=engineering_changemilestone_changemilestone&action=addMilestone',
			data : {
				'projectId' : projectId,
				'changeId' : changeId,
				'versionNo' : versionNo
			},
			async : false,
			success : function(data) {
//				location.href = "?model=engineering_changemilestone_changemilestone&changeId=" + changeId;
				self.location.reload();
				//清空预算的路径，用于重新刷新
				self.parent.$("#iframe1").attr('src','');
			}
		});
	}
}


$(function(){
	var projectId = $("#projectId").val();
	//动态添加变更申请单
    $.ajax({
		type : 'POST',
		url : '?model=engineering_change_esmchange&action=hasChangeProject',
		data : {
			'projectId' : projectId,
			'ExaStatus' : '待提交'
		},
		async : false,
		success : function(data) {
			if(data != 0){
				$("#changeId").val(data);
			}
		}
	});
});