/** ���÷�Ʊ* */

var show_page = function(page) {
	$("#invcostGrid").yxgrid("reload");
};

$(function() {

	$("#invcostGrid").yxgrid({

		model : 'finance_invcost_invcost',
		title : '���÷�Ʊ����',
		showcheckbox :false,
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,

		colModel : [{
			display : 'id',
			name : 'id',
			hide : true
		},{
			display : '��Ʊ���',
			name : 'objCode',
			sortable : true,
			width : 130
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			sortable : true,
			width : 170
		}, {
			display : '�ܽ��',
			name : 'amount',
			sortable : true,
			process: function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '�ɹ���ʽ',
			name : 'purType',
			sortable : true,
			datacode : 'cgfs'
		}, {
			display : '��������',
			name : 'payDate',
			sortable : true
		}, {
			display : '������Ŀ',
			name : 'subjects',
			sortable : true,
			datacode : 'CWKM'
		}, {
			display : '״̬',
			name : 'status',
			datacode : 'CGFPZT',
			sortable : true
		},  {
			display : '����',
			name : 'departments',
			sortable : true
		}, {
			display : 'ҵ��Ա',
			name : 'salesman',
			sortable : true
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
				showThickboxWin("?model=finance_invcost_invcost"
						+ "&action="
						+ c.action
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			},
			/**
			 * ���������õĺ�̨����
			 */
			action : 'toAdd'
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=finance_invcost_invcost"
					+ "&action=init"
					+ "&id="
					+ row.id
					+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		},{
			text : '�༭',
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
			text : '���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 'CGFPZT-WSH' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_invcost_invcost"
					+ "&action=audit"
					+ "&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=150&width=300");
			}
		},{
			text : '�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 'CGFPZT-WGJ' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_invcost_invcost"
					+ "&action=unaudit"
					+ "&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=150&width=300");
			}
		},{
			text : '������¼',
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
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 'CGFPZT-WSH' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_invcost_invcost&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#invcostGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '��Ӧ������',
			name : 'supplierName',
			sortable : true
		},{
			display : '��Ʊ���',
			name : 'objCode',
			sortable : true
		}]
	});
});