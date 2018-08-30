$(function() {
	//付款信息
	var payablesapplyId=$("#payablesapplyId").val();
	$("#payDetail").yxeditgrid({
		title : '分摊明细',
		url:'?model=finance_payablescost_payablescost&action=listJson',
		type :'view',
		param : {
			payapplyId : payablesapplyId
		},
		isAddOneRow : true,
		realDel : false,
		colModel :[
		           {
		        	name : 'id',
		        	display:'id',
		        	type : 'hidden'
		           },
		           {
					name : 'shareType',
					display : '分摊类型',
					type:'select'
		           },
		           {
					name : 'shareObjName',
					display : '分摊对象',
					process : function(v,row){
		        	   		if(row.shareType=='售后费用')
		        	   			return '<a href="?model=contract_contract_contract&action=toViewTab&id='+row.shareObjId+'" target="_blank">'+v+'</a>';
		        	   		else
		        	   			return v;
		           		}
			        },
			        {
					name : 'shareObjCode',
					display : '分摊对象code',
					type : 'hidden'
				    },
				    {
					name : 'shareObjId',
					display : '分摊对象id',
					type : 'hidden'
				    },
			        {
					name : 'feeType',
					display : '费用类型',
					type : 'select'
				    },
				    {
					name : 'shareMoney',
					display : '分摊金额',
					type : 'money'
				}
			],
		event : {
			'reloadData' : function(e,obj,data){
				if(data.length > 0){
					$("#payDetailTr").show();
					var totalMoney=0;
					for(i=0;i<data.length;i++){
						totalMoney = accAdd(totalMoney,data[i].shareMoney,2);
					}
					var $tbody = $("#payDetail").find('tbody');
					$tbody.after("<tr class='tr_count'><td colspan='2'>合计</td>"
						+ "<td colspan='2'></td>"
						+ "<td>"
						+'<span id="payDetailMoney">' + moneyFormat2(totalMoney) + '</span>'
						+"</td>"
						+ "</tr>");
				}
			}
		}
	});
});


