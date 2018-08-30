$(function(){
	$("#handoverTip").hide();
	$("#leaveDateId").hide();

	$("#handitemList").yxeditgrid({
		objName : 'leave[handitem]',
		isAddOneRow : true,
		colModel : [{
			display : '�������豸��������',
			name : 'handContent',
			width : 500,
			type : 'txt'
		},{
			display : '������',
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
			display : '������Id',
			name : 'recipientId',
			type : 'hidden'
		}]
	});

	//ѡ�����Ͱ��¼�
	$("#quitTypeCode").bind('change', function() {//���ѡ�������Ϊ�Ǵ�ְ���ͣ�����Ҫ��д������
		if($(this).val()=='YGZTCZ'){//��ְ����
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
					display : '�������豸��������',
					name : 'handContent',
					width : 500,
					type : 'txt'
				},{
					display : '������',
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
					display : '������Id',
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
					display : '�������豸��������',
					name : 'handContent',
					width : 500,
					type : 'txt',
					validation : {
						required : true
					}
				},{
					display : '������',
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
					display : '������Id',
					name : 'recipientId',
					type : 'hidden'
				}]
			});
		}
	});

	// ��֤��Ϣ
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
		alert("����д��ְ���ں͹��ʽ����ֹ����");
		return false;
	} else {
		return true;
	}
}