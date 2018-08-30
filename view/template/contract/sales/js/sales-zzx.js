var show_page = function(page) {
	$("#salesZZX").yxgrid("reload");
};
$(function() {
	$("#salesZZX").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
        param : {"exaStatus":"完成","contStatus":"1,3"},
		model : 'contract_sales_sales',
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

			action: function(row){
                showOpenWin('?model=contract_sales_sales&action=infoTab&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						);
			}
//		},{
//			text : '合同处理',
//			icon : 'edit',
//			action: function(row){
//				parent.location='?model=contract_sales_sales&action=executeContract&id='
//						+ row.id;
//			}
		},{
			text : '指定负责人',
			icon : 'edit',

			action: function(row){
                showThickboxWin('?model=contract_sales_sales&action=changeprincipal&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
			}
		},{
			text : '指定执行人',
			icon : 'edit',

			action: function(row){
                showThickboxWin('?model=contract_sales_sales&action=changeExecute&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
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
			width : 160
		}, {
			display : '客户名称',
			name : 'customerName',
			sortable : true
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
			display : '负责人名称',
			name : 'principalName',
			sortable : true
		}, {
			display : '执行人名称',
			name : 'executorName',
			sortable : true
		}, {
			display : '合同状态',
			name : 'contStatus',
			sortable : true,
			process : function(v,row){
				if(row.changeStatus == '1'){
					return '变更中';
				}
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
		title : '在执行的销售合同'
	});
});