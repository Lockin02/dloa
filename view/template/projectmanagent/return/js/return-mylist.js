var show_page = function(page) {
	$("#returnGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnGrid").yxsubgrid({
		model : 'projectmanagent_return_return',
		param : {'createId' : $("#userId").val()},
		title : '�����˻�',
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
			name : 'returnCode',
			display : '�˻������',
			sortable : true,
			width : 150
		}, {
			name : 'contractCode',
			display : 'Դ�����',
			sortable : true,
			width : 150,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ v
						+ "</font>"
						+ '</a>';
			}
		}, {
			name : 'saleUserName',
			display : '���۸�����',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true
		}, {
			name : 'reason',
			display : '����ԭ��',
			sortable : true,
			width : 300
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_return_returnequ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'returnId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
						name : 'productCode',
						width : 100,
						display : '���ϱ��'
					},{
						name : 'productName',
						width : 150,
						display : '��������'
					}, {
						name : 'productModel',
						width : 200,
						display : '�ͺ�/�汾'
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
				showModalWin('?model=projectmanagent_return_return&action=toAdd');

			}
		}],
		menusEx : [

		{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_return_return&action=viewTab&id='
						+ row.id
						+ "&skey="+row['skey_']);
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
				showModalWin('?model=projectmanagent_return_return&action=init&id='
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
					showThickboxWin('controller/projectmanagent/return/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
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
						url : "?model=projectmanagent_return_return&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#returnGrid").yxsubgrid("reload");
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
			display : '�˻������',
			name : 'returnCode'
		},{
			display : 'Դ�����',
			name : 'contractCode'
		}]
	});
});