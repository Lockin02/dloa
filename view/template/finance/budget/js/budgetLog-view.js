$(function () {

			$("#detailLog").yxeditgrid({
				objName: 'budget[budgetDetail]',
		        tableClass: 'form_in_table',
		        url:'?model=finance_budget_budgetLog&action=listJson&detailId='+$("#detailId").val(),
		        type:'view',
		        colModel: [ {
		            display: '�޸��ֶ�',
		            name: 'modifyField',
		            process:function(v){
		            	switch(v){
		            		case 'totalBudget': return '������Ԥ��';break;
		            		case 'firstBudget': return '��һ����Ԥ��';break;
		            		case 'secondBudget': return '�ڶ�����Ԥ��';break;
		            		case 'thirdBudget': return '��������Ԥ��';break;
		            		case 'fourthBudget': return '���ļ���Ԥ��';break;
		            	}
		            }
		        }, {
		            display: '�޸ļ�¼',
		            name: 'record',
		            process:function(v,row){
		            	return row.beforeModify+"->"+row.afterModify;
		            }
		        }, {
		            display: '�޸���',
		            name: 'updateName'
		        }, {
		            display: '�޸�ʱ��',
		            name: 'updateTime'
		        }]
			})

});