$(function () {

			$("#budgetDetail").yxeditgrid({
				objName: 'budget[budgetDetail]',
		        tableClass: 'form_in_table',
		        url:'?model=finance_budget_budgetDetail&action=listJson&mainId='+$("#id").val()+'&totalBudget=1&sort=areaId',
		        type:'view',
		        colModel: [{
		            display: '����ID',
		            name: 'areaId',
					type : 'hidden'
		        }, {
		            display: '����',
		            name: 'area',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: '��˾',
		            name: 'company',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: 'Q1Ԥ��',
		            name: 'firstBudget'
		        }, {
		            display: 'Q1����',
		            name: 'firstFinal',
		            process:function(v,row){
		            	if((v-row.firstBudget)>0&&row.firstBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: 'Q2Ԥ��',
		            name: 'secondBudget'
		        }, {
		            display: 'Q2����',
		            name: 'secondFinal',
		            process:function(v,row){
		            	if((v-row.secondBudget)>0&&row.secondBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: 'Q3Ԥ��',
		            name: 'thirdBudget'
		        }, {
		            display: 'Q3����',
		            name: 'thirdFinal',
		            process:function(v,row){
		            	if((v-row.thirdBudget)>0&&row.thirdBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: 'Q4Ԥ��',
		            name: 'fourthBudget'
		        }, {
		            display: 'Q4����',
		            name: 'fourthFinal',
		            process:function(v,row){
		            	if((v-row.fourthBudget)>0&&row.fourthBudget>0){
		            		return "<font style='color:#FF0000'>"+v+"</font>";
		            	}else{
		            		return v;
		            	}
		            }
		        }, {
		            display: '������Ԥ��',
		            name: 'totalBudget'
		        }, {
		            display: '�����ܾ���',
		            name: 'final'
		        }, {
		            display: '����',
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
		            display: 'ʡ�����Ƿ�ɼ�',
		            name: 'isProvinceVisible',
		            process:function(v){
		            	return v==1?'�ɼ�':'���ɼ�';
		            }
		        }, {
		            display: '����',
		            name: 'operate',
		            process:function(v,row){
		            	return "<a href='javascript:showHistory("+row.id+")'>�鿴�޸���ʷ</a>";
		            }
		        }]
			})

});
function showHistory(id){
	 showModalWin("?model=finance_budget_budgetLog&action=toView&detailId="+id);
}
//������
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
