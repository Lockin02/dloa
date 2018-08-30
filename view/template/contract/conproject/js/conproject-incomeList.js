var show_page = function() {
	$("#incomeListGrid").yxgrid("reload");
};

$(function() {

	var M = new Date();
	var Year = M.getFullYear();
	var belongYearOpts = [{
			text : '最新',
			value : 'max'
		},{
			text : Year+'年',
			value : Year
		}
	];
	for(var i = 1;i <= 12;i++){
		belongYearOpts.push({
			text : (Year-i)+'年',
			value : (Year-i)
		});
	}


	var belongMonthOpts = [];
	for(var i = 1;i <= 12;i++){
		belongMonthOpts.push({
			text : i+'月',
			value : i
		});
	}

	$("#incomeListGrid").yxgrid({
		model: 'contract_conproject_conproject',
		title: '收入成本',
		param: {projectId: $("#projectId").val()},
		action : 'incomeListJson',
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
			name: 'rateMoney',
			display: '税后项目金额',
			process: function(v) {
                return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
			},
            width: 120
		}, {
			name: 'schedule',
			display: '项目进度',
			process: function(v) {
				return v + ' %';
			},
			width: 120
		}, {
            name: 'objDeduct',
            display: '项目扣款',
			width: 120,
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            }
        }, {
            name: 'objBad',
            display: '项目坏账',
			width: 120,
            process: function(v) {
				v = (v == '' || v == 'null')? 0 : v;
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            }
        }, {
			name: 'earnings',
			display: '项目营收',
            width: 120,
			process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
			}
		},{
			name: 'estimates',
			display: '项目概算',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
			name: 'cost',
			display: '项目决算',
			process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
			},
            width: 120
		}, {
			name: 'exgrossTrue',
			display: '毛利率',
			process: function(v) {
				return v + ' %';
			},
            width: 120
		}, {
            name: 'shipCost',
            display: '计提发货成本',
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            },
            width: 120
        },{
            name: 'feeCostbx',
            display: '报销支付成本',
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            },
            width: 120
        },{
            name: 'otherCost',
            display: '其他成本',
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            },
            width: 120
        }],
		comboEx : [{
			text : '版本年份',
			key : 'belongYear',
			data : belongYearOpts,
			value: 'max'
		},{
			text : '版本月份',
			key : 'belongMonth',
			data : belongMonthOpts
		}],
        sortorder: 'desc',
        sortname: 'version'
	});
});