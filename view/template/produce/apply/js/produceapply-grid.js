var show_page = function(page) {
	$("#produceapplyGrid").yxsubgrid("reload");
};
/**
 * ��Ʒ���ò鿴
 *
 * @param thisVal
 * @param goodsName
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId="
			+ thisVal;// + "&goodsName=" + goodsName;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE �鿴����
 *
 * @param thisVal
 */
function showLicense(thisVal) {
	url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id="
			+ thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}
$(function() {
	var paramObj = {};
	var docStatusExArr = [];
	if ($("#applyType").val() == "finish") {
		paramObj = {
			"docStatusIn" : '2,3'
		};
		docStatusExArr = [ {
			text : 'ȫ���´�',
			value : '2'
		}, {
			text : '�ѹر�',
			value : '3'
		} ];

	} else {
		paramObj = {
			"docStatusIn" : '0,1'
		};
		docStatusExArr = [ {
			text : 'δ�´�',
			value : '0'
		}, {
			text : '�����´�',
			value : '1'
		} ];
	}

	$("#produceapplyGrid")
			.yxsubgrid(
					{
						model : 'produce_apply_produceapply',
						title : '�������뵥',
						isAddAction : false,
						isDelAction : false,
						isEditAction : false,
						isViewAction : false,
						showcheckbox : false,
						param : paramObj,
						// ����Ϣ
						menusEx : [
								{
									text : '�鿴',
									icon : 'view',
									action : function(row) {
										showModalWin("?model=produce_apply_produceapply&action=toViewTab&id="
												+ row.id
												+ "&skey="
												+ row['skey_']);
									}
								},
								{
									text : '�´�����',
									icon : 'add',
									showMenuFn : function(row) {
										if (row.docStatus == "0"
												|| row.docStatus == "1") {
											return true;
										} else {
											return false;
										}
									},
									action : function(row) {
										showModalWin("?model=produce_task_producetask&action=toIssued&applyId="
												+ row.id
												+ "&docType="
												+ row.docType
												+ "&skey="
												+ row['skey_']);
										// showThickboxWin('?model=produce_task_producetask&action=toIssued&applyId='
										// + row.id
										// +
										// '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
									}
								},
								// {
								// text : '�鿴�����ʷ',
								// icon : 'business',
								// action : function(row) {
								// showThickboxWin('?model=common_changeLog&action=toProduceApplyList&logObj=contract&objId='
								// + row.id
								// +
								// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
								// }
								// },
								{
									text : '�ر�',
									icon : 'business',
									showMenuFn : function(row) {
										if (row.docStatus == "2"
												|| row.docStatus == "3") {
											return false;
										} else {
											return true;
										}
									},
									action : function(row) {
										if (confirm("��ȷ��Ҫ�رմ����뵥?")) {
											$
													.ajax({
														type : "POST",
														async : false,
														url : "?model=produce_apply_produceapply&action=closedApply",
														data : {
															id : row.id
														},
														success : function(
																result) {
															if (result == 0) {
																alert("�رճɹ�!");
																show_page();
															} else {
																alert("�ر�ʧ��!");
															}
														}
													})
										}

									}
								},
								{
									text : '����',
									icon : 'add',
									showMenuFn : function(row) {
										if (row.docStatus == "3") {
											return true;
										} else {
											return false;
										}
									},
									action : function(row) {
										if (confirm("��ȷ��Ҫ���������뵥?")) {
											$
													.ajax({
														type : "POST",
														async : false,
														url : "?model=produce_apply_produceapply&action=openApply",
														data : {
															id : row.id
														},
														success : function(
																result) {
															if (result == 0) {
																alert("�����ɹ�!");
																show_page();
															} else {
																alert("����ʧ��!");
															}
														}
													})
										}

									}
								} ],
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						},  {
							name : 'relDocCode',
							display : 'Դ�����',
							width : 150,
							sortable : true
						},{
							name : 'docCode',
							display : '���ݱ��',
							sortable : true
						}, {
							name : 'docType',
							display : '��������',
							sortable : true,
							width : 70,
							hide : true
						}, {
							name : 'relDocType',
							display : 'Դ������',
							sortable : true,
							width : 70,
							process : function(v, row) {
								if (v == "CONTRACT") {
									return "��ͬ";
								} else if (v == "PRESENT") {
									return "����";
								} else if (v == "BORROW") {
									return "������";
								} else {
									return v;
								}
							}
						}, {
							name : 'customerName',
							display : '�ͻ�����',
							sortable : true,
							width : 200
						}, {
							name : 'saleUserName',
							display : '����������',
							sortable : true
						}, {
							name : 'applyUserName',
							display : '����������',
							sortable : true
						}, {
							name : 'applyDate',
							display : '��������',
							sortable : true
						}, {
							name : 'docStatus',
							display : '�´�״̬',
							sortable : true,
							width : 70,
							process : function(v, row) {
								if (v == "0") {
									return "δ�´�";
								} else if (v == "1") {
									return "�����´�";
								} else if (v == "2") {
									return "ȫ���´�";
								} else if (v == "3") {
									return "�ѹر�";
								} else {
									return v;
								}
							}
						}, {
							name : 'ExaStatus',
							display : '����״̬',
							width : 70,
							sortable : true
						}, {
							name : 'ExaDT',
							display : '����ʱ��',
							width : 70,
							sortable : true
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
							name : 'updateName',
							display : '�޸���',
							sortable : true,
							hide : true
						} ],
						// ���ӱ������
						subGridOptions : {
							url : '?model=produce_apply_produceapplyitem&action=subItemJson',
							param : [ {
								paramId : 'mainId',
								colId : 'id'
							} ],
							colModel : [
									{
										name : 'productCode',
										display : '���ϱ���',
										sortable : true
									},
									{
										name : 'productName',
										display : '��������',
										width : 200,
										sortable : true
									},
									{
										name : 'pattern',
										display : '����ͺ�',
										sortable : true
									},
									{
										name : 'unitName',
										display : '��λ',
										sortable : true
									},
									{
										name : 'produceNum',
										display : '��������',
										sortable : true
									},
									{
										name : 'exeNum',
										display : '���´�����',
										sortable : true
									},
									{
										name : 'stockNum',
										display : '���������',
										sortable : true
									},
									{
										name : 'planEndDate',
										display : '�ƻ�����ʱ��',
										sortable : true
									},
									{
										name : 'jmpz',
										display : '��������',
										process : function(v, row) {
											return "<a title='"
													+ row.remark
													+ "' href='#' onclick='showLicense("
													+ row.licenseConfigId
													+ ")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
										}
									},
									{
										name : 'cppz',
										display : '��Ʒ����',
										sortable : true,
										process : function(v, row) {
											return "<a title='"
													+ row.remark
													+ "' href='#' onclick='showGoodsConfig("
													+ row.goodsConfigId
													+ ")' > <img title='��ϸ' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
										}

									} ]
						},
						toAddConfig : {
							action : 'toAdd',
							formWidth : "850"
						},
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						comboEx : [ {
							text : '�´�״̬',
							key : 'docStatus',
							data : docStatusExArr
						}, {
							text : 'Դ������',
							key : 'relDocType',
							data : [ {
								text : '��ͬ',
								value : 'CONTRACT'
							}, {
								text : '����',
								value : 'PRESENT'
							}, {
								text : '������',
								value : 'BORROW'
							} ]
						} ],
						searchitems : [ {
							display : "���ݱ��",
							name : 'docCode'
						}, {
							display : "Դ�����",
							name : 'relDocCode'
						}, {
							display : '�ͻ�����',
							name : 'customerName'
						} ]
					});
});