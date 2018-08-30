var show_page = function(page) {
	$("#tutorrecordsGrid").yxgrid("reload");
};
$(function() {
	// ��ͷ��ť����
	buttonsArr = [];
	$("#tutorrecordsGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		param : {
			'studentSuperiorId' : $('#userId').val()
		},
		title : '��ʦ������Ϣ��',
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_studentlist',
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '��ʦԱ�����',
					width:'80',
					sortable : true
				},  {
					name : 'userName',
					display : '��ʦ����',
					width:'60',
					sortable : true,
					process : function(v, row) {
						return "<a href='#' onclick='showThickboxWin(\"?model=hr_tutor_tutorrecords&action=toView&id="
								+ row.id
								+ '&skey='
								+ row.skey_
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
								+ v + "</a>";
					}
				}, {
					name : 'jobName',
					display : '��ʦְλ',
					width:'80',
					sortable : true
				},  {
					name : 'deptName',
					display : '��ʦ����',
					width:'80',
					sortable : true,
					hide : true
				}, {
					name : 'studentNo',
					display : 'ѧԱԱ�����',
					width:'80',
					sortable : true,
					hide : true
				},  {
					name : 'studentName',
					display : 'ѧԱ����',
					width:'60',
					sortable : true
				}, {
					name : 'studentDeptName',
					display : 'ѧԱ����',
					width:'80',
					sortable : true
				}, {
					name : 'status',
					display : '��ǰ״̬',
					width:'80',
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
					display : '��ѧ��ʼʱ��',
					width:'80',
					sortable : true
				}, {
					name : 'assessmentScore',
					display : '���˷���',
					width:'60',
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == '1') {
							return v;
						}
					}
				}, {
					name : 'rewardPrice',
					display : '�� ��(Ԫ)',
					width:'60',
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == 1) {
							return v;
						};
					}
				}, {
					name : 'isCoachplan',
					display : '�Ƿ���Ҫ�ƶ������ƻ�',
					sortable : true,
					width : 130
				},{
					name : 'isWeekly',
					display : '�Ƿ���Ҫ����HR��ģʽ�ύ�ܱ�',
					sortable : true,
					width : 180
				}],
		lockCol:['userNo','userName','jobName'],//����������
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�ܱ�',
			icon : 'view',
			// showMenuFn : function(row) {
			// if (row.status == '1' || row.status == '0') {
			// return true;
			// }
			// return false;
			// },
			action : function(row) {
				if (row.role == "��ʦ") {
					showModalWin("?model=hr_tutor_weekly&role=��ʦ&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else if (row.role == "ѧԱ") {
					showModalWin("?model=hr_tutor_weekly&role=ѧԱ&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {
					showModalWin("?model=hr_tutor_weekly&role=����&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		}, {
			text : 'Ա�������ƻ�',
			icon : 'view',
			// showMenuFn : function(row) {
			// if (row.status == '1' || row.status == '0') {
			// return true;
			// }
			// return false;
			// },
			action : function(row) {
				if (row.role == "��ʦ") {
					showModalWin("?model=hr_tutor_coachplan&action=toCoachplan&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {
					showModalWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		}, {
			text : '��ʦ��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3'&&row.supScore==0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_tutor_scheme&action=toScore&tutorId="
							+ row.id + "&role=sup");
				}
			}
		}, {
			text : '��ʦ���˱�',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == 1 || row.status == 4) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showModalWin("?model=hr_tutor_scheme&action=toView&id="
						+ row.id);
			}
		}],
		comboEx : [{
					text : '״̬',
					key : 'status',
					data : [{
								text : '������',
								value : '1'
							}
							// , {
							// text : 'Ա������',
							// value : '2'
							// }
							, {
								text : '��ʦ����',
								value : '3'
							}, {
								text : '���',
								value : '4'
							}]
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
		// buttonsEx : buttonsArr,
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
				}, {
					display : "��ʦ����",
					name : 'userNameM'
				}, {
					display : "ѧԱ����",
					name : 'studentNameM'
				}, {
					display : "ѧԱ����",
					name : 'studentDeptNameM'
				}]
	});
});