var show_page = function(page) {
	$("#salesZZX").yxgrid("reload");
};
$(function() {
	$("#salesZZX").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
        param : {"exaStatus":"���","contStatus":"1,3"},
		model : 'contract_sales_sales',
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

			action: function(row){
                showOpenWin('?model=contract_sales_sales&action=infoTab&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						);
			}
//		},{
//			text : '��ͬ����',
//			icon : 'edit',
//			action: function(row){
//				parent.location='?model=contract_sales_sales&action=executeContract&id='
//						+ row.id;
//			}
		},{
			text : 'ָ��������',
			icon : 'edit',

			action: function(row){
                showThickboxWin('?model=contract_sales_sales&action=changeprincipal&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
			}
		},{
			text : 'ָ��ִ����',
			icon : 'edit',

			action: function(row){
                showThickboxWin('?model=contract_sales_sales&action=changeExecute&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
			}
		}],
		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '������ͬ��',
			name : 'contNumber',
			sortable : true,
			width : 150
		}, {
			display : '��ͬ����',
			name : 'contName',
			sortable : true,
			width : 160
		}, {
			display : '�ͻ�����',
			name : 'customerName',
			sortable : true
		}, {
			display : '�ͻ���ͬ��',
			name : 'customerContNum',
			sortable : true
		}, {
			display : '�ͻ�����',
			name : 'customerType',
			datacode : 'KHLX',
			sortable : true
		}, {
			display : '�ͻ�����ʡ��',
			name : 'province',
			sortable : true,
			width : 80
		}, {
			display : '����������',
			name : 'principalName',
			sortable : true
		}, {
			display : 'ִ��������',
			name : 'executorName',
			sortable : true
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
		title : '��ִ�е����ۺ�ͬ'
	});
});