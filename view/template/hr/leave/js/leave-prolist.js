var show_page = function(page) {
	$("#leaveGrid").yxgrid("reload");
};

$(function() {
	$("#leaveGrid").yxgrid({
		model : 'hr_leave_leave',
		param : {
			'userAccount' : $("#userId").val()
		},
		title : '��ְ����',
		showcheckbox : false,
		isDelAction : false,
		isEditAction : true,
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',

		buttonsEx : [{
			name : 'Add',
			text : "����",
			icon : 'add',
			action : function(row) {
				alert("���ã���OA�����ߣ��뵽��OA�ύ���롣лл��");
				return false;
				showThickboxWin('?model=hr_leave_leave&action=staffToAdd&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
			}
		}],

		event : {
			row_dblclick : function(e, row, data) {
				showThickboxWin("?model=hr_leave_leave&action=toView&id=" + data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴�����嵥',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.isHandover == '1' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toViewByApply&leaveId=' + row.id );
			}
		},{
			text : 'ȷ�Ͻ����嵥',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.isHandover=='1' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=handoverProlist&leaveId=' + row.id );
			}
		},{
			text : '�ύ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
//				if (window.confirm(("ȷ��Ҫ�ύ?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=hr_leave_leave&action=ajaxSubmit",
//						data : {
//							id : row.id,
//							state:1
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('�ύ�ɹ���');
//								$("#leaveGrid").yxgrid("reload");
//							}
//						}
//					});
//				}
				if (window.confirm(("���ã���OA�����ߣ���ת����OA�����ύ����?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		},{
			name : 'cancel',
			text : '��������',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.state == '2' && row.ExaStatus == '��������') || (row.state == '1' && row.ExaStatus == 'δ�ύ')|| (row.state == '2'&& row.ExaStatus == 'δ�ύ')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.ExaStatus == "δ�ύ"){
						$.ajax({
							type : "POST",
							url : "?model=hr_leave_leave&action=ajaxSubmit",
							data : {
								id : row.id,
								state:0
							},
							success : function(msg) {
								if (msg == 1) {
									alert('���سɹ���');
									$("#leaveGrid").yxgrid("reload");
								}
							}
						});
					} else {
						var ewfurl = 'controller/hr/leave/ewf_index1.php?actTo=delWork&billId=';
						$.ajax({
							type : "POST",
							url : "?model=common_workflow_workflow&action=isAudited",
							data : {
								billId : row.id,
								examCode : 'oa_hr_leave'
							},
							success : function(msg) {
								if (msg == '1') {
									alert('�����Ѿ�����������Ϣ�����ܳ���������');
									$("#leaveGrid").yxgrid("reload");
									return false;
								} else {
									if(confirm('ȷ��Ҫ����������')){
										$.ajax({
											type: "GET",
											url: ewfurl,
											data: {"billId" : row.id },
											async: false,
											success: function(data){
												$.ajax({
													type : "POST",
													url : "?model=hr_leave_leave&action=ajaxSubmit",
													data : {
														id : row.id,
														state:0
													},
													success : function(msg) {
														if (msg == 1) {
															alert('���سɹ���');
															$("#leaveGrid").yxgrid("reload");
														}
													}
												});
											}
										});
									}
								}
							}
						});
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_leave&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			name : 'close',
			text : '�ر�ԭ��',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toCloseReason&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '����',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=updateExaStatus",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#leaveGrid").yxgrid("reload");
							} else{
								alert('����ʧ�ܣ�');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'leaveCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showThickboxWin(\'?model=hr_leave_leave&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		},{
			name : 'userNo',
			display : 'Ա�����',
			width : 80,
			sortable : true
		},{
			name : 'userName',
			display : 'Ա������',
			width : 60,
			sortable : true
		},{
			name : 'deptName',
			display : '����',
			width : 80,
			sortable : true
		},{
			name : 'jobName',
			display : 'ְλ',
			width : 80,
			sortable : true
		},{
			name : 'entryDate',
			display : '��ְ����',
			width : 80,
			sortable : true
		},{
			name : 'state',
			display : '����״̬',
			sortable : true,
			width:70,
			process:function(v ,row) {
				if (v == "0") {
					return "δ�ύ";
				} else if (v == "1") {
					return "δȷ������ ";
				} else if (v == "2") {
					if(row.ExaStatus =='���' && row.nowDate > row.comfirmQuitDate) {
						return "����������";
					} else {
						return "��ȷ������";
					}
				} else if (v == '4') {
					return "�ѹر�";
				} else {
					return "�Ѹ��µ���";
				}
			}
		},{
			name : 'quitTypeName',
			display : '��ְ����',
			width : 60,
			sortable : true
		},{
			name : 'requireDate',
			display : 'Ҫ����ְ����',
			width : 80,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '����״̬',
			width : 60,
			sortable : true
		},{
			name : 'createName',
			display : '������',
			width : 60,
			sortable : true
		},{
			name : 'quitReson',
			display : '��ְԭ��',
			width : 300,
			sortable : true,
			align : 'left',
			process :��function(v) {
				//��ȡ��ְԭ���滻�����ַ�
				var str = v.substring(-5);
				if (str == "^nbsp") { //û�а�������ԭ��
					v = v.replace(/\^nbsp/g,"��");
				} else {
					var num =  v.split("^nbsp").length - 1;
					for (var i = 0; i < num - 1; i++) {
						v = v.replace(/\^nbsp/,"��");
					}
					v = v.replace(/\^nbsp/,":"); //���һ��Ϊ����
				}

				return v;
			}
		}],

		lockCol:['leaveCode','userNo','useName'],//����������

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			formHeight : 600,
			action : 'toEdit'
		},
		toViewConfig : {
			formHeight : 600,
			action : 'toView'
		},

		searchitems : [{
			display : "���ݱ��",
			name : 'leaveCode'
		},{
			display : "Ա�����",
			name : 'userNo'
		},{
			display : "Ա������",
			name : 'userName'
		}]
	});
});