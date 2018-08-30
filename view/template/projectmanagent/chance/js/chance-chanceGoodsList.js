var show_page = function(page) {
	$("#chanceGoodsListGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [], $("#chanceGoodsListGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceGoodsJson',
		title : '�����̻�',
		leftLayout : false,
		customCode : 'chanceGoodsListGrid',

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceIds',
			display : '�̻�ids',
			sortable : true,
			hide : true
		}, {
			name : 'goodsName',
			display : '��Ʒ����',
			sortable : true,
			width : 200
		}, {
			name : 'goodsId',
			display : '��Ʒ����',
			sortable : true,
			width : 100
		}, {
			name : 'chanceNum',
			display : '�̻�����',
			sortable : true,
			width : 100,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=chanceListByids&ids='
						+ row.chanceIds
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'numberSum',
			display : '��Ʒ����',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : '�ܼƽ��',
			sortable : true,
			width : 150,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}],
		buttonsEx : buttonsArr,
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_chance_chance&action=chanceGoodsInfoJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'conProductId',// ���ݸ���̨�Ĳ�������
				colId : 'goodsId'// ��ȡ���������ݵ�������
			}
//			,{"status":$("#status").val()}
			],
			// ��ʾ����
			colModel : [{
				name : 'goodsName',
				display : '��Ʒ����',
				width : 150
			}, {
				name : 'goodsId',
				display : '��Ʒ����',
				width : 50
			}, {
				name : 'chanceNum',
				display : '�̻�����',
				width : 50,
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=chanceListByids&ids='
							+ row.chanceIds
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000\')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
			}, {
				name : 'numberSum',
				display : '��Ʒ����'
			}, {
				name : 'chanceMoney',
				display : '�ܼƽ��',
				width : 80,
				process : function(v, row) {
					if (v == '') {
						return "0.00";
					} else {
						return moneyFormat2(v);
					}
				}
			}, {
				name : 'winRate',
				display : '�̻�Ӯ��',
				datacode : 'SJYL',
				width : 80
			//}, {
			//	name : 'chanceStage',
			//	display : '�̻��׶�',
			//	datacode : 'SJJD',
			//	width : 80
			}]
		},
//		comboEx : [{
//			text : '�̻�״̬',
//			key : 'status',
//			value : '5',
//			data : [{
//				text : '������',
//				value : '5'
//			}, {
//				text : '��ͣ',
//				value : '6'
//			}, {
//				text : '�ر�',
//				value : '3'
//			}, {
//				text : '�����ɺ�ͬ',
//				value : '4'
//			}]
//		}],
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
