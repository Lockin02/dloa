var show_page = function(page) {
	$("#MyExecute").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;

	$("#MyExecute").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'contract_sales_sales',
		action : 'myExecutePageJson',
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
		// �Ƿ���ʾ������ť
		isAddAction : false,
		// �Ƿ���ʾ������
		isToolBar : false,
		//�Ƿ���ʾcheckbox
	    showcheckbox : false,

		// ��չ�Ҽ��˵�

		menusEx : [

		{
			text : '��ͬ��Ϣ',
			icon : 'view',
			    action: function(row){
	                showOpenWin('?model=contract_sales_sales&action=infoTab&id='
							+ row.id
							+ '&contNumber='
							+ row.contNumber
							);
						}
//		}, {
//			text : '���뷢��',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.changeStatus == '0' && row.contStatus == '1') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showThickboxWin('?model=stock_outstock_outapply&action=toApply&relDocId='
//						+ row.id
//						+ "&outapplyType=CKSQ-SALES"
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
//			}
		}],

		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '������ͬ��',
			name : 'contNumber',
			sortable : true,
			width : 150
		}, {
			display : '��ͬ����',
			name : 'contName',
			sortable : true,
			width : 150
		}, {
			display : '�ͻ�����',
			name : 'customerName',
			sortable : true,
			width : 100
		}, {
			display : '�ͻ���ͬ��',
			name : 'customerContNum',
			sortable : true,
			width : 100
		}, {
			display : '�ͻ�����',
			name : 'customerType',
			datacode : 'KHLX',
			sortable : true,
			width : 100
		}, {
			display : '�ͻ�����ʡ��',
			name : 'province',
			sortable : true,
			width : 100
		}, {
			display : '����������',
			name : 'principalName',
			sortable : true,
			width : 150
		}, {
			display : '��ͬ״̬',
			name : 'contStatus',
			sortable : true,
			process : function(v,row){
				if(row.changeStatus == '1'){
					return '�����';
				}
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
			sortable : true
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '��ִ�еĺ�ͬ'
	});
});