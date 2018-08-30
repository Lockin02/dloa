var show_page = function() {
	$("#costListGrid").yxgrid("reload");
};

$(function() {
	$("#costListGrid").yxgrid({
		model: 'contract_conproject_conproject',
		title: '项目成本',
		param: {projectId: $("#projectId").val()},
		action : 'costListJson',
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		isEditAction: false,
		showcheckbox: false,
		isOpButton: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			hide: true
		}, {
            name: 'version',
            display: '版本号',
            width: 80
        }, {
			name: 'materialCost',
			display: '发货成本(存货模块)',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
			name: 'payCost',
			display: '报销支付成本',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		},{
			name: 'shipcost',
			display: '导入发货成本',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 120
		},{
			name: 'othercost',
			display: '导入其他成本',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 120
		}, {
			name: 'allCost',
			display: '合计成本',
			process: function(v,row) {
				var allCost = parseInt(row.materialCost) + parseInt(row.payCost);
				return moneyFormat2(allCost);
			},
			width: 120
		}],
        sortorder: 'desc',
        sortname: 'version'
	});
});