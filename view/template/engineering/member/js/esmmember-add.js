$(document).ready(function(){
	$("#memberName").yxselect_user({
		formCode : 'esmmeberAdd',
		hiddenId : 'memberId',
		event : {
			'select' : function(e, returnValue) {
				//判断项目中是否有该成员，有则清空
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_member_esmmember&action=checkMemberRepeat",
				    data: {"projectId" : $("#projectId").val() , 'memberId' : returnValue.val},
				    async: false,
				    success: function(data){
				   		if(data == '1'){
							alert('项目中已存在该成员，请选择其他成员');
							$("#memberName").val("");
							$("#memberId").val("");
				   	    }
					}
				});
			}
		}
	});

	/**
	 * 验证信息
	 */
	validate({
//		"roleName" : {
//			required : true
//		},
		"memberName" : {
			required : true
		}
	});

	// 动态人力预算
	$("#personLevel").yxcombogrid_eperson({
		hiddenId : 'personLevelId',
		width : 380,
		height : 250,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param  : {'status' : 0 ,'isLeaf' : 1},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#coefficient").val(data.coefficient);
					$("#price").val(data.price);

					calPerson();
				}
			}
		}
	});

	// 动态添加角色下拉
	$("#roleName").yxcombogrid_esmrole({
		hiddenId : 'roleId',
		height : 250,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param  : {'isLeaf' : 1,'bigID' : '0' , 'projectId' : $("#projectId").val()},
			event : {
				'row_dblclick' : function(e, row, data) {
//					$("#activityName").val(data.activityName);
//					$("#activityId").val(data.activityId);
				}
			}
		}
	});

	// 动态添加任务下拉
//	$("#activityName").yxcombogrid_esmactivity({
//		hiddenId : 'activityId',
//		isShowButton : false,
//		height : 250,
//		gridOptions : {
//			isShowButton : true,
//			isTitle : true,
//			param  : {'isLeaf' : 1,'bigID' : '0' , 'projectId' : $("#projectId").val()},
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#coefficient").val(data.coefficient);
//					$("#price").val(data.price);
//
//					calPerson();
//				}
//			}
//		}
//	});
});