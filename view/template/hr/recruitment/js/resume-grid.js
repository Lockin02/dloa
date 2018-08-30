var show_page = function(page) {
	$("#resumeGrid").yxgrid("reload");
};

$(function() {
	//��ͷ��ť����
	buttonsArr = [{
		name : 'advancedsearch',
		text : "�߼�����",
		icon : 'search',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_resume&action=search&gridName=resumeGrid"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
		}
	},{
		name : 'add',
		text : "����",
		icon : 'add',
		action : function(row) {
			showModalWin("?model=hr_recruitment_resume&action=toAdd" ,"1");
		}
	},{
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_resume&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	}];

	$("#resumeGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '��������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		bodyAlign:'center',
		customCode : 'resumeGrid',

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴����',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toViewTab&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '�༭����',
			icon : 'edit',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toEdit&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '��ӡ����',
			icon : 'edit',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '�����������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.resumeType == 2) {
					return false;
				}
				return true;
			},
			action : function(row) {
				$.ajax({
					type : "POST",
					url : "?model=hr_recruitment_interview&action=isAdded",
					data : {
						resumeId:row.id
					},
					success : function(msg){
						if(msg == 0) { //�ж��Ƿ�����������
							if (window.confirm(("�ü�����������������,�Ƿ�������?"))) {
								showModalWin('?model=hr_recruitment_interview&action=toAddByResume&resumeId='
									+ row.id + "&skey=" + row['skey_'],'1');
							}
						} else {
							showModalWin('?model=hr_recruitment_interview&action=toAddByResume&resumeId='
								+ row.id + "&skey=" + row['skey_'],'1');
						}
					}
				});
			}
		},{
			text : '����¼��֪ͨ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.resumeType == 2) {
					return false;
				}
				return true;
			},
			action : function(row) {
				$.ajax({
					type : "POST",
					url : "?model=hr_recruitment_resume&action=checkInvitation",
					data : {
						resumeId:row.id
					},
					success:function(msg){
						if(msg == 0) {//�ж��Ƿ�����������
							if (window.confirm(("������δ��������������������ȷ���Ƿ�Ҫ����¼��֪ͨ��"))) {
								showModalWin('?model=hr_recruitment_resume&action=toSendNotifi&resumeId='
									+ row.id + "&skey=" + row['skey_'],'1');
							}
						} else {
							showModalWin('?model=hr_recruitment_resume&action=toSendNotifi&resumeId='
								+ row.id + "&skey=" + row['skey_'],'1');
						}
					}
				});
			}
		},{
			text : 'תΪ��ְ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.resumeType != 1 && row.resumeType != 5) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��תΪ��ְ����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxTurnType",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : 'תΪ��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType != 3) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��תΪ��������?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxReservelist",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '���������',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.resumeType!=2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ�����������?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxBlacklist",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : 'תΪ��˾����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType!=0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��תΪ��˾����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxCompanyResume",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : 'תΪ��̭����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType!=4) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��תΪ��̭����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxChangeResume",
						data : {
							id : row.id,
							resumeType : 4
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : 'תΪ��ְ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType!=6) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��תΪ��ְ����?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxChangeResume",
						data : {
							id : row.id,
							resumeType : 6
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isInform == 1 || row.resumeType == 1) {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							//msg 1.�ɹ���2-����������
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#resumeGrid").yxgrid("reload");
							}else if(msg == 2){
								alert("�ü����ѹ��������������⡰����ֹɾ����")
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
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v, row) {
					return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
			}
		},{
			name : 'applicantName',
			display : 'ӦƸ������',
			sortable : true,
			width : 60
		},{
			name : 'post',
			display : 'ӦƸְλ',
			sortable : true,
			width:'60',
			datacode : 'YPZW'
		},{
			name : 'reserveA',
			display : 'ӦƸְλ(С��)',
			sortable : true,
			width : 100
		},{
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		},{
			name : 'email',
			display : '��������',
			sortable : true,
			width : 150
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width : 40
		},{
			name : 'education',
			display : 'ѧ��',
			sortable : true,
			datacode:'HRJYXL',
			width : 60
		},{
			name : 'sourceA',
			display : '������Դ(����)',
			sortable : true,
			datacode : 'JLLY',
			width : 100
		},{
			name : 'sourceB',
			display : '������Դ(С��)',
			sortable : true,
			width : 100
		},{
			name : 'resumeType',
			display : '��������',
			sortable : true,
			process : function(v,row){
				if(v == "0") {
					return "��˾����";
				}else if(v == "1") {
					return "��ְ����";
				}else if(v == "2") {
					return "������";
				}else if(v == "3") {
					return "��������";
				}else if(v == "4") {
					return "��̭����";
				}else if(v == "5") {
					return "��ְ����";
				}else if(v == "6") {
					return "��ְ����";
				}
			}
		},{
			name : 'birthdate',
			display : '��������',
			sortable : true,
			width : 90
		},{
			name : 'marital',
			display : '����״��',
			sortable : true,
			width : 60
		},{
			name : 'wishAdress',
			display : '���������ص�',
			sortable : true,
			width : 100
		},{
			name : 'graduateDate',
			display : '��ҵʱ��',
			sortable : true,
			width : 80
		},{
			name : 'workSeniority',
			display : '��������',
			sortable : true,
			width : 60
		},{
			name : 'computerGrade',
			display : '�����ˮƽ',
			sortable : true,
			datacode : 'JSJSP',
			width : 100
		},{
			name : 'language',
			display : '����',
			sortable : true,
			datacode : 'HRYZ',
			width : 40
		},{
			name : 'languageGrade',
			display : '����ˮƽ',
			sortable : true,
			datacode : 'WYSP',
			width : 60
		},{
			name : 'college',
			display : '��ҵԺУ',
			sortable : true,
			width : 150
		},{
			name : 'major',
			display : '��ҵרҵ',
			sortable : true,
			width : 100
		},{
			name : 'wishSalary',
			display : '����нˮ',
			sortable : true,
			width : 60
		},{
			name : 'prevCompany',
			display : '�ϼҹ�˾����',
			sortable : true,
			width : 150
		},{
			name : 'hillockDate',
			display : '����ʱ��',
			sortable : true,
			width : 80
		},{
			name : 'specialty',
			display : '�س�',
			sortable : true,
			width : 200
		},{
			name : 'selfAssessment',
			display : '��������',
			sortable : true,
			hide:true,
			width : 200
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			hide:true,
			width : 200
		}],

		lockCol:['resumeCode','applicantName'],//����������

		comboEx : [{
			text : '��������',
			key : 'resumeTypeArr',
			value :��'0',
			data : [{
				text : '��˾����',
				value : '0'
			},{
				text : '������',
				value : '2'
			},{
				text : '��������',
				value : '3'
			},{
				text : '��̭����',
				value : '4'
			},{
				text : '��ְ����',
				value : '1,5'
			},{
				text : '��ְ����',
				value : '6'
			}]
		}],

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "�������",
			name : 'resumeCode'
		},{
			display : "ӦƸ������",
			name : 'applicantName'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "��������",
			name : 'birthdate'
		},{
			display : "��ϵ�绰",
			name : 'phone'
		},{
			display : "��������",
			name : 'email'
		},{
			display : "����״��",
			name : 'marital'
		},{
			display : "���������ص�",
			name : 'wishAdress'
		},{
			display : "ӦƸְλ(����)",
			name : 'post'
		},{
			display : "ӦƸְλ(С��)",
			name : 'reserveA'
		},{
			display : "��ҵʱ��",
			name : 'graduateDate'
		},{
			display : "��������",
			name : 'workSeniority'
		},{
			display : "�����ˮƽ",
			name : 'computerGrade'
		},{
			display : "����ˮƽ",
			name : 'languageGrade'
		},{
			display : "��ҵԺУ",
			name : 'college'
		},{
			display : "��ҵרҵ",
			name : 'major'
		},{
			display : "����нˮ",
			name : 'wishSalary'
		},{
			display : "�ϼҹ�˾����",
			name : 'prevCompany'
		},{
			display : "����ʱ��",
			name : 'hillockDate'
		},{
			display : "�س�",
			name : 'specialty'
		},{
			display : "������Դ(����)",
			name : 'sourceA'
		},{
			display : "������Դ(С��)",
			name : 'sourceB'
		},{
			display : "��������",
			name : 'selfAssessment'
		},{
			display : "��ע",
			name : 'remark'
		}]
	});
});