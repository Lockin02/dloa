$(function(){
	$("#handoverTip").hide();
	$("#leaveDateId").hide();

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
					hiddenId : 'handitemList_cmp_recipientId' + rowNum
				});
			}
		},{
			display : '接收人Id',
			name : 'recipientId',
			type : 'hidden'
		}]
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
							hiddenId : 'handitemList_cmp_recipientId' + rowNum
						});
					}
				},{
					display : '接收人Id',
					name : 'recipientId',
					type : 'hidden'
				}]
			});
		} else {
			$("#comfirmQuitDate").val("");
			$("#salaryEndDate").val("");
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
							hiddenId : 'handitemList_cmp_recipientId' + rowNum
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
		"quitTypeCode" : {
			required : true
		}
	});
});

function toSubmit(){
	document.getElementById('form1').action="?model=hr_leave_leave&action=editType&actType=staff";
}

function sub(){
	if($("#quitTypeCode").val() != 'YGZTCZ'
			&& ($("#comfirmQuitDate").val() == '' || $("#salaryEndDate").val() == '')) {
		alert("请填写离职日期和工资结算截止日期");
		return false;
	} else {
		return true;
	}
}