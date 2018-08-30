var show_page = function(page) {
	$("#confirmlist").yxgrid("reload");
};

$(function() {
	$("#confirmlist").yxgrid({
		model : 'stock_outplan_outplan',
		action : 'confirmListJson',
		title : '待确认发货计划',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'docCode',
			display : '合同编号',
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
			display : '合同名称',
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
			display : '发货计划编号',
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
			display : '希望交货日期',
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '计划发货日期',
			sortable : true
		}, {
			name : 'overTimeReason',
			display : '超期发货原因',
			sortable : true
		}, {
			name : 'confirm',
			display : '确认',
			sortable : true,
			process : function(v,row){
				return "<input type='button' value = '同意' onclick=\"argee("+row.id+");\" />&nbsp;&nbsp;&nbsp;<input type='button' value = '不同意' onclick=\"disargee("+row.id+");\" />";
			}
		}]
	});
});

function argee(id){
	if(confirm("确认同意该发货计划？")){
		$.ajax({
			type: "POST",
		    url: "?model=stock_outplan_outplan&action=confirm",
		    data: {"isNeedConfirm" : 0,"id" : id},
		    async: false,
		    success: function(result){
		    	if(result == 1){
		    		alert('确认成功');
		    		show_page();
		    	}else{
		    		alert('确认失败');
		    	}
		    }

		});
	}
}


function disargee(id){
	if(confirm("确认不同意该发货计划？")){
		$.ajax({
			type: "POST",
		    url: "?model=stock_outplan_outplan&action=confirm",
		    data: {"isNeedConfirm" : 2,"id" : id},
		    async: false,
		    success: function(result){
		    	if(result == 1){
		    		alert('确认成功');
		    		show_page();
		    	}else{
		    		alert('确认失败');
		    	}
		    }

		});
	}
}