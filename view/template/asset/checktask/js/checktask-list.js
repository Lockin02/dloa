// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$("#taskGrid").yxgrid('reload');
};

$(function() {
	$("#taskGrid").yxgrid({

		model : 'asset_checktask_checktask',
		title : '�̵�������Ϣ',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '������',
			name : 'billNo',
			sortable : true
		}, {
			display : '�̵�ʱ��',
			name : 'checkDate',
			sortable : true
		}, {
			display : 'Ԥ���̵����ʱ��',
			name : 'endDate',
			sortable : true
		}, {
			display : '������id',
			name : 'initiatorId',
			sortable : true,
			hide : true

		}, {
			display : '������',
			name : 'initiator',
			sortable : true

		}, {
			display : '�̵㲿��id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '�̵㲿��',
			name : 'deptName',
			sortable : true,
			width : 230
		}, {
			display : '������id',
			name : 'participantId',
			sortable : true,
			hide : true

		}, {
			display : '������',
			name : 'participant',
			sortable : true,
			width : 230

		}, {
			display : '����˵��',
			name : 'remark',
			sortable : true
		}],

		isViewAction : true,
		isEditAction : true,
		toAddConfig : {
			formWidth : 1000,
			formHeight : 450
		},
		toEditConfig : {
			formWidth : 800,
			formHeight : 400
		},
		toViewConfig : {
			formWidth : 800,
			formHeight : 350
		},

		// ��������
		searchitems : [{
			display : '������',
			name : 'billNo'
		}, {
			display : '�̵�ʱ��',
			name : 'checkDate'
		}, {
			display : '�̵㲿��',
			name : 'deptName'
		}],
		sortname : "id",
		sortorder : "ASC"

	});

});