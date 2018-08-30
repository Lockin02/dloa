$(function () {

			$("#budgetDetail").yxeditgrid({
				objName: 'budget[budgetDetail]',
		        tableClass: 'form_in_table',
		        url:'?model=finance_budget_budgetDetail&action=listJson&mainId='+$("#id").val()+'&totalBudget=1&sort=areaId',
		        type:'view',
		        colModel: [{
		            display: '区域ID',
		            name: 'areaId',
					type : 'hidden'
		        }, {
		            display: '区域',
		            name: 'area',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: '公司',
		            name: 'company',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: 'Q1预算',
		            name: 'firstBudget'
		        }, {
		            display: 'Q1决算',
		            name: 'firstFinal',
		            process:function(v,row){
		            	if((v-row.firstBudget)>0&&row.firstBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: 'Q2预算',
		            name: 'secondBudget'
		        }, {
		            display: 'Q2决算',
		            name: 'secondFinal',
		            process:function(v,row){
		            	if((v-row.secondBudget)>0&&row.secondBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: 'Q3预算',
		            name: 'thirdBudget'
		        }, {
		            display: 'Q3决算',
		            name: 'thirdFinal',
		            process:function(v,row){
		            	if((v-row.thirdBudget)>0&&row.thirdBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: 'Q4预算',
		            name: 'fourthBudget'
		        }, {
		            display: 'Q4决算',
		            name: 'fourthFinal',
		            process:function(v,row){
		            	if((v-row.fourthBudget)>0&&row.fourthBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: '区域总预算',
		            name: 'totalBudget'
		        }, {
		            display: '区域总决算',
		            name: 'final'
		        }, {
		            display: '进度',
		            name: 'rate',
		            process:function(v,row){
		            	if(row.final>0&&row.totalBudget>0){
		            		var rate = (row.final/row.totalBudget)*100;
		            		rate = Math.round(rate);
		            		return formatProgress(rate);
		            	}else{
			            	return formatProgress(0);
						}
		            }
		        }, {
		            display: '省经理是否可见',
		            name: 'isProvinceVisible',
		            process:function(v){
		            	return v==1?'可见':'不可见';
		            }
		        }, {
		            display: '操作',
		            name: 'operate',
		            process:function(v,row){
		            	return "<a href='javascript:showHistory("+row.id+")'>查看修改历史</a>";
		            }
		        }]
			})

});
function showHistory(id){
	 showModalWin("?model=finance_budget_budgetLog&action=toView&detailId="+id);
}
//进度条
function formatProgress(value) {
	var color="#66FF66";
    if (value) {
    	var width = value;
    	if((value-100)>0){
			color="#FF0000";
			width = 100;
    	}
        var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
            + '<div style="width:'
            + width
            + '%;background:'+color+';white-space:nowrap;padding: 0px;">'
            + value + '%' + '</div>'
        '</div>';
        return s;
    } else {
        return '';
    }
}
