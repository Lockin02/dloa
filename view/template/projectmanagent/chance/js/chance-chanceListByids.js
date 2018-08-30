var show_page = function(page) {
	$("#chanceListByidsGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [],
	$("#chanceListByidsGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceGridJson',
		param : {"ids" : $("#ids").val()},
		title : '�����̻�',
		customCode : 'chancegridA',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
						+ data.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
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
			name : 'chanceType',
			display : '��Ŀ����',
			datacode : 'SJLX',
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
		},{
		    name : 'goodsNameStr',
		    display : '��Ʒ����',
		    width : 200,
		    sortable : false
		}],
		buttonsEx : buttonsArr,
		comboEx : [{
			text : '�̻�ӯ��',
			key : 'winRate',
			data : [{
				text : '0%',
				value : '0'
			}, {
				text : '25%',
				value : '25'
			}, {
				text : '50%',
				value : '50'
			}, {
				text : '80%',
				value : '80'
			}, {
				text : '100%',
				value : '100'
			}]
		//}, {
		//	text : '�̻��׶�',
		//	key : 'chanceStage',
		//	data : [{
		//		text : '�׶�һ',
		//		value : 'SJJD01'
		//	}, {
		//		text : '�׶ζ�',
		//		value : 'SJJD02'
		//	}, {
		//		text : '�׶���',
		//		value : 'SJJD03'
		//	}, {
		//		text : '�׶���',
		//		value : 'SJJD04'
		//	}, {
		//		text : '�׶���',
		//		value : 'SJJD05'
		//	}, {
		//		text : '�׶���',
		//		value : 'SJJD06'
		//	}]
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}

		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_chance_goods&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'chanceId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
				name : 'goodsName',
				width : 200,
				display : '��Ʒ����'
			}, {
				name : 'number',
				display : '����',
				width : 80
			}, {
				name : 'money',
				display : '���',
				width : 80,
				process : function(v, row) {
					if (v == '') {
						return "0.00";
					} else {
						return moneyFormat2(v);
					}
				}
			}]
		},
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
		}, {
			display : '��Ʒ����',
			name : 'goodsName'
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
