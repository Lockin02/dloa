$(document).ready(function() {

	//ְλѡ��
	$("#afterPositionName").yxcombogrid_position({
		hiddenId : 'afterPositionId',
		width:350,
		width:350,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//��ѡ��Ա�ȼ�
	$("#personLevel").yxcombogrid_eperson({
		hiddenId : 'personLevelId',
		gridOptions : {
			showcheckbox : false
		}
	});
});


//����֤
function checkForm(){

	//ת������
	if($('#permanentDate').val() ==��""){
		alert('����дת������');
		return false;
	}

	if($('#afterSalary').val() ==��""){
		alert('����дת������');
		return false;
	}

	if($('#hrSalary').val() ==��""){
		alert('����д���½��鹤��');
		return false;
	}

	if($('#afterPositionName').val() ==��""){
		alert('����дת����ְλ');
		return false;
	}

//	if($('#levelName').val() ==��""){
//		alert('����дת����ְ��');
//		return false;
//	}

	if($('#personLevel').val() ==��""){
		alert('����д��Ա�����ȼ�');
		return false;
	}

	return true;
}

//�༭ҳ - �ύ����
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_trialplan_trialdeptsuggest&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_trialplan_trialdeptsuggest&action=edit";
	}
}
