/** ���÷�Ʊ* */

var show_page = function(page) {
	$("#pickiingGrid").yxgrid("reload");
};

$(function() {

	$("#pickiingGrid").yxgrid({

		model : 'stock_picking_pickingapply',
		// action:'pageJson',
		title : '�������뵥�б�',
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
			display : '�������뵥��',
			name : 'pickingCode',
			sortable : true
		}, {
			display : '��������',
			name : 'pickingType',
			sortable : true,
			width : 170
		}, {
			display : '���ϲ���',
			name : 'departments',
			sortable : true
		}, {
			display : '���ϲֿ�',
			name : 'stockName',
			sortable : true
		}, {
			display : '������',
			name : 'pickName',
			sortable : true
		}, {
			display : '������',
			name : 'sendName',
			sortable : true
		}, {
			display : '״̬',
			name : 'status',
			sortable : true
		}, {
			display : '���״̬',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '���ʱ��',
			name : 'ExaDT',
			sortable : true
//		}, {
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
				showThickboxWin("?model=stock_picking_pickingapply"
						+ "&action="
						+ c.action
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			},
			/**
			 * ���������õĺ�̨����
			 */
			action : 'toAdd'
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�༭',
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
			text : '�鿴',
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
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row){
				alert( row.ExaStatus)
				if(row.ExaStatus == '���ύ' || row.ExaStatus == ''){
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
			display : '��������',
			name : 'pickingType',
			sortable : true
		}],
		sortorder : 'ASC'
	});
});