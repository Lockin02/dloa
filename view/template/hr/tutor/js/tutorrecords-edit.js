$(document).ready(function() {
	//员工
//	$("#userName").yxselect_user({
//		hiddenId : 'userAccount',
//		userNo : 'userNo',
//		isGetDept : [true, "deptId", "deptName"],
//		isGetJob : [true, "jobId", "jobName"],
//		formCode : 'tutor',
//		event : {
//			"select" : function(obj,row){
//				//从新配置邮件收件人
//				setMail();
//			}
//		}
//	});

	//直接上级
	$("#studentSuperior").yxselect_user({
		hiddenId : 'studentSuperiorId',
		event : {
			"select" : function(obj,row){
				//从新配置邮件收件人
				setMail();
			}
		}
	});

	// 验证信息
	validate({
		"userName" : {
			required : true
		},
		"studentSuperior" : {
			required : true
		},
		"beginDate" : {
			required : true
		}
	});

	//邮件发送人
	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID',
			formCode : 'tutor'
		});
	}

	//邮件抄送人
	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'tutor'
		});
	}

	//初始化邮件接收人
	setMail();
})

//配置邮件收件人
function setMail(){
	//初始化邮件内容缓存
	var idArr = [];
	var nameArr = [];

	//导师部分
	var userName = $("#userName").val();
	if(userName){
		var userAccount = $("#userAccount").val();

		idArr.push(userAccount);
		nameArr.push(userName);
	}

	//学员部分
	var studentName = $("#studentName").val();
	if(studentName){
		var studentAccount = $("#studentAccount").val();

		idArr.push(studentAccount);
		nameArr.push(studentName);
	}

	//上级部分
	var studentSuperior = $("#studentSuperior").val();
	if(studentSuperior){
		var studentSuperiorId = $("#studentSuperiorId").val();

		idArr.push(studentSuperiorId);
		nameArr.push(studentSuperior);
	}

	$("#TO_NAME").val(nameArr.toString());
	$("#TO_ID").val(idArr.toString());
}