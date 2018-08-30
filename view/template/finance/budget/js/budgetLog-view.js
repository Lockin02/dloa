$(function () {

			$("#detailLog").yxeditgrid({
				objName: 'budget[budgetDetail]',
		        tableClass: 'form_in_table',
		        url:'?model=finance_budget_budgetLog&action=listJson&detailId='+$("#detailId").val(),
		        type:'view',
		        colModel: [ {
		            display: '修改字段',
		            name: 'modifyField',
		            process:function(v){
		            	switch(v){
		            		case 'totalBudget': return '区域总预算';break;
		            		case 'firstBudget': return '第一季度预算';break;
		            		case 'secondBudget': return '第二季度预算';break;
		            		case 'thirdBudget': return '第三季度预算';break;
		            		case 'fourthBudget': return '第四季度预算';break;
		            	}
		            }
		        }, {
		            display: '修改记录',
		            name: 'record',
		            process:function(v,row){
		            	return row.beforeModify+"->"+row.afterModify;
		            }
		        }, {
		            display: '修改人',
		            name: 'updateName'
		        }, {
		            display: '修改时间',
		            name: 'updateTime'
		        }]
			})

});