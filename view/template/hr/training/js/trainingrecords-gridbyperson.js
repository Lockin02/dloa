var show_page = function(page) {
	$("#trainingrecordsGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [
        {
			name : 'view',
			text : "�߼���ѯ",
			icon : 'view',
			action : function() {
				alert('������δ�������');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        },{
			name : 'exportIn',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_training_trainingrecords&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}
	];

	$("#trainingrecordsGrid").yxgrid({
		height:450,
		model : 'hr_training_trainingrecords',
		param : {
//			'userAccount' : $('#userAccount').val()
			'userNo' : $('#userNo').val()
		},
		title : '��ѵ��¼',
		isAddAction : false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				width:70,
				sortable : true
			}, {
				name : 'userName',
				display : '����',
				sortable : true,
				width:60,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_trainingrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'deptName',
				display : '����',
				width:90,
				sortable : true
			}, {
				name : 'jobName',
				display : 'ְλ',
				width:80,
				sortable : true
			}, {
				name : 'courseName',
				display : '�γ�����',
				sortable : true
			}, {
				name : 'isInner',
				display : '��ѵ/��ѵ',
				sortable : true,
				width:60,
				process : function(v){
					if(v == '1'){
						return '��ѵ';
					}else{
						return '��ѵ';
					}
				}
			}, {
				name : 'trainsTypeName',
				display : '��ѵ����',
				width:60,
				sortable : true
			}, {
				name : 'beginDate',
				display : '��ʼ����',
				width:80,
				sortable : true
			}, {
				name : 'endDate',
				display : '��������',
				width:80,
				sortable : true
			}, {
				name : 'agency',
				display : '��ѵ����',
				sortable : true
			}, {
				name : 'address',
				display : '��ѵ�ص�',
				width:100,
				sortable : true
			}, {
				name : 'teacherName',
				display : '��ѵ��ʦ',
				width:70,
				sortable : true
			}, {
				name : 'fee',
				display : '��ѵ����',
				width:80,
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'status',
				display : '״̬',
				width:60,
				sortable : true,
				datacode : 'HRPXZT'
			}, {
				name : 'assessmentName',
				display : '��������',
				width:80,
				sortable : true
			}, {
				name : 'assessmentScore',
				display : '���˳ɼ�',
				width:60,
				sortable : true
			}, {
				name : 'isUploadTA',
				display : '�����뽲ʦ�ڿ��������ύ״̬',
				sortable : true,
				hide : true
			}, {
				name : 'isUploadTU',
				display : '����ѵ�������Ľ��ƻ����ύ״̬',
				sortable : true,
				hide : true
			}],
		lockCol:['userNo','userName','jobName'],//����������
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
//		buttonsEx : buttonsArr,
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		searchitems : [{
			display : "�γ�����",
			name : 'courseNameM'
		},{
			display : "��ѵ����",
			name : 'agencyM'
		},{
			display : "��ѵ��ʦ",
			name : 'teacherNameM'
		}]
	});
});