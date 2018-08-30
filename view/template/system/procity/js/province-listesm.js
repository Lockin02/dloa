var show_page = function(page) {
	$("#provincelist").yxgrid("reload");
}

$(function() {
	$("#provincelist").yxgrid({
		model : 'system_procity_province',
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '国家',
			name : 'countryName',
			sortable : true,
			hide : true
		}, {
			display : '省份名称',
			name : 'provinceName',
			sortable : true
		}, {
			display : '省份编号',
			name : 'provinceCode',
			sortable : true,
			hide : true
		}, {
			display : '工程服务经理',
			name : 'esmManager',
			width : 150,
			sortable : true
		}, {
			display : '备注信息',
			name : 'remark',
			width : 200,
			sortable : true
		}, {
			display : '序号',
			name : 'sequence',
			sortable : true,
			hide : true
		}],
		toEditConfig : {
			action : 'toEditEsm'
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '省份名称',
			name : 'provinceName'
		}],
		sortorder : "ASC",
		title : '省份名称'
	});
});