var show_page = function() {
	$("#grid").yxgrid("reload");
};

$(function() {
	$("#grid").yxgrid({
		model: 'engineering_records_esmincome',
		title: '项目收入',
		param: {projectId: $("#projectId").val()},
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
            name: 'versionNo',
            display: '版本号',
            width: 80
        }, {
			name: 'contractMoney',
			display: '合同金额',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
            name: 'workRate',
            display: '工作占比',
            process: function(v) {
                return v + ' %';
            },
            width: 120
        }, {
			name: 'projectProcess',
			display: '项目进度',
			process: function(v) {
                return v + ' %';
			},
            width: 120
		}, {
			name: 'deductMoney',
			display: '扣款金额（累计）',
			process: function(v) {
				return moneyFormat2(v);
			},
            width: 120
		}, {
			name: 'invoiceMoney',
			display: '开票金额（累计）',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
            name: 'reserveEarnings',
            display: '预留营收（累计）',
            process: function(v) {
                return moneyFormat2(v);
            },
            width: 120
        }, {
            name: 'curIncome',
            display: '项目收入（累计）',
            process: function(v) {
                return moneyFormat2(v);
            },
            width: 120
        }],
        sortorder: 'desc',
        sortname: 'versionNo'
	});
});