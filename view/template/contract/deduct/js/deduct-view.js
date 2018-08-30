
$(function(){
	initIncomeCheck();
});

//渲染到款核销从表
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		objName : 'deduct[incomeCheck]',
		type : 'view',
		title : '回款核销',
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
			display : '合同id',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '合同名称',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '合同编号',
			name : 'contractCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '付款条件id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '付款条件',
			name : 'payConName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '本次核销金额',
			name : 'checkMoney',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : '核销日期',
			name : 'checkDate',
			tclass : 'txtmiddle Wdate',
			type : 'date'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
}