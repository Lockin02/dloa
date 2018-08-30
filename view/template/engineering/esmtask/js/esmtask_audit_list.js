var show_page = function(page) {
	$(".esmtaskauditGrid").yxgrid("reload");
};
$(function() {
	$(".esmtaskauditGrid").yxgrid({

		model : 'engineering_esmtask_esmtask',
		action : 'auditPageJson',
		title : '��Ŀ����--�����',
		showcheckbox : true, // ��ʾcheckbox
		isToolBar : false, // ��ʾ�б��Ϸ��Ĺ�����

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '��������',
					name : 'name',
					sortable : true
				}, {
					display : '������Ŀ',
					name : 'projectName',
					sortable : true
				}, {
					display : '���ȼ�',
					name : 'priority',
					sortable : true
				}, {
					display : '״̬',
					name : 'status',
					datacode : 'XMRWZT',
					sortable : true
				}, {
					display : '�����',
					name : 'effortRate',
					sortable : true
				}, {
					display : 'ƫ����',
					name : 'warpRate',
					sortable : true
				}, {
					display : '������',
					name : 'chargeName',
					sortable : true
				}, {
					display : '�������ʱ��',
					name : 'updateTime',
					sortable : true,
					width : 150
				}, {
					display : '�ƻ����ʱ��',
					name : 'planEndDate',
					sortable : true
				}, {
					display : '��������',
					name : 'taskType',
					sortable : true
				}],
		// ��չ��ť
		buttonsEx : [],
		// �Ҽ���չ
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {

				showThickboxWin('?model=engineering_task_protask&action=taskTab&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
			}

		}, {
			text : '���',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=engineering_task_esmtaskover&toAdd&id='+ row.id+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
			}

		}],
		// ��������
		searchitems : [{
					display : '�ȼ�',
					name : 'name'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '�ȼ�',
		// Ĭ�������ֶ���
		sortname : "name",
		// Ĭ������˳��
		sortorder : "ASC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// ������Ӱ�ť
		isAddAction : false,
		// ����ɾ����ť
		isDelAction : false,
		// �鿴��չ��Ϣ
		toViewConfig : {
			action : 'toRead'
		},

		// �޸���չ��Ϣ
		toEditConfig : {
			action : 'toEdit'
		}
	});

});