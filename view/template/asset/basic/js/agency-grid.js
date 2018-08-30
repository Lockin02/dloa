var show_page = function(page) {
	$("#agencyGrid").yxgrid("reload");
};
$(function() {
	$("#agencyGrid").yxgrid({
		model : 'asset_basic_agency',
		title : '行政区域',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'agencyName',
			display : '区域名称',
			sortable : true
		}, {
			name : 'agencyCode',
			display : '区域编码',
			sortable : true
		}, {
			name : 'chargeId',
			display : '区域负责人Id',
			sortable : true,
			hide : true
		}, {
			name : 'chargeName',
			display : '区域负责人',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
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
			display : "区域名称",
			name : 'agencyName'
		}]
	});
});