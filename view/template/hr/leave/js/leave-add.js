$(document).ready(function() {
	$("#handoverTip").hide();
	$("#leaveDateId").hide();

	//离职类型
	quitTypeCodeArr = getData('YGZTLZ');
	addDataToSelect(quitTypeCodeArr, 'quitTypeCode');

	//员工
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		formCode : 'userName',
		event :{
			'select' :  function(e, row, data){
				$.ajax({
					type : 'POST',
					url : '?model=hr_leave_leave&action=getPersonnelInfo',
					data:{
						userAccount : $("#userAccount").val()
					},
					async: false,
					success : function(data){
						var obj = eval("(" + data +")");
						$("#userNo").val(obj.userNo);
						$("#jobName").val(obj.jobName);
						$("#jobId").val(obj.jobId);
						$("#deptId").val(obj.belongDeptId);
						$("#deptName").val(obj.belongDeptName);
						$("#entryDate").val(obj.entryDate);
						$("#contractBegin").val(obj.beginDate);
						$("#contractEnd").val(obj.closeDate);
						$("#wageLevelCode").val(obj.wageLevelCode);
						$("#wageLevelName").val(obj.wageLevelName);
						$("#mobile").val(obj.mobile);
						$("#personEmail").val(obj.personEmail);
					}
				});
			}
		}
	});

	//选择类型绑定事件
	$("#quitTypeCode").bind('change', function() {//如果选择的类型为非辞职类型，则需要填写交接项
		if($(this).val()=='YGZTCZ'){//辞职类型
			$("#subAudit").show();
			$("#handoverTip").hide();
			$("#leaveDateId").hide();
			$("#handitemList").yxeditgrid('remove');
			$("#comfirmQuitDate").val("");
			$("#salaryEndDate").val("");
			$("#handitemList").yxeditgrid({
				objName : 'leave[handitem]',
				isAddOneRow : true,
				colModel : [{
					display : '工作及设备交接事项',
					name : 'handContent',
					width : 500,
					type : 'txt'
				},{
					display : '接收人',
					name : 'recipientName',
					type : 'text',
					readonly:true,
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxselect_user({
							hiddenId : 'handitemList_cmp_recipientId'+rowNum,
							event : {
								'select' : function(e, row, data) {
									setinfo();
								}
							}
						});
					}
				},{
					display : '接收人Id',
					name : 'recipientId',
					type : 'hidden'

				}]
			});
		} else{
			$("#comfirmQuitDate").val("");
			$("#salaryEndDate").val("");
//			$("#comfirmQuitDate").val($("#contractEnd").val(obj.closeDate));
//			$("#salaryEndDate").val($("#contractEnd").val(obj.closeDate));
			$("#subAudit").hide();
			$("#handoverTip").show();
			$("#leaveDateId").show();
			$("#handitemList").yxeditgrid('remove');
			$("#handitemList").yxeditgrid({
				objName : 'leave[handitem]',
				isAddOneRow : true,
				colModel : [{
					display : '工作及设备交接事项',
					name : 'handContent',
					width : 500,
					type : 'txt',
					validation : {
						required : true
					}
				},{
					display : '接收人',
					name : 'recipientName',
					type : 'text',
					readonly:true,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxselect_user({
							hiddenId : 'handitemList_cmp_recipientId'+rowNum,
							event : {
								'select' : function(e, row, data) {
									setinfo();
								}
							}
						});
					}
				},{
					display : '接收人Id',
					name : 'recipientId',
					type : 'hidden'
				}]
			});
		}
	});

	// 验证信息
	validate({
		"userName" : {
			required : true
		},
		"quitTypeCode" : {
			required : true
		}
	});
});

function sub(){
	var str = '';
	$("input[name^='leave[checkbox]']").each(function() {
		if($(this).attr("checked")) {
			str += $(this).val() + ",";
			return false; //跳出循环
		}
	});

	if(str == "") {
		alert("请选择离职原因！");
		return false;
	}

	if(!$("#comOther").hasClass('validate[required]')) {
		$("#comOther").val('');
	}

	var flag =  $.ajax({
		type : 'POST',
		url : '?model=hr_leave_leave&action=getLeaveInfo',
		data:{
			userAccount : $("#userAccount").val()
		},
		async: false,
		success : function(data){
			return data;
		}
	}).responseText;
	if(flag == '0') {
		if($("#quitTypeCode").val() != 'YGZTCZ'
				&& ($("#comfirmQuitDate").val() == '' || $("#salaryEndDate").val() == '')) {
			alert("请填写离职日期和工资结算截止日期");
			return false;
		}else{
			if(!$("#projectManager").val()){
				if(confirm("项目经理栏目为空，是否继续？")){
					return true;
				}else{
					return false;
				}
			}
		}
	} else {
		alert("该员工离职申请单已存在！");
		return false;
	}
}

//直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=hr_leave_leave&action=add&actType=staff";
}