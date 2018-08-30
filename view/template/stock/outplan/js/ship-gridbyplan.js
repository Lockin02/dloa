var show_page = function(page) {
	$("#shipGrid").yxgrid("reload");
};
$(function() {
	$("#shipGrid").yxgrid({
		model : 'stock_outplan_ship',
		param : { planId : $('#planId').val() },
		title : '������',
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'shipGrid',


		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=800');
			}
		}],


		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'docId',
			display : '������ͬId',
			hide : true,
			sortable : true
		}, {
			name : 'shipCode',
			display : '��������',
			width : 120,
			sortable : true
		}, {
			name : 'docCode',
			display : '������ͬ��',
			width : 180,
			sortable : true
		}, {
			name : 'customerContCode',
			display : '�ͻ���ͬ��',
			width : 120,
			sortable : true
		}, {
			name : 'shipType',
			display : '������ʽ',
			sortable : true,
			process : function(v) {
				if (v == 'order') {
					return "����";
				}else if (v == 'borrow') {
					return "����";
				}else if (v == 'lease'){
				    return "����";
				}else if (v == 'trial'){
				    return "����";
				}else if (v == 'change'){
				    return "����";
				}
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'docType',
			display : '��������',
			sortable : true,
			process : function(v) {
				if (v == 'oa_contract_contract') {
					return "��ͬ����";
				}else if (v == 'oa_present_present') {
					return "���ͷ���";
				}else if (v == 'oa_contract_exchangeapply'){
				    return "��������";
				}else if (v == 'oa_borrow_borrow'){
				    return "���÷���";
				}
			}
		}, {
			name : 'linkman',
			display : '��ϵ��',
			hide : true,
			sortable : true
		}, {
			name : 'mobil',
			display : '�ֻ���',
			hide : true,
			sortable : true
		}, {
			name : 'postCode',
			display : '�ʱ�',
			hide : true,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			hide : true,
			sortable : true
		}, {
			name : 'outstockman',
			display : '������',
			sortable : true
		}, {
			name : 'shipman',
			display : '������',
			sortable : true
		}, {
			name : 'auditman',
			display : '�����',
			sortable : true
		}, {
			name : 'shipDate',
			display : '��������',
			sortable : true
		}, {
			name : 'isMail',
			display : '�Ƿ��ʼ�',
			hide : true,
			sortable : true
		}, {
			name : 'isSign',
			display : '�Ƿ�ǩ��',
			process : function(v){
					(v == '1')? (v = '��'):(v = '��');
					return v;
			},
			sortable : true
		}, {
			name : 'signman',
			display : 'ǩ����',
			hide : true,
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			hide : true,
			sortable : true
		}, {
			name : 'createName',
			display : '����������',
			hide : true,
			sortable : true
		}, {
			name : 'createId',
			display : '������',
			hide : true,
			sortable : true
		}, {
			name : 'updateName',
			display : '�޸�������',
			hide : true,
			sortable : true
		}, {
			name : 'updateTime',
			display : '�޸�ʱ��',
			hide : true,
			sortable : true
		}, {
			name : 'updateId',
			display : '�޸���',
			hide : true,
			sortable : true
		}, {
			name : 'signDate',
			display : 'ǩ������',
			hide : true,
			sortable : true
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ע',
			name : 'itemRemark'
		}]
	});
});