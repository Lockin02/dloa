var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};
$(function() {
	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		action : 'pageJsonForRead',
		title : '���Լ�¼��ѯ',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '����',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				}
			}, {
				name : 'sexy',
				display : '�Ա�',
				sortable : true,
				width : 70
			}, {
				name : 'positionsName',
				display : 'ӦƸ��λ',
				sortable : true
			}, {
				name : 'deptName',
				display : '���˲���',
				sortable : true
			}, {
				name : 'projectGroup',
				display : '������Ŀ��',
				sortable : true
			}, {
				name : 'useWriteEva',
				display : '���˲��ű�������',
				sortable : true
			}, {
				name : 'useInterviewEva',
				display : '���˲�����������',
				sortable : true
			}, {
				name : 'useInterviewResult',
				display : '���˲��Ž������Խ��',
				sortable : true,
				width : 130
			}, {
				name : 'useInterviewer',
				display : '���˲������Թ�',
				sortable : true
			}, {
				name : 'useInterviewDate',
				display : '���˲�����������',
				sortable : true
			}, {
				name : 'useHireTypeName',
				display : '���˲��Ž���¼����ʽ',
				sortable : true,
				width : 120
			}, {
				name : 'useJobName',
				display : '����-ְλ����',
				sortable : true,
				hide : true
			}, {
				name : 'useAreaName',
				display : '���������֧������',
				sortable : true,
				width : 120
			}, {
				name : 'useTrialWage',
				display : '���˲��Ž��������ڹ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'useFormalWage',
				display : '���˲��Ž���ת������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'useDemandEqu',
				display : '�칫���������豸����',
				sortable : true,
				width : 120
			}, {
				name : 'useSign',
				display : '�Ƿ�ǩ������ҵ����Э�顷',
				sortable : true,
				width : 140
			}, {
				name : 'useManager',
				display : '���˲��Ÿ�������׼',
				sortable : true
			}, {
				name : 'useSignDate',
				display : '���˲��Ÿ�����ǩ������',
				sortable : true,
				hide : true
			}, {
				name : 'hrInterviewResult',
				display : 'HR��������',
				sortable : true
			}, {
				name : 'hrInterviewer',
				display : 'HR���Թ�',
				sortable : true
			}, {
				name : 'hrInterviewDate',
				display : 'HR��������',
				sortable : true
			}, {
				name : 'hrHireTypeName',
				display : '�ù���ʽ',
				sortable : true
			}, {
				name : 'hrRequire',
				display : '��Ƹ����',
				sortable : true
			}, {
				name : 'hrSourceType1',
				display : '������Դ����',
				sortable : true
			}, {
				name : 'hrSourceType2',
				display : '������ԴС��',
				sortable : true
			}, {
				name : 'hrJobName',
				display : '¼��ְλ����ȷ��',
				sortable : true
			}, {
				name : 'hrIsManageJob',
				display : '�Ƿ�����',
				sortable : true
			}, {
				name : 'hrIsMatch',
				display : '������������н�㼰н�������Ƿ��Ӧ',
				sortable : true
			}, {
				name : 'hrCharger',
				display : '��Ƹ������׼',
				sortable : true
			}, {
				name : 'hrManager',
				display : '��Ƹ������׼',
				sortable : true
			}, {
				name : 'manager',
				display : '������Դ����������׼',
				sortable : true
			}, {
				name : 'deputyManager',
				display : '���ܾ�����׼',
				sortable : true
			}],
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
					showModalWin("?model=hr_recruitment_interview&action=toView&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			},
			formHeight : 500,
			formWidth : 900
		},
		searchitems : [{
			display : '����',
			name : 'userNameSearch'
		}, {
			display : '���˲���',
			name : 'deptNamSearche'
		}]
	});
});