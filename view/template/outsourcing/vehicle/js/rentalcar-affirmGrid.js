var show_page = function(page) {
	$("#rentalcarGrid").yxgrid("reload");
};
$(function() {
	var buttonsArr = [];
	var excelOut = {
		name : 'excelOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			window.open("?model=outsourcing_vehicle_rentalcar&action=excelOut"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=40&width=60");
		}
	};
	var excelOutCustom = {
		name : 'exportOut',
		text : "�Զ��嵼��",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_vehicle_rentalcar&action=toExcelOutCustom"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=outsourcing_vehicle_rentalcar&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOut);
				buttonsArr.push(excelOutCustom);
			}
		}
	});

	$("#rentalcarGrid").yxgrid({
		model : 'outsourcing_vehicle_rentalcar',
		param : {'stateArr' : "5','6','7"},
		title : '�⳵������������',
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
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width : 160,
			process: function(v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=outsourcing_vehicle_rentalcar&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'state',
			display : '����״̬',
			sortable : true,
			width : 60,
			process : function (v) {
				switch (v) {
					case '0' : return '����';break;
					case '1' : return '������';break;
					case '2' : return '�������';break;
					case '3' : return '���';break;
					case '4' : return '�ӿ������';break;
					case '5' : return '��ȷ��';break;
					case '6' : return '���';break;
					case '7' : return '���';break;
					default : return '';
				}
			}
		},{
//			name : 'ExaStatus',
//			display : '����״̬',
//			sortable : true,
//			width : 60,
//			process : function (v) {
//				if (v == 0) {
//					return 'δ�ύ';
//				}
//				return v;
//			}
//		},{
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			width : 200
		},{
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 200
		},{
			name : 'projectType',
			display : '��Ŀ����',
			sortable : true,
			width : 60
		},{
			name : 'rentalProperty',
			display : '�⳵����',
			sortable : true,
			width : 60
		},{
			name : 'createName',
			display : '������',
			sortable : true,
			width : 80
		},{
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 130
		},{
			name : 'applicantPhone',
			display : '�����˵绰',
			sortable : true,
			width : 80
		},{
			name : 'province',
			display : '�ó��ص�',
			sortable : true,
			process : function (v ,row) {
				return v + "-" + row.city;
			}
		},{
			name : 'useCarAmount',
			display : '�ó�����',
			sortable : true,
			width : 50
		},{
			name : 'expectStartDate',
			display : 'Ԥ�ƿ�ʼ�ó�ʱ��',
			sortable : true
		},{
			name : 'useCycle',
			display : '�ó�����',
			sortable : true
		}],

		buttonsEx : buttonsArr,

		menusEx : [{
			text : 'ȷ�Ϲ�Ӧ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '5') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=outsourcing_vehicle_rentalcar&action=toAffirmEdit&id=' + row.id ,1);
			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '5') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_vehicle_rentalcar&action=toBack&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=900");
			}
		},{
			text : '���ԭ��',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.backReason != '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_vehicle_rentalcar&action=toBackReason&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=900");
			}
		},{
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_rentalcar&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: "����״̬",
			key: 'state',
			data : [{
				text : '��ȷ��',
				value : '5'
			},{
				text : '���',
				value : '7'
			},{
				text : '���',
				value : '6'
			}]
		},{
			text: "�⳵����",
			key: 'rentalPropertyCode',
			datacode : 'GCZCXZ'
		}],

		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_vehicle_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},

		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_rentalcar&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "��Ŀ���",
			name : 'projectCode'
		},{
			display : "��Ŀ����",
			name : 'projectName'
		},{
			display : "��Ŀ����",
			name : 'projectType'
		},{
			display : "������",
			name : 'createNameSea'
		},{
			display : "����ʱ��",
			name : 'createTimeSea'
		},{
			display : "����绰",
			name : 'applicantPhone'
		},{
			display : "�ó��ص�",
			name : 'useCarPlace'
		},{
			display : "�ó�����",
			name : 'useCarAmountSea'
		},{
			display : "Ԥ�ƿ�ʼ�ó�ʱ��",
			name : 'expectStartDateSea'
		},{
			display : "�ó�����",
			name : 'useCycleSea'
		}]
 	});
 });