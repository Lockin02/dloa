var show_page = function(page) {
	$("#tutorSchemeGrid").yxgrid("reload");
};
$(function() {
	$("#tutorSchemeGrid").yxgrid({
		model : 'hr_tutor_tutorScheme',
		title : '��ʦ���˷���',
		isOpButton : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'schemeName',
			display : '��������',
			sortable : true,
			width:150
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			width : 100
		}, {
			name : 'remark',
			display : '��ע',
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
			display : "��������",
			name : 'schemeName'
		}]
	});
});