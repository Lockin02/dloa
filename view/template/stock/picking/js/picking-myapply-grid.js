/** �ҵ��������뵥* */

var show_page = function(page) {
	$("#myapplyGrid").yxgrid("reload");
};

$(function() {

	$("#myapplyGrid").yxgrid({

		model : 'stock_picking_pickingapply',
		 action:'myApplyJson',
		title : '�ҵ��������뵥�б�',
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
			display : '�������뵥��',
			name : 'pickingCode',
			sortable : true
		}, {
			display : '��������',
			name : 'pickingType',
			sortable : true,
			width : 120
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
			text : '�鿴',
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
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '������'){
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
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '������'){
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
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '���'&&row.status == '���ύ'){
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
			text : '�h��',
			icon : 'delete',
			showMenuFn : function(row){
				if(row.ExaStatus == '������' || row.ExaStatus == '���'){
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('ȷ��ɾ����')){
					showThickboxWin('?model=stock_picking_pickingapply&action=del&id='
							+ row.id
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300');
				}
			}
		}, {
			text : '���±༭',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.status != 'reedit' && row.ExaStatus == '���'){
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
			display : '��������',
			name : 'pickingType',
			sortable : true
		}]
	});
});