var show_page = function(page) {
	$("#trialdeptsuggestGrid").yxgrid("reload");
};

$(function() {
	$("#trialdeptsuggestGrid").yxgrid({
		model : 'hr_trialplan_trialdeptsuggest',
		title : '���Ž���',
		isAddAction : false,
//		isEditAction : false,
		isDelAction : false,
		customCode : 'trialdeptsuggestGrid',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width : 80
		},{
			name : 'userAccount',
			display : 'Ա���˺�',
			sortable : true,
			hide : true
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width : 80
		},{
			name : 'deptName',
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'deptId',
			display : '����Id',
			sortable : true,
			hide : true
		},{
			name : 'jobName',
			display : 'ְλ����',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'deptSuggest',
			display : '���Ž���',
			sortable : true,
			hide : true
		},{
			name : 'deptSuggestName',
			display : '���Ž���',
			sortable : true,
			width : 80
		},{
			name : 'suggestion',
			display : '��������',
			sortable : true,
			width : 150
		},{
			name : 'permanentDate',
			display : '�ƻ�ת������',
			sortable : true,
			width : 70
		},{
			name : 'beforeSalary',
			display : '������������',
			sortable : true,
			width : 70,
			process : function(v) {
				return moneyFormat2(v);
			}
		},{
			name : 'afterSalary',
			display : '���Ž��鹤��',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name : 'hrSalary',
			display : '���½��鹤��',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name : 'beforePersonLv',
			display : 'ԭ�����ȼ�',
			sortable : true,
			width : 70
		},{
			name : 'personLevel',
			display : '���Ž���ȼ�',
			sortable : true,
			width : 70
		},{
			name : 'afterPositionName',
			display : 'ת����ְλ',
			sortable : true,
			width : 70
//			},{
//				name : 'levelName',
//				display : 'ת����ְ��',
//				sortable : true,
//				width : 70
		},{
			name : 'levelCode',
			display : 'ת����ְ�����',
			sortable : true,
			hide : true
		},{
			name : 'positionCode',
			display : 'ת����ְλ���',
			sortable : true,
			hide : true
		},{
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		},{
			name : 'ExaDT',
			display : '��������',
			sortable : true,
			width : 70
		},{
			name : 'status',
			display : '״̬',
			sortable : true,
			hide : true
		},{
			name : 'createName',
			display : '������',
			sortable : true
		},{
			name : 'createId',
			display : '������ID',
			sortable : true,
			hide : true
		},{
			name : 'createTime',
			display : '����ʱ��',
			sortable : true
		},{
			name : 'updateName',
			display : '�޸���',
			sortable : true,
			hide : true
		},{
			name : 'updateId',
			display : '�޸���ID',
			sortable : true,
			hide : true
		},{
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true,
			hide : true
		}],

		lockCol:['userNo','userName','deptName'],//����������

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : 'toEdit',
			formWidth : 900,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 900,
			formHeight : 500
		},

		//��չ�Ҽ�
		menusEx : [{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if(row.hrSalary == '') {
					if(confirm('δ¼�����½��鹤�ʣ������ύ������Ҫ���ڽ���¼����')){
						showThickboxWin('?model=hr_trialplan_trialdeptsuggest&action=toEdit&id='
							+ row.id
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					} else {
						return false;
					}
				} else {
					if(row.hrSalary *1 != row.afterSalary *1 || row.beforePersonLv != row.personLevel){
						showThickboxWin('controller/hr/trialplan/ewf_index1.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					} else {
						showThickboxWin('controller/hr/trialplan/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}
				}
			}
		}],

		searchitems : [{
			display : "Ա�����",
			name : 'userNo'
		},{
			display : "Ա������",
			name : 'userNameSearch'
		},{
			display : "��������",
			name : 'deptName'
		},{
			display : "���Ž���",
			name : 'deptSuggestName'
		},{
			display : "��������",
			name : 'suggestion'
		},{
			display : "�ƻ�ת������",
			name : 'permanentDate'
		},{
			display : "������������",
			name : 'beforeSalary'
		},{
			display : "���Ž��鹤��",
			name : 'afterSalary'
		},{
			display : "���½��鹤��",
			name : 'hrSalary'
		},{
			display : "ԭ�����ȼ�",
			name : 'beforePersonLv'
		},{
			display : "���Ž���ȼ�",
			name : 'personLevel'
		},{
			display : "ת����ְλ",
			name : 'afterPositionName'
		},{
			display : "������",
			name : 'createName'
		},{
			display : "����ʱ��",
			name : 'createTime'
		}]
	});
});