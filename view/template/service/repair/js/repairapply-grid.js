var show_page = function(page) {
	$("#repairapplyGrid").yxsubgrid("reload");
};

/**
 * �鿴ά�޼����Ϣ
 *
 * @param {}
 *            applyItemId
 */
function viewCheckDetail(applyItemId) {
	var skey = "";
	// $.ajax({
	// type : "POST",
	// url : "?model=service_repair_repaircheck&action=md5RowAjax",
	// data : {
	// "id" : mainId
	// },
	// async : false,
	// success : function(data) {
	// skey = data;
	// }
	// });
	showThickboxWin("?model=service_repair_repaircheck&action=toViewAtApply&applyItemId="
			+ applyItemId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
}

$(function() {
	$("#repairapplyGrid")
			.yxsubgrid(
					{
						model : 'service_repair_repairapply',
						title : 'ά���������',
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
						}, {
							name : 'docDate',
							display : '��������',
							width : '80',
							sortable : true
						}, {
							name : 'contractCode',
							display : '��ͬ���',
							sortable : true,
							width : 120
						}, {
							name : 'contractName',
							display : '��ͬ����',
							sortable : true,
							width : 120,
							hide : true
						}, {
							name : 'prov',
							display : 'ʡ��',
							sortable : true,
							width : 60
						}, {
							name : 'customerName',
							display : '�ͻ�����',
							sortable : true,
							width : 200
						}, {
							name : 'contactUserName',
							display : '�ͻ���ϵ��',
							sortable : true,
							width : 80
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
							name : 'subCost',
							display : 'ά�޷���',
							sortable : true,
							width : '70',
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'docStatus',
							display : '����״̬',
							sortable : true,
							width : '70',
							process : function(v) {
								if (v == 'WZX') {
									return "δִ��";
								} else if (v == 'ZXZ') {
									return "ִ����";
								} else if (v == "YWC") {
									return "�����";
								} else {
									return v;
								}
							}
						}, {
							name : 'applyUserName',
							display : '����������',
							sortable : true
						}, {
							name : 'applyUserCode',
							display : '�������˺�',
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
						}, {
							name : 'deliveryDocCode',
							display : '��ݵ���',
							sortable : true

						} ],
						isDelAction : false,
						showcheckbox : false,
						isViewAction : false,
						isEditAction : false,
						toAddConfig : {
							toAddFn : function(p) {
								action: showModalWin("?model=service_repair_repairapply&action=toAdd")
							}
						},
						buttonsEx : [ {
							name : 'expport',
							text : "����",
							icon : 'excel',
							action : function(row) {
								// window
								// .open(
								// "?model=service_accessorder_accessorder&action=toExportSearch",
								// "", "width=200,height=200,top=200,left=200");
								showThickboxWin("?model=service_repair_repairapply&action=toExportSearch&docType=RKPURCHASE"
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=620")

							}
						} ],
						menusEx : [
								{
									text : '�鿴',
									icon : 'view',
									action : function(row, rows, grid) {
										if (row) {
											showModalWin("?model=service_repair_repairapply&action=viewTab&id="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
										}
									}
								}],

						// ���ӱ������
						subGridOptions : {
							url : '?model=service_repair_applyitem&action=pageJson',
							param : [ {
								paramId : 'mainId',
								colId : 'id'
							} ],
							colModel : [
									{
										name : 'productCode',
										display : '���ϱ��',
										width : 80,
										sortable : true
									},
									{
										name : 'productName',
										display : '��������',
										sortable : true,
										width : 200
									},
									{
										name : 'serilnoName',
										display : '���к�',
										sortable : true,
										width : 200
									},
									{
										name : 'isGurantee',
										display : '�Ƿ����',
										sortable : true,
										width : 50,
										process : function(val) {
											if (val == "0") {
												return "��";
											} else {
												return "��";
											}
										}
									},
									{
										name : 'repairType',
										display : '��������',
										sortable : true,
										width : 80,
										process : function(val) {
											if (val == "0") {
												return "�շ�ά��";
											} else if (val == "1") {
												return "����ά��";
											} else if (val == "2") {
												return "�ڲ�ά��";
											} else {
												return val;
											}
										}
									},
									{
										name : 'repairCost',
										display : 'ά�޷���',
										sortable : true,
										process : function(v, row) {
											return moneyFormat2(v);
										}
									},
									{
										name : 'cost',
										display : '��ȡ����',
										sortable : true,
										process : function(v, row) {
											return moneyFormat2(v);
										}
									},
									{
										name : 'isDetect',
										display : '�Ƿ����´���ά��',
										width : 120,
										sortable : true,
										process : function(val, row) {
											if (val == "0") {
												return "δ�´�";
											} else {
												return "<a href='#' onclick='viewCheckDetail("
														+ row.id
														+ ")' >"
														+ "���´�"
														+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
												;
											}
										}
									}, {
										name : 'isQuote',
										display : '�Ƿ�ȷ�ϱ���',
										width : 80,
										sortable : true,
										process : function(val) {
											if (val == "0") {
												return "δȷ��";
											} else {
												return "��ȷ��";
											}
										}
									}, {
										name : 'isShip',
										display : '�Ƿ��ѷ���',
										width : 80,
										sortable : true,
										process : function(val) {
											if (val == "0") {
												return "δ����";
											} else {
												return "�ѷ���";
											}
										}
									} ]
						},
						searchitems : [{
							display : '���ݱ��',
							name : 'docCode'
						}, {
							display : 'ʡ��',
							name : 'prov'
						}, {
							display : '�ͻ�����',
							name : 'customerName'
						}, {
						    display : '������',
						    name : 'applyUserName'
						}, {
							display : '���ϱ��',
							name : 'productCode'
						}, {
							display : '��������',
							name : 'productName'
						}, {
							display : '��ݵ���',
							name : 'deliveryDocCode'
						}, {
							display : '���к�',
							name : 'serilnoName'
						}],
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
						} ]

					});

});