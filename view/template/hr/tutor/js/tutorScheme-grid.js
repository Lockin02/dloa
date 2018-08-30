var show_page = function(page) {
	$("#tutorSchemeGrid").yxgrid("reload");
};
$(function() {
	$("#tutorSchemeGrid").yxgrid({
		model : 'hr_tutor_tutorScheme',
		title : '导师考核方案',
		isOpButton : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'schemeName',
			display : '方案名称',
			sortable : true,
			width:150
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			width : 100
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}],
		toAddConfig : {
			toAddFn : function(p,g){
				showModalWin("?model=hr_tutor_tutorScheme&action=toAdd");
			}
		},
		toEditConfig : {
			toEditFn :  function(p,g){
				var rowObj = g.getSelectedRow();
					if (rowObj) {
						var rowData = rowObj.data('data');
						showModalWin("?model=hr_tutor_tutorScheme&action=toEdit&id="+rowData['id']);
					}

			}
		},
		toViewConfig : {
			toViewFn : function(p,g){
				var rowObj = g.getSelectedRow();
					if (rowObj) {
						var rowData = rowObj.data('data');
						showModalWin("?model=hr_tutor_tutorScheme&action=toView&id="+rowData['id']);
					}

			}
		},
		searchitems : [{
			display : "方案名称",
			name : 'schemeName'
		}]
	});
});