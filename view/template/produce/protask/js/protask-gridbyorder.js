var show_page = function(page) {
	$("#pagebyorderGrid").yxgrid("reload");
};
$(function() {
	var purchType = $('#purchType').val();
	var orderId = $('#orderId').val();
	$("#pagebyorderGrid").yxgrid({
		model : 'produce_protask_protask',
		action : 'pagebyorder',
		title : '合同生产任务书',
		param : { 'relDocType': purchType,'relDocId':orderId },
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'taskCode',
			display : '生产任务编号',
			width : '120',
			sortable : true
		}, {
			name : 'relDocType',
			display : '源单类型',
			width : '60',
			sortable : true,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "销售合同";
				}else if (v == 'oa_sale_lease') {
					return "租赁合同";
				}else if (v == 'oa_sale_service'){
				    return "服务合同";
				}else if (v == 'oa_sale_rdproject'){
				    return "研发合同";
				}else if (v == 'oa_borrow_borrow'){
				    return "借用合同";
				}
			}
		}, {
			name : 'relDocId',
			display : '源单id',
			sortable : true,
			hide : true
		}, {
			name : 'relDocCode',
			display : '源单编号',
			width : '150',
			sortable : true
		}, {
			name : 'relDocName',
			display : '源单名称',
			width : '180',
			sortable : true
		}, {
			name : 'issuedDeptName',
			display : '下单部门',
			sortable : true
		}, {
			name : 'execDeptName',
			display : '执行部门',
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'referDate',
			display : '交货日期',
			sortable : true
		}, {
			name : 'proStatus',
			display : '单据状态',
			width : '60',
			sortable : true,
			process : function(v){
				( v=='YWC' ) ? (v='已完成') : ( v='未完成' );
				return v;
			}
		}, {
			name : 'issuedStatus',
			display : '下达状态',
			width : '60',
			sortable : true,
			process : function(v){
				( v==0 ) ? (v='未下达') : ( v='已下达' );
				return v;
			}
		}, {
			name : 'qualityType',
			display : '验证类型',
			sortable : true,
			hide : true
		}, {
			name : 'taskType',
			display : '性质',
			sortable : true,
			hide : true
		}, {
			name : 'issuedman',
			display : '下达人',
			sortable : true
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=produce_protask_protask&action=toView&id='
						+ row.id
						+ '&relDocType='
						+ row.relDocType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}]
	});
});