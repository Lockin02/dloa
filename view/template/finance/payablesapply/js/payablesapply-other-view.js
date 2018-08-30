$(function() {
	//������Ϣ
	var payablesapplyId=$("#payablesapplyId").val();
	$("#payDetail").yxeditgrid({
		title : '��̯��ϸ',
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
					display : '��̯����',
					type:'select'
		           },
		           {
					name : 'shareObjName',
					display : '��̯����',
					process : function(v,row){
		        	   		if(row.shareType=='�ۺ����')
		        	   			return '<a href="?model=contract_contract_contract&action=toViewTab&id='+row.shareObjId+'" target="_blank">'+v+'</a>';
		        	   		else
		        	   			return v;
		           		}
			        },
			        {
					name : 'shareObjCode',
					display : '��̯����code',
					type : 'hidden'
				    },
				    {
					name : 'shareObjId',
					display : '��̯����id',
					type : 'hidden'
				    },
			        {
					name : 'feeType',
					display : '��������',
					type : 'select'
				    },
				    {
					name : 'shareMoney',
					display : '��̯���',
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
					$tbody.after("<tr class='tr_count'><td colspan='2'>�ϼ�</td>"
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


