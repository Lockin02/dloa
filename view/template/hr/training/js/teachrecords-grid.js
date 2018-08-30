var show_page = function(page) {
	$("#teachrecordsGrid").yxgrid("reload");
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
			showThickboxWin("?model=hr_training_teachrecords&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	}];

	$("#teachrecordsGrid").yxgrid({
//		showcheckbox : false,
		model : 'hr_training_teachrecords',
		title : '��ѵ����-�ڿμ�¼',
		isAddAction : false,
		bodyAlign : 'center',
		customCode : 'hr_training_teachrecords',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'courseCode',
			display : '�γ̱��',
			sortable : true
		},{
			name : 'courseName',
			display : '�γ�����',
			sortable : true,
			width : 100
		},{
			name : 'duration',
			display : 'ʱ��',
			sortable : true,
			width : 50
		},{
			name : 'teacherName',
			display : '��ʦ����',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teacher&action=toView&id=" + row.teacherId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width : 100
		},{
			name : 'trainsTypeName',
			display : '��ѵ����',
			sortable : true,
			width : 80
		},{
			name : 'trainsMethod',
			display : '��ѵ��ʽ',
			sortable : true,
			width : 80
		},{
			name : 'orgDeptName',
			display : '��֯����',
			sortable : true,
			width : 80
		},{
			name : 'trainsMonth',
			display : '��ѵ�·�',
			sortable : true,
			width : 80
		},{
			name : 'teachDate',
			display : '�ڿο�ʼ����',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teachrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'teachEndDate',
			display : '�ڿν�������',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teachrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'trainsNum',
			display : '��ѵ����',
			sortable : true
		},{
			name : 'agency',
			display : '��ѵ����',
			sortable : true,
			width : 100
		},{
			name : 'address',
			display : '�ڿεص�',
			sortable : true,
			width : 100
		},{
			name : 'joinNum',
			display : '��������',
			sortable : true,
		},{
			name : 'fee',
			display : '����',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'assessmentName',
			display : '��������',
			width : 80,
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
		},{
			name : 'createName',
			display : '����������',
			sortable : true,
			hide : true
		},{
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 130,
			hide : true
		}],

		// lockCol:['userNo','teacherName'],//����������

		toEditConfig : {
			action : 'toEdit',
			formHeight : 450
		},
		toViewConfig : {
			action : 'toView'
		},

		buttonsEx : buttonsArr,

		searchitems : [{
			display : "Ա�����",
			name : 'userNo'
		},{
			display : "��ʦ����",
			name : 'teacherNameM'
		},{
			display : "�ڿο�ʼ����",
			name : 'teachDate'
		},{
			display : "�ڿν�������",
			name : 'teachEndDate'
		},{
			display : "�γ�����",
			name : 'courseNameM'
		},{
			display : "�ڿεص�",
			name : 'address'
		}]
	});
});