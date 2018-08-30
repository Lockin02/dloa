var show_page = function(page) {
	$("#produceplanGrid").yxgrid("reload");
};

$(function() {
	var isCanAdd = $("#taskId").val() > 0 ? true : false; //�Ƿ��������־
	$("#produceplanGrid").yxgrid({
		model: 'produce_plan_produceplan',
		param : {
			taskId : $("#taskId").val()
		},
		title: '�����ƻ�',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width : 110,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			width : 60,
			align : 'center',
			process : function(v ,row) {
				switch (v) {
				case '0':
					return "δȷ��";
					break;
				case '1':
					return "δ����";
					break;
				case '2':
					return "��������";
					break;
				case '3':
					return "��������";
					break;
				case '4':
					return "��������";
					break;
				case '5':
					return "�������";
					break;
				case '6':
					return "�ѹر�";
					break;
				case '7':
					return "�������";
					break;
				case '8':
					return "�����";
					break;
				default:
					return "--";
				}
			}
		},{
			name: 'urgentLevel',
			display: '���ȼ�',
			sortable: true,
			align : 'center'
		},{
			name: 'docDate',
			display: '��������',
			sortable: true,
			width : 80,
			align : 'center'
		},{
			name: 'proType',
			display: '��������',
			sortable: true
		},{
			name: 'productName',
			display: '��������',
			sortable: true,
			width : 200
		},{
			name: 'productCode',
			display: '���ñ���',
			sortable: true
		},{
			name: 'planNum',
			display: '����',
			sortable: true,
			width : 60
		},{
			name: 'qualifiedNum',
			display: '�ʼ�ϸ�����',
			sortable: true,
			width : 80
		},{
			name: 'stockNum',
			display: '�������',
			sortable: true,
			width : 60
		},{
			name: 'taskCode',
			display: '�������񵥺�',
			sortable: true,
			width : 120
		},{
			name: 'relDocCode',
			display: '��ͬ���',
			sortable: true
		},{
			name: 'applyDocCode',
			display: '�������뵥��',
			sortable: true
		},{
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			width : 150
		},{
			name: 'productionBatch',
			display: '��������',
			sortable: true
		},{
			name: 'planStartDate',
			display: '�ƻ���ʼʱ��',
			sortable: true,
			align : 'center'
		},{
			name: 'planEndDate',
			display: '�ƻ�����ʱ��',
			sortable: true,
			align : 'center'
		},{
			name: 'chargeUserName',
			display: '������',
			sortable: true,
			align : 'center'
		},{
			name: 'saleUserName',
			display: '���۴���',
			sortable: true,
			align : 'center'
		},{
			name: 'deliveryDate',
			display: '��������',
			sortable: true,
			align : 'center'
		},{
			name: 'remark',
			display: '��ע',
			sortable: true,
			width : 350
		}],

		//��չ�Ҽ��˵�
		menusEx : [{
			text : '��ӡ',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toView&id=' + row.id ,'1');
			}
		},{
			text : '���',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=produce_plan_produceplan&action=toEdit&id='
					+ row.id
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
			}
		}],

		//��������
		comboEx : [{
			text : '���ȼ�',
			key : 'urgentLevelCode',
			datacode : 'SCJHYXJ'
		},{
			text : '����״̬',
			key : 'docStatus',
			data : [{
				text : 'δȷ��',
				value : '0'
			},{
				text : 'ִ����',
				value : '1'
			},{
				text : '�����',
				value : '2'
			},{
				text : '�ѹر�',
				value : '3'
			}]
		}],

		toAddConfig: {
			toAddFn : function(p ,g) {
				if (g && $("#taskId").val() > 0) {
					showModalWin("?model=produce_plan_produceplan&action=toAddByTask&taskId=" + $("#taskId").val() ,'1');
				}
			}
		},
		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_produceplan&action=toViewTab&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		},{
			name: 'docDate',
			display: '��������'
		},{
			name: 'productName',
			display: '��������'
		},{
			name: 'productCode',
			display: '���ñ���'
		},{
			name: 'taskCode',
			display: '�������񵥺�'
		},{
			name: 'relDocCode',
			display: '��ͬ���'
		},{
			name: 'applyDocCode',
			display: '�������뵥��'
		},{
			name: 'customerName',
			display: '�ͻ�����'
		},{
			name: 'productionBatch',
			display: '��������'
		},{
			name: 'planStartDate',
			display: '�ƻ���ʼʱ��'
		},{
			name: 'planEndDate',
			display: '�ƻ�����ʱ��'
		},{
			name: 'chargeUserName',
			display: '������'
		},{
			name: 'urgentLevel',
			display: '���ȼ�'
		},{
			name: 'saleUserName',
			display: '���۴���'
		},{
			name: 'deliveryDate',
			display: '��������'
		}]
	});
});