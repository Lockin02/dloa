var show_page = function(page) {
	$("#returnGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnGrid").yxsubgrid({
		model : 'projectmanagent_return_return',
		title : '�����˻�',
		param : {'qualityState' : '3','ExaStatusArr' : '���'},
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
			sortable : true,
			width : 90
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			width : 80
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true
		}, {
			name : 'reason',
			display : '����ԭ��',
			sortable : true,
			width : 300
		}, {
			name : 'instockStatus',
			display : '���״̬',
			sortable : true,
			process : function(v) {
				if(v == 0){
					return "δ���";
				}else if(v == 1){
					return "�������";
				}else if(v == 2){
					return "�����";
				}
			},
			width : 80
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
						width : 200,
						display : '���ϱ��'
					},{
						name : 'productName',
						width : 200,
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
		menusEx : [
				{
					text : '�鿴',
					icon : 'view',
					action : function(row) {
						showModalWin('?model=projectmanagent_return_return&action=viewTab&id='
								+ row.id
								+ "&skey="+row['skey_']);
					}
				}, {
					name : 'addred',
					text : "���۳���(����)",
					icon : 'business',
					showMenuFn : function(row) {
						if (row.ExaStatus == "���" && (row.instockStatus == 0 || row.instockStatus == 1)) {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						showModalWin("?model=stock_outstock_stockout&action=toAddRedByAwait&id="
								+ row.id
								+ "&skey="
								+ row['skey_'])
					}
				}, {
					name : 'addorther',
					text : "�������",
					icon : 'business',
					showMenuFn : function(row) {
						if (row.ExaStatus == "���" && (row.instockStatus == 0 || row.instockStatus == 1)) {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						showModalWin("?model=stock_instock_stockin&action=toBluePush&docType=RKOTHER" +
								"&relDocId="+row.id +
								"&relDocType=RXSTH"+
								"&relDocCode="+row.returnCode
								)
					}
				}],
		comboEx : [{
			text : '���״̬',
			key : 'instockStatusArr',
			value : '0,1',
			data : [{
				text : 'δ���',
				value : '0'
			}, {
				text : '�������',
				value : '1'
			}, {
				text : '�����',
				value : '2'
			}, {
				text : 'δ��⣬�������',
				value : '0,1'
			}]
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
		},{
			display : '���۸�����',
			name : 'saleUserName'
		}]
	});
});