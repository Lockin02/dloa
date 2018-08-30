(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_linkman', {
		options : {
			model : 'customer_linkman_linkman',
//            action : 'linkmanPageJson',
		isViewAction : false,
		isEditAction : false,

      buttonsEx : [{
				// ����EXCEL�ļ���ť
				name : 'import',
				text : "����EXCEL",
				icon : 'excel',
				action : function(row) {
					showThickboxWin("?model=customer_linkman_linkman&action=toUplod"
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=270&width=500");
				}
			}],
       menusEx : [
		{
			text : '�鿴',
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=customer_linkman_linkman&action=init&id='
						+ row.id
						+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�༭',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=customer_linkman_linkman&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		toAddConfig : {
			formWidth : 900,
			formHeight : 500
		},

		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�ͻ�',
			name : 'customerName',
			sortable : true,
			width : 150
		}, {
			display : '����',
			name : 'areaName',
			sortable : true,
			width : 80
		}, {
			display : '����',
			name : 'linkmanName',
			sortable : true
		}, {
			display : '�绰����',
			name : 'phone',
			sortable : true
		}, {
			display : '�ֻ�����',
			name : 'mobile',
			sortable : true
		}, {
			display : 'QQ',
			name : 'QQ',
			sortable : true
		}, {
			display : 'MSN',
			name : 'MSN',
			sortable : true,
			width : 150
		}, {
			display : 'email',
			name : 'email',
			sortable : true,
			width : 150
		}],

		/**
		 * ��������
		 */
		searchitems : [
			{
			display : '�ͻ�����',
			name : 'customerName'
		},{
			display : '����',
			name : 'linkmanName'
		}],
		sortorder : "ASC",
		title : '���пͻ���ϵ��'
		}
	});
})(jQuery);