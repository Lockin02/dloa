var show_page = function(page) {
	$("#MyPrincipal").yxgrid("reload");
};
$(function() {
	$("#MyPrincipal").yxgrid({
		model : 'contract_sales_sales',
		action : 'myprincipalPageJson',
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
			action : function(row) {
				if (row.ExaStatus == '���'){
				    showOpenWin('?model=contract_sales_sales&action=infoTab&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
					);
				} else {
				    showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
						+ row.id );
				}
			}
		}, {
			text : '��ͬ��ʷ',
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=versionShow&contNumber='
						+ row.contNumber
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '����ִ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.contStatus == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=contractBeginAction&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500');
			}
		}, {
			text : '������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.changeStatus == '0' && (row.contStatus == '0'
						|| row.contStatus == '1')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				toUrl('?model=contract_sales_sales&action=readInfoForChange&id='
						+ row.id);
			}
//		}, {
//			text : '���뷢��',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.changeStatus == '0' && row.contStatus == '1') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showThickboxWin('?model=stock_shipapply_shipapply&action=toAdd'
//					+ '&shipapply[objId]=' + row.id
//					+ '&shipapply[objCode]=' + row.contNumber
//					+ '&shipapply[objName]=' + row.contName
//					+ '&shipapply[objType]=KPRK-XSHT'
//					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1000'
//				);
//			}
		}, {
			text : '��ͬǩԼ״̬',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.contStatus == '1' && row.changeStatus == '0' && row.signStatus != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=toSign&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600');
			}
		}, {
			text : '�رպ�ͬ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.changeStatus == '0' &&row.ExaStatus != '��������' && row.ExaStatus != '������' && row.contStatus != 9) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_common_bcinfo&action=toClose&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						+ '&customerContNum'
						+ row.customerContNum
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
			}
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
		}, {
			display : 'ǩԼ״̬',
			name : 'signStatus',
			sortable : true,
			process : function(v){
				switch (v) {
					case '0': return 'δǩԼ';break;
					case '1': return '��ǩԼ';break;
					case '2': return '���õ�ֽ�ʺ�ͬ';break;
					case '3': return '���ύֽ�ʺ�ͬ';break;
					case '4': return '������ǩ��';break;
					default : return 'δǩԼ';break;
				}
			}
		}],
		comboEx : [{
			text : '��ͬ״̬',
			key : 'contStatus',
			data :[
				{
					text : 'δ����',
					value : 0
				},{
					text : '��ִ��&�����',
					value : 1
				},{
					text : '�ѹر�',
					value : 9
				}
			]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			type : 'workFlow'
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '�Ҹ���ĺ�ͬ'
	});
});