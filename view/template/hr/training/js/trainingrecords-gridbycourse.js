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
        }
	];

	$("#trainingrecordsGrid").yxgrid({
		model : 'hr_training_trainingrecords',
		param : { 'courseId' : $("#courseId").val()},
		title : '��ѵ��¼',
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
				sortable : true
			}, {
				name : 'userName',
				display : '����',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_trainingrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'deptName',
				display : '����',
				sortable : true
			}, {
				name : 'jobName',
				display : 'ְλ',
				sortable : true
			}, {
				name : 'courseName',
				display : '�γ�����',
				sortable : true
			}, {
				name : 'isInner',
				display : '��ѵ/��ѵ',
				sortable : true,
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
				sortable : true
			}, {
				name : 'beginDate',
				display : '��ʼ����',
				sortable : true
			}, {
				name : 'endDate',
				display : '��������',
				sortable : true
			}, {
				name : 'agency',
				display : '��ѵ����',
				sortable : true
			}, {
				name : 'address',
				display : '��ѵ�ص�',
				sortable : true
			}, {
				name : 'teacherName',
				display : '��ѵ��ʦ',
				sortable : true
			}, {
				name : 'fee',
				display : '��ѵ����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				datacode : 'HRPXZT'
			}, {
				name : 'assessmentName',
				display : '��������',
				sortable : true
			}, {
				name : 'assessmentScore',
				display : '���˳ɼ�',
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
			display : "�γ�����",
			name : 'courseNameM'
		},{
			display : "��ѵ����",
			name : 'agencyM'
		}]
	});
});