$(document).ready(function() {
	$("#handoverTip").hide();
	$("#leaveDateId").hide();

	//��ְ����
	quitTypeCodeArr = getData('YGZTLZ');
	addDataToSelect(quitTypeCodeArr, 'quitTypeCode');

	//Ա��
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
							hiddenId : 'handitemList_cmp_recipientId'+rowNum,
							event : {
								'select' : function(e, row, data) {
									setinfo();
								}
							}
						});
					}
				},{
					display : '������Id',
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
							hiddenId : 'handitemList_cmp_recipientId'+rowNum,
							event : {
								'select' : function(e, row, data) {
									setinfo();
								}
							}
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
			return false; //����ѭ��
		}
	});

	if(str == "") {
		alert("��ѡ����ְԭ��");
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
			alert("����д��ְ���ں͹��ʽ����ֹ����");
			return false;
		}else{
			if(!$("#projectManager").val()){
				if(confirm("��Ŀ������ĿΪ�գ��Ƿ������")){
					return true;
				}else{
					return false;
				}
			}
		}
	} else {
		alert("��Ա����ְ���뵥�Ѵ��ڣ�");
		return false;
	}
}

//ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=hr_leave_leave&action=add&actType=staff";
}