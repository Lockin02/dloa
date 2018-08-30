var show_page = function(page) {
	$("#salesZBG").yxgrid("reload");
};
$(function() {

	$("#salesZBG").yxgrid({
		model : 'contract_sales_sales',
		action : 'zbgPageJson',
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
			text : '变更前版本',
			icon : 'view',

			action: function(row){
                showThickboxWin('?model=contract_sales_sales&action=readBaseInfoNoedit&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		},{
			text : '变更中版本',
			icon : 'view',

			action: function(row){
                showThickboxWin('?model=contract_change_change&action=showAction&id='
						+ row.changeId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		},{

			text : '审批情况',
			icon : 'view',

			action : function(row) {
				showThickboxWin('controller/contract/sales/readview.php?itemtype=oa_contract_sales&pid='
		             +row.id
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
				display : '鼎利合同号',
				name : 'contNumber',
				sortable : true,
				width : 150
			}, {
				display : '合同名称',
				name : 'contName',
				sortable : true,
				width : 150
			}, {
				display : '客户名称',
				name : 'customerName',
				sortable : true,
				width : 120
			}, {
				display : '客户合同号',
				name : 'customerContNum',
				sortable : true
			}, {
				display : '客户类型',
				name : 'customerType',
				datacode : 'KHLX',
				sortable : true
			}, {
				display : '客户所属省份',
				name : 'province',
				sortable : true,
				width : 80
			}, {
				display : '变更申请单号',
				name : 'formNumber',
				sortable : true,
				width : 150
			}, {
				display : '负责人名称',
				name : 'principalName',
				sortable : true
			}, {
				display : '变更申请日期',
				name : 'applyTime',
				sortable : true
			},{
				display : '申请单状态',
				name : 'changeFormStatus',
				sortable : true,
				width : 100
			}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '在变更的销售合同'
	});
});