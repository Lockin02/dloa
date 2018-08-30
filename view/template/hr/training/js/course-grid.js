var show_page = function(page) {
	$("#courseGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [
        {
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
				showThickboxWin("?model=hr_training_course&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}
    ];

	$("#courseGrid").yxgrid({
		model : 'hr_training_course',
		title : '��ѵ�γ�',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'courseName',
				display : '�γ�����',
				sortable : true,
				width : 120,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_training_course&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				}
			}, {
				name : 'courseType',
				display : '�γ����',
				sortable : true,
				hide : true
			}, {
				name : 'courseTypeName',
				display : '�γ����',
				sortable : true
			}, {
				name : 'agency',
				display : '��ѵ����',
				sortable : true
			}, {
				name : 'teacherName',
				display : '��ѵ��ʦ',
				sortable : true
			}, {
				name : 'teacherId',
				display : '��ѵ��ʦid',
				sortable : true,
				hide : true
			}, {
				name : 'courseDate',
				display : '��ѵ����',
				sortable : true
			}, {
				name : 'address',
				display : '��ѵ�ص�',
				sortable : true,
				hide : true
			}, {
				name : 'lessons',
				display : '��ѵ��ʱ',
				sortable : true
			}, {
				name : 'fee',
				display : '��ѵ����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'outline',
				display : '�γ̴��',
				sortable : true,
				hide : true
			}, {
				name : 'forWho',
				display : '���ö���',
				sortable : true
			}, {
				name : 'status',
				display : '�γ�״̬',
				sortable : true,
				datacode : 'HRKCZT'
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				hide : true
			}, {
				name : 'personsListName',
				display : '��ѵ��Ա����',
				sortable : true,
				hide : true
			}, {
				name : 'personsListAccount',
				display : '��ѵ��Ա�˺�',
				sortable : true,
				hide : true
			}, {
				name : 'personsListNo',
				display : '��ѵ��Ա���',
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
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
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
					showOpenWin("?model=hr_training_course&action=viewTab&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		buttonsEx : buttonsArr,
		/**
		 * ��������
		 */
		searchitems : [{
			display : "�γ�����",
			name : 'courseNameM'
		},{
			display : "�γ����",
			name : 'courseTypeNameM'
		},{
			display : "��ѵ����",
			name : 'agencyM'
		},{
			display : "��ѵ��ʦ",
			name : 'teacherNameM'
		}]
	});
});