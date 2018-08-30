var show_page = function(page) {
	$("#teachrecordsGrid").yxgrid("reload");
};
$(function() {
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
//        },
        	{
			name : 'exportIn',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_training_teachrecords&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}
	];

	$("#teachrecordsGrid").yxgrid({
		height:150,
		showcheckbox : false,
		model : 'hr_training_teachrecords',
		param : {
//			'teacherName' : $('#userName').val(),
			'userNo' : $('#userNo').val()
		},
		title : '��ѵ����-�ڿμ�¼',
		isAddAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true,
				width : 100
			}, {
				name : 'teacherName',
				display : '��ʦ����',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teacher&action=toView&id=" + row.teacherId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'teachDate',
				display : '�ڿ�����',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teachrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			},{
				name : 'courseName',
				display : '�γ�����',
				sortable : true,
				width : 100
			},{
				name : 'teachingClass',
				display : '�ڿο�ʱ',
				sortable : true,
				width : 50
			}, {
				name : 'address',
				display : '�ڿεص�',
				sortable : true,
				width : 100
			}, {
				name : 'assessmentScore',
				display : '�ڿ������÷�',
				sortable : true,
				width : 80
			},{
				name : 'theParticipationName',
				display : '��ѵ������',
				sortable : true,
				width : 250
			},{
				name : 'subsidiesToTeach',
				display : '�ڿβ������',
				sortable : true,
				width : 80
			},{
				name : 'distribution',
				display : '�ڿβ����������',
				sortable : true,
				width : 100
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '����������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 130,
				hide : true
			}],
			lockCol:['userNo','teacherName'],//����������
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
//		buttonsEx : buttonsArr,
		searchitems : [{
			display : "��ʦ����",
			name : 'teacherNameM'
		},{
			display : "�γ�����",
			name : 'courseNameM'
		}]
	});
});