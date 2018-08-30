$(document).ready(function() {


	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'cassessManager'
	});

	$("#memberName").yxselect_user({
		hiddenId : 'memberId',
		mode : 'check',
		formCode : 'cassessMember'
	});

	//ģ��
	$("#cdetail").yxeditgrid({
		url : '?model=hr_baseinfo_certifytemplatedetail&action=listJson',
		param : {"modelId" : $("#modelId").val()},
		objName : 'cassess[cdetail]',
		tableClass : 'form_in_table',
		title : '��ְ�ʸ�ȼ���׼',
		isAdd : false,
		colModel : [{
			display : 'ģ��Id',
			name : 'modeId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��',
			name : 'moduleName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '��ΪҪ��id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '��ΪҪ��',
			name : 'detailName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : 'Ȩ��(%)',
			name : 'weights',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '��ְ��׼',
			name : 'standard',
			tclass : 'txtlong',
			validation : {
				required : true
			}
		}, {
			display : '��Ҫ�ṩ�����۲���',
			name : 'needMaterial',
			tclass : 'txtlong',
			validation : {
				required : true
			}
		}]
	})
});

//����֤
function checkform() {

	//�ж�Ȩ���Ƿ�Ϊ100
	var rowAmountVa = 0;
	var cmps = $("#cdetail").yxeditgrid("getCmpByCol", "weights");
	cmps.each(function () {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	if(rowAmountVa != 100){
		alert('��ǰȨ�غ�Ϊ ' + rowAmountVa + ",�����µ���Ȩ��");
		return false;
	}

	return true;
}

//�ύ����
function handup(thisType){
	if(thisType == 'handup'){
		$("#status").val(1);
	}else{
		$("#status").val(0);
	}
}
