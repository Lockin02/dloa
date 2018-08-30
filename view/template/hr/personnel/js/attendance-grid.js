var show_page = function(page) {
	$("#attendanceGrid").yxgrid("reload");
};
$(function() {

	buttonsArr = [];

	// ��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_attendance&action=toImport"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_attendance&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});
	$("#attendanceGrid").yxgrid({
		model : 'hr_personnel_attendance',
		title : '������Ϣ',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userNo',
			display : 'Ա�����',
			sortable : true
		}, {
			// name : 'userAccount',
			// display : 'Ա���˺�',
			// sortable : true
			// }, {
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_attendance&action=toView&id="
						+ row.id
						+ '&skey='
						+ row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
						+ v + "</a>";
			}
		}, {
			// name : 'companyType',
			// display : '��˾����',
			// sortable : true
			// }, {
			name : 'companyName',
			display : '��˾����',
			sortable : true
		}, {
			name : 'deptNameS',
			display : '��������',
			sortable : true
		}, {
			name : 'deptNameT',
			display : '��������',
			sortable : true
		}, {
			name : 'beginDate',
			display : '��ʼʱ��',
			sortable : true
		}, {
			name : 'days',
			display : '����',
			sortable : true
		}, {
			// name : 'typeCode',
			// display : '������ͱ���',
			// sortable : true
			// }, {
			name : 'typeName',
			display : '�������',
			sortable : true
		}, {
			// name : 'docStatus',
			// display : '״̬����',
			// sortable : true
			// }, {
			name : 'docStatusName',
			display : '״̬',
			sortable : true
		}, {
			name : 'inputName',
			display : '�Ƶ�������',
			sortable : true
		}],
		buttonsEx : buttonsArr,
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_personnel_attendance&action=toView&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// toEditConfig : {
		// action : 'toEdit'
		// },
		// toViewConfig : {
		// action : 'toView'
		// },
		searchitems : [{
			display : "Ա�����",
			name : 'userNoM'
		}, {
			display : "Ա������",
			name : 'userNameM'
		}, {
			display : "����",
			name : 'deptName'
		}]
	});
});