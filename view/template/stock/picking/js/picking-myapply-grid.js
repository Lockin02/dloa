/** 我的领料申请单* */

var show_page = function(page) {
	$("#myapplyGrid").yxgrid("reload");
};

$(function() {

	$("#myapplyGrid").yxgrid({

		model : 'stock_picking_pickingapply',
		 action:'myApplyJson',
		title : '我的领料申请单列表',
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

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
			width : 120
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
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=stock_picking_pickingapply"
							+ "&action=init"
							+ "&id="
							+ row.id
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '待审批'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=stock_picking_pickingapply"
							+ "&action=init"
							+ "&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}, {
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '待审批'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row)
					location = "controller/stock/picking/ewf_index.php?actTo=ewfSelect&billId="
							+ row.id;
			}
		}, {
			text : '提交出库',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '完成'&&row.status == '待提交'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_picking_pickingapply"
					+ "&action=toHandUp"
					+ "&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=150&width=300");			}
		}, {
			text : '刪除',
			icon : 'delete',
			showMenuFn : function(row){
				if(row.ExaStatus == '待审批' || row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('确认删除？')){
					showThickboxWin('?model=stock_picking_pickingapply&action=del&id='
							+ row.id
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300');
				}
			}
		}, {
			text : '重新编辑',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.status != 'reedit' && row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row) {
					showThickboxWin('?model=stock_picking_pickingapply&action=toReEdit&id='
							+ row.id
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				}
		}],

		searchitems : [{
			display : '领料类型',
			name : 'pickingType',
			sortable : true
		}]
	});
});