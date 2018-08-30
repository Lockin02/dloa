var show_page = function(page) {
	$("#setGrid").yxgrid("reload");
};

$(function() {
	$("#setGrid").yxgrid({
		model : 'hr_worktime_set',
		title : '�����ڼ���',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'year',
			display : '���',
			sortable : true,
			align : 'center'
		},{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 400
		}],

		// ���ӱ������
		subGridOptions : {
			url : '?model=hr_worktime_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '�ӱ��ֶ�'
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
			display : "���",
			name : 'year'
		}],

		sortname : 'year'
	});
});