var show_page = function(page) {
	$("#teacherGrid").yxgrid("reload");
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
				showThickboxWin("?model=hr_training_teacher&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}
	];

	$("#teacherGrid").yxgrid({
		height:450,
		model : 'hr_training_teacher',
		title : '��ѵ����-��ʦ����',
		param : {
//			'teacherName' : $('#userName').val(),
			'teacherNum' : $('#userNo').val()
		},
		isAddAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'teacherNum',
				display : '��ʦ���',
				sortable : true
			},{
				name : 'teacherName',
				display : '��ʦ����',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teacher&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'teacherAccount',
				display : '��ʦ�˺�',
				sortable : true,
				hide : true
			},{
				name : 'belongDeptName',
				display : '��ʦ����',
				sortable : true
			},{
				name : 'trainingAgency',
				display : '��ѵ����',
				sortable : true
			}, {
				name : 'belongDeptId',
				display : '��ʦ����ID',
				sortable : true,
				hide : true
			},{
				name : 'lecturerPost',
				display : '��ʦ��λ',
				sortable : true
			},{
				name : 'lecturerPostId',
				display : '��ʦ��λID',
				sortable : true,
				hide : true
			},{
				name : 'lecturerCategory',
				display : '��ʦ���',
				sortable : true
			},
//			{
//				name : 'isInner',
//				display : '�Ƿ���ѵʦ',
//				sortable : true,
//				process : function(v){
//					if(v == '1'){
//						return '��';
//					}else{
//						return '��';
//					}
//				}
//			},
			 {
				name : 'levelIdName',
				display : '��ѵʦ����',
				sortable : true
			},
			{
				name : 'certifyDate',
				display : '��֤����',
				sortable : true,
				process : function(v,row){
					if(row.certifyDate == '0000-00-00'){
						return '';
					}else{
						return v;
					}
				}
			}, {
				name : 'scores',
				display : '��֤����',
				sortable : true
//				process : function(v,row){
//					if(row.isInner != '1'){
//						return '';
//					}else{
//						return v;
//					}
//				}
			}, {
				name : 'courses',
				display : '���ڿγ�',
				sortable : true,
				width : 300
			}, {
				name : 'remark',
				display : '��ע˵��',
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
				hide : true
			}, {
				name : 'updateName',
				display : '�޸�������',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		lockCol:['teacherNum','teacherName','belongDeptName'],//����������
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
		//��������
		lockCol:['teacherNum','teacherName','belongDeptName'],//����������
//		comboEx : [{
//			text : '��ʦ�ڱ�',
//			key : 'lecturerCategory',
//			data : [{
//					text : '��ѵʦ',
//					value :'��ѵʦ'
//				},{
//					text : '��ʱ��ʦ',
//					value :'��ʱ��ʦ'
//				},{
//					text : '�ⲿ��ʦ',
//					value :'�ⲿ��ʦ'
//				}]
//			},{
//				text : '��ѵʦ����',
//				key : 'levelId',
//				datacode : 'HRNSSJB'
//			}
//		],
		/**
		 * ��������
		 */
		searchitems : [{
			display : "��ʦ����",
			name : 'teacherNameM'
		}]
	});
});