var show_page = function(page) {
	$("#personGrid").yxgrid("reload");
};
$(function() {
	// ��ͷ��ť����
	// buttonsArr = [
	// {
	// name : 'view',
	// text : "�߼���ѯ",
	// icon : 'view',
	// action : function() {
	// alert('������δ�������');
	// showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
	// +
	// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
	// }
	// }
	// ];

	$("#personGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		action : 'personJson',
		param : {
			'personIdSearch' : $("#userId").val()
		},
		title : '��ʦ����',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_mylist',
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�����ܱ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.role == "��ʦ"&&row.isWeekly!="��") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_weekly&role=����&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {

					showModalWin("?model=hr_tutor_weekly&role=��ʦ&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")

				}
			}
		}, {
			text : '�ύ�ܱ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.role == "ѧԱ"&&row.isWeekly!="��") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_weekly&role=����&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {

					showModalWin("?model=hr_tutor_weekly&role=ѧԱ&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")

				}
			}
		}, {
			text : '�ƶ������ƻ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.role == "��ʦ"&&row.isCoachplan!="��"&&row.isAddPlan==0) {
					return true;
				}
				return false;
			},
			action : function(row) {
					showModalWin("?model=hr_tutor_coachplan&action=toAdd&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		},{
			text : '�����ƻ�',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.role == "��ʦ"&&row.isCoachplan!="��"&&row.isAddPlan==1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}else{
					showModalWin("?model=hr_tutor_coachplan&action=toCoachplan&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		},  {
			text : '�����ƻ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.role == "ѧԱ"&&row.isCoachplan!="��") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {

					showModalWin("?model=hr_tutor_coachplan&action=toStudentView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		}, {
			text : '��ʦ��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3'&&((row.role == "��ʦ"&&row.tutorScore=='0')||(row.role == "ѧԱ"&&row.staffScore=='0'))) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					if (row.role == "��ʦ") {
						var roleh = "tut";
					} else {
						var roleh = "stu";
					}
					showModalWin("?model=hr_tutor_scheme&action=toScore&tutorId="
							+ row.id + "&role=" + roleh);
				}
			}
		}],
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'role',
					display : '��ɫ',
					sortable : false,
					width:60,
					process : function(v, row) {
						return "<span style='color:#FF5151'>" + v + "</span>";
					}
				}, {
					name : 'status',
					display : '��ǰ״̬',
					width:60,
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
					name : 'userNo',
					display : '��ʦԱ�����',
					width:80,
					sortable : true
				},  {
					name : 'userName',
					display : '��ʦ����',
					sortable : true,
					width:60,
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
					width:80,
					sortable : true
				}, {
					name : 'deptName',
					display : '��ʦ����',
					sortable : true,
					width:80
				}, {
					name : 'studentNo',
					display : 'ѧԱԱ�����',
					sortable : true,
					hide : true
				}, {
					name : 'studentName',
					display : 'ѧԱ����',
					width:60,
					sortable : true
				}, {
					name : 'studentDeptName',
					display : 'ѧԱ����',
					width:80,
					sortable : true
				}, {
					name : 'beginDate',
					display : '��ѧ��ʼʱ��',
					width:80,
					sortable : true
				}, {
					name : 'assessmentScore',
					display : '���˷���',
					width:60,
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == 1&&row.role != "ѧԱ") {
							return v;
						};
					}
				}, {
					name : 'rewardPrice',
					display : '�� ��(Ԫ)',
					sortable : true,
					width:60,
					process : function(v, row) {
						if (row.role != "ѧԱ"&& row.isPublish == 1) {
							return v;
						};
					}
				},{
					name : 'isCoachplan',
					display : '�Ƿ���Ҫ�ƶ������ƻ�',
					sortable : true,
					width : 130
				},{
					name : 'isWeekly',
					display : '�Ƿ���Ҫ����HR��ģʽ�ύ�ܱ�',
					sortable : true,
					width : 150
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					width : 130,
					hide : true
				}],
		lockCol:['role','status','userName'],//����������
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
		// buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [{
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