var show_page = function() {
	$("#costListGrid").yxgrid("reload");
};

$(function() {
	$("#costListGrid").yxgrid({
		model: 'contract_conproject_conproject',
		title: '��Ŀ�ɱ�',
		param: {projectId: $("#projectId").val()},
		action : 'costListJson',
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
			name: 'materialCost',
			display: '�����ɱ�(���ģ��)',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		}, {
			name: 'payCost',
			display: '����֧���ɱ�',
			process: function(v) {
                return moneyFormat2(v);
			},
            width: 120
		},{
			name: 'shipcost',
			display: '���뷢���ɱ�',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 120
		},{
			name: 'othercost',
			display: '���������ɱ�',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 120
		}, {
			name: 'allCost',
			display: '�ϼƳɱ�',
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