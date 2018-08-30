var show_page = function(page) {
	$("#shareGrid").yxgrid("reload");
};
$(function() {
	$("#shareGrid").yxgrid({
		param : {
			"toshareNameId" : $("#userId").val()
		},
		model : 'contract_common_share',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		sortorder : "DESC",
		title : '����ĺ�ͬ',
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=contract_contract_contract&action=init&perm=view&id='
						+ row.orderId + "&skey=" + row['skey_']);
			}
		}],
		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'orderType',
			display : '�����ͬ����',
			sortable : true
		}, {
			name : 'orderName',
			display : '�����ͬ����',
			sortable : true,
			width : 150
		}, {
			name : 'shareName',
			display : '������',
			sortable : true
		}, {
			name : 'shareDate',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'toshareName',
			display : '��������',
			sortable : true
		}],
		comboEx : [{
			text : '��ͬ����',
			key : 'orderType',
			data : [{
				text : '���ۺ�ͬ',
				value : '���ۺ�ͬ'
			}, {
				text : '�����ͬ',
				value : '�����ͬ'
			}, {
				text : '���޺�ͬ',
				value : '���޺�ͬ'
			}, {
				text : '�з���ͬ',
				value : '�з���ͬ'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		}]
	});
});