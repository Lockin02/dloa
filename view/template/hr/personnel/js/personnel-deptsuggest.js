
$(document).ready(function() {

	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});

	//ְλѡ��
	$("#afterPositionName").yxcombogrid_position({
		hiddenId : 'afterPositionId',
		width:350,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//��ѡ��Ա�ȼ�
	$("#personLevel").yxcombogrid_eperson({
		hiddenId : 'personLevelId',
		width : 350,
		gridOptions : {
			showcheckbox : false
		}
	});
});


//���Ž���ı��
function changeSuggest(thisVal){
	//���Ž�����Ϣ
//	var deptSuggest = $("#deptSuggest").val();
//	if($("#deptSuggest").val() == 'HRBMJY-03'){
//		$(".deptsuggestDismiss").show();
//		$(".deptsuggestPositive").hide();
//	}else{
//		$(".deptsuggestDismiss").hide();
//		$(".deptsuggestPositive").show();
//	}
}

//����֤
function checkForm(){
	if($('#deptSuggest').val() ==��"HRBMJY-00"){
		alert('����Ϊ����ʱ�����ύ���Ž���');
		return false;
	}

	if($('#suggestion').val() ==��""){
		alert('����д��������');
		return false;
	}

	var deptSuggest = $("#deptSuggest").val();
	//�޽���
	if(deptSuggest == 'HRBMJY-00'){
		alert('��ѡ��һ�����Ž���');
		return false;
	}

	//ת������
	if(deptSuggest == 'HRBMJY-01' || deptSuggest == 'HRBMJY-02'){
		if($('#permanentDate').val() ==��""){
			alert('����дת������');
			return false;
		}

		if($('#afterSalary').val() ==��""){
			alert('����дת������');
			return false;
		}

//		if($('#afterPositionName').val() ==��""){
//			alert('����дת����ְλ');
//			return false;
//		}

		if($('#levelName').val() ==��""){
			alert('����дת����ְ��');
			return false;
		}

		if($('#personLevel').val() ==��""){
			alert('����д��Ա�����ȼ�');
			return false;
		}
	}
	return true;
}


//���� - �ύ����
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_personnel_personnel&action=deptSuggest&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_personnel_personnel&action=deptSuggest";
	}
}
