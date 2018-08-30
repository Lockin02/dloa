var show_page = function(page) {
	$("#contactGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [];

	//��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=toContactExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelOutArr2 = {
		name : 'exportOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=toContactExcelOut"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
			}
		}
	});
	$("#contactGrid").yxgrid({
		model : 'hr_personnel_personnel',
		action:"contractPageJson",
		title : '��Ա��ϵ��Ϣ',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton:false,
		bodyAlign:'center',
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
			width:60,
			process : function(v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_personnel&action=toContactView&id="
					+ row.id
					+ '&skey='
					+ row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
					+ v + "</a>";
			}
		},{
			name : 'staffName',
			display : '����',
			width:60,
			sortable : true
		},{
			name : 'companyName',
			display : '��˾',
			width:60,
			sortable : true
		},{
			name : 'belongDeptName',
			display : '��������',
			width:80,
			hide : true
		},{
			name : 'deptName',
			display : 'ֱ������',
			width:80,
			sortable : true
		},{
			name : 'deptNameS',
			display : '��������',
			width:80,
			sortable : true
		},{
			name : 'deptNameT',
			display : '��������',
			width:80,
			sortable : true
		},{
            name : 'deptNameF',
            display : '�ļ�����',
            width:80,
            sortable : true
        },{
			name : 'telephone',
			display : '�̶��绰',
			sortable : true
		},{
			name : 'mobile',
			display : '�ƶ��绰',
			sortable : true
		},{
			name : 'personEmail',
			display : '���˵�������',
			sortable : true
		},{
			name : 'findCompanyEmail',
			display : '��˾����',
			sortable : true
		},{
			name : 'homePhone',
			display : '��ͥ�绰',
			sortable : true
		},{
			name : 'emergencyName',
			display : '������ϵ��',
			sortable : true
		},{
			name : 'emergencyRelation',
			display : '������ϵ�˹�ϵ',
			sortable : true
		},{
			name : 'emergencyTel',
			display : '������ϵ�˵绰',
			sortable : true
		},{
			name : 'unitPhone',
			display : '��λ�绰',
			sortable : true
		},{
			name : 'extensionNum',
			display : '�ֻ���',
			sortable : true
		},{
			name : 'unitFax',
			display : '��λ����',
			sortable : true
		},{
			name : 'shortNum',
			display : '�̺�',
			sortable : true
		},{
			name : 'otherPhone',
			display : '�����ֻ�',
			sortable : true
		},{
			name : 'otherPhoneNum',
			display : '��������',
			sortable : true
		}],

		lockCol:['userNo','staffName'],//����������

		menusEx:[{
			text:'�޸�',
			icon:'edit',
			action:function(row) {
				if(row) {
					showModalWin("?model=hr_personnel_personnel&action=toMyContactEdit&id=" + row.id  + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=400");
				}
			}
		}],

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toContactView'
		},

		searchitems : [{
			display : "Ա�����",
			name : 'userNoSearch'
		},{
			display : "Ա������",
			name : 'staffNameSearch'
		},{
			display : "ֱ������",
			name : 'deptNameSearch'
		},{
			display : "��������",
			name : 'deptNameSSearch'
		},{
			display : "��������",
			name : 'deptNameTSearch'
		},{
            display : "�ļ�����",
            name : 'deptNameFSearch'
        },{
			display : "ְλ",
			name : 'jobNameSearch'
		},{
			display : "��˾",
			name : 'companyNameSearch'
		},{
			display : "��������",
			name : 'personEmail'
		},{
			display : "��˾����",
			name : 'compEmailA'
		}]
	});
});