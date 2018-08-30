/** ���÷�Ʊ* */

var show_page = function(page) {
	$("#contractinvcostGrid").yxgrid("reload");
};

$(function() {

	$("#contractinvcostGrid").yxgrid({

		model : 'finance_invcost_invcost',
		action : 'contractJson',
		param:{'purcontId' : $('#applyId').val()},
		title : '���÷�Ʊ',
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
			display : '��Ʊ���',
			name : 'objCode',
			sortable : true
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
				showThickboxWin("?model=finance_invcost_invcost"
					+ "&action=sureDel"
					+ "&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=150&width=300");
			}
		}],
		buttonsEx : [{
			name : 'close',
			text : "����",
			icon : 'edit',
			action : function() {
				history.back();
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