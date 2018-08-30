var show_page = function(page) {
	$("#salesInfoGrid").yxgrid("reload");
};
$(function() {
	$("#salesInfoGrid").yxgrid({
		model : 'projectmanagent_order_order',
		title : '已签订的合同',
        action : 'salesInfoPageJson&id='+$('#id').val(),
		isDelAction : false,
		isToolBar : false, //是否显示工具栏
		showcheckbox : false,
		// 表单
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : 'contId',
				name : 'contId',
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
				width : 100
			}, {
				display : '客户合同号',
				name : 'customerContNum',
				sortable : true,
				width : 100
			}, {
			display : '负责人名称',
			name : 'principalName',
			sortable : true,
			width : 150
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'contName'
		},{
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		}],
          menusEx : [{
			text : '查看',
			icon : 'view',
			action: function(row){

				showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
					+ row.contId );
			}
		   }],

          //设置查看页面宽度
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          }

	});
});