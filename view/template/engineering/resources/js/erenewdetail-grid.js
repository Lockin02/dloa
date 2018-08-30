var show_page = function(page) {
	$("#erenewdetailGrid").yxgrid("reload");
};
$(function() {
	$("#erenewdetailGrid").yxgrid({
		model : 'engineering_resources_erenewdetail',
		title : '续借申请明细',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'mainId',
			display : 'mainId',
			sortable : true
		}, {
			name : 'resourceId',
			display : '设备id',
			sortable : true
		}, {
			name : 'resourceName',
			display : '设备名称',
			sortable : true
		}, {
			name : 'resourceTypeId',
			display : '设备类型id',
			sortable : true
		}, {
			name : 'resourceTypeName',
			display : '设备类型名称',
			sortable : true
		}, {
			name : 'coding',
			display : '机身码',
			sortable : true
		}, {
			name : 'dpcoding',
			display : '部门码',
			sortable : true
		}, {
			name : 'number',
			display : '数量',
			sortable : true
		}, {
			name : 'unit',
			display : '单位',
			sortable : true
		}, {
			name : 'endDate',
			display : '预计归还日期',
			sortable : true
		} ]
	});
});