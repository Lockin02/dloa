var show_page = function(page) {
	$("#tutordeptGrid").yxgrid("reload");
};
$(function() {
	// ��ͷ��ť����
	buttonsArr = [
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
	];

	excelOutArr = {
		name : 'exportOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			location = "?model=hr_tutor_tutorrecords&action=export&deptid="
					+ $("#deptId").val();
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

	$("#tutordeptGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		action : 'pageJsonForDept',
//		param : {
//			'deptId' : $("#deptId").val()
//		},
		title : '��ʦ����',
		showcheckbox:false,
		isAddAction : true,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_deptlist',
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�����쵼',
			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.status == '0' || row.status == '1') {
//					return true;
//				}
//				return false;
//			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_tutor_tutorrecords&action=toEditLeader&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
			}
		}, {
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
			text : '��ʦ���˱�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.sign == 1) {// ���ڵ�ʦ���˱��¼,����ʾ��ť
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showModalWin("?model=hr_tutor_scheme&action=toTutorAssess&id="
						+ row.id);
			}
		}, {
			text : '��ʦ��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 3&&row.assistantScore==0) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_tutor_scheme&action=toScore&tutorId="
							+ row.id + "&role=dept");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_tutor_tutorrecords&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#tutordeptGrid").yxgrid("reload");
							}
						}
					});
				}
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
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '��ʦԱ�����',
					width : '80',
					sortable : true
				}, {
					name : 'userName',
					display : '��ʦ����',
					width : '60',
					sortable : true,
					process : function(v, row) {
						return "<a href='#' onclick='showThickboxWin(\"?model=hr_tutor_tutorrecords&action=toView&id="
								+ row.id
								+ '&skey='
								+ row.skey_
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
								+ v + "</a>";
					}
				},{
					name : 'jobName',
					display : '��ʦְλ',
					width : '80',
					sortable : true
				}, {
					name : 'deptName',
					display : '��ʦ����',
					width : '80',
					sortable : true,
					hide : true
				}, {
					name : 'studentNo',
					display : 'ѧԱԱ�����',
					sortable : true,
					width : '80',
					hide : true
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
				}, {
					name : 'realBecomeDate',
					display : 'ת��ʱ��',
					width:80,
					process : function(v, row) {
						if(v!=""){
							return v;
						}else{
							return row.becomeDate;
						}
					},
					sortable : true
				}, {
					name : 'status',
					display : '��ǰ״̬',
					width : '60',
					sortable : true,
					process : function(v, row) {
						switch (v) {
							case '0' :
								return "������";
								break;
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
							case '5' :
								return "�ѹر�";
								break;
						}
					}
				}, {
					name : 'beginDate',
					display : '��ѧ��ʼʱ��',
					width : '80',
					sortable : true
				}, {
					name : 'assessmentScore',
					display : '���˷���',
					width : '60',
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == 1) {
							return v;
						}
					}
				}, {
					name : 'rewardPrice',
					display : '�� ��(Ԫ)',
					width : '60',
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == 1) {
							return v;
						}
					}
				}, {
					name : 'isCoachplan',
					display : '�Ƿ���Ҫ�ƶ������ƻ�',
					sortable : true,
					width : 130
				}, {
					name : 'isWeekly',
					display : '�Ƿ���Ҫ����HR��ģʽ�ύ�ܱ�',
					sortable : true,
					width : 200
				}],
		lockCol:['userNo','userName','jobName'],//����������
		toAddConfig : {
			formWidth : '800',
			formHeight : '500'
		},
		toViewConfig : {
			action : 'toView',
			formWidth : '800',
			formHeight : '500'
		},
		buttonsEx : buttonsArr,
				// Ĭ�������ֶ���
				sortname : "userName",
				// Ĭ������˳��
				sortorder : "asc",
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