$(function() {
	$("#grid").yxeditgrid({
		url: '?model=engineering_records_esmflights&action=searchJson',
		type: 'view',
		param: {
			projectId: $("#projectId").val()
		},
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
            name: 'from',
            display: '来源',
            process: function(v, row) {
                if (!v && row.id != 'noId') {
                    return '<span class="blue">机票系统</span>';
                } else {
                    return v;
                }
            },
            width: 80
        }, {
			name: 'thisYear',
			display: '年份',
			width: 80
		}, {
			name: 'thisMonth',
			display: '月份',
			width: 80
		}, {
			name: 'fee',
			display: '决算',
			align: 'right',
			process: function(v) {
				return moneyFormat2(v);
			},
            width: 80
		}]
	});
});