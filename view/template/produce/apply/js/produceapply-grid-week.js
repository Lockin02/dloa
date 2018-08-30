var show_page = function (page) {
	$("#produceapplyGrid").yxgrid("reload");
};

/**
 * 产品配置查看
 * @param thisVal
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId=" + thisVal;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE 查看方法
 * @param thisVal
 */
function showLicense(thisVal) {
	url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id=" + thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

$(function () {
	$("#produceapplyGrid").yxgrid({
		model: 'produce_apply_produceapplyitem',
		action: 'mainPageJson',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		title: '生产计划',
		param: {
			startWeekDate: $("#startWeekDate").val(),
			endWeekDate: $("#endWeekDate").val(),
			pDocStatusIn: '1,2,8'
		},
		// 列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'relDocCode',
			display: '合同(源单)编号',
			width: 150,
			sortable: true,
            process: function (v, row) {
            	if(row.relDocTypeCode == 'HTLX-XSHT' || row.relDocTypeCode == 'HTLX-FWHT' || 
            		row.relDocTypeCode == 'HTLX-ZLHT' || row.relDocTypeCode == 'HTLX-YFHT'){
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.relDocId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            	}
            	return v;
            }
		}, {
			name: 'docCode',
			display: '单据编号',
			width: 110,
			sortable: true,
			process: function (v, row) {
				if (row.docStatus == 0) {
					v = '<img title="新增的生产需求" src="images/new.gif">' + v;
				} else if (row.docStatus == 1) {
					if (row.actualDeliveryDate == '') {
						var nowData = new Date();
						var dateArr = (row.hopeDeliveryDate).split('-');
						var hopeDeliveryDate = new Date(dateArr[0], parseInt(dateArr[1]), parseInt(dateArr[2]));
						if (nowData.getTime() > hopeDeliveryDate.getTime()) {
							v = '<img title="超时的生产需求" src="images/icon/hred.gif">' + v;
						}
					}
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toViewTab&id=" + row.mainId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			width: 200
		}, {
			name: 'tDocStatus',
			display: '单据状态',
			sortable: true,
			process: function (v) {
				if (v == '0') {
					return '未接收';
				} else if (v == 1) {
					return '未制定计划';
				} else if (v == 2) {
					return '已制定计划';
				}
			}
		}, {
			name: 'productCode',
			display: '物料编码',
			sortable: true,
			width: 150,
			process: function (v, row) {
				if (row.state == 1) {
					return v + '<span style="color:red">（已关闭）</span>';
				} else {
					return v;
				}
			}
		}, {
			name: 'productName',
			display: '物料名称',
			width: 200,
			sortable: true
		}, {
			name: 'pattern',
			display: '规格型号',
			sortable: true
		}, {
			name: 'unitName',
			display: '单位',
			sortable: true
		}, {
			name: 'planEndDate',
			display: '计划交货时间',
			sortable: true
		}, {
			name: 'produceNum',
			display: '申请数量',
			sortable: true
		}, {
			name: 'exeNum',
			display: '已下达数量',
			sortable: true
		}, {
			name: 'stockNum',
			display: '已入库数量',
			sortable: true
		}, {
			name: 'applyDate',
			display: '下单日期',
			sortable: true
		}, {
			name: 'relDocType',
			display: '源单类型',
			sortable: true,
			width: 80
		}, {
			name: 'saleUserName',
			display: '销售负责人',
			width: 80,
			sortable: true
		}, {
			name: 'jmpz',
			display: '加密配置',
			process: function (v, row) {
				if (row.licenseConfigId > 0) {
					return "<a title='" + row.remark + "' href='#' onclick='showLicense(" + row.licenseConfigId +
						")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
				} else {
					return '';
				}
			}
		}, {
			name: 'cppz',
			display: '产品配置',
			sortable: true,
			process: function (v, row) {
				if (row.goodsConfigId > 0) {
					return "<a title='" + row.remark + "' href='#' onclick='showGoodsConfig(" + row.goodsConfigId +
						")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
				} else {
					return '';
				}
			}
		}],

		//扩展右键菜单
		menusEx: [{
			text: '制定生产计划',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == '1' && row.taskId > 0) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin('?model=produce_plan_produceplan&action=toAddByTask&taskId=' + row.taskId, '1');
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_apply_produceapply&action=toViewTab&id=" + get['mainId'], '1');
				}
			}
		},

		comboEx: [{
			text: '下达状态',
			key: 'tDocStatusIn',
			data: [{
				text: '未接收',
				value: '0'
			}, {
				text: '未制定计划',
				value: '1'
			}, {
				text: '已制定计划',
				value: '2'
			}]
		}],

		searchitems: [{
			display: "单据编号",
			name: 'docCode'
		}, {
			display: '物料编码',
			name: 'productCode'
		}, {
			display: '物料名称',
			name: 'productName'
		}, {
			display: "源单编号",
			name: 'relDocCode'
		}, {
			display: '客户名称',
			name: 'customerName'
		}, {
			display: '销售负责人',
			name: 'saleUserName'
		}, {
			display: '下单人',
			name: 'applyUserName'
		}]
	});
});