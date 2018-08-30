var show_page = function() {
	$("#incomeListGrid").yxgrid("reload");
};

$(function() {

	var M = new Date();
	var Year = M.getFullYear();
	var belongYearOpts = [{
			text : '����',
			value : 'max'
		},{
			text : Year+'��',
			value : Year
		}
	];
	for(var i = 1;i <= 12;i++){
		belongYearOpts.push({
			text : (Year-i)+'��',
			value : (Year-i)
		});
	}


	var belongMonthOpts = [];
	for(var i = 1;i <= 12;i++){
		belongMonthOpts.push({
			text : i+'��',
			value : i
		});
	}

	$("#incomeListGrid").yxgrid({
		model: 'contract_conproject_conproject',
		title: '����ɱ�',
		param: {projectId: $("#projectId").val()},
		action : 'incomeListJson',
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		isEditAction: false,
		showcheckbox: false,
		isOpButton: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			hide: true
		}, {
            name: 'version',
            display: '�汾��',
            width: 80
        }, {
			name: 'rateMoney',
			display: '˰����Ŀ���',
			process: function(v) {
                return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
			},
            width: 120
		}, {
			name: 'schedule',
			display: '��Ŀ����',
			process: function(v) {
				return v + ' %';
			},
			width: 120
		}, {
            name: 'objDeduct',
            display: '��Ŀ�ۿ�',
			width: 120,
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            }
        }, {
            name: 'objBad',
            display: '��Ŀ����',
			width: 120,
            process: function(v) {
				v = (v == '' || v == 'null')? 0 : v;
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            }
        }, {
			name: 'earnings',
			display: '��ĿӪ��',
            width: 120,
			process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
			}
		},{
			name: 'estimates',
			display: '��Ŀ����',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
			name: 'cost',
			display: '��Ŀ����',
			process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
			},
            width: 120
		}, {
			name: 'exgrossTrue',
			display: 'ë����',
			process: function(v) {
				return v + ' %';
			},
            width: 120
		}, {
            name: 'shipCost',
            display: '���ᷢ���ɱ�',
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            },
            width: 120
        },{
            name: 'feeCostbx',
            display: '����֧���ɱ�',
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            },
            width: 120
        },{
            name: 'otherCost',
            display: '�����ɱ�',
            process: function(v) {
				return "<span class='text-right'>"+moneyFormat2(v)+"</span>";
            },
            width: 120
        }],
		comboEx : [{
			text : '�汾���',
			key : 'belongYear',
			data : belongYearOpts,
			value: 'max'
		},{
			text : '�汾�·�',
			key : 'belongMonth',
			data : belongMonthOpts
		}],
        sortorder: 'desc',
        sortname: 'version'
	});
});