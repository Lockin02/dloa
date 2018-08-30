var show_page = function(page) {
	$("#basicGrid").yxgrid("reload");
};

$(function() {
	$("#basicGrid").yxgrid({
		model: 'outsourcing_account_basic',
		title: '�������',
		isAddAction: false,
		isDelAction: false,
		isEditAction: false,
		isViewAction: false,
		isOpAction: false,
		showcheckbox: false,
		bodyAlign: 'center',
		param: {
			"stateArr": '1,2'
		},

		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'formCode',
			display: '���ݱ��',
			width: 155,
			sortable: true,
			process: function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_account_basic&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
//			name : 'approvalCode',
//			display : '���������',
//			width:155,
//			sortable : true,
//			process : function(v,row){
//				return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_approval_basic&action=toViewTab&id=" + row.approvalId +"\",1)'>" + v + "</a>";
//			}
//		},{
			name: 'suppVerifyCode',
			display: '������ȷ�ϵ����',
			width: 155,
			sortable: true,
			process: function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_suppVerify&action=toView&id=" + row.suppVerifyId + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'projectCode',
			display: '��Ŀ���',
			width: 125,
			sortable: true
		},{
			name: 'projectName',
			display: '��Ŀ����',
			width: 125,
			sortable: true
		},{
			name: 'outsourcingName',
			display: '�����ʽ',
			width: 65,
			sortable: true
		},{
			name: 'outContractCode',
			display: '������',
			width: 125,
			sortable: true
		},{
			name: 'suppName',
			display: '�����Ӧ��',
			width: 125,
			sortable: true
		},{
			name: 'projectTypeName',
			display: '��Ŀ����',
			width: 55,
			sortable: true
		},{
			name: 'saleManangerName',
			display: '���۸�����',
			width: 105,
			sortable: true
		},{
			name: 'projectManangerName',
			display: '��Ŀ����',
			width: 105,
			sortable: true
		},{
			name: 'payTypeName',
			display: '���ʽ',
			width: 55,
			sortable: true
		},{
			name: 'taxPoint',
			display: '��ֵ˰ר�÷�Ʊ˰��',
			width: 105,
			sortable: true
		},{
			name: 'state',
			display: '״̬',
			width: 65,
			sortable: true,
			process : function (v) {
				if (v == 1) {
					return 'δȷ��';
				}
				return '��ȷ��';
			}
		},{
			name: 'ExaStatus',
			display: '����״̬',
			width: 65,
			sortable: true
		},{
			name: 'createName',
			display: '������',
			width: 55,
			sortable: true
		},{
			name: 'createTime',
			display: '¼������',
			width: 120,
			sortable: true
		}],

		//��������
		comboEx: [{
			text: '����״̬',
			key: 'ExaStatus',
			data: [{
				text: 'δ����',
				value: 'δ����'
			},{
				text: '��������',
				value: '��������'
			},{
				text: '���',
				value: '���'
			},{
				text: '���',
				value: '���'
			}]
		},{
			text: '�����ʽ',
			key: 'outsourcing',
			datacode: 'HTWBFS'
		}],

		menusEx: [{
			text: "�鿴",
			icon: 'view',
			action: function(row) {
				showModalWin("?model=outsourcing_account_basic&action=toView&id=" + row.id, '1');
			}
		},{
			text: "�������ȷ��",
			icon: 'add',
			showMenuFn: function(row) {
				if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action: function(row) {
				showModalWin("?model=outsourcing_account_basic&action=toAffirm&id=" + row.id, '1');
			}
		},{
			text: "�ύ����",
			icon: 'add',
			showMenuFn: function(row) {
				if ((row.ExaStatus == 'δ����' || row.ExaStatus == '���') && row.state == '2') {
					return true;
				}
				return false;
			},
			action: function(row) {
				showThickboxWin('controller/outsourcing/account/ewf_index.php?actTo=ewfSelect&billId=' + row.id + '&billDept=' + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			}
		},{
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action: function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_account&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}
		,{
				name : 'payapply',
				text : '���븶��',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == "���"){
						return true;
					}
					else
						return false;
				},
				action : function(row, rows, grid) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_account_basic&action=getCanApply",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 0) {
								alert('��������Ϊ0,���������븶��');
							    return false;
							}else{
								showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-05&objId=" + row.id);
							}
						}
					});
				}

		}],
		searchitems: [{
			display: "���ݱ��",
			name: 'formCode'
		},{
			display: "���������",
			name: 'approvalCode'
		},{
			display: "��Ŀ���",
			name: 'projectCode'
		},{
			display: "��Ŀ����",
			name: 'projectName'
		},{
			display: "������",
			name: 'outContractCode'
		},{
			display: "�����Ӧ��",
			name: 'suppName'
		},{
			display: "��Ŀ����",
			name: 'projectTypeName'
		},{
			display: "���۸�����",
			name: 'saleManangerName'
		},{
			display: "��Ŀ����",
			name: 'projectManangerName'
		}]
	});
});