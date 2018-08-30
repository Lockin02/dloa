var show_page = function(page) {
	$("#chanceGrid").yxgrid("reload");
};
$(function() {
	$("#chanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		title : '�ҵ������̻�',
		param : {
			'status' : '0',
			'prinvipalId' : $("#userId").val(),
			'isTemp' : '0'
		},
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
						+ data.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		showcheckbox : false,
		formHeight : 600,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'newUpdateDate',
			display : '�������ʱ��',
			sortable : true
		}, {
			name : 'chanceCode',
			display : '�̻����',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'chanceName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : '��Ŀ�ܶ�',
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'chanceTypeName',
			display : '��Ŀ����',
			sortable : true
		}, {
		//	name : 'chanceStage',
		//	display : '�̻��׶�',
		//	datacode : 'SJJD',
		//	sortable : true,
		//	process : function(v, row) {
		//		return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=boostChanceStageInfo&id='
		//				+ row.id
		//				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
		//				+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
		//	}
		//}, {
			name : 'winRate',
			display : '�̻�Ӯ��',
			datacode : 'SJYL',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=winRateInfo&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'predictContractDate',
			display : 'Ԥ�ƺ�ͬǩ������',
			sortable : true
		}, {
			name : 'predictExeDate',
			display : 'Ԥ�ƺ�ִͬ������',
			sortable : true
		}, {
			name : 'contractPeriod',
			display : '��ִͬ�����ڣ��£�',
			sortable : true
		}, {
			name : 'progress',
			display : '��Ŀ��չ����',
			sortable : true
		}, {
			name : 'Province',
			display : '����ʡ',
			sortable : true
		}, {
			name : 'City',
			display : '������',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '��������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '�̻�������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '�̻�������',
			sortable : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'status',
			display : '�̻�״̬',
			process : function(v) {
				if (v == 0) {
					return "������";
				} else if (v == 3) {
					return "�ر�";
				} else if (v == 4) {
					return "�����ɺ�ͬ";
				} else if (v == 5) {
					return "������"
				} else if (v == 6) {
					return "��ͣ"
				}
			},
			sortable : true
		}, {
			name : 'rObjCode',
			display : 'oaҵ����',
			sortable : true
		}, {
			name : 'contractCode',
			display : '��ͬ��',
			sortable : true
		}],

		comboEx : [{
			text : '�̻�����',
			key : 'chanceType',
			datacode : 'SJLX'
		}, {
			text : '�̻�״̬',
			key : 'status',
			value : '5',
			data : [{
				text : '������',
				value : '5'
			}, {
				text : '��ͣ',
				value : '6'
			}, {
				text : '�ر�',
				value : '3'
			}, {
				text : '�����ɺ�ͬ',
				value : '4'
			}]
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&perm=view&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}],
		// ��������
		searchitems : [{
			display : '�̻����',
			name : 'chanceCode'
		}, {
			display : '�̻�����',
			name : 'chanceName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false

			// toAddConfig : {
			// text : '�½�',
			// icon : 'add',
			// /**
			// * Ĭ�ϵ��������ť�����¼�
			// */
			//
			// toAddFn : function(p) {
			// self.location =
			// "?model=projectmanagent_chance_chance&action=toAdd";
			//			}
			//		}
	});
});