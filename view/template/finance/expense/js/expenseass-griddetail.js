var show_page = function(page) {
	$("#expenseassGrid").yxgrid("reload");
};
$(function() {
	$("#expenseassGrid").yxgrid({
		model : 'finance_expense_expenseass',
		title : '申请明细表',
		param : { "HeadID" : $("#HeadID").val() },
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
//				name : 'Place',
//				display : '地点',
//				sortable : true
//			}, {
//				name : 'Note',
//				display : 'Note',
//				sortable : true
//			}, {
				name : 'CostDateBegin',
				display : '开始日期',
				sortable : true
			}, {
				name : 'CostDateEnd',
				display : '结束日期',
				sortable : true
			}, {
				name : 'BillNo',
				display : 'BillNo',
				sortable : true,
				hide : true
			}, {
				name : 'Status',
				display : '状态',
				sortable : true
			}, {
				name : 'UpdateDT',
				display : '更新时间',
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
				display : '创建时间',
				sortable : true,
				hide : true
			}],
		//打开的是expense中的方法 -- 情形比较特殊
		toEditConfig : {
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_expense_expense&action=toEdit&id=" + rowData.HeadID + "&assId=" + rowData.id );
			}
		},
		//打开的是expense中的方法 -- 情形比较特殊
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_expense_expense&action=toView&id=" + rowData.HeadID + "&assId=" + rowData.id );
			}
		},
		buttonsEx :[{
			text: "返回",
			icon: 'edit',
			action: function(row,rows,idArr ) {
				location = "?model=finance_expense_expense&action=myList";
			}
		}],
		searchitems : [{
			display : "搜索字段",
			name : 'XXX'
		}]
	});
});