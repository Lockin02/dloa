var show_page = function(page) {
	$("#changeContractList").yxgrid("reload");
};
$(function() {

	$("#changeContractList").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'contract_change_change',
		action : 'ConpageJson',

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
        //�Ƿ���ʾ��Ӱ�ť
        isAddAction : false,
        //�Ƿ���ʾ������
        isToolBar : false,
        //�Ƿ���ʾcheckbox
         showcheckbox : false,
		// ��
		colModel : [{
			display : 'changeId',
			name : 'changeId',
			sortable : true,
			hide : true
		},{
			display : '������뵥��',
			name : 'formNumber',
			sortable : true,
			width : 150

		}, {
			display : 'ϵͳ��ͬ��',
			name : 'contNumber',
			sortable : true,

			width : 150
		}, {
			display : '��������',
			name : 'task',
			sortable : true,
			width : 150
		}, {
			display : '���������',
			name : 'applyName',
			sortable : true,
			width : 150
		}, {
			display : '��������',
			name : 'applyTime',
			sortable : true,
			width : 150

		}, {
			display : '���뵥״̬',
			name : 'ExaStatus',
			sortable : true,
			width : 150
		}],
		// ��չ�Ҽ��˵�
		menusEx : [

		{
			text : '�鿴',
			icon : 'view',

			action : function(row) {
				showOpenWin('?model=contract_change_change&action=showAction&id='
						+ row.changeId
						)
			}
		},{

			text : '����',
			icon : 'edit',

			action: function(row){

				parent.location = 'controller/contract/change/ewf_index.php?&actTo=ewfExam&examCode=oa_contract_change&taskId='
						+ row.task
						+ '&spid='
						+ row.id
						+ '&billId='
						+ row.changeId

			        }
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '���뵥��',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '�������ı������'
	});
});