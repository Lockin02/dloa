var show_page = function(page) {
	$("#chanceEquListGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [], $("#chanceEquListGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceEquListJson',
		title : '�����̻�',
		leftLayout : false,
		customCode : 'chanceEquListGrid',

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
			name : 'productName',
			display : '�豸����',
			sortable : true,
			width : 200
		}, {
			name : 'productId',
			display : '�豸ID',
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
			url : '?model=projectmanagent_chance_chance&action=chanceEquInfoJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'productId',// ���ݸ���̨�Ĳ�������
				colId : 'productId'// ��ȡ���������ݵ�������
			}, {
				paramId : 'winRate',// ���ݸ���̨�Ĳ�������
				colId : 'winRate'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				name : 'chanceCode',
				width : 100,
				display : '�̻����'
			}, {
				name : 'chanceName',
				display : '�̻�����',
				width : 150,
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
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
				name : 'productName',
				display : '�豸����',
				width : 200
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
//		// ��չ�Ҽ��˵�
//		menusEx : [{
//			text : '���۱�������',
//			icon : 'add',
//			action : function(row) {
//				if (row) {
//					showThickboxWin("?model=projectmanagent_stockup_stockup&action=toAdd"
//					        + "&sourceType=chanceEqu"
//					        + "&sourceId=" + row.productId
//							+ "&skey="
//							+ row['skey_']
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1000");
//				}
//			}
//
//		}],
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
			display : '�豸����',
			name : 'productName'
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
