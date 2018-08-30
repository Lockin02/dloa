var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};

//�鿴Ա������
function viewPersonnel(id,userNo,userAccount){
	var skey = "";
	$.ajax({
		type: "POST",
		url: "?model=hr_personnel_personnel&action=md5RowAjax",
		data: {"id" : id},
		async: false,
		success: function(data){
			skey = data;
		}
	});
	showModalWin("?model=hr_personnel_personnel&action=toTabView&id=" + id
		+ "&userNo=" + userNo + "&userAccount=" + userAccount + "&skey=" + skey
		,'newwindow1','resizable=yes,scrollbars=yes');
}

$(function() {
	//��ͷ��ť����
	var buttonsArr = [{
		name : 'view',
		text : "����",
		icon : 'add',
		action : function() {
			showModalWin("?model=hr_personnel_personnel&action=toAdd",'newwindow1','resizable=yes,scrollbars=yes');
		}
	},{
		name : 'view',
		text : "�߼���ѯ",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
		}
	}];

	//��ͷ��ť����
	var excelOutArr = {
		name : 'exportIn',
		text : "���������Ϣ",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	//��ͷ��ť����
	var excelUpdateArr = {
		name : 'exportIn',
		text : "������Ϣ����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=toExcelEdit"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};


	//��ͷ��ť����
	var inleaveExcelArr = {
		name : 'inLeaveExportIn',
		text : "��������ְ��Ϣ",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=toInLeaveExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	var fileImportArr={
		text : "���븽��",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=importStaffFile"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	var excelOutSelect = {
		name : 'excelOutSelect',
		text : "����������Ϣ",
		icon : 'excel',
		action : function() {
			if($("#totalSize").val() < 1) {
				alert("û�пɵ����ļ�¼");
			}else{
				document.getElementById("form2").submit();
			}
		}
	};

	var excelOutOtherSelect = {
		name : 'excelOutOtherSelect',
		text : "����������Ϣ",
		icon : 'excel',
		action : function() {
			if($("#totalSize").val()<1){
				alert("û�пɵ����ļ�¼");
			}else{
				document.getElementById("form3").submit();
			}
		}
	};

//	var excelOutAllArr={
//		text : "����",
//		icon : 'excel',
//		action : function(row,rows,grid) {
////			alert(grid.listSql);
////			showModalWin("?model=hr_personnel_personnel&action=excelOutAll"
////		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
//		}
//	}

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data ==1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(inleaveExcelArr);
				buttonsArr.push(excelUpdateArr);
				buttonsArr.push(excelOutSelect);
				buttonsArr.push(excelOutOtherSelect);
			}
		}
	});

	//���븽��Ȩ��
	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '���븽��Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data ==1) {
				buttonsArr.push(fileImportArr);
			}
		}
	});

	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		title : '��Ա������Ϣ',
		action : 'personnelPageJson',
		showcheckbox:true,
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		isViewAction:false,
		isOpButton:false,
		bodyAlign:'center',
		event : {
			'afterload' : function(data,g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
				$("#otherListSql").val(g.listSql);
				$("#otherTotalSize").val(g.totalSize);
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
			sortable : true,
			width:60,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
					+ row.id
					+ "\",\""
					+  row.userNo
					+ "\",\""
					+ row.userAccount
					+ "\")' >"
					+ v
					+ "</a>";
			}
		},{
			name : 'staffName',
			display : '����',
			sortable : true,
			width:60,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
					+ row.id
					+"\",\""
					+ row.userNo
					+"\",\""
					+row.userAccount
					+"\")' >"
					+ v
					+ "</a>";
			}
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width:40
		},{
			name : 'companyType',
			display : '��˾����',
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
			hide : true,
			sortable : true
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
			name : 'jobName',
			display : 'ְλ',
			width:80,
			sortable : true
		},{
			name : 'isNeedTutor',
			display : '��ʦ״̬',
			sortable : true,
			width :90,
			process : function(v, row) {
				if(v == 1) {
					return "����Ҫָ����ʦ";
				}else{
					if(row.isTut == 1) {
						return "��ָ����ʦ";
					}else{
						return "δָ����ʦ";
					}
				}
			}
		},{
			name : 'employeesStateName',
			display : 'Ա��״̬',
			sortable : true,
			width:60
		},{
			name : 'personnelTypeName',
			display : 'Ա������',
			width:60,
			sortable : true
		},{
			name : 'positionName',
			display : '��λ����',
			width:60,
			sortable : true
		},{
			name : 'personnelClassName',
			display : '��Ա����',
			width:60,
			sortable : true
		},{
			name : 'wageLevelName',
			display : '���ʼ���',
			width:70,
			sortable : true
		},{
			name : 'jobLevel',
			display : 'ְ��',
			width:60,
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
			key:'personnelTutorState',
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

		//��չ�Ҽ�
		menusEx:[{
			text:'�鿴',
			icon:'view',
			action:function(row,rows,grid){
				if(row){
					showModalWin("?model=hr_personnel_personnel&action=toTabView&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
						'newwindow1','resizable=yes,scrollbars=yes');
				}
			}
		},{
			text:'�޸�',
			icon:'edit',
			action:function(row,rows,grid){
				if(row){
					showModalWin("?model=hr_personnel_personnel&action=toTabEdit&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
						'newwindow1','resizable=yes,scrollbars=yes');
				}else{
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
			name : 'view',
			text : "�����켣",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperationItem&action=businessView&pkValue="
					+ row.id
					+ "&tableName=oa_hr_personnel"
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		},{
			name : 'associatePosition',
			text : "����ְλ����",
			icon : 'add',
			action : function(row ,rows ,grid) {
				showModalWin("?model=hr_personnel_personnel&action=toAssociatePosition&id=" + row.id + "&skey=" + row['skey_'] ,'newwindow1' ,'resizable=yes,scrollbars=yes');
			}
		// },{
		// 	name : 'fileLimits',
		// 	text : "����Ȩ�޹���",
		// 	icon : 'add',
		// 	action : function(row, rows, grid) {
		// 		showThickboxWin("?model=hr_personnel_personnel&action=toFileLimits&id="
		// 			+ row.id
		// 			+ "&skey=" + row['skey_']
		// 			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
		// 	}
		}],

		searchitems : [{
			display : "Ա�����",
			name : 'userNoSearch'
		},{
			display : "����",
			name : 'staffNameSearch'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "��˾",
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
			display : "ְλ",
			name : 'jobNameSearch'
		},{
			display : "����",
			name : 'regionNameSearch'
		},{
			display : "Ա��״̬",
			name : 'employeesStateNameSearch'
		},{
			display : "Ա������",
			name : 'personnelTypeNameSearch'
		},{
			display : "��λ����",
			name : 'positionNameSearch'
		},{
			display : "��Ա����",
			name : 'personnelClassNameSearch'
		},{
			display : "ְ��",
			name : 'jobLevelSearch'
		}]
	});
});