var show_page = function(page) {
	$("#recommendGrid").yxgrid("reload");
};

$(function() {
	$("#recommendGrid").yxgrid({
		model : 'hr_recruitment_recommend',
		title : '�ڲ��Ƽ�',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		param : {
			groupBy : 'c.id',
			stateArr : '1,2,3,4,5,6,8,9'
		},

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_recommend&action=toTabPage&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700\",1)'>"
						+ v + "</a>";
			}
		},{
			name : 'formDate',
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'isRecommendName',
			display : '������',
			sortable : true,
			width : 60
		},{
			name : 'positionName',
			display : '�Ƽ�ְλ',
			sortable : true
		},{
			name : 'recommendName',
			display : '�Ƽ���',
			sortable : true,
			width : 60
		},{// ״̬ת����̨����
			name : 'stateC',
			display : '״̬',
			width : 60
		},{
			name : 'hrJobName',
			display : '¼��ְλ',
			sortable : true
		},{
			name : 'becomeDate',
			display : 'Ԥ��ת��ʱ��',
			sortable : true,
			width : 80
		},{
			name : 'realBecomeDate',
			display : 'ʵ��ת��ʱ��',
			sortable : true,
			width : 80
		},{
			name : 'quitDate',
			display : '��ְʱ��',
			sortable : true,
			width : 80
		},{
			name : 'recruitManName',
			display : '��������',
			sortable : true,
			width : 60
		},{
			name : 'assistManName',
			display : 'Э����',
			sortable : true,
			width : 150
		},{
			name : 'isBonus',
			display : '�Ƿ񷢷Ž���',
			sortable : true,
			process : function(v) {
				if (v == 1) {
					return "��";
				} else {
					return "��";
				}
			},
			width : 80
		},{
			name : 'bonus',
			display : '�����',
			sortable : true,
			width : 80
		},{
			name : 'bonusProprotion',
			display : '�ѷ�����',
			sortable : true,
			width : 60
		},{
			name : 'recommendReason',
			display : '�Ƽ�����',
			width : 300,
			sortable : true,
			align : 'left'
		},{
			name : 'closeRemark',
			display : '�������',
			width : 300,
			sortable : true,
			align : 'left'
		}],

		lockCol:['formCode','formDate','isRecommendName'],//����������

		comboEx : [{
			text : '״̬',
			key : 'state',
			data : [{
				text : 'δ���',
				value : '1'
			},{
				text : '�ѷ���',
				value : '2'
			},{
				text : '��ͨ��',
				value : '3'
			},{
				text : '������',
				value : '4'
			},{
				text : '����ְ',
				value : '8'
			},{
				text : '����ְ',
				value : '5'
			},{
				text : '������ְ',
				value : '9'
			},{
				text : '�ر�',
				value : '6'
			}]
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_recommend&action=toTabPage&id=" + get[p.keyField]+"&stateC="+get.stateC,'1');
				}
			}
		},

		//��չ�˵�
		buttonsEx : [{
			name : 'excelOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toExcelOut"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		}],

		//�Ҽ��˵�
		menusEx : [{
			text : '���为����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toGive&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			text : '�޸ĸ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toChangeHead&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			text : '�����Ƽ���',
			icon : 'add',
			// showMenuFn : function(row) {
			// 	if (row.state == 1 || row.state == 2) {
			// 		return true;
			// 	} else {
			// 		return false;
			// 	}
			// },
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toCheck&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			text : 'ת���ʼ�',
			icon : 'add',
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toForwardMail&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		}],

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "��������",
			name : 'formDate'
		},{
			display : "������",
			name : 'isRecommendName'
		},{
			display : "�Ƽ�ְλ",
			name : 'positionName'
		},{
			display : "�Ƽ���",
			name : 'recommendName'
		},{
			display : "��������",
			name : 'recruitManName'
		},{
			display : "Э����",
			name : 'assistManName'
		},{
			display : "�Ƽ�����",
			name : 'recommendReason'
		},{
			display : "�������",
			name : 'closeRemark'
		}]
	});
});