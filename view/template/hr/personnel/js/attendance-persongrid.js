var show_page = function(page) {
	$("#attendanceGrid").yxgrid("reload");
};
$(function() {
	$("#attendanceGrid").yxgrid({
		model : 'hr_personnel_attendance',
		param : { 'userNo': $("#userNo").val()},
		title : '������Ϣ',
		//����Ϣ
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
//			name : 'companyType',
//			display : '��˾����',
//			sortable : true
//		}, {
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
//			name : 'typeCode',
//			display : '������ͱ���',
//			sortable : true
//		}, {
			name : 'typeName',
			display : '�������',
			sortable : true
		}, {
//			name : 'docStatus',
//			display : '״̬����',
//			sortable : true
//		}, {
			name : 'docStatusName',
			display : '״̬����',
			sortable : true
		}, {
//			name : 'inputId',
//			display : '�Ƶ���id',
//			sortable : true
//		}, {
			name : 'inputName',
			display : '�Ƶ�������',
			sortable : true
		}],
//		buttonsEx : [{
//			name : 'import',
//			text : '����',
//			icon : 'excel',
//			action : function(row, rows, grid) {
//				showThickboxWin("?model=hr_personnel_attendance&action=toImport"
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
//			}
//		}] ,
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
//		toEditConfig : {
//			action : 'toEdit'
//		},
//		toViewConfig : {
//			action : 'toView'
//		},
		searchitems : [{
			display : "����",
			name : 'deptName'
		}]
	});
});