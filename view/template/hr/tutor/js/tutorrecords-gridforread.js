var show_page = function(page) {
	$("#tutorrecordsGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
//	buttonsArr = [
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
//    ];

	$("#tutorrecordsGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		action : 'pageJsonForRead',
		title : '��ʦ������Ϣ��',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '��ʦԱ�����',
				sortable : true
			}, {
				name : 'userAccount',
				display : '��ʦԱ���˺�',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '��ʦ����',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_tutor_tutorrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'jobId',
				display : '��ʦְλid',
				sortable : true,
				hide : true
			}, {
				name : 'jobName',
				display : '��ʦְλ',
				sortable : true
			}, {
				name : 'deptId',
				display : '��ʦ����Id',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '��ʦ����',
				sortable : true,
				hide : true
			}, {
				name : 'studentNo',
				display : 'ѧԱԱ�����',
				sortable : true,
				hide : true
			}, {
				name : 'studentAccount',
				display : 'ѧԱԱ���˺�',
				sortable : true,
				hide : true
			}, {
				name : 'studentName',
				display : 'ѧԱ����',
				sortable : true
			}, {
				name : 'studentDeptName',
				display : 'ѧԱ����',
				sortable : true
			}, {
				name : 'beginDate',
				display : '��ѧ��ʼ����',
				sortable : true
			}, {
				name : 'assessmentScore',
				display : '���˷���',
				sortable : true
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true,
				width : 130
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
		toEditConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toEdit',
			formWidth : '800',
			formHeight : '400'
		},
		toViewConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toView',
			formWidth : '800',
			formHeight : '400'
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			}
		},
//		buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : "Ա�����",
			name : 'userNoM'
		},{
			display : "��ʦ����",
			name : 'userNameM'
		},{
			display : "ѧԱ����",
			name : 'studentNameM'
		},{
			display : "ѧԱ����",
			name : 'studentDeptNameM'
		}]
	});
});