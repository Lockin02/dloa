$(document).ready(function() {
	var idArr = [];
	var nameArr = [];
	//员工
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		formCode : 'tutor',
		event : {
			"select" : function(obj,row){
				$.ajax({
					type: "POST",
					url: "?model=hr_personnel_personnel&action=getPersonnelInfo",
					async: false,
					data: {"userAccount" :row.val },
					success: function(data){
			   			var dataObj = eval("(" + data +")");
						$("#userNo").val(dataObj.userNo);
						$("#deptId").val(dataObj.belongDeptId);
						$("#deptName").val(dataObj.belongDeptName);
						$("#jobId").val(dataObj.jobId);
						$("#jobName").val(dataObj.jobName);
					}
				});
				//从新配置邮件收件人
				setMail();
			}
		}
//        isOnlyCurDept : true
	});
    //直接上级
	$("#studentSuperior").yxselect_user({
		hiddenId : 'studentSuperiorId',
		formCode : 'tutorSup',
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