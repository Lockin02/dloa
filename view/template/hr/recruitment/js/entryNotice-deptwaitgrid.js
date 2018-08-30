var show_page = function(page) {
	$("#entryNoticeGrid").yxgrid("reload");
};

$(function() {
	//��ͷ��ť����
	buttonsArr = [];

	excelOutArr2 = {
		name : 'expport',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_entryNotice&action=toExport&docType=RKPURCHASE"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
		}
	};

	excelOutSelect = {
		name : 'excelOutAllArr',
		text : "�Զ��嵼����Ϣ",
		icon : 'excel',
		action : function() {
			if ($("#totalSize").val() < 1) {
				alert("û�пɵ����ļ�¼");
			} else {
				document.getElementById("form2").submit();
			}
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_education&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr2);
				buttonsArr.push(excelOutSelect);
			}
		}
	});


	$("#entryNoticeGrid").yxgrid({
		model : 'hr_recruitment_entryNotice',
		title : '��ְ����',
		bodyAlign : 'center',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		isOpButton : false,
		param : {
//			deptId : $("#deptId").val(),
			state : 1,
			isSaveN : 1
		},
		event : {
			'afterload' : function(data, g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
			}
		},

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id="
					+ row.id + "\",1)'>" + v + "</a>";
			},
			width : 120
		},{
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code="
					+ v + "\",1)'>" + v + "</a>";
			},
			width : 90
		},{
			name : 'hrSourceType2Name',
			display : '������ԴС��',
			sortable : true
		},{
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width : 70
		},{
			name : 'userName',
			display : '����',
			sortable : true,
			width : 60
		},{
			name : 'stateC',
			display : '״̬',
			width : 80
		},{
			name : 'assistManName',
			display : '��ְЭ����',
			sortable : true,
			width : 60
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width : 60,
			hide : true
		},{
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true,
			hide : true
		},{
			name : 'email',
			display : '��������',
			sortable : true,
			hide : true
		},{
			name : 'deptName',
			display : '���˲���',
			sortable : true,
			width : 80
		},{
			name : 'workPlace',
			display : '�����ص�',
			sortable : true,
			width : 80,
			process : function (v ,row) {
				return row.workProvince + ' - ' + row.workCity;
			}
		},{
			name : 'socialPlace',
			display : '�籣�����',
			sortable : true,
			width : 60
		},{
			name : 'hrJobName',
			display : '¼��ְλ',
			sortable : true,
			width : 80
		},{
			name : 'hrIsManageJob',
			display : '�Ƿ�����',
			sortable : true,
			hide : true,
			hide : true
		},{
			name : 'useHireTypeName',
			display : '¼����ʽ',
			sortable : true,
			width : 60
		},{
			name : 'useAreaName',
			display : '���������֧������',
			sortable : true
		},{
			name : 'sysCompanyName',
			display : '������˾',
			sortable : true,
			width : 60
		},{
			name : 'personLevel',
			display : '�����ȼ�',
			sortable : true,
			width : 60
		},{
			name : 'probation',
			display : '������(��)',
			sortable : true,
			width : 60
		},{
			name : 'contractYear',
			display : '��ͬ����(��)',
			sortable : true,
			width : 60
		},{
			name : 'useSign',
			display : 'ǩ������ҵ����Э�顷',
			sortable : true,
			width : 110
		},{
			name : 'entryRemark',
			display : '��ְ���ȱ�ע',
			sortable : true
		},{
			name : 'formDate',
			display : '��������',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'applyCode',
			display : 'ְλ������',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id="
					+ row.applyId + "\")'>" + v + "</a>";
			},
			hide : true
		},{
			name : 'developPositionName',
			display : '�з�ְλ',
			sortable : true,
			width : 60,
			hide : true
		},{
			name : 'useDemandEqu',
			display : '�����豸',
			sortable : true,
			hide : true
		}],

		lockCol:['formCode','userName','stateC'],//����������

		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_recruitment_entryNotice&action=toView&id="
							+ rowData[p.keyField]);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},

		comboEx:[{
			text:'״̬',
			key:'stateSearch',
			data:[{
				text:'����ְ',
				value:'1'
			},{
				text:'�ѽ��˺�',
				value:'2'
			},{
				text:'�ѽ�����',
				value:'3'
			},{
				text:'��ǩ��ͬ',
				value:'4'
			}]
		}],

		buttonsEx : buttonsArr,

		// ��չ�Ҽ�
		menusEx : [{
			name : 'resume',
			text : '�鿴��������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.resumeId > 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId ,'1');
				}
			}
		},{
			name : 'jobApply',
			text : '�鿴����ְλ����',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.applyId > 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId ,'1');
				}
			}
		},{
			name : 'apply',
			text : '�鿴������Ա����',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.sourceId > 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_apply&action=toView&id=' + row.sourceId ,'1');
				}
			}
		},{
			name : 'interview',
			text : '�鿴������������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.parentId > 0) {
					//�ж��Ƿ���Ȩ��
					var interviewLimits = $.ajax({
							type : 'POST',
							url : '?model=hr_recruitment_entryNotice&action=getLimits',
							data : {
								'limitName' : '�鿴������������Ȩ��'
							},
							async : false
						}).responseText;

					if (interviewLimits == 1) {
						return true;
					}
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_interview&action=toView&id=' + row.parentId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800','1');
				}
			}
		},{
			text : '��ͨOA�˺�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.accountState == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=user&action=adduser"
						+ "&oId="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
				} else {
					alert("��ѡ��һ����¼��Ϣ");
				}
			}
		},{
			text : '�༭Ա������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.staffFileState == 0 && row.accountState == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=hr_personnel_personnel&action=toAddByEntryNotice"
						+ "&entryId="
						+ row.id
						+ "&applyId="
						+ row.applyId
						+ "&resumeId="
						+ row.resumeId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
				} else {
					alert("��ѡ��һ����¼��Ϣ");
				}
			}
		},{
			text : 'ǩ����ͬ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.contractState == 0 && row.accountState == 1&& row.staffFileState == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_contract_contract&action=toAddByExternal"
						+ "&entryId="
						+ row.id
						+ "&jobName="
						+ row.hrJobName
						+ "&jobId="
						+ row.hrJobId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
				} else {
					alert("��ѡ��һ����¼��Ϣ");
				}
			}
		},{
			text : 'ָ����ʦ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.staffFileState == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toSetEntryTutor&entryId="
						+ row.id
						+ "&entryDate="
						+ row.entryDate
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		},{
			text : '�����ְ',
			icon : 'add',
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ��Ҫ�����ְ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_entryNotice&action=doneEntry",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ְ�ɹ���');
								show_page();
							}
						}
					});
				}
			}
		},{
			text : '��ְ���ȱ�ע',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toEntryRemark&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '����ְλ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.applyId == 0 || row.applyId == '') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toLinkApply&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text:'�޸���ְʱ��',
			icon:'edit',
			action:function(row ,rows ,grid){
				showThickboxWin("?model=hr_recruitment_entryNotice&action=changeEntryDate&id="
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=800");
			}
		},{
			text:'������ְ',
			icon:'delete',
			action:function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toCancelEntry&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
				}
			}
		}],

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "����",
			name : 'userName'
		}],

		sortname : 'entryDate',
		sortorder : 'DESC'
	});
});