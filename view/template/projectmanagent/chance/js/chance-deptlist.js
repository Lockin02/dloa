var show_page = function(page) {
	$("#deptchanceGrid").yxgrid("reload");
};
$(function() {
	$("#deptchanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		title : '���������̻�',

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'chanceName',
					display : '�̻�����',
					sortable : true
				},{
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
								+ row.oldId
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
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
				//				+ "<font color = '#4169E1'>"
				//				+ v
				//				+ "</font>"
				//				+ '</a>';
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
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					}
				}, {
					name : 'predictContractDate',
					display : 'Ԥ�ƺ�ͬǩ������',
					sortable : true,
					hide : true
				}, {
					name : 'predictExeDate',
					display : 'Ԥ�ƺ�ִͬ������',
					sortable : true,
					hide : true
				}, {
					name : 'contractPeriod',
					display : '��ִͬ�����ڣ��£�',
					sortable : true,
					hide : true
				}, {
					name : 'rObjCode',
					display : 'oaҵ����',
					sortable : true,
					hide : true
				}, {
					name : 'contractCode',
					display : '��ͬ��',
					sortable : true,
					hide : true
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
				}],
		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + row.id + "&skey="+row['skey_']
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
				}
			}

		},{

			text : 'ָ����Ŀ�Ŷ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3' || row.status == '4'|| row.status == '6') {
					return false;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_chance_chance&action=deptTrackman&id='
						+ row.id + "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
		}],
		//��������
		searchitems : [{
			display : '�̻�����',
			name : 'chanceName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}],
		//Ĭ������˳��
		sortorder : "DSC",
		//��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});