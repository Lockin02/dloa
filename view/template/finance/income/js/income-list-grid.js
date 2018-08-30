/** 到款列表* */

var show_page = function(page) {
	$("#incomeGrid").yxgrid("reload");
}

$(function() {
	$("#incomeGrid").yxgrid({
		model : 'finance_income_income',
		// action:'pageJson',
		title : '所有的到款',
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,


		// 过滤数据
		comboEx : [{
			text : '状态',
			key : 'status',
			datacode : 'DKZT'
		}],


		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '进账单号',
			name : 'inFormNum',
			sortable : true,
			width : 110
		}, {
			display : '到款号',
			name : 'incomeNo',
			sortable : true,
			width : 120
		}, {
			display : '到款单位',
			name : 'incomeUnitName',
			sortable : true,
			width : 130
		}, {
			display : '省份',
			name : 'province',
			sortable : true,
			width : 80
		}, {
			display : '到款日期',
			name : 'incomeDate',
			sortable : true
		}, {
			display : '到款方式',
			name : 'incomeType',
			datacode : 'DKFS',
			sortable : true,
			width : 80
		}, {
			display : '到款类型',
			name : 'sectionType',
			datacode : 'DKLX',
			sortable : true,
			width : 80
		}, {
			display : '到款金额',
			name : 'incomeMoney',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 90
		},  {
			name : 'businessBelongName',
			display : '归属公司',
			sortable : true,
			width : 100
		},	{
			display : '录入人',
			name : 'createName',
			sortable : true
		}, {
			display : '状态',
			name : 'status',
			datacode : 'DKZT',
			sortable : true,
			width : 80
		}, {
			display : '录入时间',
			name : 'createTime',
			sortable : true,
			width : 120
		}],
        buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : " 导 入 ",
			icon : 'edit',

			action : function(row) {

				showThickboxWin("?model=finance_income_income&action=toExcel"
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600")
			}
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
				showThickboxWin("?model=finance_income_income&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			},
			/**
			 * 新增表单调用的后台方法
			 */
			action : 'toAdd'
		},

		// 扩展右键菜单
		menusEx : [ {
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=finance_income_income"
							+ "&action=init"
							+ "&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500"
							+ "&width=900");
			}
		},{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 'DKZT-WFP' || row.status == 'DKZT-FHK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=finance_income_income"
							+ "&action=init"
							+ "&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
							+ "&width=900");
			}
		}, {
			name : 'delete',
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 'DKZT-WFP' || row.status == 'DKZT-FHK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_income_income&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									// grid.reload();
									alert('删除成功！');
									$("#incomeGrid").yxgrid("reload");
								}
							}
						});
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		searchitems : [{
			display : '到款号',
			name : 'incomeNo'
		}],
		sortname : 'id',
		sortorder : 'DESC'

	});
});