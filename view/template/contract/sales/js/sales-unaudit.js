var show_page = function(page) {
	$("#salesUnaudit").yxgrid("reload");
};
$(function() {

	$("#salesUnaudit").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装

		model : 'contract_sales_sales',
		action : 'unauditPageJson',
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

		menusEx : [{
			text : '合同信息',
			icon : 'view',
			action: function(row){
                showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
						+ row.contractId);
			}
		},{
			text : '审批',
			icon : 'edit',
			action: function(row){
                parent.location = 'controller/contract/sales/ewf_index.php?taskId='+
                	row.task +
                	'&spid=' +
                	row.id +
                	'&billId=' +
                	row.contractId + '&examCode=oa_contract_sales&actTo=ewfExam';
			}
		}],

		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'contractId',
			name : 'contractId',
			hide : true
		},{
			display : '鼎利合同号',
			name : 'contNumber',
			sortable : true,
			width : 150
		}, {
			display : '审批单号',
			name : 'task',
			sortable : true,
			width : 80
		}, {
			display : '合同名称',
			name : 'contName',
			sortable : true,
			width : 160
		}, {
			display : '客户名称',
			name : 'customerName',
			sortable : true,
			width : 130
		}, {
			display : '客户合同号',
			name : 'customerContNum',
			sortable : true,
			width : 100
		}, {
			display : '客户类型',
			name : 'customerType',
			datacode : 'KHLX',
			sortable : true,
			width : 100
		}, {
			display : '客户所属省份',
			name : 'province',
			sortable : true,
			width : 90
		}, {
			display : '负责人名称',
			name : 'principalName',
			sortable : true
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '待审批的销售合同'
	});
});