var show_page = function(page) {
	$("#initializeGrid").yxsubgrid("reload");
};
$(function() {
			$("#initializeGrid").yxsubgrid({
				model : 'projectmanagent_borrow_borrow',
				param : {
					"initTip" : "1"
				},
				title : '�����ó�ʼ�����ݱ�',
				isAddAction : false,
				isViewAction : false,
				isEditAction : false,
				isDelAction : false,
				showcheckbox : false,
				customCode : "initializeGrid",
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'limits',
							display : '����',
							sortable : true
						}, {
							name : 'Code',
							display : '���',
							sortable : true
						}, {
							name : 'customerName',
							display : '�ͻ�����',
							sortable : true
						}, {
							name : 'salesName',
							display : '���۸�����',
							sortable : true
						}, {
							name : 'createName',
							display : '������',
							sortable : true
						}, {
							name : 'beginTime',
							display : '��ʼ����',
							sortable : true
						}, {
							name : 'closeTime',
							display : '��ֹ����',
							sortable : true
						}, {
							name : 'ExaStatus',
							display : '����״̬',
							sortable : true,
							width : 90
						}, {
							name : 'DeliveryStatus',
							display : '����״̬',
							sortable : true,
							process : function(v) {
								if (v == '0') {
									return "δ����";
								} else if (v == '1') {
									return "�ѷ���";
								} else if (v == '2') {
									return "���ַ���";
								}
							}
						}, {
							name : 'remark',
							display : '��ע',
							sortable : true
						}, {
							name : 'objCode',
							display : 'ҵ����',
							width : 120
						}],
				comboEx : [{
							text : '����״̬',
							key : 'ExaStatus',
							data : [{
										text : 'δ����',
										value : 'δ����'
									}, {
										text : '��������',
										value : '��������'
									}, {
										text : '���',
										value : '���'
									}]
						}, {
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
						}, {
							text : '����������',
							key : 'limits',
							data : [{
										text : '�ͻ�',
										value : '�ͻ�'
									}, {
										text : 'Ա��',
										value : 'Ա��'
									}]
						}],
				// ���ӱ������
				subGridOptions : {
					url : '?model=projectmanagent_borrow_borrowequ&action=pageJson',// ��ȡ�ӱ�����url
					// ���ݵ���̨�Ĳ�����������
					param : [{
								paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
								colId : 'id'// ��ȡ���������ݵ�������

							}],
					// ��ʾ����
					colModel : [{
								name : 'productNo',
								width : 200,
								display : '��Ʒ���',
								process : function(v, row) {
									return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
								}
							}, {
								name : 'productName',
								width : 200,
								display : '��Ʒ����',
								process : function(v, row) {
									return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
								}
							}, {
								name : 'number',
								display : '��������',
								width : 80
							}, {
								name : 'executedNum',
								display : '��ִ������',
								width : 80
							}, {
								name : 'backNum',
								display : '�ѹ黹����',
								width : 80
							}]
				},
				/**
				 * ��������
				 */
				searchitems : [{
							display : '���',
							name : 'Code'
						}, {
							display : '�ͻ�����',
							name : 'customerName'
						}, {
							display : 'ҵ����',
							name : 'objCode'
						}, {
							display : '���۸�����',
							name : 'salesName'
						}, {
							display : '������',
							name : 'createNmae'
						}, {
							display : '��������',
							name : 'createTime'
						}, {
							display : 'K3��������',
							name : 'productNameKS'
						}, {
							display : 'ϵͳ��������',
							name : 'productName'
						}, {
							display : 'K3���ϱ���',
							name : 'productNoKS'
						}, {
							display : 'ϵͳ���ϱ���',
							name : 'productNo'
						}],
				buttonsEx : [{
					text : "��ʼ������",
					icon : 'edit',
					action : function() {
						window.open("?model=projectmanagent_borrow_borrow&action=initializeBorrowData"
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height=100&width=100")
					}
				}, {
					name : 'import',
					text : "���������ݵ���",
					icon : 'excel',
					action : function(row) {
						showThickboxWin("?model=projectmanagent_borrow_initialize&action=toImportExcel"
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
					}
				}, {
					name : 'renew',
					text : "���¹黹����",
					icon : 'edit',
					action : function(row) {
						if (confirm("ȷ�ϸ��¹黹������")) {
							$.ajax({
										url : '?model=projectmanagent_borrow_initialize&action=renewInitBorrowEquNum',
										success : function(data) {
											if (data == 1) {
												alert("���³ɹ�.");
											} else {
												alert("����ʧ��." + data);
											}
										}
									});
						}
					}
				}],
				// ��չ�Ҽ��˵�

				menusEx : [{
							text : '�鿴',
							icon : 'view',
							action : function(row) {
								if (row) {
									showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id=" + row.id + "&skey=" + row['skey_']);

								}
							}

						}],
				toEditConfig : {
					action : 'toEdit'
				},
				toViewConfig : {
					action : 'toView'
				}
			});
		});