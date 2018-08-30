var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};

//�鿴Ա������
function viewPersonnel(id, userNo, userAccount) {
	var skey = "";
	$.ajax({
		type : "POST",
		url : "?model=hr_personnel_personnel&action=md5RowAjax",
		data : {
			"id" : id
		},
		async : false,
		success : function(data) {
			skey = data;
		}
	});
	showModalWin("?model=hr_personnel_personnel&action=toTabView&id=" + id
		+ "&userNo=" + userNo + "&userAccount=" + userAccount
		+ "&skey=" + skey, 'newwindow1',
		'resizable=yes,scrollbars=yes');
}

$(function() {
	//��ͷ��ť����
	buttonsArr = [{
		name : 'view',
		text : "��ѯ",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	}];

	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		action : 'pageJsonWaitEntry',
		title : '��ת��Ա���б�',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		customCode : 'personnelWaitGrid',

		//����Ϣ
		colModel : [{
			name : 'becomeMonth',
			display : 'Ԥ��ת���·�',
			width : 80
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
					+ row.id + "\",\"" + row.userNo + "\",\""
					+ row.userAccount + "\")' >" + v + "</a>";
			},
			width : 70
		},{
			name : 'staffName',
			display : '����',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
					+ row.id + "\",\"" + row.userNo + "\",\""
					+ row.userAccount + "\")' >" + v + "</a>";
			},
			width : 60
		},{
			name : 'personLevel',
			display : '�����ȼ�',
			sortable : true,
			width : 50
		},{
			name : 'deptName',
			display : '����',
			sortable : true,
			width : 80
		},{
			name : 'deptNameS',
			display : '��������',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'deptNameT',
			display : '��������',
			sortable : true,
			width : 80,
			hide : true
		},{
            name : 'deptNameF',
            display : '�ļ�����',
            sortable : true,
            width : 80,
            hide : true
        },{
			name : 'officeName',
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width : 80
		},{
			name : 'becomeDate',
			display : 'ת������',
			sortable : true,
			width : 80
		},{
			name : 'lastBecomeDate',
			display : 'ת������',
			sortable : true,
			width : 60
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width : 50,
			hide : true
		},{
			name : 'jobLevel',
			display : 'ְ��',
			sortable : true,
			hide : true
		},{
			name : 'nativePlace',
			display : '����',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'nativePlacePro',
			display : '����ʡ��',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'nativePlaceCity',
			display : '�������',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'companyType',
			display : '��˾����',
			sortable : true,
			hide : true
		},{
			name : 'companyName',
			display : '��˾',
			sortable : true,
			hide : true
		},{
			name : 'jobName',
			display : 'ְλ',
			sortable : true,
			hide : true
		},{
			name : 'employeesStateName',
			display : 'Ա��״̬',
			sortable : true,
			width : 60,
			hide : true
		},{
			name : 'personnelTypeName',
			display : 'Ա������',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'positionName',
			display : '��λ����',
			sortable : true,
			hide : true
		},{
			name : 'personnelClassName',
			display : '��Ա����',
			sortable : true,
			hide : true
		},{
			name : 'baseScore',
			display : '������',
			sortable : true,
			hide : true,
			width : 60
		},{
			name : 'allScore',
			display : '�ۼƻ���',
			sortable : true,
			hide : true,
			width : 60
		},{
			name : 'trialPlanProcess',
			display : 'ת����չ',
			sortable : true,
			width : 60,
			process : function(v){
				return v + " %";
			}
		},{
			name : 'trialPlanProcessC',
			display : 'Ԥ�ƽ���',
			sortable : true,
			width : 60,
			process : function(v){
				return v + " %";
			},
			hide : true
		},{
			name : 'diff',
			display : '���',
			sortable : true,
			width : 60,
			process : function(v) {
				if( v * 1 < 0){
					return "<span class='red'>" + v + " %</span>";
				} else {
					return v + " %";
				}
			}
		},{
			name : 'trialPlan',
			display : '��ѵ�ƻ�',
			sortable : true,
			width : 130,
			process : function(v ,row) {
				if(row.trialPlanId == "0") {
					return "<span class='blue'>" + v + "</span>" ;
				} else {
					return v;
				}
			}
		},{
			name : 'trialTask',
			display : '��ѵ����',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'deptSuggest',
			display : '���Ž���',
			sortable : true,
			datacode : 'HRBMJY',
			width : 80
		},{
			name : 'suggestion',
			display : '��������',
			sortable : true,
			width : 120,
			hide : true
		},  {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		},{
			name : 'ExaDT',
			display : '��������',
			sortable : true,
			width : 70
		}],

		lockCol:['userNo','staffName','deptName'],//����������

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		//��չ�Ҽ�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin(
						"?model=hr_personnel_personnel&action=toTabView&id="
						+ row.id + "&skey=" + row['skey_']
						+ "&userNo=" + row.userNo + "&userAccount="
						+ row.userAccount, 'newwindow1',
						'resizable=yes,scrollbars=yes');
				}
			}
		},{
			text : '������ѵ�ƻ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.trialPlanId != "" && row.trialPlanId != "0") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin(
						"?model=hr_trialplan_trialplan&action=toSelectModel&id="
						+ row.id + "&skey=" + row['skey_']
						+ "&userNo=" + row.userNo + "&userAccount=" + row.userAccount + "&userName="
						+ row.userName, 'newwindow1',
						'resizable=yes,scrollbars=yes');
				}
			}
		},{
			text : '�鿴�ƻ���չ',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_trialplan_trialplandetail&action=viewList&userAccount="
						+ row.userAccount
						+ "&userName="
						+ row.userName
						+ "&planId="
						+ row.trialPlanId ,1);
				}
			}
		},{
			text : '¼�벿�Ž���',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.suggestId == '' || row.deptSuggest == 'HRBMJY-00') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_personnel_personnel&action=toDeptSuggest&id="
						+ row.id
						+ "&skey=" + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		},{
			text : '�鿴���Ž���',
			icon : 'view',
			showMenuFn : function(row) {
				if(row.suggestId == '' || row.deptSuggest == 'HRBMJY-00'){
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_trialplan_trialdeptsuggest&action=toView&id="
						+ row.suggestId
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
//		},{
//			text : '�޸Ĳ��Ž���',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if(row.ExaStatus == '���ύ'){
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("?model=hr_trialplan_trialdeptsuggest&action=toReEdit&id="
//						+ row.suggestId
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
//				}
//			}
		}],

		//��������
		comboEx:[{
			text:'���Ž���',
			key:'deptSuggest',
			datacode : 'HRBMJY',
			value : 'HRBMJY-00'
		},{
			text: "����״̬",
			key: 'tExaStatus',
			type : 'workFlow'
		}],

		searchitems : [{
			display : "Ա�����",
			name : 'userNoSearch'
		},{
			display : "����",
			name : 'staffNameSearch'
		},{
			display : "����",
			name : 'deptNameSearch'
		},{
			display : "Ԥ��ת���·�",
			name : 'becomeMonth'
		},{
			display : "�����ȼ�",
			name : 'personLevelSearch'
		},{
			display : "��������",
			name : 'officeName'
		},{
			display : "��ְ����",
			name : 'entryDateSearch'
		},{
			display : "ת������",
			name : 'becomeDateSearch'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "��ѵ�ƻ�",
			name : 'trialPlan'
		},{
			display : "���Ž���",
			name : 'deptSuggest'
		}]
	});
});