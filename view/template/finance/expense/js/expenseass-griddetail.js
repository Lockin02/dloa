var show_page = function(page) {
	$("#expenseassGrid").yxgrid("reload");
};
$(function() {
	$("#expenseassGrid").yxgrid({
		model : 'finance_expense_expenseass',
		title : '������ϸ��',
		param : { "HeadID" : $("#HeadID").val() },
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
//				name : 'Place',
//				display : '�ص�',
//				sortable : true
//			}, {
//				name : 'Note',
//				display : 'Note',
//				sortable : true
//			}, {
				name : 'CostDateBegin',
				display : '��ʼ����',
				sortable : true
			}, {
				name : 'CostDateEnd',
				display : '��������',
				sortable : true
			}, {
				name : 'BillNo',
				display : 'BillNo',
				sortable : true,
				hide : true
			}, {
				name : 'Status',
				display : '״̬',
				sortable : true
			}, {
				name : 'UpdateDT',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'Updator',
				display : 'Updator',
				sortable : true,
				hide : true
			}, {
				name : 'Creator',
				display : 'Creator',
				sortable : true,
				hide : true
			}, {
				name : 'CreateDT',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}],
		//�򿪵���expense�еķ��� -- ���αȽ�����
		toEditConfig : {
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_expense_expense&action=toEdit&id=" + rowData.HeadID + "&assId=" + rowData.id );
			}
		},
		//�򿪵���expense�еķ��� -- ���αȽ�����
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_expense_expense&action=toView&id=" + rowData.HeadID + "&assId=" + rowData.id );
			}
		},
		buttonsEx :[{
			text: "����",
			icon: 'edit',
			action: function(row,rows,idArr ) {
				location = "?model=finance_expense_expense&action=myList";
			}
		}],
		searchitems : [{
			display : "�����ֶ�",
			name : 'XXX'
		}]
	});
});