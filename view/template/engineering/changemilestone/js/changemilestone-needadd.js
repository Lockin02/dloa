function toMilestone(){
	var projectId = $("#projectId").val();
	var changeId = $("#changeId").val();
	var versionNo = $("#versionNo").val();
	if(changeId == ""){
		if(confirm('û�ж�Ӧ�ı�����뵥,�ڶ���̱����ǰϵͳ���Զ�����һ�����뵥,ȷ��Ҫ�����̱���?')){
			//��̬��ӱ�����뵥 - ��ȡ��Ŀ�е���̱�������������
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
					//���Ԥ���·������������ˢ��
					self.parent.$("#iframe1").attr('src','');
				}
			});
		}else{
			$("#milestoneChange").attr('checked',false);
		}
	}else{
		//��ȡ��Ŀ�е���̱�������������
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
				//���Ԥ���·������������ˢ��
				self.parent.$("#iframe1").attr('src','');
			}
		});
	}
}


$(function(){
	var projectId = $("#projectId").val();
	//��̬��ӱ�����뵥
    $.ajax({
		type : 'POST',
		url : '?model=engineering_change_esmchange&action=hasChangeProject',
		data : {
			'projectId' : projectId,
			'ExaStatus' : '���ύ'
		},
		async : false,
		success : function(data) {
			if(data != 0){
				$("#changeId").val(data);
			}
		}
	});
});