var show_page = function(page) {
	$("#behamoduleGrid").yxgrid("reload");
};
$(function() {
	$("#behamoduleGrid").yxgrid({
		model : 'hr_baseinfo_behamodule',
		title : '��Ϊģ������',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'moduleName',
			display : 'ģ������',
			sortable : true,
			width : 200,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=hr_baseinfo_behamodule&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'remark',
			display : '��ע˵��',
			sortable : true,
			width : 300
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "ģ������",
			name : 'moduleNameSearch'
		}]
	});
});