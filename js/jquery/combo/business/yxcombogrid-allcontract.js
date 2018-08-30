/**
 * ������ͬ �б�
 */
(function ($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_allcontract', {
		options: {
			isDown: true,
			hiddenId: 'id',
			nameCol: 'contractCode',
			focusoutCheckAction: 'getCountByNameForView',
			openPageOptions: {
				url: '?model=contract_contract_contract&action=selectContract'
			},
			gridOptions: {
				title: "ѡ���ͬ",
				isTitle: true,
				model: 'contract_contract_contract',
				param: { 'isTemp': '0' },
				// ����Ϣ
				// ����Ϣ
				colModel: [
					{
						display: 'id',
						name: 'id',
						sortable: true,
						hide: true
					}, {
						name: 'contractType',
						display: '��ͬ����',
						sortable: true,
						datacode: 'HTLX',
						width: 60
					}, {
						name: 'contractCode',
						display: '��ͬ���',
						sortable: true,
						width: 120
					}, {
						name: 'contractName',
						display: '��ͬ����',
						sortable: true,
						width: 150
					}, {
						name: 'customerName',
						display: '�ͻ�����',
						sortable: true,
						width: 180
					}, {
						name: 'customerType',
						display: '�ͻ�����',
						sortable: true,
						datacode: 'KHLX',
						width: 70,
						hide: true
					}, {
						name: 'prinvipalName',
						display: '��ͬ������',
						sortable: true,
						width: 70
					}, {
						name: 'areaName',
						display: '��������',
						sortable: true,
						width: 70
					}, {
						name: 'state',
						display: '��ͬ״̬',
						sortable: true,
						process: function (v) {
							if (v == '0') {
								return "δ�ύ";
							} else if (v == '1') {
								return "������";
							} else if (v == '2') {
								return "ִ����";
							} else if (v == '3') {
								return "�ѹر�";
							} else if (v == '4') {
								return "�����";
							} else if (v == '5') {
								return "�Ѻϲ�";
							} else if (v == '6') {
								return "�Ѳ��";
							}
						},
						width: 70
					}, {
						name: 'ExaStatus',
						display: '����״̬',
						sortable: true,
						width: 70
					}
				],
				// ��������
				searchitems: [{
					display: '��ͬ���',
					name: 'contractCode'
				}, {
					display: '��ͬ����',
					name: 'contractName',
					isdefault: true
				}, {
					display: '�ͻ�����',
					name: 'customerName',
					isdefault: true
				}],

				// Ĭ������˳��
				sortorder: "DESC"
			}
		}
	});
})(jQuery);