var show_page = function (page) {
	$("#viewListGrid").yxgrid("reload");
};
$(function () {
	//��ͷ��ť����
	buttonsArr = [
		//        {
		//			name : 'view',
		//			text : "�߼���ѯ",
		//			icon : 'view',
		//			action : function() {
		//				alert('������δ�������');
		//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
		//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		//			}
		//        }
	];

	//��ͷ��ť����
	excelOutArr = {};
	$("#viewListGrid").yxgrid({
		model : 'hr_recruitment_interview',
		param : {'resumeId' : $("#resumeId").val()},
		title : '���Լ�¼',
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
			}, {
				name : 'formCode',
				display : '���ݱ��',
				sortable : true,
				width:120,
				process : function (v, row) {
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1)'>" + v + "</a>";
				}
			}, {
				name : 'formDate',
				display : '��������',
				width:70,
				sortable : true
			}, {
				name : 'userName',
				display : '����',
				width:70,
				sortable : true
			}, {
				name : 'sexy',
				display : '�Ա�',
				sortable : true,
				width : 60
			}, {
				name : 'positionsName',
				display : 'ӦƸ��λ',
				sortable : true
			}, {
				name : 'deptState',
				display : '����ȷ��״̬',
				width:70,
				sortable : true,
				process : function (v) {
					if (v == 1)
						return "��ȷ��";
					else
						return "δȷ��";
				}
			}, {
				name : 'hrState',
				display : '������Դȷ��״̬',
				sortable : true,
				process : function (v) {
					if (v == 1)
						return "��ȷ��";
					else
						return "δȷ��";
				}
			}, {
				name : 'stateC',
				display : '״̬',
				width:70
			}, {
				name : 'ExaStatus',
				display : '���״̬',
				width:70,
				sortable : true
			}, {
				name : 'deptName',
				display : '���˲���',
				sortable : true
			},/* {
				name : 'hrRequire',
				display : '��Ƹ����',
				sortable : true
			},*/ {
				name : 'useInterviewResult',
				display : '���Խ��',
				width:70,
				sortable : true,
				process : function (v) {
					if (v == 0)
						return "�����˲�";
					else
						return "����¼��";
				}
			}, {
				name : 'hrSourceType1Name',
				display : '������Դ����',
				sortable : true
			}, {
				name : 'hrSourceType2Name',
				display : '������ԴС��',
				sortable : true
			}
		],
		lockCol:['formCode','formDate','userName'],//����������
		toEditConfig : {
			toEditFn : function (p, g) {
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
					showOpenWin("?model=hr_recruitment_interview&action=lastedit&id=" +  + rowData[p.keyField]
						 + keyUrl,'1');
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		toViewConfig : {
			toViewFn : function (p, g) {
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
		buttonsEx : buttonsArr,
		searchitems : [{
				display : '����',
				name : 'userNameSearch'
			}, {
				display : '���˲���',
				name : 'deptNamSearche'
			}
		]
	});
});