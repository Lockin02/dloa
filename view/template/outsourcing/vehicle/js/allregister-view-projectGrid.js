var show_page = function(page) {
	$("#allregisterGrid").yxgrid("reload");
};

$(function() {
	$("#allregisterGrid").yxgrid({
		model: 'outsourcing_vehicle_allregister',
		param : {
			'projectId' : $("#projectId").val()
		},
		title: '�⳵�Ǽǻ���',
		bodyAlign : 'center',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'state',
			display: '״̬',
			sortable: true,
			width : 60,
			process : function (v) {
				switch (v) {
					case '0' : return 'δ�ύ';break;
					case '1' : return '������';break;
					case '2' : return '�������';break;
					case '3' : return '���';break;
					default : return '';
				}
			}
		},{
			name: 'useCarDate',
			display: '�ó�ʱ��',
			sortable: true,
			process : function (v) {
				return v.substr(0, 7);
			},
			width : 60
		},{
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width : 200
		},{
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width : 200
		},{
			name: 'projectManager',
			display: '��Ŀ����',
			sortable: true,
			width : 160
		},{
			name: 'actualUseDay',
			display: 'ʵ���ó�����',
			sortable: true,
			width : 80
		},{
			name: 'effectMileage',
			display: '��Ч���',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'rentalCarCost',
			display: '�⳵�ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'reimbursedFuel',
			display : 'ʵ��ʵ���ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMCost',
			display : '������Ƽ��ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'parkingCost',
			display: 'ͣ���ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'tollCost',
			display: '·�ŷѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'mealsCost',
			display: '�����ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'accommodationCost',
			display: 'ס�޷ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'overtimePay',
			display: '�Ӱ�ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'specialGas',
			display: '�����ͷѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'allCost',
			display: '�ܷ��ã�Ԫ��',
			sortable: true,
			process : function (v ,row) {
				var sum = parseFloat(row.rentalCarCost ? row.rentalCarCost : 0)
						+ parseFloat(row.reimbursedFuel ? row.reimbursedFuel : 0)
						+ parseFloat(row.gasolineKMCost ? row.gasolineKMCost : 0)
						+ parseFloat(row.parkingCost ? row.parkingCost : 0)
						+ parseFloat(row.mealsCost ? row.mealsCost : 0)
						+ parseFloat(row.accommodationCost ? row.accommodationCost : 0)
						+ parseFloat(row.overtimePay ? row.overtimePay : 0)
						+ parseFloat(row.specialGas ? row.specialGas : 0);
				return moneyFormat2(sum ,2);
			}
		},{
			name: 'effectLogTime',
			display: '��ЧLOGʱ��',
			sortable: true
		}],

		menusEx : [{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_allregister&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: "״̬",
			key: 'state',
			data : [{
				text : 'δ�ύ',
				value : '0'
			},{
				text : '������',
				value : '1'
			},{
				text : '�������',
				value : '2'
			},{
				text : '���',
				value : '3'
			}]
		}],

		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
		searchitems: [{
			display: "�ó�ʱ��",
			name: 'useCarDateSea'
		},{
			display: "ʵ���ó�����",
			name: 'actualUseDaySea'
		}]
	});

});