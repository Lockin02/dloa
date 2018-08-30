var show_page = function(page) {
	$("#produceplanGrid").yxgrid("reload");
};

$(function() {
	$("#produceplanGrid").yxgrid({
		model: 'produce_plan_produceplan',
		param : {
			startWeekDate : $("#startWeekDate").val(),
			endWeekDate : $("#endWeekDate").val()
		},
		title: '���������ƻ�',
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
			width : 140,
			process : function (v ,row) {
				if (row.docStatus == 0) {
					v = '<img title="�����������ƻ�" src="images/new.gif">' + v;
				} else if (row.docStatus == 1) {
					if (row.planNum > row.stockNum) {
						var nowData = new Date();
						var dateArr = (row.planEndDate).split('-');
						var planEndDate = new Date(dateArr[0] ,parseInt(dateArr[1]) ,parseInt(dateArr[2]));
						if (nowData.getTime() > planEndDate.getTime()) {
							v = '<img title="��ʱ�������ƻ�" src="images/icon/hred.gif">' + v;
						}
					}
				}
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
					case '0' : return "δȷ��";break;
					case '1' : return "ִ����";break;
					case '2' : return "�����";break;
					case '3' : return "�ѹر�";break;
					default : return "--";
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
			name: 'productName',
			display: '��������',
			sortable: true,
			width : 200
		},{
			name: 'productCode',
			display: '���ϱ���',
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

		//��������
		comboEx : [{
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
		},{
			text : '���ȼ�',
			key : 'urgentLevelCode',
			datacode : 'SCJHYXJ'
		}],

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
			display: '���ϱ���'
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