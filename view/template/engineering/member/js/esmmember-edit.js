
$(document).ready(function(){
	if($("#status").val() == 1){
		$("#statusName").val("离开项目");
	}else{
		$("#statusName").val("正常");
	}
	/**
	 * 验证信息
	 */
	validate({
		"memberName" : {
			required : true
		}
	});

//	if($("#isManager").val() == "0"){
//		// 动态添加角色下拉
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