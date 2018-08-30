(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_selectcontract', {
		options : {
			model : 'contract_contract_contract',
//            action : 'presentInfoJson',
			param : {'isTemp' : '0'},
            title : '��ͬ�б�',
			/**
			 * �Ƿ���ʾ�鿴��ť/�˵�
			 */
			isViewAction : false,
			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 */
			isEditAction : false,
			// ����Ϣ
			colModel :
			[
				{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'contractType',
					display : '��ͬ����',
					sortable : true,
					datacode : 'HTLX',
					width : 60
				}, {
					name : 'contractCode',
					display : '��ͬ���',
					sortable : true,
					width : 120
				}, {
					name : 'contractName',
					display : '��ͬ����',
					sortable : true,
					width : 150
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 180
				}, {
					name : 'customerType',
					display : '�ͻ�����',
					sortable : true,
					datacode : 'KHLX',
					width : 70,
					hide : true
				}, {
					name : 'prinvipalName',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '��ͬ״̬',
					sortable : true,
					process : function(v) {
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
					width : 80
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 80
				}
			],
			// ��������
			searchitems : [
				{
					display : '��ͬ���',
					name : 'contractCode'
				}, {
					display : '��ͬ����',
					name : 'contractName'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}
			],

			// Ĭ������˳��
			sortorder : "DESC"
		}
	});
})(jQuery);