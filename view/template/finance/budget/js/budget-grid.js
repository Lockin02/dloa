var show_page = function(page) {
	$("#budgetGrid").yxgrid("reload");
};

$(function() {
	$("#budgetGrid").yxgrid({
    	model : 'finance_budget_budget',
    	title : '����Ԥ���б�',
    	sortorder : 'asc',
    	isDelAction : false,
   		/**
		 * ��������������
		 */
		toAddConfig : {
			toAddFn : function(p) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				window.open("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ c.plusUrl
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			}
		},
		toEditConfig : {
			toEditFn : function(p, g) {
					var c = p.toEditConfig;
					var w = c.formWidth ? c.formWidth : p.formWidth;
					var h = c.formHeight ? c.formHeight : p.formHeight;
					var rowObj = g.getSelectedRow();
					if (rowObj) {
						var rowData = rowObj.data('data');
						var keyUrl = "";
						window.open("?model="
								+ p.model
								+ "&action="
								+ c.action
								+ c.plusUrl
								+ "&id="
								+ rowData[p.keyField]
								+ keyUrl
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
								+ h + "&width=" + w);
					} else {
						alert('��ѡ��һ�м�¼��');
					}
				},
			action : 'toEdit'
		},
		/**
		 * �鿴����������
		 */
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					window.open("?model="
							+ p.model
							+ "&action="
							+ p.toViewConfig.action
							+ c.plusUrl
							+ "&id="
							+ rowData[p.keyField]
							+ keyUrl
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			},
			action : 'toView'
		},
    	//����Ϣ
		colModel : [
			{
				display : 'id',
				name : 'id',
				hide : true
			}, {
				name : 'year',
				display : '���',
				sortable : true
			}, {
				name : 'expenseType',
				display : '��������',
				process:function(v){
					if(v=='SQFY'){ //��ǰ����
						return '��ǰ����';
					}
					return v;
				}
			}, {
				name : 'expenseClass',
				display : '����С��'
			}, {
				name : 'totalBudget',
				display : '��Ԥ��'
			}, {
				name : 'final',
				display : '�ܾ���'
			}
		],
		sortorder: 'id'
	});
});