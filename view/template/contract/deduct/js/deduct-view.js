
$(function(){
	initIncomeCheck();
});

//��Ⱦ��������ӱ�
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		objName : 'deduct[incomeCheck]',
		type : 'view',
		title : '�ؿ����',
		param : { 'incomeId' : $("#id").val(),'incomeType' : 1 },
		tableClass : 'form_in_table',
		isAdd : false,
		event : {
			'reloadData' : function(e, g, data){
				if(!data){
//					objGrid.hide();
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'incomeId',
			name : 'incomeId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : 'incomeType',
			name : 'incomeType',
			type : 'hidden',
			value : 1
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '��ͬ����',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '��ͬ���',
			name : 'contractCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��������id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'payConName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '���κ������',
			name : 'checkMoney',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : '��������',
			name : 'checkDate',
			tclass : 'txtmiddle Wdate',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
}