var show_page = function(page) {
	$("#applyitemGrid").yxgrid("reload");
};

/**
 * 查看维修申请单具体信息
 *
 * @param {}
 *            id
 */
function viewApplyDetail(mainId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairapply&action=md5RowAjax",
				data : {
					"id" : mainId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairapply&action=toView&id="
			+ mainId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}

/**
 * 查看报价申报单详细信息
 *
 * @param {}
 *            id
 */
function viewQuoteDetail(quoteId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairquote&action=md5RowAjax",
				data : {
					"id" : quoteId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairquote&action=toView&id="
			+ quoteId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}

$(function() {
	$("#applyitemGrid").yxgrid({
		model : 'service_repair_applyitem',
		title : '已审核维修清单',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			"quoteIdAudit" : null
		},
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'productType',
					display : '物料分类',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '物料编号',
					sortable : true
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					width : 250
				}, {
					name : 'applyCode',
					display : '维修申请单编号',
					process : function(v, row) {
						return "<a href='#' onclick='viewApplyDetail("
								+ row.mainId
								+ ")' >"
								+ v
								+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					name : 'quoteCode',
					display : '申报单编号',
					process : function(v, row) {
						return "<a href='#' onclick='viewQuoteDetail("
								+ row.quoteId
								+ ")' >"
								+ v
								+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					name : 'pattern',
					display : '规格型号',
					sortable : true
				}, {
					name : 'unitName',
					display : '单位',
					sortable : true
				}, {
					name : 'serilnoName',
					display : '序列号',
					sortable : true
				}, {
					name : 'fittings',
					display : '配件信息',
					sortable : true,
					hide : true
				}, {
					name : 'place',
					display : '现在地点',
					sortable : true,
					hide : true
				}, {
					name : 'troubleInfo',
					display : '故障现象',
					sortable : true,
					hide : true
				}, {
					name : 'checkInfo',
					display : '检测处理方法',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					hide : true
				}, {
					name : 'isGurantee',
					display : '是否过保',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "是";
						} else {
							return "否";
						}
					}
				}, {
					name : 'repairType',
					display : '费用类型',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "收费维修";
						}
						if (val = "1") {
							return "保内维修";
						} else {
							return "内部维修";
						}
					}

				}, {
					name : 'repairCost',
					display : '维修费用',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'cost',
					display : '收取费用',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'isDetect',
					display : '是否已下达检测维修',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "未下达";
						} else {
							return "已下达";
						}
					},
					hide : true
				}, {
					name : 'delivery',
					display : '是否已发货',
					sortable : true,
					process : function(val) {
						if (val == "0") {
							return "未发货";
						} else {
							return "已发货";
						}
					},
					hide : true

				}],
		searchitems : [{
					display : "物料编号",
					name : 'productCode'
				}, {

					display : "物料名称",
					name : 'productName'
				}],
		toViewConfig : {
			action : 'toView'
		}
	});
});