/** �����б�* */

var show_page = function(page) {
	$("#incomeAllotGrid").yxgrid("reload");
}

$(function() {
	$("#incomeAllotGrid").yxgrid({
		model : 'finance_income_income',
		action : 'allotPageJson',
		title : '�������',
		isToolBar : true,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		// ��������
		comboEx : [{
			text : '״̬',
			key : 'status',
			datacode : 'DKZT'
		}],

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���˵���',
			name : 'inFormNum',
			sortable : true,
			width : 110
		}, {
			display : '�����',
			name : 'incomeNo',
			sortable : true,
			width : 120
		}, {
			display : '���λ',
			name : 'incomeUnitName',
			sortable : true,
			width : 130
		}, {
			display : 'ʡ��',
			name : 'province',
			sortable : true,
			width : 80
		}, {
			display : '��������',
			name : 'incomeDate',
			sortable : true
		}, {
			display : '���ʽ',
			name : 'incomeType',
			datacode : 'DKFS',
			sortable : true,
			width : 80
		}, {
			display : '��������',
			name : 'sectionType',
			datacode : 'DKLX',
			sortable : true,
			width : 80
		}, {
			display : '������',
			name : 'incomeMoney',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 90
		}, {
			display : '¼����',
			name : 'createName',
			sortable : true
		}, {
			display : '״̬',
			name : 'status',
			datacode : 'DKZT',
			sortable : true,
			width : 80
		}, {
			display : '¼��ʱ��',
			name : 'createTime',
			sortable : true,
			width : 120
		}],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '���䵽��',
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
			text : '�鿴',
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
			display : '�����',
			name : 'incomeNo'
		}],
		sortname : 'id',
		sortorder : 'ASC'

	});
});