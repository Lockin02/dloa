var show_page = function(page) {
	$(".protasklist").yxgrid("reload");
};

$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;
	var pjId = $('#projectId').val();
	$(".protasklist").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

       model : 'engineering_task_protask',

            /**
			 * �Ƿ���ʾ��Ӱ�ť/�˵�
             */
			isAddAction : false,
			/**
			 * �Ƿ���ʾ�鿴��ť/�˵�
             */
			isViewAction : false,
			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
             */
			isEditAction : false,
			/**
			 * �Ƿ���ʾɾ����ť/�˵�
			 */
			isDelAction : false,

		colModel : [ {
			display : '��������',
			name : 'name',
			sortable : true,
			width : 80
		},

//			{
//			display : '������Ŀ',
//			name : 'projectName',
//			sortable : true,
//			width : 80
//		},

			{
			display : '���ȼ�',
			name : 'priority',
			sortable : true,
			width : 100
		}, {
			display : '״̬',
			name : 'status',
			sortable : true,
			datacode : 'XMRWZT',

			width : 100
		}, {
			display : '�����',
			name : 'effortRate',
			sortable : true,
			width : 100
		}, {
			display : 'ƫ����',
			name : 'warpRate',
			sortable : true,
			width : 100
		}, {
			display : '������',
			name : 'chargeName',
			sortable : true,
			width : 100
		}, {
			display : '������',
			name : 'publishName',
			sortable : true,
			width : 100
		}, {
			display : '�ƻ���ʼʱ��',
			name : 'planBeginDate',
			sortable : true,
			width : 100
		}, {
			display : '�ƻ����ʱ��',
			name : 'planEndDate',
			sortable : true,
			width : 100
		}, {
			display : '��������',
			name : 'taskType',
			width : 100,
			sortable : true
		}],

	    param:{
			"projectId":$("#projectId").val()
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '#',
			name : '#'
		}],
		sortorder : "ASC",
		title : '��Ŀ����'
	});
});