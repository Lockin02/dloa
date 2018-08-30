var show_page = function(page) {
	$("#budgetGrid").yxgrid("reload");
};

$(function() {
	$("#budgetGrid").yxgrid({
    	model : 'finance_budget_budget',
    	title : '费用预算列表',
    	sortorder : 'asc',
    	isDelAction : false,
   		/**
		 * 新增表单属性配置
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
						alert('请选择一行记录！');
					}
				},
			action : 'toEdit'
		},
		/**
		 * 查看表单属性配置
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
					alert('请选择一行记录！');
				}
			},
			action : 'toView'
		},
    	//列信息
		colModel : [
			{
				display : 'id',
				name : 'id',
				hide : true
			}, {
				name : 'year',
				display : '年份',
				sortable : true
			}, {
				name : 'expenseType',
				display : '费用类型',
				process:function(v){
					if(v=='SQFY'){ //售前费用
						return '售前费用';
					}
					return v;
				}
			}, {
				name : 'expenseClass',
				display : '费用小类'
			}, {
				name : 'totalBudget',
				display : '总预算'
			}, {
				name : 'final',
				display : '总决算'
			}
		],
		sortorder: 'id'
	});
});