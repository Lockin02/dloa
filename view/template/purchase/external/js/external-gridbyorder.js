var show_page = function(page) {
	$("#externalGrid").yxgrid("reload");
};
$(function() {
	var purchType = $('#purchType').val();
	var orderId = $('#orderId').val();
	$("#externalGrid").yxgrid({
		model : 'purchase_plan_basic',
		action : 'pagebyorder',
		title : '合同采购列表',
		param : { 'isTemp':0,'purchType': purchType,'sourceID':orderId },
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=purchase_plan_basic&action=read&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}],
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'purchType',
			display : '采购类型',
			width : 120,
			sortable : true,
			hide : true
		}, {
			name : 'planNumb',
			display : '采购申请编号',
			width : 120,
			sortable : true
		}, {
			name : 'sourceNumb',
			display : '源单据号',
			width : 120,
			sortable : true
		}, {
			name : 'contractName',
			display : '合同名称',
			width : 90,
			sortable : true
		}, {
			name : 'sendTime',
			display : '申请日期',
			width : 180,
			sortable : true
		}, {
			name : 'dateHope',
			display : '期望完成日期',
			width : 120,
			sortable : true
		}, {
			name : 'sendName',
			display : '申请人名称',
			sortable : true,
			width : 60
		}, {
			name : 'department',
			display : '申请部门',
			sortable : true
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '源单据号',
			name : 'sourceID'
		}, {
			display : '合同名称',
			name : 'contractName'
		}, {
			display : '采购申请编号',
			name : 'planNumb'
		}]
	});
});