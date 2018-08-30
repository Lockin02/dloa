/** 费用发票* */

var show_page = function(page) {
	$("#pickiingGrid").yxgrid("reload");
};

$(function() {

	$("#pickiingGrid").yxgrid({

		model : 'stock_picking_pickingapply',
		// action:'pageJson',
		title : '领料申请单列表',
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		showcheckbox : true,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '领料申请单号',
			name : 'pickingCode',
			sortable : true
		}, {
			display : '领料类型',
			name : 'pickingType',
			sortable : true,
			width : 170
		}, {
			display : '领料部门',
			name : 'departments',
			sortable : true
		}, {
			display : '发料仓库',
			name : 'stockName',
			sortable : true
		}, {
			display : '领料人',
			name : 'pickName',
			sortable : true
		}, {
			display : '发料人',
			name : 'sendName',
			sortable : true
		}, {
			display : '状态',
			name : 'status',
			sortable : true
		}, {
			display : '审核状态',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '审核时间',
			name : 'ExaDT',
			sortable : true
//		}, {
		}],
		/**
		 * 新增表单属性配置
		 */
		toAddConfig : {
			text : '新增',
			/**
			 * 默认点击新增按钮触发事件
			 */
			toAddFn : function(p) {
				var c = p.toAddConfig;
				showThickboxWin("?model=stock_picking_pickingapply"
						+ "&action="
						+ c.action
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			},
			/**
			 * 新增表单调用的后台方法
			 */
			action : 'toAdd'
		},

		// 扩展右键菜单
		menusEx : [{
			text : '编辑',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=stock_picking_pickingapply"
							+ "&action=init"
							+ "&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=1000&width=1100");

			}
		}, {
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=stock_picking_pickingapply"
							+ "&action=init"
							+ "&id="
							+ row.id
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=800&width=900");

			}
		}, {
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row){
				alert( row.ExaStatus)
				if(row.ExaStatus == '待提交' || row.ExaStatus == ''){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row)
					location = "controller/stock/shipapply/ewf_index.php?actTo=ewfSelect&billId="
							+ row.id;
			}
		}],

		searchitems : [{
			display : '领料类型',
			name : 'pickingType',
			sortable : true
		}],
		sortorder : 'ASC'
	});
});