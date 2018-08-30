var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		action : 'pageJsonForStampType',
		title : '���¼�¼',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		customCode : 'stampGrid',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
            	width : 80
			}, {
				name : 'contractType',
				display : '��ͬ����',
				sortable : true,
            	width : 80,
            	datacode : 'HTGZYD'
			}, {
				name : 'contractId',
				display : '��ͬid',
				sortable : true,
				hide : true
			}, {
				name : 'contractCode',
				display : '��ͬ���',
            	width : 130,
				sortable : true
			}, {
				name : 'contractName',
				display : '��ͬ����',
				sortable : true,
            	width : 130
			}, {
				name : 'signCompanyName',
				display : 'ǩԼ��λ',
				sortable : true,
            	width : 130
			}, {
				name : 'contractMoney',
				display : '��ͬ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'applyUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '������',
				sortable : true,
            	width : 80
			}, {
				name : 'stampType',
				display : '��������',
				sortable : true
			}, {
				name : 'stampUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'stampUserName',
				display : '������',
				sortable : true,
            	width : 80
			}, {
				name : 'stampDate',
				display : '��������',
				sortable : true,
            	width : 80
			}, {
				name : 'stampCompany',
				display : '��˾��',
				sortable : true,
				width : 80
			},  {
				name : 'stampCompanyId',
				display : '��˾ID',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(v=="1"){
						return "�Ѹ���";
					}else if(v=='2'){
						return "�ѹر�";
					}else{
						return "δ����";
					}
				}
			}, {
				name : 'objCode',
				display : 'ҵ����',
				width : 120,
				sortable : true
			}, {
				name : 'batchNo',
				display : '��������',
				sortable : true
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true
			}
		],
		/**
		 * ��չ��ť
		 */
		buttonsEx : [
			{
				text : "����ȷ��",
				icon : 'edit',
                name : 'batchStamp',
                action : function(row, rows, rowIds, grid) {
                	if(rows != null){
                		if(confirm('ȷ�ϸ���?')){
							for(var i=0; i<rows.length; i++){
								if(rows[i]['status'] == 1 ){
									alert("��ѡ��δ���µ����ݣ�");
									return false;
								}
							}
							$.ajax({
							    type: "POST",
							    url: "?model=contract_stamp_stamp&action=batchStamp",
							    data: {'rowIds' : rowIds },
							    async: false,
							    success: function(data){
							    	if(data==1){
										alert('����ȷ�ϳɹ���');
										show_page();
									}else{
										alert('����ȷ��ʧ�ܣ�');
									}
								}
							});
						}
                	}else{
                		alert("������ѡ��һ�����ݣ�");
                	}
                }
            }
        ],

		// ��չ�Ҽ��˵�
		menusEx : [{
				name : 'view',
				text : '�鿴',
				icon : 'view',
				action : function(row, rows, grid) {
					showModalWin('?model=contract_stamp_stamp&action=toView&id=' + row.id);
				}
			},{
				name : 'stamp',
				text : 'ȷ�ϸ���',
				icon : 'edit',
				showMenuFn : function(row) {
					if ((row.status == "0")) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showModalWin('?model=contract_stamp_stamp&action=toConfirmStamp&id=' + row.id);
				}
			}
		],
		searchitems : [{
			display : "��ͬ���",
			name : 'contractCodeSer'
		},{
			display : "������",
			name : 'applyUserNameSer'
		},{
			display : "��˾",
			name : "stampCompanySearch"
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text: "��ͬ����",
			key: 'contractType',
			datacode : 'HTGZYD'
		},{
			text: "����״̬",
			key: 'status',
			value :'0',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '�Ѹ���',
				value : '1'
			}, {
				text : '�ѹر�',
				value : '2'
			}]
		}]
	});
});