var show_page = function(page) {
	$("#reduceapplyGrid").yxgrid("reload");
};
$(function() {
	$("#reduceapplyGrid").yxgrid({
		model : 'service_reduce_reduceapply',
		param : {
			'applyId' : $('#applyId').val()
		},
		title : 'ά�޷��ü������뵥',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '���ݱ��',
					sortable : true

				}, {
					name : 'applyCode',
					display : 'ά�����뵥���',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 200
				}, {
					name : 'adress',
					display : '�ͻ���ַ',
					sortable : true,
					hide : true
				}, {
					name : 'applyUserName',
					display : '����������',
					sortable : true
				}, {
					name : 'applyUserCode',
					display : '�������˺�',
					sortable : true,
					hide : true
				}, {
					name : 'subCost',
					display : 'ά�޷���',
					sortable : true,
					hide : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'subReduceCost',
					display : '�������',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}, {
					name : 'createName',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸���',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�����',
					sortable : true,
					hide : true
				}], // ���ӱ������

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "���" || row.ExaStatus == "���") {
					if (row) {
						showThickboxWin("?model=service_reduce_reduceapply&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showThickboxWin("?model=service_reduce_reduceapply&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		}],

		subGridOptions : {
			url : '?model=service_reduce_reduceitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '���ϱ��'
					}, {
						name : 'productName',
						display : '��������',
						width : 200
					}, {
						name : 'pattern',
						display : '����ͺ�'
					}, {
						name : 'productType',
						display : '�豸����'
					}, {
						name : 'unitName',
						display : '��λ'
					}, {
						name : 'serilnoName',
						display : '���к�'
					}, {
						name : 'fittings',
						display : '�����Ϣ',
						hide : true
					}, {
						name : 'serilnoName',
						display : '��ȡ����',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}, {
						name : 'reduceCost',
						display : '�������',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}]
		},
		comboEx : [{
					text : '����״̬',
					key : 'ExaStatus',
					data : [{
								text : '���ύ',
								value : '���ύ'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���',
								value : '���'
							}]
				}],
		searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : 'ά�����뵥���',
					name : 'applyCode'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}]
	});
});