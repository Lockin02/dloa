$(function() {
	$("#grid").yxeditgrid({
		url: '?model=engineering_records_esmflights&action=searchJson',
		type: 'view',
		param: {
			projectId: $("#projectId").val()
		},
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
            name: 'from',
            display: '��Դ',
            process: function(v, row) {
                if (!v && row.id != 'noId') {
                    return '<span class="blue">��Ʊϵͳ</span>';
                } else {
                    return v;
                }
            },
            width: 80
        }, {
			name: 'thisYear',
			display: '���',
			width: 80
		}, {
			name: 'thisMonth',
			display: '�·�',
			width: 80
		}, {
			name: 'fee',
			display: '����',
			align: 'right',
			process: function(v) {
				return moneyFormat2(v);
			},
            width: 80
		}]
	});
});