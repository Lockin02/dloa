/** �����б�* */

var show_page = function(page) {
	$("#incomeGrid").yxgrid("reload");
}

$(function() {
	$("#incomeGrid").yxgrid({
		model : 'finance_income_income',
		// action:'pageJson',
		title : '���еĵ���',
		isToolBar : true,
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
		},  {
			name : 'businessBelongName',
			display : '������˾',
			sortable : true,
			width : 100
		},	{
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
        buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : " �� �� ",
			icon : 'edit',

			action : function(row) {

				showThickboxWin("?model=finance_income_income&action=toExcel"
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600")
			}
		}],
		/**
		 * ��������������
		 */
		toAddConfig : {
			text : '����',
			/**
			 * Ĭ�ϵ��������ť�����¼�
			 */
			toAddFn : function(p) {
				var c = p.toAddConfig;
				showThickboxWin("?model=finance_income_income&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			},
			/**
			 * ���������õĺ�̨����
			 */
			action : 'toAdd'
		},

		// ��չ�Ҽ��˵�
		menusEx : [ {
			text : '�鿴',
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
			text : '�༭',
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
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 'DKZT-WFP' || row.status == 'DKZT-FHK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_income_income&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									// grid.reload();
									alert('ɾ���ɹ���');
									$("#incomeGrid").yxgrid("reload");
								}
							}
						});
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		searchitems : [{
			display : '�����',
			name : 'incomeNo'
		}],
		sortname : 'id',
		sortorder : 'DESC'

	});
});