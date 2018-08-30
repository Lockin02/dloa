var show_page = function(page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function() {
	$("#schemeGrid").yxgrid({
		model : 'hr_tutor_scheme',
		title : '��ʦ���˱�',
		showcheckbox:false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_schemelist',
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '��ʦ���',
					width : '60',
					sortable : true
				}, {
					name : 'userName',
					display : '��ʦ����',
					width : '60',
					sortable : true
				}, {
					name : 'jobName',
					display : '��ʦְλ',
					width : '85',
					sortable : true
				}, {
					name : 'deptName',
					display : '��ʦ����',
					width : '80',
					sortable : true
				}, {
					name : 'studentNo',
					display : 'ѧԱ���',
					hide : true,
					sortable : true
				}, {
					name : 'studentName',
					display : 'ѧԱ����',
					width : '60',
					sortable : true
				}, {
					name : 'studentDeptName',
					display : 'ѧԱ����',
					width : '80',
					sortable : true
				},
				{
					name : 'status',
					display : '��ǰ״̬',
					width : '80',
					sortable : true,
					process : function(v, row) {
						switch (v) {
							case '1' :
								return "������";
								break;
							case '2' :
								return "Ա������";
								break;
							case '3' :
								return "��ʦ����";
								break;
							case '4' :
								return "���";
								break;
						}
					}
				},
				{
					name : 'assessmentScore',
					display : '���˷���',
					width : '60',
					sortable : true,
					process : function(v,row) {
						if (v > 0 && v != null) {
							return v;
						} else {
							return '';
						}
					}
				}, {
					name : 'tryBeginDate',
					display : '��ְ����',
					width : '80',
					sortable : true
				}, {
					name : 'tryEndDate',
					display : 'ת������',
					width : '80',
					sortable : true
				}, {
					name : 'tutorScore',
					display : '��ʦ����',
					sortable : false,
					width : '50'
				}, {
					name : 'supScore',
					display : '�ϼ�����',
					sortable : false,
					width : '50'
				}, {
					name : 'hrScore',
					display : 'HR����',
					sortable : false,
					width : '50'
				}, {
					name : 'assistantScore',
					display : '������������',
					sortable : false,
					width : '50'
				}, {
					name : 'staffScore',
					display : 'ѧԱ����',
					sortable : false,
					width : '50'
				}],
		lockCol:['userNo','userName','jobName'],//����������
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_tutor_scheme&action=toView&id="
							+ row.id);
				}
			}
		}, {
			text : '�༭���˷���',
			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.status == 4 || row.status == 5) {
//					return true;
//				} else {
//					return false;
//				}
//			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toEditScore&id="
							+ row.tutorId
							+ "&role=HR&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}
		// , {
		// text : '����',
		// icon : 'edit',
		// action : function(row) {
		// if (row) {
		// showModalWin("?model=hr_tutor_scheme&action=toScore&id="
		// + row.id);
		// }
		// }
		// }
		],
		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_tutor_schemeinfo&action=pageItemJson',
			// param : [{
			// paramId : 'mainId',
			// colId : 'id'
			// }],
			colModel : [{
						name : 'id',
						display : '�ӱ��ֶ�'
					}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "��ʦ���",
					name : 'userNo'
				}, {
					display : "��ʦ����",
					name : 'userName'
				}, {
					display : "��ʦְλ",
					name : 'jobName'
				}, {
					display : "��ʦ����",
					name : 'deptName'
				}, {
					display : "ѧԱ����",
					name : 'studentName'
				}, {
					display : "ѧԱ����",
					name : 'studentDeptName'
				}]
	});
});