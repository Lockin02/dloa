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
	var paramObj = {};
	var docStatusExArr = [];
	if ($("#applyType").val() == "finish") {
		paramObj = {
			"docStatusIn" : '2,3'
		};
		docStatusExArr = [ {
			text : '全部下达',
			value : '2'
		}, {
			text : '已关闭',
			value : '3'
		} ];

	} else {
		paramObj = {
			"docStatusIn" : '0,1'
		};
		docStatusExArr = [ {
			text : '未下达',
			value : '0'
		}, {
			text : '部分下达',
			value : '1'
		} ];
	}

	$("#produceapplyGrid")
			.yxsubgrid(
					{
						model : 'produce_apply_produceapply',
						title : '生产申请单',
						isAddAction : false,
						isDelAction : false,
						isEditAction : false,
						isViewAction : false,
						showcheckbox : false,
						param : paramObj,
						// 列信息
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
								},
								{
									text : '下达任务',
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
								// text : '查看变更历史',
								// icon : 'business',
								// action : function(row) {
								// showThickboxWin('?model=common_changeLog&action=toProduceApplyList&logObj=contract&objId='
								// + row.id
								// +
								// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
								// }
								// },
								{
									text : '关闭',
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
										if (confirm("你确认要关闭此申请单?")) {
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
																alert("关闭成功!");
																show_page();
															} else {
																alert("关闭失败!");
															}
														}
													})
										}

									}
								},
								{
									text : '开启',
									icon : 'add',
									showMenuFn : function(row) {
										if (row.docStatus == "3") {
											return true;
										} else {
											return false;
										}
									},
									action : function(row) {
										if (confirm("你确认要开启此申请单?")) {
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
																alert("开启成功!");
																show_page();
															} else {
																alert("开启失败!");
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
							display : '源单编号',
							width : 150,
							sortable : true
						},{
							name : 'docCode',
							display : '单据编号',
							sortable : true
						}, {
							name : 'docType',
							display : '单据类型',
							sortable : true,
							width : 70,
							hide : true
						}, {
							name : 'relDocType',
							display : '源单类型',
							sortable : true,
							width : 70,
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
						}, {
							name : 'customerName',
							display : '客户名称',
							sortable : true,
							width : 200
						}, {
							name : 'saleUserName',
							display : '负责人名称',
							sortable : true
						}, {
							name : 'applyUserName',
							display : '申请人名称',
							sortable : true
						}, {
							name : 'applyDate',
							display : '申请日期',
							sortable : true
						}, {
							name : 'docStatus',
							display : '下达状态',
							sortable : true,
							width : 70,
							process : function(v, row) {
								if (v == "0") {
									return "未下达";
								} else if (v == "1") {
									return "部分下达";
								} else if (v == "2") {
									return "全部下达";
								} else if (v == "3") {
									return "已关闭";
								} else {
									return v;
								}
							}
						}, {
							name : 'ExaStatus',
							display : '审批状态',
							width : 70,
							sortable : true
						}, {
							name : 'ExaDT',
							display : '审批时间',
							width : 70,
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
							hide : true
						}, {
							name : 'updateName',
							display : '修改人',
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
										name : 'planEndDate',
										display : '计划交货时间',
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
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						comboEx : [ {
							text : '下达状态',
							key : 'docStatus',
							data : docStatusExArr
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