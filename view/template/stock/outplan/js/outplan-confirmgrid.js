var show_page = function(page) {
	$("#confirmlist").yxgrid("reload");
};

$(function() {
	$("#confirmlist").yxgrid({
		model : 'stock_outplan_outplan',
		action : 'confirmListJson',
		title : '��ȷ�Ϸ����ƻ�',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'docCode',
			display : '��ͬ���',
			sortable : true,
			width : 150,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
					+ row.docId
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		}, {
			name : 'docName',
			display : '��ͬ����',
			sortable : true,
			width : 150,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
					+ row.docId
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		}, {
			name : 'planCode',
			display : '�����ƻ����',
			sortable : true,
			width : 150,
			process : function(v,row){
				var skey = "";
				    $.ajax({
					    type: "POST",
					    url: "?model=stock_outplan_outplan&action=md5RowAjax",
					    data: { "id" : row.id },
					    async: false,
					    success: function(data){
					   	   skey = data;
						}
					});
				return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=outplandetailTab&planId='
					+ row.id
					+ '&docType=oa_contract_contract'
					+'&skey='
					+ skey
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		}, {
			name : 'deliveryDate',
			display : 'ϣ����������',
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '�ƻ���������',
			sortable : true
		}, {
			name : 'overTimeReason',
			display : '���ڷ���ԭ��',
			sortable : true
		}, {
			name : 'confirm',
			display : 'ȷ��',
			sortable : true,
			process : function(v,row){
				return "<input type='button' value = 'ͬ��' onclick=\"argee("+row.id+");\" />&nbsp;&nbsp;&nbsp;<input type='button' value = '��ͬ��' onclick=\"disargee("+row.id+");\" />";
			}
		}]
	});
});

function argee(id){
	if(confirm("ȷ��ͬ��÷����ƻ���")){
		$.ajax({
			type: "POST",
		    url: "?model=stock_outplan_outplan&action=confirm",
		    data: {"isNeedConfirm" : 0,"id" : id},
		    async: false,
		    success: function(result){
		    	if(result == 1){
		    		alert('ȷ�ϳɹ�');
		    		show_page();
		    	}else{
		    		alert('ȷ��ʧ��');
		    	}
		    }

		});
	}
}


function disargee(id){
	if(confirm("ȷ�ϲ�ͬ��÷����ƻ���")){
		$.ajax({
			type: "POST",
		    url: "?model=stock_outplan_outplan&action=confirm",
		    data: {"isNeedConfirm" : 2,"id" : id},
		    async: false,
		    success: function(result){
		    	if(result == 1){
		    		alert('ȷ�ϳɹ�');
		    		show_page();
		    	}else{
		    		alert('ȷ��ʧ��');
		    	}
		    }

		});
	}
}