var show_page = function(page) {
	$("#changeapplyGrid").yxgrid("reload");
};
$(function() {
			$("#changeapplyGrid").yxgrid({
						model : 'service_change_changeapply',
						param : {'relDocId' : $('#relDocId').val(),'relDocType' : 'WXSQD'},
						title : '���ϸ������뵥',
						isDelAction : false,
						isAddAction : false,
						isEditAction : false,
						isViewAction : false,
						showcheckbox : false,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'docCode',
									display : '���ݱ��',
									sortable : true
								}, {
									name : 'rObjCode',
									display : 'Դ��ҵ����',
									sortable : true,
									hide : true
								}, {
									name : 'relDocCode',
									display : 'Դ�����',
									sortable : true
								}, {
									name : 'relDocName',
									display : 'Դ������',
									sortable : true,
									hide : true
								}, {
									name : 'relDocType',
									display : 'Դ������',
									sortable : true,
									process : function(v) {
										if (v == '') {
											return "";
										} else if (v == 'WXSQD') {
											return "ά�����뵥";
										}
									}
								}, {
									name : 'customerName',
									display : '�ͻ�����',
									sortable : true,
									width:200
								}, {
									name : 'adress',
									display : '�ͻ���ַ',
									sortable : true,
									hide : true
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
									name : 'ExaStatus',
									display : '����״̬',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '����ʱ��',
									sortable : true,
									hide : true
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true,
									hide : true
								}, {
									name : 'createId',
									display : '������id',
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
									name : 'updateId',
									display : '�޸���id',
									sortable : true,
									hide : true
								}, {
									name : 'updateTime',
									display : '�޸�����',
									sortable : true,
									hide : true
								}],

								menusEx : [{
									text : '�鿴',
									icon : 'view',
									action : function(row, rows, grid) {
										if (row.ExaStatus == "���" || row.ExaStatus == "���") {
											if (row) {
												showOpenWin("?model=service_change_changeapply&action=viewTab&id="
														+ row.id
														+ "&skey="
														+ row['skey_']
														+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
											}
										} else {
											if (row) {
												showOpenWin("?model=service_change_changeapply&action=toView&id="
														+ row.id
														+ "&skey="
														+ row['skey_']
														+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
											}
										}
									}
								}],


						subGridOptions : {
							url : '?model=service_change_changeitem&action=pageJson',
							param : [{
								paramId : 'mainId',
								colId : 'id'
							}],
							colModel : [{
								name : 'productCode',
								display : '���ϱ��'
							}, {
								name : 'productName',
								display : '��������',
								width:200
							}, {
								name : 'pattern',
								display : '����ͺ�'
							}, {
								name : 'unitName',
								display : '��λ'
							}, {
								name : 'serilnoName',
								display : '���к�'
							}, {
								name : 'remark',
								display : '���ԭ��',
								width:200
							}]
						},
						comboEx : [{
							text : '����״̬',
							key : 'ExaStatus',
							data : [{
										text : '���ύ',
										value : '���ύ'
									},{
										text : '���',
										value : '���'
									}, {
										text : '��������',
										value : '��������'
									}, {
										text : '���',
										value : '���'
									}]
						}],
						searchitems : [{
							display : '���ݱ��',
							name : 'docCode'
						}, {
							display : 'Դ�����',
							name : 'relDocCode'
						}, {
							display : 'Դ������',
							name : 'relDocType'
						}]
					});
		});