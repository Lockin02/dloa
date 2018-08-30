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
	$("#produceapplyGrid").yxsubgrid({
		model : 'produce_apply_produceapply',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		title : '生产需求',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'relDocCode',
			display : '源单编号',
			width : 150,
			sortable : true
		},{
			name : 'docCode',
			display : '单据编号',
			width : 80,
			sortable : true,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'docType',
			display : '单据类型',
			sortable : true,
			hide : true
		},{
			name : 'relDocType',
			display : '源单类型',
			sortable : true,
			width : 80
		},{
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 200
		},{
			name : 'saleUserName',
			display : '销售负责人',
			width : 80,
			sortable : true
		},{
			name : 'applyUserName',
			display : '下单人',
			width : 80,
			sortable : true
		},{
			name : 'applyDate',
			display : '下单日期',
			sortable : true
		},{
			name : 'docStatus',
			display : '下达状态',
			sortable : true,
			width : 80,
			process : function(v, row) {
				switch (v) {
					case '0' : return "未下达";
					case '1' : return "部分下达";
					case '2' : return "全部下达";
					case '3' : return "关闭";
					case '4' : return "打回";
				}
			}
		},{
			width : 80,
			name : 'hopeDeliveryDate',
			display : '期望交货日期',
			sortable : true
		},{
			name : 'actualDeliveryDate',
			display : '实际交货日期',
			sortable : true
		},{
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 300,
			align : 'left'
		},{
			name : 'createName',
			display : '创建人',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'updateName',
			display : '修改人',
			width : 80,
			sortable : true,
			hide : true
		}],

		// 主从表格设置
		subGridOptions : {
			url : '?model=produce_apply_produceapplyitem&action=subItemJson',
			param : [ {
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCode',
				display : '物料编码',
				sortable : true
			},{
				name : 'productName',
				display : '物料名称',
				width : 200,
				sortable : true
			},{
				name : 'pattern',
				display : '规格型号',
				sortable : true
			},{
				name : 'unitName',
				display : '单位',
				sortable : true
			},{
				name : 'planEndDate',
				display : '计划交货时间',
				sortable : true
			},{
				name : 'produceNum',
				display : '申请数量',
				sortable : true
			},{
				name : 'exeNum',
				display : '已下达数量',
				sortable : true
			},{
				name : 'stockNum',
				display : '已入库数量',
				sortable : true
			},{
				name : 'jmpz',
				display : '加密配置',
				process : function(v, row) {
					if (row.licenseConfigId > 0) {
						return "<a title='"
								+ row.remark
								+ "' href='#' onclick='showLicense("
								+ row.licenseConfigId
								+ ")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					} else {
						return '';
					}
				}
			},{
				name : 'cppz',
				display : '产品配置',
				sortable : true,
				process : function(v, row) {
					if (row.goodsConfigId > 0) {
						return "<a title='"
								+ row.remark
								+ "' href='#' onclick='showGoodsConfig("
								+ row.goodsConfigId
								+ ")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					} else {
						return '';
					}
				}
			}]
		},

		toViewConfig : {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_apply_produceapply&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},

		//扩展右键菜单
		menusEx : [{
			text : '查看打回原因',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.backReason != '') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toViewBack&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		},{
			text : '下达生产任务',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "0" || row.docStatus == "1") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=900');
			}
		},{
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == "0") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toBack&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=900');
			}
		}],

		comboEx : [ {
			text : '下达状态',
			key : 'docStatus',
			data : [{
				text : '未下达',
				value : '0'
			},{
				text : '部分下达',
				value : '1'
			},{
				text : '全部下达',
				value : '2'
			},{
				text : '已关闭',
				value : '3'
			},{
				text : '打回',
				value : '4'
			}]
		},{
			text : '源单类型',
			key : 'relDocTypeCode',
			datacode : 'HTLX'
		}],

		searchitems : [{
			display : "单据编号",
			name : 'docCode'
		},{
			display : "源单编号",
			name : 'relDocCode'
		},{
			display : '客户名称',
			name : 'customerName'
		},{
			display : '销售负责人',
			name : 'saleUserName'
		},{
			display : '下单人',
			name : 'applyUserName'
		},{
			display : '下单日期',
			name : 'applyDate'
		},{
			display : '期望交货日期',
			name : 'hopeDeliveryDate'
		},{
			display : '实际交货日期',
			name : 'actualDeliveryDate'
		},{
			display : '备  注',
			name : 'remark'
		}]
	});
});