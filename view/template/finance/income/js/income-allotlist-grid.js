/** 到款列表* */

var show_page = function(page) {
	$("#incomeAllotGrid").yxgrid("reload");
}

$(function() {
	$("#incomeAllotGrid").yxgrid({
		model : 'finance_income_income',
		action : 'allotPageJson',
		title : '到款分配',
		isToolBar : true,
		isAddAction : false,
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
		}, {
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

		// 扩展右键菜单
		menusEx : [{
			text : '分配到款',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status != 'DKZT-YFP' && row.sectionType != 'DKLX-FHK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=finance_income_income"
							+ "&action=toAllot"
							+ "&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
							+ "&width=950");
			}
		}, {
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row)
					showThickboxWin("?model=finance_income_income"
							+ "&action=toAllot"
							+ "&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500"
							+ "&width=900");
			}
		}],
		searchitems : [{
			display : '到款号',
			name : 'incomeNo'
		}],
		sortname : 'id',
		sortorder : 'ASC'

	});
});