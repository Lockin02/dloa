var show_page = function(page) {
	$("#returnGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnGrid").yxsubgrid({
		model : 'projectmanagent_return_return',
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
			name : 'qualityState',
			display : '״̬',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return "������";
					case '1' : return "�ʼ���";
					case '2' : return "�Ѵ���";
                    case '3' : return "�ʼ����";
				}
			},
			width : 50
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
				},{
					text : '�ύ�ʼ�',
					icon : 'add',
					showMenuFn : function(row) {
						if (row.qualityState == "0" && row.ExaStatus == "���") {
							return true;
						}
						return false;
					},
					action : function(row) {
						if (row) {
							showOpenWin("?model=produce_quality_qualityapply&action=toAdd&relDocId="
								+ row.id
								+ "&relDocType=ZJSQYDTH"
								+ "&relDocCode=" + row.returnCode
								,1,500,1000,row.id
							);
						}
					}
				},],
		comboEx : [{
			text : '����״̬',
			key : 'qualityState',
			value : '0',
			data : [{
				text : '������',
				value : '0'
			}, {
				text : '�ʼ���',
				value : '1'
			}, {
                text : '�ʼ����',
                value : '3'
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