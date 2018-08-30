var show_page = function(page) {
	$("#incomeAllotGrid").yxgrid("reload");
};
$(function() {

	$("#incomeAllotGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装

		model : 'finance_income_incomeAllot',
		action : 'incomeDetPageJson',
		param : { 'exObjCode' : $('#contractNo').val() , 'exObjType' : 'KPRK-XSHT'},
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
        /*
         * 是否显示右键菜单
         */
         isRightMenu : false,
         // 扩展右键菜单
		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '到款号',
			name : 'incomeNo',
			sortable : true,
			width : 130

		}, {
			display : '到款单位',
			name : 'incomeUnitName',
			sortable : true,

			width : 100
		}, {
			display : '到款日期',
			name : 'incomeDate',
			sortable : true,
			width : 100
		}, {
			display : '到款方式',
			name : 'incomeType',
			sortable : true,
			width : 100,
			datacode : "DKFS"
		}, {
			display : '到款金额',
			name : 'money',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}

		}, {
			display : '录入人',
			name : 'createName',
			sortable : true,
			width : 100
		},{
			display : '录入时间',
			name : 'createTime',
			sortable : true,
			width : 130
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '到款号',
			name : 'incomeNo'
		}],
		sortorder : "ASC",
		title : '到款记录'
	});
});