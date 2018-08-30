var show_page = function(page) {
	$("#procompositebaseGrid").yxgrid("reload");
};
$(function() {
	$("#procompositebaseGrid")
			.yxgrid(
					{
						model : 'stock_extra_procompositebase',
						title : '�����豸����ʱ�估�����Ϣ',
						isViewAction : false,
						isEditAction : false,

						// ����Ϣ
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'reportName',
							display : '����',
							sortable : true,
							width : '300'
						}, {
							name : 'activeYear',
							display : '���',
							sortable : true,
							width : '50'
						}, {
							name : 'periodSeNum',
							display : 'ʱ��',
							sortable : true,
							width : '50',
							hide : true
						}, {
							name : 'periodType',
							display : '��������',
							sortable : true,
							process : function(v, row) {
								if (v == "0") {
									return "�����";
								} else if (v == "1") {
									return "һ����";
								} else if (v == "2") {
									return "һ������";
								} else {
									return v;
								}
							},
							hide : true
						}, {
							name : 'isActive',
							display : '�Ƿ���Ч',
							width:'50',
							process : function(v, row) {
								if (v == "0") {
									return "��Ч";
								} else {
									return "��Ч";
								}
							},
							sortable : true
						}, {
							name : 'remark',
							display : '��ע',
							sortable : true
						}, {
							name : 'createName',
							display : '������',
							sortable : true
						}, {
							name : 'createTime',
							display : '��������',
							sortable : true,
							hide : true
						}, {
							name : 'updateName',
							display : '�޸���',
							sortable : true
						}, {
							name : 'updateTime',
							display : '����ʱ��',
							width : '150',
							sortable : true
						} ],
						// ���ӱ������
						// subGridOptions : {
						// url :
						// '?model=stock_extra_procompositebaseitem&action=pageItemJson',
						// param : [ {
						// paramId : 'mainId',
						// colId : 'id'
						// } ],
						// colModel : [ {
						// name : 'XXX',
						// display : '�ӱ��ֶ�'
						// } ]
						// },
						toAddConfig : {
							toAddFn : function(p) {
								action: showModalWin("?model=stock_extra_procompositebase&action=toAdd")
							},
							formWidth : 880,
							formHeight : 600
						},
						// toEditConfig : {
						// // action : 'toEdit'
						// toAddFn : function(p) {
						// action:
						// showModalWin("?model=stock_extra_procompositebase&action=toAdd")
						// },
						// },
						toViewConfig : {
							action : function(row) {
								// window
								// .open(
								// "?model=?model=stock_extra_procompositebase&action=toView&id="
								// + row.id, "",
								// "width=200,height=200,top=200,left=200");
							}
						},
						menusEx : [
								{
									name : 'view',
									text : "�鿴",
									icon : 'view',
									action : function(row, rows, grid) {
										showModalWin("?model=stock_extra_procompositebase&action=toView&id="
												+ row.id
												+ "&skey="
												+ row['skey_']);
									}
								},
								{
									name : 'edit',
									text : "�༭",
									icon : 'edit',
									action : function(row, rows, grid) {
										showModalWin("?model=stock_extra_procompositebase&action=toEdit&id="
												+ row.id
												+ "&skey="
												+ row['skey_']);
									}
								},
								{
									name : 'active',
									text : "����",
									icon : 'business',
									action : function(row, rows, grid) {
										if (confirm("��ȷ��Ҫ������ݱ�����������ʧЧ?")) {
											$
													.ajax({
														type : "POST",
														async : false,
														url : "?model=stock_extra_procompositebase&action=activeReport",
														data : {
															id : row.id
														},
														success : function(
																result) {
															if ("0" == result) {
																alert("����ɹ���");
																show_page();
															}

														}
													})
										}
									}

								} ],
						searchitems : [ {
							display : "����",
							name : 'reportName'
						} ],
						sortname : "id",
						// Ĭ������˳��
						sortorder : "asc"
					});
});