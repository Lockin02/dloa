var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		action : 'myPageJson',
		title : '���¼�¼',
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isAddAction : false,
		customCode : 'myStampGrid',
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
            	width : 80,
				hide : true
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
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == "0") {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
//					if(row.applyUserId != $("#userId").val()){
//						alert('�Ǹ��������˲����޸ĸü�¼');
//						return false;
//					}
					showThickboxWin('?model=contract_stamp_stamp&action=toEdit&id=' + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800"
					);
				}
			}
			,{
				name : 'close',
				text : '�ر�',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.status == "0") {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(confirm('�رպ������¿�����ȷ�Ϲر���')){
						$.ajax({
							type : "POST",
							url : "?model=contract_stamp_stamp&action=close",
							data : {
								"id" : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�رճɹ�!');
									show_page(1);
								}else{
									alert('�ر�ʧ��!');
								}
							}
						});
					}
				}
			}
		],
		searchitems : [{
			display : "��ͬ���",
			name : 'contractCodeSer'
		},{
			display : "������",
			name : 'applyUserNameSer'
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