var show_page = function(page) {
	$("#exchangeGrid").yxgrid("reload");
};
$(function() {
	$("#exchangeGrid").yxgrid({
		model : 'projectmanagent_exchange_exchange',
		title : '销售换货',
		isDelAction : false,
		isToolBar : true, //是否显示工具栏
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'exchangeCode',
			display : '换货单编号',
			sortable : true,
			width : 120
		}, {
			name : 'contractCode',
			display : '源单号',
			sortable : true,
			width : 120
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 250
		}, {
			name : 'saleUserName',
			display : '销售负责人',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true
		}, {
			name : 'reason',
			display : '换货原因',
			sortable : true
		}],
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=toAdd');

			}
		}],
		menusEx : [

		{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&perm=view' );
			}
		},{
			text : '审批情况',
			icon : 'view',
            showMenuFn : function (row) {
               if (row.ExaStatus=='未审批'){
                   return false;
               }
                   return true;
            },
			action : function(row) {

				showThickboxWin('controller/projectmanagent/return/readview.php?itemtype=oa_sale_return	&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '换货单编号',
			name : 'exchangeCode'
		}]
	});
});