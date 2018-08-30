var show_page = function(page) {
	$("#chanceProductListGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [], $("#chanceProductListGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceProductJson',
		title : '�����̻�',
		leftLayout : false,
		customCode : 'chanceProductListGrid',

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceType',
			display : '�̻�����',
			datacode : 'SJLX',
			sortable : true
		}, {
			name : 'conProductName',
			display : '���۲�Ʒ����',
			sortable : true,
			width : 200
		}, {
			name : 'conProductId',
			display : '���۲�ƷId',
			sortable : true,
			width : 200,
			hide : true
		}, {
			name : 'numberSum',
			display : '����',
			sortable : true
		}, {
			name : 'winRate',
			display : 'Ӯ��',
			datacode : 'SJYL',
			sortable : true
		}],
		buttonsEx : buttonsArr,
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_chance_chance&action=chanceProInfoJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'conProductId',// ���ݸ���̨�Ĳ�������
				colId : 'conProductId'// ��ȡ���������ݵ�������
			}, {
				paramId : 'winRate',// ���ݸ���̨�Ĳ�������
				colId : 'winRate'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				name : 'chanceCode',
				width : 100,
				display : '�̻����',
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}, {
				name : 'chanceName',
				display : '�̻�����',
				width : 150
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
				sortable : true,
				width : 50
			}, {
				name : 'conProductName',
				display : '��Ʒ����',
				width : 150
			}, {
				name : 'number',
				display : '����',
				width : 50
			}, {
			//	name : 'chanceStage',
			//	display : '�̻��׶�',
			//	datacode : 'SJJD',
			//	sortable : true,
			//	width : 50
			//}, {
				name : 'predictContractDate',
				display : 'Ԥ�ƺ�ͬǩ������',
				sortable : true
			}]
		},
		comboEx : [{
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
		// ��������
		searchitems : [{
			display : '�̻����',
			name : 'chanceCode'
		}, {
			display : '�̻�����',
			name : 'chanceName'
		}, {
			display : '��Ʒ����',
			name : 'goodsNameSer'
		}],
		// Ĭ������˳��
		sortorder : "DSC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});
