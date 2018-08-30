var show_page = function(page) {
	$("#accessorderGrid").yxsubgrid("reload");
};
$(function() {
	$("#accessorderGrid")
			.yxsubgrid(
					{
						model : 'service_accessorder_accessorder',
						title : '���������',
						isDelAction : false,
						isEditAction : false,
						isViewAction : false,
						showcheckbox : false,
						// ����Ϣ
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'docCode',
							display : '���ݱ��',
							sortable : true
						},{
							name : 'docDate',
							display : 'ǩ������',
							sortable : true
						}, {
							name : 'customerName',
							display : '�ͻ�����',
							sortable : true,
							width : 200
						}, {
							name : 'contactUserName',
							display : '�ͻ���ϵ��',
							sortable : true
						}, {
							name : 'telephone',
							display : '��ϵ�绰',
							sortable : true
						}, {
							name : 'adress',
							display : '�ͻ���ַ',
							sortable : true,
							hide : true
						}, {
							name : 'chargeUserName',
							display : '����������',
							sortable : true
						}, {
							name : 'ExaStatus',
							display : '����״̬',
							sortable : true
						}, {
							name : 'ExaDT',
							display : '����ʱ��',
							sortable : true,
							hide : true
						}, {
							name : 'auditerUserName',
							display : '����������',
							sortable : true,
							hide : true
						}, {
							name : 'docStatus',
							display : '����״̬',
							sortable : true,
							process : function(v, row) {
								if (row.docStatus == 'WZX') {
									return "δִ��";
								} else if (row.docStatus == 'ZXZ') {
									return "ִ����";
								} else if (row.docStatus == 'YWC') {
									return "�����";
								}
							}
						}, {
							name : 'saleAmount',
							display : '�������',
							sortable : true,
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'invoiceMoney',
							display : '�ѿ�Ʊ���',
							sortable : true,
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'areaLeaderName',
							display : '������������',
							sortable : true,
							hide : true
						}, {
							name : 'areaName',
							display : '��������',
							sortable : true,
							hide : true
						}, {
							name : 'remark',
							display : '��ע',
							sortable : true,
							hide : true
						}, {
							name : 'createName',
							display : '������',
							sortable : true,
							hide : true
						}, {
							name : 'createTime',
							display : '��������',
							sortable : true,
							hide : true
						}, {
							name : 'updateName',
							display : '�޸���',
							sortable : true,
							hide : true
						}, {
							name : 'updateTime',
							display : '�޸�����',
							sortable : true,
							hide : true
						} ],
						buttonsEx : [ {
							name : 'expport',
							text : "δ�����궩��",
							icon : 'excel',
							action : function(row) {
								window
										.open(
												"?model=service_accessorder_accessorder&action=toExportNotIncomeExcel",
												"",
												"width=200,height=200,top=200,left=200");
							}
						} ],
						menusEx : [
								{
									text : '�鿴',
									icon : 'view',
									action : function(row, rows, grid) {
										if (row.ExaStatus == "���"
												|| row.ExaStatus == "���") {
											if (row) {
												showModalWin("?model=service_accessorder_accessorder&action=viewTab&id="
														+ row.id
														+ "&skey="
														+ row['skey_']
														+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
											}
										} else {
											if (row) {
												showThickboxWin("?model=service_accessorder_accessorder&action=toView&id="
														+ row.id
														+ "&skey="
														+ row['skey_']
														+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
											}
										}
									}
								},
								{
									text : '�༭',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == "���ύ"
												|| row.ExaStatus == "���") {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=service_accessorder_accessorder&action=toEdit&id="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
										}
									}
								},
								{
									name : 'sumbit',
									text : '�ύ����',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == '���ύ'
												|| row.ExaStatus == '���') {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {

											showThickboxWin("controller/service/accessorder/ewf_index.php?actTo=ewfSelect&billId="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&flowMoney="
													+ row.saleAmount
													+ "&examCode=oa_service_accessorder&formName=�������������"
													+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");

										} else {
											alert("��ѡ��һ������");
										}
									}
								},
								{
									text : 'ɾ��',
									icon : 'delete',
									showMenuFn : function(row) {
										if (row.ExaStatus == '���ύ'
												|| row.ExaStatus == '���') {
											return true;
										}
										return false;
									},
									action : function(row) {
										if (window.confirm(("ȷ��ɾ����"))) {
											$
													.ajax({
														type : "POST",
														data : {
															id : row.id
														},
														url : "?model=service_accessorder_accessorder&action=ajaxdeletes",
														success : function(msg) {
															if (msg == 1) {
																show_page();
																alert('ɾ���ɹ���');
															} else {
																alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
															}
														}
													});
										}
									}
								},
								{
									text : '��д������',
									icon : 'add',
									showMenuFn : function(row) {
										if (row.ExaStatus == '���'
												&& row.docStatus != "YWC") {
											var checkResult = false;
											$
													.ajax({
														type : "POST",
														data : {
															mainId : row.id
														},
														async : false,
														url : "?model=service_accessorder_accessorderitem&action=isShipAll",
														success : function(
																result) {
															if (result == 0) {
																checkResult = true;
															}
														}
													});
											return checkResult;
										} else
											return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showModalWin("?model=stock_outplan_ship&action=toAdd&id="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&docType=oa_service_accessorder&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&&width=1100");
										} else {
											alert("��ѡ��һ������");
										}
									}
								},
								{
									text : '��Ʊ����',
									icon : 'add',
									showMenuFn : function(row) {
										if (row.ExaStatus == "���"
												&& row.docStatus != "YWC")
											return true;
										else
											return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											if (row.isBill == "1") {
												showModalWin("?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]="
														+ row.id
														+ "&invoiceapply[objCode]="
														+ row.docCode
														+ "&skey="
														+ row['skey_']
														+ "&invoiceapply[objType]=KPRK-10&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&&width=1100");
											} else {
												alert("�˶������ÿ�Ʊ�����ܽ��п�Ʊ����!");
											}

										} else {
											alert("��ѡ��һ������");
										}
									}
								},
								{
									text : '�ر����',
									icon : 'delete',
									showMenuFn : function(row) {
										if (row.docStatus == 'ZXZ') {
											return true;
										}
										return false;
									},
									action : function(row) {
										if (window.confirm(("ȷ���ر������"))) {
											$
													.ajax({
														type : "POST",
														url : "?model=service_accessorder_accessorder&action=closeFinished&id="
																+ row.id,
														success : function(
																result) {
															if (result == 0) {
																alert("�رճɹ�!")
																show_page();
															} else {
																alert("�ر�ʧ��");
															}

														}
													});
										}
									}
								} ],

						// ���ӱ������
						subGridOptions : {
							url : '?model=service_accessorder_accessorderitem&action=pageItemJson',
							param : [ {
								paramId : 'mainId',
								colId : 'id'
							} ],
							colModel : [ {
								name : 'productCode',
								display : '���ϱ��'
							}, {
								name : 'productName',
								display : '��������',
								width : 250
							}, {
								name : 'pattern',
								display : '����ͺ�'
							}, {
								name : 'price',
								display : '����',
								process : function(v, row) {
									return moneyFormat2(v);
								}
							}, {
								name : 'proNum',
								display : '��������',
								width : '80'
							}, {
								name : 'actOutNum',
								display : '�ѳ�������',
								width : '80'
							}, {
								name : 'subCost',
								display : '���',
								process : function(v, row) {
									return moneyFormat2(v);
								}
							} ]
						},
						toAddConfig : {
							formWidth : '1100px',
							formHeight : 600
						},
						toEditConfig : {
							action : 'toEdit',
							formWidth : '1100px',
							formHeight : 600
						},
						searchitems : [ {
							display : '���ϱ��',
							name : 'productCode'
						}, {
							name : 'productName',
							display : '��������'
						}, {
							display : '���ݱ��',
							name : 'docCode'
						}, {
							display : '�ͻ�����',
							name : 'customerName'
						}, {
							display : '�ͻ���ϵ��',
							name : 'contactUserName'
						} ],
						comboEx : [ {
							text : '����״̬',
							key : 'docStatus',
							data : [ {
								text : 'δִ��',
								value : 'WZX'
							}, {
								text : 'ִ����',
								value : 'ZXZ'
							}, {
								text : '�����',
								value : 'YWC'
							} ]
						}, {
							text : '����״̬',
							key : 'ExaStatus',
							data : [ {
								text : '���ύ',
								value : '���ύ'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '���',
								value : '���'
							} ]
						} ]
					});
});