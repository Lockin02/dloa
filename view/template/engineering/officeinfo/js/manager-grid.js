var show_page = function(page) {
	$("#managerGrid").yxgrid("reload");
};
$(function() {
	$("#managerGrid").yxgrid({
		model : 'engineering_officeinfo_manager',
		title : '服务经理',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productLine',
			display : '执行区域',
			sortable : true,
			datacode : 'GCSCX'
		}, {
			name : 'province',
			display : '省份',
			sortable : true
		}, {
			name : 'managerId',
			display : '服务经理ID',
			sortable : true,
			hide : true
		}, {
			name : 'managerName',
			display : '服务经理',
			sortable : true,
			width : 150
		}, {
			name : 'formBelong',
			display : '表格所属编码',
			sortable : true,
			hide : true
		}, {
			name : 'formBelongName',
			display : '表格所属名称',
			sortable : true,
			hide : true
		}, {
			name : 'businessBelong',
			display : '归属公司编码',
			sortable : true,
			hide : true
		}, {
			name : 'businessBelongName',
			display : '归属公司',
			sortable : true
		}, {
			name : 'remark',
			display : '备注信息',
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
			display : "省份",
			name : 'provinceSch'
		},{
			display : "服务经理",
			name : 'managerNameSch'
		},{
			display : "归属公司",
			name : 'businessBelongNameSch'
		},{
			display : "执行区域",
			name : 'productLineNameSch'
		}]
	});
});