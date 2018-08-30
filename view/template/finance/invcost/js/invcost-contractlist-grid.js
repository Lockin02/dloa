/** 费用发票* */

var show_page = function(page) {
	$("#contractinvcostGrid").yxgrid("reload");
};

$(function() {

	$("#contractinvcostGrid").yxgrid({

		model : 'finance_invcost_invcost',
		action : 'contractJson',
		param:{'purcontId' : $('#applyId').val()},
		title : '费用发票',
		showcheckbox :false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,

		colModel : [{
			display : 'id',
			name : 'id',
			hide : true
		},{
			display : '发票编号',
			name : 'objCode',
			sortable : true
		}, {
			display : '供应商',
			name : 'supplierName',
			sortable : true,
			width : 170
		}, {
			display : '总金额',
			name : 'amount',
			sortable : true,
			process: function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '采购方式',
			name : 'purType',
			sortable : true,
			datacode : 'cgfs'
		}, {
			display : '付款日期',
			name : 'payDate',
			sortable : true
		}, {
			display : '状态',
			name : 'status',
			datacode : 'CGFPZT',
			sortable : true
		},  {
			display : '部门',
			name : 'departments',
			sortable : true
		}, {
			display : '业务员',
			name : 'salesman',
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
				showThickboxWin("?model=finance_invcost_invcost"
						+ "&action="
						+ c.action
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
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
			action : function(row) {
				showThickboxWin("?model=finance_invcost_invcost"
					+ "&action=init"
					+ "&id="
					+ row.id
					+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		},{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 'CGFPZT-WSH' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_invcost_invcost"
					+ "&action=init"
					+ "&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		},{
			text : '钩稽记录',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == 'CGFPZT-YGJ') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_related_detail"
					+ "&action=hookInfo"
					+ "&id="
					+ row.id
					+ "&hookObj=invcost"
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 'CGFPZT-WSH' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_invcost_invcost"
					+ "&action=sureDel"
					+ "&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=150&width=300");
			}
		}],
		buttonsEx : [{
			name : 'close',
			text : "返回",
			icon : 'edit',
			action : function() {
				history.back();
			}
		}],

		searchitems : [{
			display : '供应商名称',
			name : 'supplierName',
			sortable : true
		},{
			display : '发票编号',
			name : 'objCode',
			sortable : true
		}]
	});
});