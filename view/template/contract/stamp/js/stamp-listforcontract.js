var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		title : '���¼�¼',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		param : {
			"contractId" : $("#contractId").val(),
			"contractType" : $("#contractType").val()
		},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
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
            	width : 130,
				hide : true
			}, {
				name : 'contractType',
				display : '��ͬ����',
				sortable : true,
            	datacode : 'HTGZYD',
				hide : true
			}, {
				name : 'signCompanyName',
				display : 'ǩԼ��λ',
				sortable : true,
            	width : 130,
				hide : true
			}, {
				name : 'applyUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '������',
				sortable : true
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true
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
				sortable : true
			}, {
				name : 'stampDate',
				display : '��������',
				sortable : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
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
				name : 'batchNo',
				display : '��������',
				sortable : true
			}, {
				name : 'remark',
				display : '��ע',
				width : 200,
				sortable : true
			}
		],

		// ��չ�Ҽ��˵�
		menusEx : [{
				name : 'stamp',
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == "0" && row.applyUserId == $("#userId").val()) {
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
					if (row.status == "0" && row.applyUserId == $("#userId").val()) {
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
		toViewConfig : {
			action : 'toViewOnly'
		},
		searchitems : [{
			display : "��ͬ���",
			name : 'contractCodeSer'
		},{
			display : "������",
			name : 'applyUserNameSer'
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text: "����״̬",
			key: 'status',
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