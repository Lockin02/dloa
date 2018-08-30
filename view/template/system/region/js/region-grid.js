var show_page = function(page) {
	$("#regionGrid").yxgrid("reload");
};
$(function() {
	$("#regionGrid").yxgrid({
		model : 'system_region_region',
		title : '销售区域管理',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display: '所属板块',
			name: 'module',
			width: 100,
			sortable: true,
			datacode: 'HTBK'
		}, {
			name : 'areaName',
			display : '区域名称',
			sortable : true,
			width : 100
		}, {
			name : 'areaCode',
			display : '区域编码',
			sortable : true,
			hide : true
		}, {
			name : 'areaPrincipal',
			display : '区域负责人',
			sortable : true,
			width : 100
		}, {
			name : 'areaSalesman',
			display : '区域销售人员',
			sortable : true,
			width : 180
		}, {
			name : 'areaPrincipalId',
			display : '区域负责人Id',
			sortable : true,
			hide : true
		}, {
			name : 'province',
			display : '省份',
			sortable : true,
			width : 150
		}, {
			name : 'provinceManager',
			display : '省经理',
			sortable : true,
			width : 90
		}, {
			name : 'departmentLeader',
			display : '部门领导',
			sortable : true,
			width : 90
		}, {
			name : 'departmentDirector',
			display : '部门总监',
			sortable : true,
			width : 90
		}, {
			name : 'businessBelongName',
			display : '归属公司',
			sortable : true,
			width : 100
		}, {
			name : 'businessBelong',
			display : '归属公司编码',
			sortable : true,
			hide : true
		}, {
			name : 'formBelongName',
			display : '数据归属',
			sortable : true,
			hide : true
		}, {
			name : 'formBelong',
			display : '数据归属编码',
			sortable : true,
			hide : true
		}, {
			name : 'isStart',
			display : '是否开启',
			sortable : true,
			width : 80,
			process : function(v) {
				if (v == '0') {
					return "开启";
				} else {
					return "关闭";
				}
			}
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}, {
			name : 'expand',
			display : '扩展控制',
			sortable : true,
			width : 50,
			process : function(v) {
				if (v == '0') {
					return "否";
				} else {
					return "是";
				}
			}
		}],

		searchitems : [{
			display : '区域名称',
			name : 'areaName'
		},{
			display : '区域负责人',
			name : 'areaPrincipal'
		},{
			display : '省份',
			name : 'province'
		}]
	});
});