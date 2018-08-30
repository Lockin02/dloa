var show_page = function(page) {
	$("#myexchangeGrid").yxsubgrid("reload");
};
$(function() {
	$("#myexchangeGrid").yxsubgrid({
		model : 'projectmanagent_exchange_exchange',
		param : {'createId' : $("#userId").val(),'toListMy' : 1},
		title : '���ۻ���',
		isDelAction : false,
		isToolBar : true, //�Ƿ���ʾ������
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'exchangeCode',
			display : '���������',
			sortable : true,
			width : 120
		}, {
			name : 'contractCode',
			display : 'Դ����',
			sortable : true,
			width : 130
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'saleUserName',
			display : '���۸�����',
			sortable : true
		}, {
			name : 'reason',
			display : '����ԭ��',
			sortable : true,
			width : 300
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_exchange_exchangebackequ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'exchangeId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				name : 'productCode',
				width : 200,
				display : '���ϱ��'
			},{
				name : 'productName',
				width : 200,
				display : '��������'
			}, {
			    name : 'number',
			    display : '��������',
				width : 80
			}, {
			    name : 'executedNum',
			    display : '��ִ������',
				width : 80
			}]
		},
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "����",
			icon : 'add',

			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=toAdd');

			}
		}],
		menusEx : [

		{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&perm=view' );
			}
		},{
			text : '�༭',
			icon : 'edit',
            showMenuFn : function(row){
				if(row.ExaStatus == 'δ����' || row.ExaStatus == '���'){
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=toEdit&id='
						+ row.id
						+ "&skey="+row['skey_']);
			}
		},{
			text : '�ύ���',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == 'δ����' || row.ExaStatus == '���'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/projectmanagent/exchange/ewf_index2.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}

		},{
				text : '�������',
				icon : 'view',
	            showMenuFn : function (row) {
	               if (row.ExaStatus=='����'){
	                   return false;
	               }
	                   return true;
	            },
				action : function(row) {

					showThickboxWin('controller/projectmanagent/exchange/readview.php?itemtype=oa_contract_exchangeapply&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_exchange_exchange&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#myexchangeGrid").yxsubgrid("reload");
							}
						}
					});
				}
			}

		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���������',
			name : 'exchangeCode'
		}]
	});
});