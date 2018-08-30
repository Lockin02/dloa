var show_page = function(page) {
	$("#produceapplyGrid").yxsubgrid("reload");
};
/**
 * 产品配置查看
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
 * LICENSE 查看方法
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
						title : '生产申请单',
						// 列信息
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						},  {
							name : 'relDocCode',
							display : '源单编号',
							width : 150,
							sortable : true
						}, {
							name : 'docCode',
							display : '单据编号',
							width : 80,
							sortable : true

						}, {
							name : 'docType',
							display : '单据类型',
							sortable : true,
							hide : true
						}, {
							name : 'relDocType',
							display : '源单类型',
							sortable : true,
							width : 80,
							process : function(v, row) {
								if (v == "CONTRACT") {
									return "合同";
								} else if (v == "PRESENT") {
									return "赠送";
								} else if (v == "BORROW") {
									return "借试用";
								} else {
									return v;
								}
							}
						},{
							name : 'customerName',
							display : '客户名称',
							sortable : true,
							width : 200
						}, {
							name : 'saleUserName',
							display : '负责人名称',
							width : 80,
							sortable : true
						}, {
							name : 'applyUserName',
							display : '申请人名称',
							width : 80,
							sortable : true
						}, {
							name : 'applyDate',
							display : '申请日期',
							sortable : true
						}, {
							name : 'docStatus',
							display : '下达状态',
							sortable : true,
							width : 80,
							process : function(v, row) {
								if (v == "0") {
									return "未下达";
								} else if (v == "1") {
									return "部分下达";
								} else if (v == "2") {
									return "全部下达";
								} else if (v == "3") {
									return "已关闭";
								}
							}
						}, {
							width : 80,
							name : 'ExaStatus',
							display : '审批状态',
							sortable : true
						}, {
							name : 'ExaDT',
							display : '审批时间',
							sortable : true
						}, {
							name : 'remark',
							display : '备注',
							sortable : true,
							hide : true
						}, {
							name : 'createName',
							display : '创建人',
							sortable : true,
							width : 80,
							hide : true
						}, {
							name : 'updateName',
							display : '修改人',
							width : 80,
							sortable : true,
							hide : true
						} ],
						// 主从表格设置
						subGridOptions : {
							url : '?model=produce_apply_produceapplyitem&action=subItemJson',
							param : [ {
								paramId : 'mainId',
								colId : 'id'
							} ],
							colModel : [
									{
										name : 'productCode',
										display : '物料编码',
										sortable : true
									},
									{
										name : 'productName',
										display : '物料名称',
										width : 200,
										sortable : true
									},
									{
										name : 'pattern',
										display : '规格型号',
										sortable : true
									},
									{
										name : 'unitName',
										display : '单位',
										sortable : true

									},
									{
										name : 'planEndDate',
										display : '计划交货时间',
										sortable : true
									},
									{
										name : 'produceNum',
										display : '申请数量',
										sortable : true
									},
									{
										name : 'exeNum',
										display : '已下达数量',
										sortable : true
									},
									{
										name : 'stockNum',
										display : '已入库数量',
										sortable : true
									},
									{
										name : 'jmpz',
										display : '加密配置',
										process : function(v, row) {
											return "<a title='"
													+ row.remark
													+ "' href='#' onclick='showLicense("
													+ row.licenseConfigId
													+ ")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
										}
									},
									{
										name : 'cppz',
										display : '产品配置',
										sortable : true,
										process : function(v, row) {
											return "<a title='"
													+ row.remark
													+ "' href='#' onclick='showGoodsConfig("
													+ row.goodsConfigId
													+ ")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
										}

									} ]
						},
						toAddConfig : {
							action : 'toAdd',
							formWidth : "850"
						},
						toEditConfig : {
							showMenuFn : function(row) {
								if (row.ExaStatus == '完成') {
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
									text : '查看',
									icon : 'view',
									action : function(row) {
										showModalWin("?model=produce_apply_produceapply&action=toViewTab&id="
												+ row.id
												+ "&skey="
												+ row['skey_']);
									}
// },
// {
// text : '变更',
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
								// text : '查看变更历史',
								// icon : 'business',
								// action : function(row) {
								// showThickboxWin('?model=common_changeLog&action=toProduceApplyList&logObj=produceapply&objId='
								// + row.id
								// +
								// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
								// }
								} ],
						comboEx : [ {
							text : '下达状态',
							key : 'docStatus',
							data : [ {
								text : '未下达',
								value : '0'
							}, {
								text : '部分下达',
								value : '1'
							}, {
								text : '全部下达',
								value : '2'
							}, {
								text : '已关闭',
								value : '3'
							} ]
						}, {
							text : '源单类型',
							key : 'relDocType',
							data : [ {
								text : '合同',
								value : 'CONTRACT'
							}, {
								text : '赠送',
								value : 'PRESENT'
							}, {
								text : '借试用',
								value : 'BORROW'
							} ]
						} ],
						searchitems : [ {
							display : "单据编号",
							name : 'docCode'
						}, {
							display : "源单编号",
							name : 'relDocCode'
						}, {
							display : '客户名称',
							name : 'customerName'
						} ]
					});
});