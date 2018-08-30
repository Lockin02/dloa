var show_page = function(page) {
	$("#cardsinfoGrid").yxgrid("reload");
};
$(function() {
	$("#cardsinfoGrid").yxgrid({
		model : 'cardsys_cardsinfo_cardsinfo',
		title : '���Կ���Ϣ',
		param : {
			'projectId' : $("#projectId").val()
		},
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'operators',
				display : '��Ӫ��',
				sortable : true
			}, {
				name : 'netType',
				display : '��������',
				sortable : true,
				datacode : 'WLLX'
			}, {
				name : 'packageType',
				display : '�ײ�����',
				sortable : true
			}, {
				name : 'ratesOf',
				display : '�ʷ����',
				sortable : true
			}, {
				name : 'carNo',
				display : '����',
				sortable : true
			}, {
				name : 'pinNo',
				display : 'pin��',
				width : 80
			}, {
				name : 'cityName',
				display : '������(��)',
				width : 80
			}, {
				name : 'cardType',
				display : '����',
				width : 80
			}, {
				name : 'status',
				display : '״̬',
				width : 80,
				datacode : 'CSKZT'
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 180,
				hide : true
			}, {
				name : 'ownerId',
				display : '�ֿ�Ա��Id',
				sortable : true,
				hide : true
			}, {
				name : 'ownerName',
				display : '�ֿ���',
				sortable : true
			}, {
				name : 'openerId',
				display : '����Ա��Id',
				sortable : true,
				hide : true
			}, {
				name : 'openerName',
				display : '����Ա��',
				sortable : true,
				hide : true
			}, {
				name : 'openDate',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'openMoney',
				display : '�������',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}, {
				name : 'initMoney',
				display : '��ʼ���',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				hide : true
			}
		],
		toViewConfig : {
			action : 'toView'
		},
		isDelAction : false,
		searchitems : [{
				display : "�ֿ���",
				name : 'ownerNameSearch'
			}, {
				display : "����",
				name : 'carNoSearch'
			}
		]
	});
});