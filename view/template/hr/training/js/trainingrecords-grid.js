var show_page = function(page) {
	$("#trainingrecordsGrid").yxgrid("reload");
};

$(function() {
	//��ͷ��ť����
	buttonsArr = [{
//			name : 'view',
//			text : "�߼���ѯ",
//			icon : 'view',
//			action : function() {
//				alert('������δ�������');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        },{
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_training_trainingrecords&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	}];

	$("#trainingrecordsGrid").yxgrid({
//		showcheckbox : false,
		model : 'hr_training_trainingrecords',
		title : '��ѵ��¼',
		isAddAction : false,
		bodyAlign:'center',
		customCode : 'hr_training_trainingrecords',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			width:80,
			sortable : true
		},{
			name : 'userName',
			display : '����',
			sortable : true,
			width:100,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_trainingrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		},{
			name : 'deptName',
			display : '����',
			width:90,
			sortable : true
		},{
			name : 'jobName',
			display : 'ְλ',
			width:80,
			sortable : true
		},{
			name : 'courseCode',
			display : '�γ̱��',
			sortable : true
		},{
			name : 'courseName',
			display : '�γ�����',
			sortable : true
		},{
			name : 'duration',
			display : 'ʱ��',
			width:50,
			sortable : true
		},{
			name : 'trainsTypeName',
			display : '��ѵ����',
			width:60,
			sortable : true
		},{
			name : 'trainsMethod',
			display : '��ѵ��ʽ',
			sortable : true,
			width:60
		},{
			name : 'orgDeptName',
			display : '��֯����',
			width:90,
			sortable : true
		},{
			name : 'trainsMonth',
			display : '��ѵ�·�',
			width:60,
			sortable : true
		},{
			name : 'beginDate',
			display : '��ʼ����',
			width:80,
			sortable : true
		},{
			name : 'endDate',
			display : '��������',
			width:80,
			sortable : true
		},{
			name : 'trainsNum',
			display : '��ѵ����',
			width:70,
			sortable : true
		},{
			name : 'agency',
			display : '��ѵ����',
			sortable : true
		},{
			name : 'address',
			display : '��ѵ�ص�',
			width:100,
			sortable : true
		},{
			name : 'teacherName',
			display : '��ѵ��ʦ',
			width:70,
			sortable : true
		},{
			name : 'fee',
			display : '��ѵ����',
			width:80,
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		},{
			name : 'status',
			display : '״̬',
			width:60,
			sortable : true,
			datacode : 'HRPXZT'
		},{
			name : 'assessmentName',
			display : '��������',
			width:80,
			sortable : true
		},{
			name : 'assessmentScore',
			display : '���˳ɼ�',
			width : 60,
			sortable : true
		},{
			name : 'courseEvaluateScore',
			display : '�γ���������',
			width : 80,
			sortable : true
		},{
			name : 'trainsOrgEvaluateScore',
			display : '��ѵ��֯��������',
			width : 100,
			sortable : true
		},{
			name : 'followTime',
			display : 'Ч������Ч����ʱ��',
			width : 100,
			sortable : true
		// },{
		// 	name : 'isUploadTA',
		// 	display : '�����뽲ʦ�ڿ��������ύ״̬',
		// 	sortable : true,
		// 	hide : true,
		// 	process : function(v) {
		// 		if (v == '1'){
		// 			return '���ύ';
		// 		} else if (v == '2') {
		// 			return '�����ύ';
		// 		} else {
		// 			return 'δ�ύ';
		// 		}
		// 	}
		// },{
		// 	name : 'isUploadTU',
		// 	display : '����ѵ�������Ľ��ƻ����ύ״̬',
		// 	sortable : true,
		// 	hide : true,
		// 	process : function(v) {
		// 		if (v == '1'){
		// 			return '���ύ';
		// 		} else if (v == '2') {
		// 			return '�����ύ';
		// 		} else {
		// 			return 'δ�ύ';
		// 		}
		// 	}
		}],

		// lockCol:['userNo','userName','deptName'],//����������

		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},

		buttonsEx : buttonsArr,

		searchitems : [{
			display : "Ա�����",
			name : 'userNoM'
		},{
			display : "����",
			name : 'userNameM'
		},{
			display : "����",
			name : 'deptNameM'
		},{
			display : "ְλ",
			name : 'jobName'
		},{
			display : "�γ�����",
			name : 'courseNameM'
		},{
			display : "��ѵ����",
			name : 'agencyM'
		},{
			display : "��ѵ�ص�",
			name : 'address'
		},{
			display : "��ѵ��ʦ",
			name : 'teacherNameM'
		}]
	});
});