var show_page = function() {
	$("#grid").yxgrid("reload");
};

$(function() {
	$("#grid").yxgrid({
		model: 'engineering_records_esmincome',
		title: '��Ŀ����',
		param: {projectId: $("#projectId").val()},
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
            name: 'versionNo',
            display: '�汾��',
            width: 80
        }, {
			name: 'contractMoney',
			display: '��ͬ���',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
            name: 'workRate',
            display: '����ռ��',
            process: function(v) {
                return v + ' %';
            },
            width: 120
        }, {
			name: 'projectProcess',
			display: '��Ŀ����',
			process: function(v) {
                return v + ' %';
			},
            width: 120
		}, {
			name: 'deductMoney',
			display: '�ۿ���ۼƣ�',
			process: function(v) {
				return moneyFormat2(v);
			},
            width: 120
		}, {
			name: 'invoiceMoney',
			display: '��Ʊ���ۼƣ�',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
            name: 'reserveEarnings',
            display: 'Ԥ��Ӫ�գ��ۼƣ�',
            process: function(v) {
                return moneyFormat2(v);
            },
            width: 120
        }, {
            name: 'curIncome',
            display: '��Ŀ���루�ۼƣ�',
            process: function(v) {
                return moneyFormat2(v);
            },
            width: 120
        }],
        sortorder: 'desc',
        sortname: 'versionNo'
	});
});