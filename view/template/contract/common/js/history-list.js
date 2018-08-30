var show_page = function(page) {
	$("#HistoryList").yxgrid("reload");
};
$(function() {
	$("#HistoryList").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装

		model : 'contract_sales_sales',
		action : 'historyListPageJson',
		param : {'equalCont':$('#contNumber').val()},
		title : '合同历史',
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
			text : '合同信息',
			icon : 'view',

			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=readBaseInfoNoedit&id=&id='
					+ row.id
				)
			}
		},{

			text : '审批情况',
			icon : 'view',

			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_sales&pid='
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
			display : '合同名称',
			name : 'contName',
			sortable : true,
			width : 150

		}, {
			display : '变更申请单号',
			name : 'formNumber',
			sortable : true,

			width : 150
		}, {
			display : '变更申请时间',
			name : 'applyTime',
			sortable : true,
			width : 90
		}, {
			display : '版本开始时间',
			name : 'beginTime',
			sortable : true,
			width : 130
		}, {
			display : '合同状态',
			name : 'contStatus',
			sortable : true,
			width : 90,
			process :function(v){
						switch (v) {
							case '': return '未启动';break;
							case '0': return '未启动';break;
							case '1': return '正执行';break;
							case '2': return '变更待审批';break;
							case '3': return '变更中';break;
							case '4': return '打回关闭';break;
							case '5': return '保留删除';break;
							case '6': return '变更后关闭';break;
							case '9': return '已关闭';break;
							default : return '未启动';break;
						}
					}
		}, {
			display : '审批状态',
			name : 'ExaStatus',
			width : 90,
			sortable : true
		}, {
			display : '版本最近更新',
			name : 'updateTime',
			sortable : true,
			width : 130
		}]
	});
});