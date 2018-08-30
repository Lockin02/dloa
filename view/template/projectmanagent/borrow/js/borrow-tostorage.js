var show_page = function(page) {
	$("#tostorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#tostorageGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'toStoragePageJson',
		param : {
           // 'isproShipcondition' : '0'
		},
		title : '������Ա������',
		//��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : 'tostorage',
		//����Ϣ
		colModel : [
			{
			name : 'DeliveryStatus',
			display : '',
			sortable : false,
			width : '20',
			align : 'center',
			process : function(v, row) {
				if (row.DeliveryStatus == 'YFH' || row.status == '3') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		},
		{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '���',
			sortable : true,
			width : 180
		}, {
			name : 'Type',
			display : '����',
			sortable : true,
			width : 60
		}, {
			name : 'limits',
			display : '��Χ',
			sortable : true,
			width : 60
		},{
			name : 'timeType',
			display : '��������',
			sortable : true,
			width : 60
		}, {
			name : 'reason',
			display : '��������',
			sortable : true,
			width : 200
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 200
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true
		},/*{
			name : 'tostorage',
			display : '�ֹ�ȷ��',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "����ȷ��";
  				}else if(v == '1'){
  					return "ȷ����";
  				}else if(v == '2'){
  					return "ȷ�����";
  				}
  			}
		},*/{
			name : 'status',
			display : '����״̬',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "����";
  				}else if(v == '1'){
  					return "���ֹ黹";
  				}else if(v == '2'){
  					return "�ر�";
  				}else if(v == '3'){
  				    return "�˻�";
  				}else if(v == '4'){
  				    return "����������"
  				}else if(v == '5'){
  				    return "ת��ִ�в�"
  				}else if(v == '6'){
  				    return "ת��ȷ����"
  				}
  			}
		},{
			name : 'backStatus',
			display : '�黹״̬',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "δ�黹";
  				}else if(v == '1'){
  					return "�ѹ黹";
  				}else if(v == '2'){
  					return "���ֹ黹";
  				}
  			}
		}
		,{
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
			process : function(v){
  				if( v == 'WFH'){
  					return "δ����";
  				}else if(v == 'YFH'){
  					return "�ѷ���";
  				}else if(v == 'BFFH'){
	                return "���ַ���";
	            }
  			}
		}
		,{
			name : 'createName',
			display : '������',
			sortable : true

		},{
			name : 'objCode',
			display : 'ҵ�����',
			sortable : true

		}],
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].tostorage == 1){
							$('#row' + data.collection[i].id).css('color', 'blue');
						}
					}
				}
			}
		},
		comboEx : [{
					text : '����״̬',
					key : 'DeliveryStatus',
					data : [{
								text : 'δ����',
								value : '0'
							}, {
								text : '�ѷ���',
								value : '1'
							}, {
								text : '���ַ���',
								value : '2'
							}]
				}],
		// ���ӱ������
		//���ӱ��м��˸��ֶ�   ���ϰ汾/�ͺ�   2013.7.5
						subGridOptions : {
							subgridcheck : true,
							url : '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
							// ���ݵ���̨�Ĳ�����������
							param : [ {
								'docType' : 'oa_borrow_borrow'
							}, {
								paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
								colId : 'id'// ��ȡ���������ݵ�������
							} ],
							// ��ʾ����
							colModel : [ {
								name : 'productName',
								width : 200,
								display : '��������'
							}, {
								name : 'productModel',
								width : 150,
								display : '���ϰ汾/�ͺ�'
							}, {
								name : 'number',
								display : '����',
								width : 40
							}, {
								name : 'lockNum',
								display : '��������',
								width : 50,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'exeNum',
								display : '�������',
								width : 50,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'issuedShipNum',
								display : '���´﷢������',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'executedNum',
								display : '�ѷ�������',
								width : 60
							}, {
								name : 'issuedPurNum',
								display : '���´�ɹ�����',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'issuedProNum',
								display : '���´���������',
								width : 90,
								process : function(v) {
									if (v == '') {
										return 0;
									} else
										return v;
								}
							}, {
								name : 'backNum',
								display : '�˿�����',
								width : 60
							}, {
								name : 'projArraDate',
								display : '�ƻ���������',
								width : 80,
								process : function(v) {
									if (v == null) {
										return '��';
									} else {
										return v;
									}
								}
							} ]
						},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		},{
			display : '������',
			name : 'createName'
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row.limits == 'Ա��') {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=proDisViewTab&id="
							+ row.id + "&skey=" + row['skey_']);
				}else{
				    showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		},{
			text : '��������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.limits == '�ͻ�' || row.DeliveryStatus == '1' || row.tostorage == '1' || row.status == '2' || row.status == '3' || row.status == '5'|| row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=toRemindMail&id=" + row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('�����ʼ����ͳɹ���');
								$("#tostorageGrid").yxsubgrid("reload");
							}else{
							    alert('����ʧ�ܣ�');
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}

			}
		}
//		,{
//			text : 'ȷ������',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.tostorage == '1' ) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//
//					showOpenWin("?model=projectmanagent_borrow_borrow&action=storageEdit&id="
//							+ row.id
//							+ "&skey="
//							+ row['skey_']
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
//				} else {
//					alert("��ѡ��һ������");
//				}
//			}
//		}
		, {
			text : '���Ƶ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == '1' || row.tostorage == '1' || row.status == '2' || row.status == '3' || row.status == '5'|| row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {

					showModalWin("?model=stock_allocation_allocation&action=toBluePush&relDocType=DBDYDLXJY&relDocId="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
				} else {
					alert("��ѡ��һ������");
				}

			}
		}
		,{
			text : '�����ô���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == '1' || row.tostorage == '1'|| row.status == '3' || row.status == '2' || row.status == '5'|| row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {

					showThickboxWin("?model=projectmanagent_borrow_borrow&action=borrowDispose&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700")
				} else {
					alert("��ѡ��һ������");
				}
			}
		}
		]
	});

});