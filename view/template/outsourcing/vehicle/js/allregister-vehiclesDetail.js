var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};
$(function() {
	$("#registerGrid").yxgrid({
		model : 'outsourcing_vehicle_register',
		action :��'vehiclesDetailPage',
		param : {
			'stateD' : 2,
			'state' : 1
		},
		title : '�⳵�Ǽ���ϸ��',
		bodyAlign : 'center',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'driverName',
			display : '˾������',
			sortable : true,
			width : 70
		},{
			name : 'createName',
			display : '¼����',
			sortable : true,
			width : 80
		},{
			name : 'createTime',
			display : '¼��ʱ��',
			sortable : true,
			width : 120
		},{
			name : 'useCarDate',
			display : '�ó�����',
			sortable : true,
			width : 80
		},{
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 200
		},{
			name : 'province',
			display : 'ʡ��',
			sortable : true,
			width : 80
		},{
			name : 'city',
			display : '����',
			sortable : true,
			width : 80
		},{
			name : 'carNum',
			display : '����',
			sortable : true,
			width : 80
		},{
			name : 'startMileage',
			display : '��ʼ���',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'endMileage',
			display : '�������',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectMileage',
			display : '��Ч���',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolineCost',
			display : '�ͷѣ�Ԫ��',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolinePrice',
			display : '�ͼۣ�Ԫ/����',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'parkingCost',
			display : '·��\ͣ���ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'rentalCarCost',
			display : '�⳵�ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'mealsCost',
			display : '�����ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'accommodationCost',
			display : 'ס�޷ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectLogTime',
			display : '��ЧLOGʱ��',
			sortable : true
		}],

		toViewConfig : {
			formWidth : 1000,
			formHeight : 620,
			action : 'toView'
		},
 		buttonsEx : [{
			name : 'excelOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_vehicle_register&action=toExcelDetail&carNum=" +$("#carNum").val()
						+ "&allregisterId=" + $("#allregisterId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
			}
		}],
		menusEx : [{
			text : "���ԭ��",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('?model=outsourcing_vehicle_register&action=toChangeReason&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=690&width=1000");
			}
        },{
			name : 'view',
			text : "������־",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcing_register"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],

		searchitems : [{
			display : "˾������",
			name : 'driverNameSea'
		},{
			display : "¼����",
			name : 'createNameSea'
		},{
			display : "¼��ʱ��",
			name : 'createTimeSea'
		},{
			display : "�ó�����",
			name : 'useCarDateSea'
		},{
			display : "��Ŀ����",
			name : 'projectNameSea'
		},{
			display : "ʡ��",
			name : 'provinceSea'
		},{
			display : "����",
			name : 'citySea'
		}]
	});
});