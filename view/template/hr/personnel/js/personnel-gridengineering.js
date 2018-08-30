var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};

$(function(){
	//����
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		mode : 'no'
	});
//	initGrid();
});

//��ʼ���б� - ��ѯ��ϸ
function initGrid(){
	var objGrid = $("#personnelGrid");
	//������־
	objGrid.yxeditgrid("remove").yxeditgrid({
		url : '?model=hr_personnel_personnel&action=listJsonEngineering',
		param : {"searchDate":$("#searchDate").val(),"employeesState":$("#employeesState").val()},
		type : 'view',
		//����Ϣ
		colModel : [{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
						+ row.id
						+ "\",\""
						+ row.userNo
						+ "\",\""
						+ row.userAccount + "\")' >" + v + "</a>";
			},
			hide : true,
			width : 80
		}, {
			name : 'userName',
			display : '����',
			sortable : true,
			width : 80
		}, {
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width : 80
		}, {
			name : 'personLevel',
			display : '�����ȼ�',
			sortable : true,
			width : 60
		}, {
			name : 'belongDeptName',
			display : '��������',
			sortable : true,
			width : 80,
			hide:true
		}, {
			name : 'workGroup',
			display : '������',
			sortable : true,
			width : 80,
			hide:true
		}, {
			name : 'officeName',
			display : '��������',
			sortable : true,
			width : 80,
			hide:true
		}, {
			name : 'eprovinceCity',
			display : '�޲�������',
			sortable : true,
			width : 70
		}, {
			name : 'belongProject',
			display : '������Ŀ',
			sortable : true,
			width : 120
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			hide:true
		}, {
			name : 'projectName',
			display : '������Ŀ',
			sortable : true,
			width : 120
		}, {
			name : 'workStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}, {
			name : 'projectEndDate',
			display : '��ĿԤ�ƽ���',
			sortable : true,
			width : 80
		}, {
			name : 'activityName',
			display : '��������',
			sortable : true
		}, {
			name : 'activityEndDate',
			display : '����Ԥ�ƽ���',
			sortable : true,
			width : 80
		}, {
			name : 'technology',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'net',
			display : '����',
			sortable : true,
			width : 80
		}, {
			name : 'equLevel',
			display : '�豸���Ҽ�����',
			sortable : true,
			width : 80
		}, {
			name : 'updateTime',
			display : '�������',
			sortable : true,
			width : 120,
			hide:true
		}]
	});
}

//����ֵ
function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.param[thisKey] = thisVal;
}

//���������
var gridObj;


//��ʼ���б�
function initPageGrid(){
	var searchDate = $("#searchDate").val();
	var employeesState = $("#employeesState").val();
	var deptId = $("#deptId").val();
	if(gridObj){
		//���б�����ȡ
		var gridData = $("#personnelGrid").data('yxgrid');
		setVal(gridData,'searchDate',searchDate);
		setVal(gridData,'employeesState',employeesState);
		setVal(gridData,'belongDeptId',deptId);
		gridData.reload();
		return false;
	}
	gridObj = $("#personnelGrid");
	var thisHeight = document.documentElement.clientHeight - 120;

	//��ͷ��ť����
	var buttonsArr = [{
		name : 'view',
		text : "��ѯ",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	}, {
		name : 'export',
		text : "����EXCEL",
		icon : 'excel',
		action : function(row) {
			window.open("?model=hr_personnel_personnel&action=outExcelEngineering&belongDeptId="+$("#deptId").val()
				+"&searchDate="+$("#searchDate").val()
				+"&employeesState="+$("#employeesState").val(),
				"", "width=200,height=200,top=200,left=200");
		}
	}];

	gridObj.yxgrid({
		model : 'hr_personnel_personnel',
		action : 'pageJsonEngineering',
		title : '��Ա״̬��',
		height : thisHeight,
		param : { "searchDate" : searchDate,"employeesState" : employeesState,"belongDeptId" : deptId },
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		customCode : 'personnelGrid',
		//����Ϣ
		colModel : [{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
//			process : function(v, row) {
//				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
//						+ row.id
//						+ "\",\""
//						+ row.userNo
//						+ "\",\""
//						+ row.userAccount + "\")' >" + v + "</a>";
//			},
			hide : true,
			width : 80
		}, {
			name : 'userName',
			display : '����',
			sortable : true,
			width : 80
		}, {
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width : 80
		}, {
			name : 'personLevel',
			display : '�����ȼ�',
			sortable : true,
			width : 60
		}, {
			name : 'belongDeptName',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'workGroup',
			display : '������',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'officeName',
			display : '��������',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'eprovinceCity',
			display : '�޲�������',
			sortable : true,
			width : 70
		}, {
			name : 'belongProject',
			display : '������Ŀ',
			sortable : true,
			width : 120
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			hide : true
		}, {
			name : 'projectName',
			display : '������Ŀ',
			sortable : true,
			width : 120
		}, {
			name : 'workStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}, {
			name : 'projectEndDate',
			display : '��ĿԤ�ƽ���',
			sortable : true,
			width : 80
		}, {
			name : 'activityName',
			display : '��������',
			sortable : true
		}, {
			name : 'activityEndDate',
			display : '����Ԥ�ƽ���',
			sortable : true,
			width : 80
		}, {
			name : 'technology',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'net',
			display : '����',
			sortable : true,
			width : 80
		}, {
			name : 'equLevel',
			display : '�豸���Ҽ�����',
			sortable : true,
			width : 80
		}, {
			name : 'updateTime',
			display : '�������',
			sortable : true,
			hide : true,
			width : 120,
			type : 'hidden'
		}],
		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},
		//��չ�Ҽ�
//		menusEx : [{
//			text : '�鿴',
//			icon : 'view',
//			action : function(row, rows, grid) {
//				if (row) {
//					showModalWin(
//						"?model=hr_personnel_personnel&action=toTabView&id="
//								+ row.id + "&skey=" + row['skey_']
//								+ "&userNo=" + row.userNo + "&userAccount="
//								+ row.userAccount, 'newwindow1',
//						'resizable=yes,scrollbars=yes');
//				}
//			}
//		}],
		searchitems : [{
			display : "Ա������",
			name : 'userNameSearch'
		},{
			display : "��ְ����",
			name : 'entryDateSearch'
		}]
	});
}

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