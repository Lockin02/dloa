var show_page = function(page) {
	$("#shareGrid").yxgrid("reload");
};
$(function() {
	$("#shareGrid").yxgrid({
		param : {
			"shareNameId" : $("#userId").val()
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
		}, {
			text : "ȡ������",
			icon : 'delete',
			action : function(row) {
				if (confirm('ȷ��Ҫȡ��������')) {
					$.ajax({
						type : "POST",
						url : "?model=contract_common_share&action=ajaxDeleteShare&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('ȡ���ɹ���');
								$("#shareGrid").yxgrid("reload");
							} else {
								alert('ȡ��ʧ�ܣ�');
							}
						}
					});
				}
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