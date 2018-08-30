$(document).ready(function() {
	var memberObj = $("#memberName");
	if($("#isManager").val() == "0" || memberObj.val() == ""){
		//项目成员
		memberObj.yxselect_user({
			hiddenId : 'memberId',
			formCode : 'esmroleEdit',
			mode : 'check'
		});
	}else{
		//项目经理
		memberObj.attr('class','readOnlyTxtMiddleLong');
		$("#roleName").attr('class','readOnlyTxtNormal');
		$("#roleName").attr('readonly',true);
		$("#appendInfo").show();
	}

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

//表单验证
function checkform(){
	if($("#roleName").val() == ""){
		alert("角色名称不能为空");
		return false;
	}

	var rs = true;
	var memberId = $("#memberId").val();
	if(memberId !=""){
		$.ajax({
			type: "POST",
		    url: "?model=engineering_member_esmmember&action=checkHasManager",
		    data: {"projectId" : $("#projectId").val(), 'memberId' : memberId},
		    async: false,
		    success: function(result){
		    	if(result == 1){
		    		alert('不能把项目经理设置其他成员角色');
		    		rs = false;
		    	}
		    }
		});
//		$.ajax({
//		    type: "POST",
//		    url: "?model=engineering_member_esmmember&action=memberIsExsist",
//		    data: {"projectId" : $("#projectId").val() , 'memberId' : memberId},
//		    async: false,
//		    success: function(data){
//		   		if(data != "0"){
//					alert(data + '已经存在于项目中，不能继续添加');
//					rs = false;
//		   	    }
//			}
//		});
	}
	return rs;
}