var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};

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
	showModalWin(
		"?model=hr_personnel_personnel&action=toDegreeTabView&id="
		+ id + "&userNo=" + userNo + "&userAccount=" + userAccount
		+ "&skey=" + skey, 'newwindow1',
		'resizable=yes,scrollbars=yes');
}

$(function() {
	// ��ͷ��ť����
	var buttonsArr = [{
		name : 'view',
		text : "�߼���ѯ",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
		}
	}];

	// ��ͷ��ť����
	excelOutArr2 = {
		name : 'excelOutAllArr',
		text : "����������Ϣ",
		icon : 'excel',
		action : function() {
			if($("#totalSize").val()<1){
				alert("û�пɵ����ļ�¼");
			} else {
				document.getElementById("form2").submit();
			}
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
				buttonsArr.push(excelOutArr2);
			}
		}
	});

	var levelArr = [];
	$.ajax({
		url : '?model=engineering_baseinfo_eperson&action=listJson',
		async : false,
		success : function(data) {
			data = eval("(" + data + ")");
			for (var i = 0; i < data.length; i++) {
				var opt = {
					text : data[i].personLevel,
					value : data[i].personLevel
				};
				levelArr.push(opt);
			}
		}
	});

	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		title : '��Ա������Ϣ',
		action : 'pageJsonForRead',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
		bodyAlign : 'center',
		event : {
			afterload : function(data ,g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
			}
		},
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			width:60,
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
					+ row.id
					+"\",\""
					+ row.userNo
					+"\",\""
					+row.userAccount
					+ "\")' >"
					+ v
					+ "</a>";
			}
		},{
			name : 'staffName',
			display : '����',
			width:60,
			sortable : true
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width:50
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
			name : 'personLevel',
			display : '�����ȼ�',
			sortable : true,
			width:60
		},{
			name : 'officeName',
			display : '��������',
			width:60,
			sortable : true
		},{
			name : 'eprovinceCity',
			display : '�޲�������',
			sortable : true
		},{
			name : 'technologyName',
			display : '��������',
			sortable : true
		},{
			name : 'networkName',
			display : '����',
			width:60,
			sortable : true
		},{
			name : 'deviceName',
			display : '�豸���Ҽ�����',
			sortable : true
		},{
			name : 'jobName',
			display : 'ְλ',
			width:80,
			sortable : true
		},{
			name : 'employeesStateName',
			display : 'Ա��״̬',
			sortable : true,
			width:60
		},{
			name : 'isNeedTutor',
			display : '��ʦ״̬',
			sortable : true,
			width : 90,
			process : function(v, row) {
				if (v == 1) {
					return "����Ҫָ����ʦ";
				} else {
					if (row.isTut == 1) {
						return "��ָ����ʦ";
					} else {
						return "δָ����ʦ";
					}
				}
			}
		},{
			name : 'personnelTypeName',
			display : 'Ա������',
			width:60,
			sortable : true
		},{
			name : 'entryDate',
			display : '��ְ����',
			width:70,
			sortable : true
		},{
			name : 'becomeDate',
			display : 'ת������',
			width:70,
			sortable : true
		},{
			name : 'mobile',
			display : '�ƶ��绰',
			width:100,
			sortable : true
		},{
			name : 'compEmail',
			display : '��˾����',
			width:180,
			sortable : true
		},{
			name : 'highEducationName',
			display : '���ѧ��',
			width:60,
			sortable : true
		},{
			name : 'highSchool',
			display : '��ҵѧУ',
			width:120,
			sortable : true
		},{
			name : 'professionalName',
			display : 'רҵ',
			width:80,
			sortable : true
		},{
			name : 'outsourcingSupp',
			display : '�����˾',
			width:80,
			sortable : true
		},{
			name : 'outsourcingName',
			display : '�������',
			width:80,
			sortable : true
		}],
		lockCol:['userNo','staffName'],//����������
		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		comboEx:[{
			text:'��ʦ״̬',
			key:'tutorState',
			data:[{
				text:'����Ҫָ����ʦ',
				value:'1'
			},{
				text:'δָ����ʦ',
				value:'2'
			},{
				text:'��ָ����ʦ',
				value:'3'
			}]
		}],

		// ��չ�Ҽ�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_personnel_personnel&action=toDegreeTabView&id="
						+ row.id+"&userNo=" +row.userNo + "&userAccount=" + row.userAccount
						+ "&skey=" + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1000");
				}
			}
		},{
			text : '�޸�',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_personnel_personnel&action=toDegreeEdit&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000");
				} else {
					alert("��ѡ��һ����¼��Ϣ");
				}
			}
		},{
			name : 'view',
			text : "������־",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
					+ row.id
					+ "&tableName=oa_hr_personnel"
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		},{
			text : 'ָ����ʦ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isNeedTutor != 1 && row.isTut == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toSetTutor&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&userNo="
						+ row.userNo
						+ "&userAccount="
						+ row.userAccount
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		},{
			text : '��ָ����ʦ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isNeedTutor != 1 && row.isTut == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toUnsetTutor&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&userNo="
						+ row.userNo
						+ "&userAccount="
						+ row.userAccount
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		searchitems : [{
			display : "Ա�����",
			name : 'userNoSearch'
		},{
			display : "��  ��",
			name : 'staffNameSearch'
		},{
			display : "��  ��",
			name : 'sex'
		},{
			display : "��  ˾",
			name : 'companyNameSearch'
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
			display : "ְ  λ",
			name : 'jobNameSearch'
		},{
			display : "Ա��״̬",
			name : 'employeesStateNameSearch'
		},{
			display : "Ա������",
			name : 'personnelTypeNameSearch'
		},{
			display : "�����ȼ�",
			name : 'personLevel'
		},{
			display : "��������",
			name : 'officeName'
		},{
			display : "�޲�������",
			name : 'eprovinceCity'
		},{
			display : "��������",
			name : 'technologyName'
		},{
			display : "����",
			name : 'networkName'
		},{
			display : "�豸���Ҽ�����",
			name : 'deviceName'
		},{
			display : "��ְ����",
			name : 'entryDateSearch'
		},{
			display : "ת������",
			name : 'becomeDateSearch'
		},{
			display : "�ƶ��绰",
			name : 'mobileSearch'
		},{
			display : "��˾����",
			name : 'compEmail'
		},{
			display : "���ѧ��",
			name : 'highEducationName'
		},{
			display : "��ҵѧУ",
			name : 'highSchool'
		},{
			display : "רҵ",
			name : 'professionalName'
		},{
			display : "�����˾",
			name : 'outsourcingSupp'
		},{
			display : "�������",
			name : 'outsourcingName'
		}]
	});
});
