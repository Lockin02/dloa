var show_page = function(page) {
	$("#esmmemberGrid").yxsubgrid("reload");
};


//��Ŀ�鿴����
//function viewProject(id) {
//	var skey = "";
//	$.ajax({
//		type: "POST",
//		url: "?model=engineering_project_esmproject&action=md5RowAjax",
//		data: { "id" : id },
//		async: false,
//		success: function(data){
//			skey = data;
//		}
//	});
//	showModalWin("?model=engineering_project_esmproject&action=viewTab&id="
//		+ id + "&skey=" + skey
//	);
//}

$(function() {
	$("#esmmemberGrid").yxsubgrid({
		model : 'engineering_member_esmmember',
		action : 'pageJsonCostMoney',
		title : '������Ŀ������Ϣ',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		noCheckIdValue : 'noId',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 140
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 160
			}, {
				name : 'statusName',
				display : '��Ŀ״̬',
				sortable : true,
				width : 80
			}, {
				name : 'memberId',
				display : '��Աid',
				width : 80,
				hide : true
			}, {
				name : 'costMoney',
				display : '¼�����',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unconfirmMoney',
				display : 'δȷ�Ϸ���',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'confirmMoney',
				display : '��ȷ�Ϸ���',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'backMoney',
				display : '��ط���',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unexpenseMoney',
				display : 'δ��������',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expensingMoney',
				display : '�ڱ�������',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expenseMoney',
				display : '�ѱ�������',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}
		],
		// ���ӱ������
		subGridOptions : {
			url : '?model=engineering_cost_esmcostdetail&action=pageJsonCostMoney',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
					paramId : 'createId',// ���ݸ���̨�Ĳ�������
					colId : 'memberId'// ��ȡ���������ݵ�������
				},{
					paramId : 'projectId',// ���ݸ���̨�Ĳ�������
					colId : 'projectId'// ��ȡ���������ݵ�������
				}
			],
			subgridcheck : true,
			// ��ʾ����
			colModel : [{
					name : 'executionDate',
					display : '��־¼������'
				},{
					name : 'costMoney',
					display : '¼�����',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'unconfirmMoney',
					display : 'δȷ�Ϸ���',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'confirmMoney',
					display : '��ȷ�Ϸ���',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'backMoney',
					display : '��ط���',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'unexpenseMoney',
					display : 'δ��������',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'expensingMoney',
					display : '�ڱ�������',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'expenseMoney',
					display : '�ѱ�������',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}
			]
		},
//		buttonsEx : [{
//			name : 'add',
//			text : "���ɱ�����",
//			icon : 'add',
//			action : function(row, rows, rowIds, g) {
//				if (row) {
//					//������
//					var allRows = g.getAllSubSelectRowDatas();
//
//					var dateArr = [];//ѡ��ı���������
//					var projectId = '';//��Ŀid
//					var sameProject = true;
//					var canExpense = 0;
//
////					$.showDump(allRows)
//					//ѭ������ѡ������
//					for(var i=0; i< allRows.length ; i++){
//						dateArr.push(allRows[i].executionDate);
//
//						//��Ŀid����
//						if(projectId == ''){
//							projectId = allRows[i].projectId;
//						}else{
//							if(projectId != allRows[i].projectId){
//								sameProject = false;
//							}
//						}
//
//						canExpense = accAdd(canExpense,allRows[i].unexpenseMoney,2);
//					}
//
//					//������ڲ�ͬ����Ŀ���򱨴�
//					if(sameProject == false){
//						alert('��ͬ����Ŀ���ò�������һ�ű�������������ѡ��');
//						return false;
//					}
//
//					//������ý��Ϊ0���򱨴�
//					if(canExpense*1 == 0){
//						alert('���ñ������Ϊ0���������ɱ�������');
//						return false;
//					}
//
//					//����������ҳ��
//					if(dateArr.length > 0){
//						showModalWin("?model=finance_expense_expense&action=toEsmExpenseAdd&days="
//							+ dateArr.toString()
//							+ "&projectId="
//							+ projectId
//						);
//					}
//				} else {
//					alert('����ѡ���¼');
//				}
//			}
//		}],
//		menusEx : [{
//			name : 'viewProject',
//			text : "�鿴��Ŀ",
//			icon : 'view',
//			action : function(row){
//				viewProject(row.projectId);
//			}
//		}],
		menusEx : [{
			text : '��ϸ����',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=engineering_cost_esmcostdetail&action=costDetail&projectId=" + row.projectId,1,row.projectId);
			}
		}],
		
		// ����״̬���ݹ���
		comboEx : [{
			text: "��Ŀ״̬",
			key: 'pstatus',
			datacode : 'GCXMZT',
			value : 'GCXMZT02'
		}],
		searchitems : [{
			display : "��Ŀ����",
			name : 'projectNameSearch'
		},{
			display : "��Ŀ���",
			name : 'projectCodeSearch'
		}],
		sortorder : 'DESC',
		sortname : 'p.updateTime'
	});
});