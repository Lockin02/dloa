var show_page = function(page) {
	$("#setGrid").yxgrid("reload");
};

$(function() {
	$("#setGrid").yxgrid({
		model : 'hr_worktime_set',
		title : '法定节假日',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'year',
			display : '年份',
			sortable : true,
			align : 'center'
		},{
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 400
		}],

		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_worktime_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '从表字段'
			}]
		},

		toAddConfig : {
			formHeight : 500,
			formWidth : 650
		},
		toEditConfig : {
			formHeight : 500,
			formWidth : 650,
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "年份",
			name : 'year'
		}],

		sortname : 'year'
	});
});