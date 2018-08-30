var show_page = function(page) {
	$("#shareGrid").yxgrid("reload");
};
$(function() {
	$("#shareGrid").yxgrid({
		param : {
			"toshareNameId" : $("#userId").val()
		},
		model : 'contract_common_share',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		sortorder : "DESC",
		title : '共享的合同',
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=contract_contract_contract&action=init&perm=view&id='
						+ row.orderId + "&skey=" + row['skey_']);
			}
		}],
		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'orderType',
			display : '共享合同类型',
			sortable : true
		}, {
			name : 'orderName',
			display : '共享合同名称',
			sortable : true,
			width : 150
		}, {
			name : 'shareName',
			display : '共享人',
			sortable : true
		}, {
			name : 'shareDate',
			display : '共享时间',
			sortable : true
		}, {
			name : 'toshareName',
			display : '被共享人',
			sortable : true
		}],
		comboEx : [{
			text : '合同类型',
			key : 'orderType',
			data : [{
				text : '销售合同',
				value : '销售合同'
			}, {
				text : '服务合同',
				value : '服务合同'
			}, {
				text : '租赁合同',
				value : '租赁合同'
			}, {
				text : '研发合同',
				value : '研发合同'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		}]
	});
});