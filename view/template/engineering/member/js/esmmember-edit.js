
$(document).ready(function(){
	if($("#status").val() == 1){
		$("#statusName").val("�뿪��Ŀ");
	}else{
		$("#statusName").val("����");
	}
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"memberName" : {
			required : true
		}
	});

//	if($("#isManager").val() == "0"){
//		// ��̬��ӽ�ɫ����
//		$("#roleName").attr("class",'txt').yxcombogrid_esmrole({
//			hiddenId : 'roleId',
//			height : 250,
//			gridOptions : {
//				isShowButton : true,
//				showcheckbox : false,
//				param  : {'isLeaf' : 1,'bigID' : '0' , 'projectId' : $("#projectId").val(),'isManager' : 0},
//				event : {
//					'row_dblclick' : function(e, row, data) {
//						$("#activityName").val(data.activityName);
//						$("#activityId").val(data.activityId);
//					}
//				}
//			}
//		});
//	}
});