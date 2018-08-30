var show_page = function(page) {
	$("#esmchangeGrid").yxgrid("reload");
};

$(function() {
	$("#esmchangeGrid").yxgrid({
		model : 'engineering_change_esmchange',
		title : '��Ŀ������뵥',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true
			}, {
				name : 'newBudgetAll',
				display : '��Ԥ��(��)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetAll){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetAll',
				display : '��Ԥ��(��)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetField',
				display : '�ֳ�Ԥ��(��)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetField){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetField',
				display : '�ֳ�Ԥ��(��)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetPerson',
				display : '����Ԥ��(��)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetPerson){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetPerson',
				display : '����Ԥ��(��)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetEqu',
				display : '�豸Ԥ��(��)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetEqu){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetEqu',
				display : '�豸Ԥ��(��)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetOutsourcing',
				display : '���Ԥ��(��)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetOutsourcing){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetOutsourcing',
				display : '���Ԥ��(��)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'actEndDate',
				display : '��Ŀ��������',
				sortable : true,
				width : 80
			}, {
				name : 'changeTimes',
				display : '�������',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'applyName',
				display : '���������',
				sortable : true
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
				width : 70
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 60
			}, {
				name : 'ExaDT',
				display : '��������',
				sortable : true,
				width : 80,
				hide : true
			}
		],
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=engineering_change_esmchange&action=toView&id=" + rowData[p.keyField],1 );
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
//			text : '�ύ����',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "���ύ") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				$.ajax({
//				    type: "POST",
//				    url: "?model=engineering_project_esmproject&action=getRangeId",
//				    data: {'projectId' : row.projectId },
//				    async: false,
//				    success: function(data){
//				   		if(data != ''){
//							showThickboxWin('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId='
//								+ row.id + "&billArea=" + data
//								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//						}else{
//							showThickboxWin('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId='
//								+ row.id
//								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//						}
//					}
//				});
//			}
//		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus != "���ύ")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_esm_change_baseinfo&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text: "����״̬",
			key: 'ExaStatus',
			type : 'workFlow'
		}],
		searchitems : [{
				display : "��Ŀ���",
				name : 'projectCode'
			}, {
				display : "��Ŀ����",
				name : 'projectName'
			}, {
				display : "���������",
				name : 'applyName'
			}]
	});
});