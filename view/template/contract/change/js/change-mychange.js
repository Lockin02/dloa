var show_page = function(page) {
	$("#MyChange").yxgrid("reload");
};

$(function() {
	$("#MyChange").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		model : 'contract_change_change',
		action : 'pageJsonMyChange',
		title : '�ҵı������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		isToolBar : false,
		sortorder : "DESC",

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=contract_change_change&action=showAction&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���������') {
					return true;
				}
				return false;
			},
			action : function(row) {
				location = '?model=contract_change_change&action=editChangeForm&id='
						+ row.id;
			}
		}, {
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���������') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_change&pid='
		             +row.id
		             + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���������') {
					return true;
				}
				return false;
			},
			action : function(row) {
				parent.location = 'controller/contract/sales/ewf_index.php?actTo=ewfSelect&formName=��ͬ���&examCode=oa_contract_change&billId='
						+ row.id
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���������') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('ȷ��ɾ����')){
					$.ajax({
						type : "POST",
						url : "?model=contract_change_change&action=delT",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#MyChange").yxgrid("reload");
							}else{
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		}, {
			text : '�������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('ȷ������?')){
					$.ajax({
						type : "POST",
						url : "?model=contract_change_change&action=beginChange",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
								$("#MyChange").yxgrid("reload");
							}else{
								alert('����ʧ��!');
							}
						}
					});
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('ȷ��ɾ����')){
					$.ajax({
						type : "POST",
						url : "?model=contract_change_change&action=notdel",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#MyChange").yxgrid("reload");
							}else{
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		}],
		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '������뵥��',
			name : 'formNumber',
			sortable : true,
			width : 150
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
			width : 150
		}, {
			display : '�ͻ�����',
			name : 'customerType',
			datacode : 'KHLX',
			sortable : true
		}, {
			display : '�ͻ�����ʡ��',
			name : 'province',
			sortable : true
		}, {
			display : '��ͬ״̬',
			name : 'contStatus',
			sortable : true,
			process : function(v) {
				switch (v) {
					case '' :
						return 'δ����';
						break;
					case '0' :
						return 'δ����';
						break;
					case '1' :
						return '��ִ��';
						break;
					case '2' :
						return '���������';
						break;
					case '3' :
						return '�����';
						break;
					case '4' :
						return '��عر�';
						break;
					case '5' :
						return '����ɾ��';
						break;
					case '6' :
						return '�����ر�';
						break;
					case '9' :
						return '�ѹر�';
						break;
					default :
						return 'δ����';
						break;
				}
			}
		}, {
			display : '��������',
			name : 'applyTime',
			sortable : true
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���뵥��',
			name : 'formNumber'
		}]
	});
});