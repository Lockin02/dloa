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
	$("#produceapplyGrid")
			.yxsubgrid(
					{
						model : 'produce_apply_produceapply',
						param : {
							"relDocId" : $("#relDocId").val(),
							"relDocType" : $("#relDocType").val()
						},
						isAddAction : false,
						isDelAction : false,
						isViewAction : false,
						showcheckbox : false,
						title : '�������뵥',
						// ����Ϣ
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
						}, {
							name : 'docCode',
							display : '���ݱ��',
							width : 80,
							sortable : true

						}, {
							name : 'docType',
							display : '��������',
							sortable : true,
							hide : true
						}, {
							name : 'relDocType',
							display : 'Դ������',
							sortable : true,
							width : 80,
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
						},{
							name : 'customerName',
							display : '�ͻ�����',
							sortable : true,
							width : 200
						}, {
							name : 'saleUserName',
							display : '����������',
							width : 80,
							sortable : true
						}, {
							name : 'applyUserName',
							display : '����������',
							width : 80,
							sortable : true
						}, {
							name : 'applyDate',
							display : '��������',
							sortable : true
						}, {
							name : 'docStatus',
							display : '�´�״̬',
							sortable : true,
							width : 80,
							process : function(v, row) {
								if (v == "0") {
									return "δ�´�";
								} else if (v == "1") {
									return "�����´�";
								} else if (v == "2") {
									return "ȫ���´�";
								} else if (v == "3") {
									return "�ѹر�";
								}
							}
						}, {
							width : 80,
							name : 'ExaStatus',
							display : '����״̬',
							sortable : true
						}, {
							name : 'ExaDT',
							display : '����ʱ��',
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
							width : 80,
							hide : true
						}, {
							name : 'updateName',
							display : '�޸���',
							width : 80,
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
										name : 'planEndDate',
										display : '�ƻ�����ʱ��',
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
							showMenuFn : function(row) {
								if (row.ExaStatus == '���') {
									return false;
								} else {
									return true;
								}
							},
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
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
// },
// {
// text : '���',
// icon : 'business',
// showMenuFn : function(row) {
// if (row.docStatus == "0"
// || row.docStatus == "1") {
// return true;
// } else {
// return false;
// }
// },
// action : function(row) {
// showThickboxWin('?model=produce_apply_produceapply&action=toChange&id='
// + row.id
// +
// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
// }
								// },
								// {
								// text : '�鿴�����ʷ',
								// icon : 'business',
								// action : function(row) {
								// showThickboxWin('?model=common_changeLog&action=toProduceApplyList&logObj=produceapply&objId='
								// + row.id
								// +
								// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
								// }
								} ],
						comboEx : [ {
							text : '�´�״̬',
							key : 'docStatus',
							data : [ {
								text : 'δ�´�',
								value : '0'
							}, {
								text : '�����´�',
								value : '1'
							}, {
								text : 'ȫ���´�',
								value : '2'
							}, {
								text : '�ѹر�',
								value : '3'
							} ]
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