var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};

$(function() {
	//��ͷ��ť����
	buttonsArr = [];

	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		action:'pageForManager',
		title : '��������',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		isOpButton:false,
		bodyAlign:'center',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width:120,
			process : function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'formDate',
			display : '��������',
			width:70,
			sortable : true
		},{
			name : 'userName',
			display : '����',
			width:60,
			sortable : true
		},{
			name : 'sexy',
			display : '�Ա�',
			width:50,
			sortable : true
		},{
			name : 'positionsName',
			display : 'ӦƸ��λ',
			sortable : true
		},{
			name : 'deptState',
			display : '����ȷ��״̬',
			sortable : true,
			width:70,
			process : function (v) {
				if (v == 1) {
					return "��ȷ��";
				} else {
					return "δȷ��";
				}
			}
		},{
			name : 'hrState',
			display : '������Դȷ��״̬',
			sortable : true,
			width:95,
			process : function (v) {
				if (v == 1) {
					return "��ȷ��";
				} else {
					return "δȷ��";
				}
			}
		},{
			name : 'stateC',
			display : '״̬',
			width:60
		},{
			name : 'ExaStatus',
			display : '���״̬',
			width:60,
			sortable : true
		},{
			name : 'entryState',
			display : '��ְ״̬',
			width : 60,
			sortable : true,
			process : function (v) {
				switch (v) {
					case '1' : return '����ְ';
					case '2' : return '����ְ';
					case '3' : return '������ְ';
					default : return '';
				}
			}
		},{
			name : 'deptName',
			display : '���˲���',
			sortable : true
		},{
			name : 'useInterviewResult',
			display : '���Խ��',
			width:70,
			sortable : true,
			process : function (v) {
				if (v == 0) {
					return "�����˲�";
				} else {
					return "����¼��";
				}
			}
		},{
			name : 'hrSourceType1Name',
			display : '������Դ����',
			sortable : true
		},{
			name : 'hrSourceType2Name',
			display : '������ԴС��',
			sortable : true
		}],

		lockCol:['formCode','formDate','userName'],//����������

		toAddConfig : {
			toAddFn : function() {
				showOpenWin("?model=hr_recruitment_interview&action=toAdd" );
			}
		},
		toEditConfig : {
			toEditFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=toEdit&id=" + rowData[p.keyField]
						+ keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=toView&id=" + rowData[p.keyField]
						+ keyUrl,'1');
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},

		menusEx:[{
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_interview&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800");
				}
			}
		},{
			name : 'resume',
			text : '�鿴��������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.resumeId >0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800','1');
				}
			}
		},{
			name : 'resume',
			text : '�鿴����ְλ����',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.applyId >0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800','1');
				}
			}
		},{
			text : '��д�������',
			icon : 'add',
			action : function(row) {
				showOpenWin("?model=hr_recruitment_interview&action=todeptedit&id="+row.id,'1');
			},
			showMenuFn: function(row) {
				if(row.deptState == 0) {
					return true;
				} else {
					return false;
				}
			}
		},{
			text : '�ύ����',
			icon : 'add',
			action : function (row) {},
			showMenuFn : function (row) {
				if (row.hrState == 0 && row.deptState == 1 && row.ExaStatus == "δ�ύ" && row.parentDeptId == '35')
					return true;
				else
					return false;
			},
			action : function (row, rows, grid) {
				if (row) {
					if (row.hrIsManageJob == "��" && row.useInterviewResult == 1) {
						location = 'controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect&billId='
						+ row.id + '&billDept=' + row.deptId
						+ '&examCode=oa_hr_recruitment_interview&formName=������������';
					} else {
						if (row.isLocal == "��") {
							location = 'controller/hr/recruitment/ewf_interview_local_index.php?actTo=ewfSelect&billId='
							+ row.id + '&billDept=' + row.deptId
							+ '&examCode=oa_hr_recruitment_interview&formName=������������';
						} else {
							location = 'controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect&billId='
							+ row.id + '&billDept=' + row.deptId
							+ '&examCode=oa_hr_recruitment_interview&formName=������������';
						}
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		buttonsEx : buttonsArr,

		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		},{
			display : '��������',
			name : 'formDate'
		},{
			display : '����',
			name : 'userNameSearch'
		},{
			display : '�Ա�',
			name : 'sexy'
		},{
			display : 'ӦƸ��λ',
			name : 'positionsNameSearch'
		},{
			display : '���˲���',
			name : 'deptNamSearche'
		},{
			display : '������Դ����',
			name : 'hrSourceType1Name'
		},{
			display : '������ԴС��',
			name : 'hrSourceType2Name'
		}]
	});
});