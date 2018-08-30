var show_page = function(page) {
	$("#HistoryList").yxgrid("reload");
};
$(function() {
	$("#HistoryList").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'contract_sales_sales',
		action : 'historyListPageJson',
		param : {'equalCont':$('#contNumber').val()},
		title : '��ͬ��ʷ',
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
			text : '��ͬ��Ϣ',
			icon : 'view',

			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=readBaseInfoNoedit&id=&id='
					+ row.id
				)
			}
		},{

			text : '�������',
			icon : 'view',

			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_sales&pid='
				             +row.id
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
			display : '��ͬ����',
			name : 'contName',
			sortable : true,
			width : 150

		}, {
			display : '������뵥��',
			name : 'formNumber',
			sortable : true,

			width : 150
		}, {
			display : '�������ʱ��',
			name : 'applyTime',
			sortable : true,
			width : 90
		}, {
			display : '�汾��ʼʱ��',
			name : 'beginTime',
			sortable : true,
			width : 130
		}, {
			display : '��ͬ״̬',
			name : 'contStatus',
			sortable : true,
			width : 90,
			process :function(v){
						switch (v) {
							case '': return 'δ����';break;
							case '0': return 'δ����';break;
							case '1': return '��ִ��';break;
							case '2': return '���������';break;
							case '3': return '�����';break;
							case '4': return '��عر�';break;
							case '5': return '����ɾ��';break;
							case '6': return '�����ر�';break;
							case '9': return '�ѹر�';break;
							default : return 'δ����';break;
						}
					}
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			width : 90,
			sortable : true
		}, {
			display : '�汾�������',
			name : 'updateTime',
			sortable : true,
			width : 130
		}]
	});
});