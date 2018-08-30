var show_page = function(page) {
	$("#incomeAllotGrid").yxgrid("reload");
};
$(function() {

	$("#incomeAllotGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'finance_income_incomeAllot',
		action : 'incomeDetPageJson',
		param : { 'exObjCode' : $('#contractNo').val() , 'exObjType' : 'KPRK-XSHT'},
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
        /*
         * �Ƿ���ʾ�Ҽ��˵�
         */
         isRightMenu : false,
         // ��չ�Ҽ��˵�
		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '�����',
			name : 'incomeNo',
			sortable : true,
			width : 130

		}, {
			display : '���λ',
			name : 'incomeUnitName',
			sortable : true,

			width : 100
		}, {
			display : '��������',
			name : 'incomeDate',
			sortable : true,
			width : 100
		}, {
			display : '���ʽ',
			name : 'incomeType',
			sortable : true,
			width : 100,
			datacode : "DKFS"
		}, {
			display : '������',
			name : 'money',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}

		}, {
			display : '¼����',
			name : 'createName',
			sortable : true,
			width : 100
		},{
			display : '¼��ʱ��',
			name : 'createTime',
			sortable : true,
			width : 130
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '�����',
			name : 'incomeNo'
		}],
		sortorder : "ASC",
		title : '�����¼'
	});
});