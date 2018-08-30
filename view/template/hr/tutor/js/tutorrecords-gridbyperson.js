var show_page = function(page) {
	$("#tutorrecordsGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [
        {
			name : 'view',
			text : "�߼���ѯ",
			icon : 'view',
			action : function() {
				alert('������δ�������');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        }
    ];

	//��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_tutor_tutorrecords&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_tutor_tutorrecords&action=getLimits',
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

	$("#tutorrecordsGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		param : {
//			'userAccount' : $('#userAccount').val()
			'userNo2' : $('#userNo').val()
		},
		title : '��ʦ������Ϣ��',
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '��ʦԱ�����',
				width:80,
				sortable : true
			}, {
				name : 'userName',
				display : '��ʦ����',
				width:80,
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_tutor_tutorrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			},  {
				name : 'jobName',
				display : '��ʦְλ',
				sortable : true
			}, {
				name : 'deptName',
				display : '��ʦ����',
				sortable : true,
				hide : true
			}, {
				name : 'studentNo',
				display : 'ѧԱԱ�����',
				sortable : true,
				hide : true
			}, {
				name : 'studentAccount',
				display : 'ѧԱԱ���˺�',
				sortable : true,
				hide : true
			}, {
				name : 'studentName',
				display : 'ѧԱ����',
				sortable : true
			}, {
				name : 'studentDeptName',
				display : 'ѧԱ����',
				sortable : true
			}, {
				name : 'role',
				display : '��ɫ',
				sortable : false
			}, {
				name : 'status',
				display : '��ǰ״̬',
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
			}, {
				name : 'beginDate',
				display : '��ѧ��ʼ����',
				sortable : true
			}, {
				name : 'assessmentScore',
				display : '���˷���',
				sortable : true,
				process : function(v, row) {
					if (row.role == "��ʦ"&&row.isPublish == 1) {
						return v;
					}
				}
			},{
				name : 'rewardPrice',
				display : '����',
				sortable : true,
				process : function(v, row) {
					if (row.role == "��ʦ"&&row.isPublish == 1) {
						return v;
					}
				}
			}],

		menusEx : [{
			text : '�ܱ�',
			icon : 'view',
			action : function(row) {
					showThickboxWin("?model=hr_tutor_weekly&role=����&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
		}, {
			text : 'Ա�������ƻ�',
			icon : 'view',
			action : function(row) {
					showOpenWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
		}, {
			text : '��ʦ���˱�',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=hr_tutor_scheme&action=toTutorAssessView&id="
						+ row.id);
			}
		}],
		toEditConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toEdit',
			formWidth : '800',
			formHeight : '400'
		},
		toViewConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toView',
			formWidth : '800',
			formHeight : '400'
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			}
		},
//		buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		/**
		 * ��������
		 */
		searchitems : [{
			display : "Ա�����",
			name : 'userNoM'
		},{
			display : "��ʦ����",
			name : 'userNameM'
		},{
			display : "ѧԱ����",
			name : 'studentNameM'
		},{
			display : "ѧԱ����",
			name : 'studentDeptNameM'
		}]
	});
});