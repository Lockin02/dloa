var show_page = function(page) {
	$("#changeContractListYsp").yxgrid("reload");
};
$(function() {
	$("#changeContractListYsp").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		model : 'contract_change_change',
		action : 'ConYsppageJson',
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
			text : '�������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_change&pid='
		             +row.changeId
		             + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],
		// ��
		colModel : [{
			display : 'id',
			name : 'id',
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

		/**
		 * ��������
		 */
		searchitems : [{
			display : '���뵥��',
			name : 'contName'
		}],
		sortorder : "DESC	",
		title : '�������ı������'
	});
});