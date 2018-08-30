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
		action : 'myMainPageJson',
		param : {
			groupBy : 'c.id',
			stateArr : '2,3,4,5,6'
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
			name : 'assistManName',
			display : 'Э����',
			width : 150
		},{
			name : 'recommendReason',
			display : '�Ƽ�����',
			width : 300,
			sortable : true
		},{
			name : 'closeRemark',
			display : '�������',
			width : 300,
			sortable : true
		}],

		lockCol:['formCode','formDate','isRecommendName'], //����������

		menusEx : [{
			text : '��Ӽ���',
			icon : 'add',
			showMenuFn : function(row){
				if(row.stateC == '��ͨ��') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showOpenWin("?model=hr_recruitment_recommend&action=toTabPage&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650",1);
			}
		},{
			text : '����Ƽ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == 2||row.state == 4||row.state == 5||row.state == 6) {
					return true;
				} else
				return false;
			},
			action : function(row) {
				showOpenWin("?model=hr_recruitment_recomBonus&action=toAdd&recomid="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650",1);
			}
		},{
			text : '�����Ƽ���',
			icon : 'add',
			// showMenuFn : function(row) {
			// 	if (row.state == 2) {
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

		// ���ӱ������
		comboEx : [{
			text : '״̬',
			key : 'state',
			data : [{
				text : '����',
				value : '0'
			},{
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
					showOpenWin("?model=hr_recruitment_recommend&action=toTabPage&id="
						+ get[p.keyField]+"&stateC="+get['stateC'],'1');
				}
			}
		},

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