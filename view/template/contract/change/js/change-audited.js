var show_page = function(page) {
	$("#changeContractListYsp").yxgrid("reload");
};
$(function() {
	$("#changeContractListYsp").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		model : 'contract_change_change',
		action : 'ConYsppageJson',
        /**
		 * 是否显示查看按钮/菜单
		 */
		isViewAction : false,
		/**
		 * 是否显示修改按钮/菜单
		 */
		isEditAction : false,
		/**
		 * 是否显示删除按钮/菜单
		 */
        isDelAction : false,
        //是否显示添加按钮
        isAddAction : false,
        //是否显示工具栏
        isToolBar : false,
        //是否显示checkbox
         showcheckbox : false,
		// 扩展右键菜单
		menusEx : [

		{
			text : '查看',
			icon : 'view',

			action : function(row) {
				showOpenWin('?model=contract_change_change&action=showAction&id='
					+ row.changeId
				)
			}
		},{
			text : '审批情况',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_change&pid='
		             +row.changeId
		             + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],
		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '变更申请单号',
			name : 'formNumber',
			sortable : true,
			width : 150

		}, {
			display : '系统合同号',
			name : 'contNumber',
			sortable : true,

			width : 150
		}, {
			display : '审批单号',
			name : 'task',
			sortable : true,
			width : 150
		}, {
			display : '变更申请人',
			name : 'applyName',
			sortable : true,
			width : 150
		}, {
			display : '申请日期',
			name : 'applyTime',
			sortable : true,
			width : 150

		}, {
			display : '申请单状态',
			name : 'ExaStatus',
			sortable : true,
			width : 150
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '申请单号',
			name : 'contName'
		}],
		sortorder : "DESC	",
		title : '已审批的变更申请'
	});
});