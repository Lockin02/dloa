var show_page = function(page) {
	$("#agencyGrid").yxgrid("reload");
};
$(function() {
	$("#agencyGrid").yxgrid({
		model : 'asset_basic_agency',
		title : '��������',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'agencyName',
			display : '��������',
			sortable : true
		}, {
			name : 'agencyCode',
			display : '�������',
			sortable : true
		}, {
			name : 'chargeId',
			display : '��������Id',
			sortable : true,
			hide : true
		}, {
			name : 'chargeName',
			display : '��������',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
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
			display : "��������",
			name : 'agencyName'
		}]
	});
});