var show_page = function(page) {
	$("#trackGrid").yxgrid("reload");
};
$(function() {
	var d = new Date();
	var year = d.getFullYear();
	var month = d.getMonth() + 1; // �ǵõ�ǰ����Ҫ+1��
	var dt = d.getDate();
	var today = year + "-" + month + "-" + dt;

	function daysBetween(DateOne,DateTwo)
	{
	    var OneMonth = DateOne.substring(5,DateOne.lastIndexOf ('-'));
	    var OneDay = DateOne.substring(DateOne.length,DateOne.lastIndexOf ('-')+1);
	    var OneYear = DateOne.substring(0,DateOne.indexOf ('-'));

	    var TwoMonth = DateTwo.substring(5,DateTwo.lastIndexOf ('-'));
	    var TwoDay = DateTwo.substring(DateTwo.length,DateTwo.lastIndexOf ('-')+1);
	    var TwoYear = DateTwo.substring(0,DateTwo.indexOf ('-'));

	    var cha=((Date.parse(OneMonth+'/'+OneDay+'/'+OneYear)- Date.parse(TwoMonth+'/'+TwoDay+'/'+TwoYear))/86400000);
	    return cha;
	}
	$("#trackGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		action : 'pageJsonMyTrack',
		title : '�ҵ������̻�',
		event : {
				'row_dblclick' : function(e, row, data) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + data.id + "&skey="+row['skey_']
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
 				}
			},
		showcheckbox : false,
		formHeight : 600,
        lockCol:['flag'],//����������
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '��ͨ��',
			sortable : true,
			width : 40,
			process : function(v, row) {
			 if (row.id == "allMoney" || row.id == undefined) {
				 return "�ϼ�";
			 }
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
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
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (numberOfDays<0 && row.status == '5'){
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = 'red'>" + v + "</font>" + '</a>';
				}else{
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
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
//		}, {
//			name : 'chanceStage',
//			display : '�̻��׶�',
//			datacode : 'SJJD',
//			sortable : true,
//			process : function(v, row) {
//				if(v == "******" || v == '' || v == undefined){
//				   return "******";
//				}else{
//				   return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=boostChanceStageInfo&id='
//						+ row.id
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
//						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
//
//				}
//			}
		}, {
			name : 'winRate',
			display : '�̻�Ӯ��',
			datacode : 'SJYL',
			sortable : true,
			process : function(v, row) {
				if(v == "******" || v == '' || v == undefined){
				  return "******";
				}else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=winRateInfo&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
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
		}, {
					name : 'signSubject',
					display : 'ǩԼ����',
					sortable : true,
					datacode : 'QYZT',
					width : 60
				}],

		comboEx : [{
					text : '�̻�����',
					key : 'chanceType',
					datacode : 'HTLX'
				},
		   {
			text : '�̻�״̬',
			key : 'status',
			value : '5',
			data : [ {
				text : '������',
				value : '5'
			},{
				text : '��ͣ',
				value : '6'
			}, {
				text : '�ر�',
				value : '3'
			},{
				text : '�����ɺ�ͬ',
				value : '4'
			}  ]
		}
		],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_chance_chance&action=toViewTab&perm=view&id=" + row.id + "&skey="+row['skey_']);
				}
			}

		},{
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status != '5') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=updateChance&id=" + row.id + "&skey="+row['skey_']
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
				}
			}

		},{

			text : '���¶�����Ϣ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status != '5') {
					return false;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_chance_chance&action=toCompetitor&chanceId='
						+ row.id + "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1000');

			}
		},{
			text : '����֧��',
			icon : 'add',
            showMenuFn : function(row){
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (row.status != '5' || numberOfDays<0) {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=projectmanagent_chance_chance&action=toAppSupport&objId=" + row.id + "&skey="+row['skey_'] + '&objCode=' + row.chanceCode + '&objName=' + row.chanceName
					         + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=400');
				}
			}

		},{

			text : '��д���ټ�¼',
			icon : 'add',
			showMenuFn : function(row) {
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (row.status != '5' || numberOfDays<0) {
					return false;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_track_track&action=toChanceTrack&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		// ��������
		searchitems : [{
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

//		toAddConfig : {
//			text : '�½�',
//			icon : 'add',
//			/**
//			 * Ĭ�ϵ��������ť�����¼�
//			 */
//
//			toAddFn : function(p) {
//               self.location ="?model=projectmanagent_chance_chance&action=toAdd";
//			}
//		}
	});
});