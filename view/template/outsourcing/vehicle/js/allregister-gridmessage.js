var show_page = function(page) {
	$("#allregisterGrid").yxgrid("reload");
};

$(function() {
    var paramData={};
    if($("#projectId").val()>0){
        paramData={
            'ExaStatusArr' : "��������,���,���",
            'projectId':$("#projectId").val()
        };
    }else{
        paramData={
            'ExaStatusArr' : "��������,���,���"
        };
    }
	$("#allregisterGrid").yxgrid({
		model: 'outsourcing_vehicle_allregister',
		action : 'messageJson',
		param : paramData,
		title: '�ó���Ϣ�����ܣ�',
		bodyAlign : 'center',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,

		//����Ϣ
		colModel: [{
			name: 'allRegisterId',
			display: '�⳵�Ǽǻ���ID',
			sortable: true,
			hide: true
		},{
			name: 'registerId',
			display: '�⳵�Ǽ�ID',
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
					case '1' : return 'δ����';break;
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
			name: 'projectType',
			display: '��Ŀ����',
			sortable: true,
			width : 70
		},{
            name: 'projectManager',
            display: '��Ŀ����',
            sortable: true,
            width : 80
        },{
			name: 'officeName',
			display: '��  ��',
			sortable: true,
			width : 70
		},{
			name: 'province',
			display: 'ʡ  ��',
			sortable: true,
			width : 70
		},{
			name: 'city',
			display: '��  ��',
			sortable: true,
			width : 70
		},{
			name: 'suppName',
			display: '��Ӧ������',
			sortable: true,
			width : 120
		},{
			name: 'rentalContractCode',
			display: '�⳵��ͬ���',
			sortable: true,
			width : 120
		},{
            name: 'contractType',
            display: '��ͬ����',
            sortable: true,
            width :80
        },{
			name: 'carNum',
			display: '���ƺ�',
			sortable: true,
			width : 100
		},{
			name: 'rentalPropertyCode',
			display: '�⳵����',
			sortable: true,
			width : 80,
			hide : true
		},{
			name: 'UseDay',
			display: '��ͬ�ó�����',
			sortable: true,
			width : 80,
			process : function(v ,row) {
				if (row.rentalPropertyCode == 'ZCXZ-01') {
					return row.contractUseDay;
				}
				return row.registerNum;
			}
		},{
			name: 'registerNum',
			display: 'ʵ���ó�����',
			sortable: true,
			width : 80
		},{
			name: 'startMileage',
			display: '���³���̣����',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 120
		},{
			name: 'endMileage',
			display: '����ĩ��̣����',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 120
		},{
			name: 'effectMileage',
			display: '��Ч��̣����',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 120
		},{
			name: 'gasolineKMPrice',
			display: '������Ƽ��ͷѵ��ۣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 160
		},{
			name: 'gasolineKMCost',
			display: '������Ƽ��ͷѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 140
		},{
			name: 'reimbursedFuel',
			display: 'ʵ��ʵ���ͷѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 140
		},{
			name: 'rentalCarCost',
			display: '�⳵�ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'parkingCost',
			display: 'ͣ���ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'tollCost',
			display: '·�ŷѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'mealsCost',
			display: '�����ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'accommodationCost',
			display: 'ס�޷ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'overtimePay',
			display: '�Ӱ�ѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'specialGas',
			display: '�����ͷѣ�Ԫ��',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
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
						+ parseFloat(row.tollCost ? row.tollCost : 0)
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
		},{
			name: 'estimate',
			display: '����',
			sortable: true,
			width : 350,
			align : 'left'
		},{
			name: 'remark',
			display: '��ע',
			sortable: true,
			width : 600,
			align : 'left'
		}],

		lockCol : ['useCarDate','projectCode'], //����������

		//��չ�˵�
		buttonsEx : [{
			name : 'exportOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_vehicle_allregister&action=toExcelOutMessage"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		}],

		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toRecord&id=" + get['allRegisterId'] + "&registerId=" + get['registerId'],'1');
				}
			}
		},

		comboEx : [{
			text: "״̬",
			key: 'state',
			data : [{
				text : 'δ����',
				value : '1'
			},{
				text : '�������',
				value : '2'
			},{
				text : '���',
				value : '3'
			}]
		}],

		searchitems: [{
			display: "�ó�ʱ��",
			name: 'useCarDateSea'
		},{
			display: "��Ŀ���",
			name: 'projectCodeSea'
		},{
			display: "��Ŀ����",
			name: 'projectNameSea'
		},{
			display: "��  ��",
			name: 'officeNameSea'
		},{
			display: "ʡ  ��",
			name: 'actualUseDaySea'
		},{
			display: "��  ��",
			name: 'actualUseDaySea'
		},{
			display: "��Ӧ������",
			name: 'suppNameSea'
		},{
			display: "�⳵��ͬ���",
			name: 'rentalContractCodeSea'
		},{
			display: "��  ��",
			name: 'estimate'
		},{
			display: "��  ע",
			name: 'remark'
		}]
	});

});