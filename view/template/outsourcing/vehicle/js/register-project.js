var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};

$(function() {
	$("#registerGrid").yxgrid({
		model: 'outsourcing_vehicle_register',
		title: '������Ӧ��-��Ŀ��Ϣ',
		action: 'projectList',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isViewAction: false,
		param: {
			'suppId': $("#suppId").val()
		},
		bodyAlign: 'center',
		showcheckbox: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'suppName',
			display: '��Ӧ������',
			sortable: true
		},{
			name: 'suppCode',
			display: '��Ӧ�̱��',
			sortable: true,
			hide: true
		},{
			name: 'suppId',
			display: '��Ӧ��Id',
			sortable: true,
			hide: true
		},{
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width : 200
		},{
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width : 200
		},{
			name: 'carNum',
			display: '���ƺ�',
			sortable: true,
			process: function(v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=outsourcing_vehicle_register&action=pageView"
						+ "&carNum=" + row.carNum
						+ "&allregisterId=" + row.allregisterId
						+ "&placeValuesBefore&TB_iframe=true&modal=false\",\"1\")'>" + v + "</a>";
			}
		},{
			name: 'useCarDate',
			display: '�ó�����',
			sortable: true,
			process: function(v) {
				return v.substr(0, 7);
			}
		},{
			name: 'rentalProperty',
			display: '�⳵����',
			sortable: true
		},{
			name: 'rentalPropertyCode',
			display: '�⳵���ʱ��',
			sortable: true,
			hide: true
		},{
			name: 'rentalContractCode',
			display: '�⳵��ͬ���',
			sortable: true
		},{
			name: 'rentalCarCost',
			display: '�⳵����',
			sortable: true
		},{
			name: 'estimate',
			display: '����',
			sortable: true,
			width : 300,
			align : 'left'
		}],
		menusEx: [{
			text: '�鿴',
			icon: 'view',
			action: function(row) {
				showModalWin("?model=outsourcing_vehicle_register&action=pageView"
						+ "&carNum=" + row.carNum
						+ "&allregisterId=" + row.allregisterId
						+ "&placeValuesBefore&TB_iframe=true&modal=false");
			}
		}],

		buttonsEx: [{
			name: 'excelOut',
			text: "����",
			icon: 'excel',
			action: function(row) {
				showThickboxWin("?model=outsourcing_vehicle_register&action=toExcelOutProject"
					+ "&suppId=" + $("#suppId").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
			}
		}],

		toAddConfig: {
			formWidth: 1000,
			formHeight: 300
		},
		toEditConfig: {
			formWidth: 1000,
			formHeight: 300,
			action: 'toEdit'
		},
		toViewConfig: {
			formWidth: 1000,
			formHeight: 500,
			action: 'toView'
		},

		searchitems: [{
			display: "���ƺ�",
			name: 'carNumber'
		},{
			display: "��Ŀ����",
			name: 'projectNameSea'
		},{
			display: "��Ŀ���",
			name: 'projectCodeE'
		},{
			display: "�⳵��ͬ���",
			name: 'rentalContractCodeE'
		}]
	});
});