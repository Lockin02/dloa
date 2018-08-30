$(document).ready(function() {
	var memberObj = $("#memberName");
	if($("#isManager").val() == "0" || memberObj.val() == ""){
		//项目成员
		memberObj.yxselect_user({
			hiddenId : 'memberId',
			formCode : 'esmroleEdit',
			mode : 'check',
			event : {
				select : function(e, returnValue) {
					if (returnValue) {
						$("#maxfixedRateInfo").parents("tr").show();
						getMaxFixedRate();
					}
				}
			}
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
	//验证项目角色是否可编辑固定投入比例
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_role_esmrole&action=isEditFixedRate",
	    data: {'id' : $("#id").val()},
	    async: false,
	    success: function(data){
	    	//0为可编辑
	   		if(data == 0){
	   			$("#fixedRate").attr('class','txt');
	   			$("#fixedRate").attr('readonly',false);
	   			$("#fixedRateInfo").hide();	
			}
		}
	});
	//初始化可填最大固定投入比例
	getMaxFixedRate();
})
//表单验证
function checkform(){
	if($("#roleName").val() == ""){
		alert("角色名称不能为空");
		return false;
	}
	var rs = true;
	var memberId = $("#memberId").val();//当前成员id
	var orgMemberId = $("#orgMemberId").val();//原成员id
	var maxfixedRate = $("#maxfixedRate").val();//可填最大固定投入比例
	if(memberId != ""){	//成员名称不为空
		if(memberId != orgMemberId){
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
//			$.ajax({
//			    type: "POST",
//			    url: "?model=engineering_member_esmmember&action=memberCanSet",
//			    data: {"projectId" : $("#projectId").val() , "roleId" : $("#id").val() , "orgMemberId" : orgMemberId , 'memberId' : memberId},
//			    async: false,
//			    success: function(data){
//			   		if(data != "1"){
//			   			var rsObj = eval("(" + data + ")");
//						switch(rsObj.val){
//							case '2' :
//								alert(rsObj.member + '已经存在于项目中，不能继续添加'); rs = false; break;
//							case '3' :
//								alert(rsObj.member + '已存在相关项目任务，不能删除'); rs = false; break;
//							case '4' :
//								alert(rsObj.member + '已经录入项目日志，不能删除'); rs = false; break;
//							default : break;
//						}
//			   	    }
//				}
//			});
		}
		//验证可填最大固定投入比例
		if($("#fixedRate").val()<0 || ($("#fixedRate").val()-maxfixedRate)>0){
			alert("请输入0~"+maxfixedRate+"之间的数！");
			$("#fixedRate").val("").focus();
			rs = false;
		}	
	}

	return rs;
}
//获取可填最大固定投入比例
function getMaxFixedRate(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_role_esmrole&action=getMaxFixedRate",
	    data: {'projectId' : $("#projectId").val(),'memberId' : $("#memberId").val()},
	    async: false,
	    success: function(data){
	    	if(data){
		    	//成员名称不为空
		    	if($("#memberId").val()!=""){
		    		$("#maxfixedRateInfo").empty().append("可填固定投入比例最大为："+data+"%");
		    		$("#maxfixedRate").val(data);
		    	}else{
		    		$("#maxfixedRateInfo").parents("tr").hide();
		    	}
	    	}
		}
	});
}