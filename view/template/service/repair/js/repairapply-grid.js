var show_page = function(page) {
	$("#repairapplyGrid").yxsubgrid("reload");
};

/**
 * 查看维修检测信息
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
						title : '维修申请管理',
						// 列信息
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'docCode',
							display : '单据编号',
							sortable : true
						}, {
							name : 'docDate',
							display : '单据日期',
							width : '80',
							sortable : true
						}, {
							name : 'contractCode',
							display : '合同编号',
							sortable : true,
							width : 120
						}, {
							name : 'contractName',
							display : '合同名称',
							sortable : true,
							width : 120,
							hide : true
						}, {
							name : 'prov',
							display : '省份',
							sortable : true,
							width : 60
						}, {
							name : 'customerName',
							display : '客户名称',
							sortable : true,
							width : 200
						}, {
							name : 'contactUserName',
							display : '客户联系人',
							sortable : true,
							width : 80
						}, {
							name : 'telephone',
							display : '联系电话',
							sortable : true
						}, {
							name : 'adress',
							display : '客户地址',
							sortable : true,
							hide : true

						}, {
							name : 'subCost',
							display : '维修费用',
							sortable : true,
							width : '70',
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'docStatus',
							display : '单据状态',
							sortable : true,
							width : '70',
							process : function(v) {
								if (v == 'WZX') {
									return "未执行";
								} else if (v == 'ZXZ') {
									return "执行中";
								} else if (v == "YWC") {
									return "已完成";
								} else {
									return v;
								}
							}
						}, {
							name : 'applyUserName',
							display : '申请人名称',
							sortable : true
						}, {
							name : 'applyUserCode',
							display : '申请人账号',
							sortable : true,
							hide : true
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
							name : 'createTime',
							display : '创建日期',
							sortable : true,
							hide : true
						}, {
							name : 'updateName',
							display : '修改人',
							sortable : true,
							hide : true
						}, {
							name : 'updateTime',
							display : '修改日期',
							sortable : true,
							hide : true
						}, {
							name : 'deliveryDocCode',
							display : '快递单号',
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
							text : "导出",
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
									text : '查看',
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

						// 主从表格设置
						subGridOptions : {
							url : '?model=service_repair_applyitem&action=pageJson',
							param : [ {
								paramId : 'mainId',
								colId : 'id'
							} ],
							colModel : [
									{
										name : 'productCode',
										display : '物料编号',
										width : 80,
										sortable : true
									},
									{
										name : 'productName',
										display : '物料名称',
										sortable : true,
										width : 200
									},
									{
										name : 'serilnoName',
										display : '序列号',
										sortable : true,
										width : 200
									},
									{
										name : 'isGurantee',
										display : '是否过保',
										sortable : true,
										width : 50,
										process : function(val) {
											if (val == "0") {
												return "是";
											} else {
												return "否";
											}
										}
									},
									{
										name : 'repairType',
										display : '费用类型',
										sortable : true,
										width : 80,
										process : function(val) {
											if (val == "0") {
												return "收费维修";
											} else if (val == "1") {
												return "保内维修";
											} else if (val == "2") {
												return "内部维修";
											} else {
												return val;
											}
										}
									},
									{
										name : 'repairCost',
										display : '维修费用',
										sortable : true,
										process : function(v, row) {
											return moneyFormat2(v);
										}
									},
									{
										name : 'cost',
										display : '收取费用',
										sortable : true,
										process : function(v, row) {
											return moneyFormat2(v);
										}
									},
									{
										name : 'isDetect',
										display : '是否已下达检测维修',
										width : 120,
										sortable : true,
										process : function(val, row) {
											if (val == "0") {
												return "未下达";
											} else {
												return "<a href='#' onclick='viewCheckDetail("
														+ row.id
														+ ")' >"
														+ "已下达"
														+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
												;
											}
										}
									}, {
										name : 'isQuote',
										display : '是否确认报价',
										width : 80,
										sortable : true,
										process : function(val) {
											if (val == "0") {
												return "未确认";
											} else {
												return "已确认";
											}
										}
									}, {
										name : 'isShip',
										display : '是否已发货',
										width : 80,
										sortable : true,
										process : function(val) {
											if (val == "0") {
												return "未发货";
											} else {
												return "已发货";
											}
										}
									} ]
						},
						searchitems : [{
							display : '单据编号',
							name : 'docCode'
						}, {
							display : '省份',
							name : 'prov'
						}, {
							display : '客户名称',
							name : 'customerName'
						}, {
						    display : '申请人',
						    name : 'applyUserName'
						}, {
							display : '物料编号',
							name : 'productCode'
						}, {
							display : '物料名称',
							name : 'productName'
						}, {
							display : '快递单号',
							name : 'deliveryDocCode'
						}, {
							display : '序列号',
							name : 'serilnoName'
						}],
						comboEx : [ {
							text : '单据状态',
							key : 'docStatus',
							data : [ {
								text : '未执行',
								value : 'WZX'
							}, {
								text : '执行中',
								value : 'ZXZ'
							}, {
								text : '已完成',
								value : 'YWC'
							} ]
						} ]

					});

});