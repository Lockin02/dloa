var show_page = function (page) {
	$("#produceapplyGrid").yxsubgrid("reload");
};

/**
 * 产品配置查看
 *
 * @param thisVal
 * @param goodsName
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId=" + thisVal; // + "&goodsName=" + goodsName;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE 查看方法
 *
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
	var param = {};
	var comboEx = [];
	if ($("#issued").val() != 'yes') { //未下达
		param = {
			docStatusIn: '0,1,9'
		};
		var comboExArr = {
			text: '下达状态',
			key: 'docStatus',
			data: [{
				text: '未下达',
				value: '0'
			},{
				text: '部分下达',
				value: '1'
			},{
				text: '部分打回',
				value: '9'
			}, {
				text: '打回',
				value: '4'
			}]
		};
		comboEx.push(comboExArr);
	} else {
		param = {
			docStatusIn: '2,3,8'
		};
		var comboExArr = {
			text: '下达状态',
			key: 'docStatus',
			data: [ {
				text: '全部下达',
				value: '2'
			}, {
				text: '关闭',
				value: '3'
			}, {
				text: '变更审批中',
				value: '8'
			}]
		};
		comboEx.push(comboExArr);
	}

	var comboExRelArr = {
		text: '合同类型',
		key: 'relDocTypeCode',
		data: [{
			text: '销售合同',
			value: 'HTLX-XSHT'
		}, {
			text: '服务合同',
			value: 'HTLX-FWHT'
		}, {
			text: '租赁合同',
			value: 'HTLX-ZLHT'
		}, {
			text: '研发合同',
			value: 'HTLX-YFHT'
		}, {
			text: '所有合同',
			value: 'allContract'
		}, {
			text: '研发生产',
			value: 'SCYDLX-01'
		}, {
			text: '生产备货',
			value: 'SCYDLX-02'
		}, {
			text: '其他类',
			value: 'SCYDLX-03'
		}]
	};
	comboEx.push(comboExRelArr);

	$("#produceapplyGrid").yxsubgrid({
		model: 'produce_apply_produceapply',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		title: '订单需求',
		param: param,
		// 列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'relDocCode',
			display: '合同编号(源单编号)',
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
				var urlStr = 'toViewTab'; //默认方法
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
				} else if (row.docStatus == 8) { //变更审批中
					urlStr += '&noSee=true';
					v = '<span style="color:red;">' + v + '</span>';
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=" + urlStr + "&id=" + row.id
					 + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '下达状态',
			sortable: true,
			width: 80,
			process: function (v, row) {
				switch (v) {
				case '0':
					return "未下达";
					break;
				case '1':
					return "部分下达";
					break;
				case '2':
					return "全部下达";
					break;
				case '3':
					return "关闭";
					break;
				case '4':
					return "打回";
					break;
				case '5':
					return "保存";
					break;
				case '6':
					return "审批中";
					break;
				case '7':
					return "审批打回";
					break;
				case '8':
					return "变更审批中";
					break;
				case '9':
					return "部分打回";
					break;
				}
			}
		}, {
			name: 'applyDate',
			display: '下单日期',
			sortable: true
		}, {
			name: 'relDocType',
			display: '合同类型(源单类型)',
			sortable: true,
			width: 120
		},  {
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			width: 200
		}, {
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width: 150
		}, {
			name: 'saleUserName',
			display: '销售负责人',
			width: 80,
			sortable: true
		}, {
			name: 'applyUserName',
			display: '下单人',
			width: 80,
			sortable: true
		}, {
			width: 80,
			name: 'hopeDeliveryDate',
			display: '期望交货日期',
			sortable: true
		}, {
			name: 'actualDeliveryDate',
			display: '实际交货日期',
			sortable: true
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 300,
			align: 'left'
		}, {
			name: 'createName',
			display: '创建人',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'updateName',
			display: '修改人',
			width: 80,
			sortable: true,
			hide: true
		}],

		// 主从表格设置
		subGridOptions: {
			url: '?model=produce_apply_produceapplyitem&action=subItemJson',
			subgridcheck: true,
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			// 显示的列,对已下达完成的从表信息进行置灰
			afterProcess : function(data, rowData, $tr) {
				if (data.produceNum == data.exeNum) {
					$tr.find("td").css("background-color", "#BCBCBC");
				}
			},
			colModel: [{
				name: 'productCode',
				display: '物料编码',
				sortable: true,
				width: 100,
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
				width: 100,
				sortable: true
			}, {
				name: 'proType',
				display: '物料类型',
				width: 80,
				sortable: true
			}, {
				name: 'pattern',
				display: '规格型号',
				width: 100,
				sortable: true
			}, {
				name: 'unitName',
				display: '单位',
				width: 50,
				sortable: true
			}, {
				name: 'planEndDate',
				display: '计划交货时间',
				width: 80,
				sortable: true
			}, {
				name: 'shipPlanDate',
				display: '计划发货日期',
				width: 80,
				sortable: true
			}, {
				name: 'produceNum',
				display: '申请数量',
				width: 50,
				sortable: true
			}, {
				name: 'exeNum',
				display: '已下达数量',
				width: 60,
				sortable: true
			}, {
				name: 'stockNum',
				display: '已入库数量',
				width: 60,
				sortable: true
			}, {
				name: 'jmpz',
				display: '加密配置',
				width: 60,
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
				width: 60,
				process: function (v, row) {
					if (row.goodsConfigId > 0) {
						return "<a title='" + row.remark + "' href='#' onclick='showGoodsConfig(" + row.goodsConfigId +
							")' > <img title='详细' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					} else {
						return '';
					}
				}
			}]
		},

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					if (get['docStatus'] == 8) { //变更审批中
						showModalWin("?model=produce_apply_produceapply&action=toViewTab&noSee=true&id=" + get[p.keyField], '1');
					} else {
						showModalWin("?model=produce_apply_produceapply&action=toViewTab&id=" + get[p.keyField], '1');
					}
				}
			}
		},

		buttonsEx: [{
			name: 'add',
			text: "交货计划报表",
			icon: 'search',
			action: function (row) {
				showModalWin('?model=produce_apply_produceapply&action=toSendplanReport', 1);
			}
		}, {
			name: 'add',
			text: "生产计划报表",
			icon: 'search',
			action: function (row) {
				showModalWin('?model=produce_plan_produceplan&action=toProduceplanReport', 1);
			}
		}],

		//扩展右键菜单
		menusEx: [{
			text: '查看打回原因',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.backReason != '') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toViewBack&id=' + row.id +
					'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		}, {
			text: '查看关闭原因',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.docStatus == "3") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toViewClose&id=' + row.id +
					'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=900');
			}
		}, {
			text: '下达生产任务',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == "0" || row.docStatus == "1" || row.docStatus == "9") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, rowIds, g) {
				//没有勾选明细,则默认下达全部物料
				var itemIds = g.getSubSelectRowCheckIds(rows).toString();
				if(itemIds === ''){
					$.ajax({
						type: "POST",
						url: "?model=produce_apply_produceapply&action=taskCheck",
						data: {id: row.id},
						async: false,
						success: function(data) {
							data = eval("(" + data + ")");
							if (data.pass == "1") {
//								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=all'
//									+ '&proType=' + data.proType + '&proTypeId=' + data.proTypeId);
								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=all');
							} else {
								alert(data.msg);
								return false;
							}
						}
					});
				}else{
					$.ajax({
						type: "POST",
						url: "?model=produce_apply_produceapply&action=taskCheck",
						data: {id : row.id,itemIds : itemIds},
						async: false,
						success: function(data) {
							data = eval("(" + data + ")");
							if (data.pass == "1") {
//								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=' + itemIds
//									+ '&proType=' + data.proType + '&proTypeId=' + data.proTypeId);
								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=' + itemIds);
							} else {
								alert(data.msg);
								return false;
							}
						}
					});
				}
			}
		}, {
			text: '下达walktour任务',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == "0" || row.docStatus == "1" || row.docStatus == "9") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, rowIds, g) {
				//没有勾选明细,则默认下达全部物料
				var itemIds = g.getSubSelectRowCheckIds(rows).toString();
				if(itemIds === ''){
					$.ajax({
						type: "POST",
						url: "?model=produce_apply_produceapply&action=taskCheck",
						data: {id: row.id},
						async: false,
						success: function(data) {
							data = eval("(" + data + ")");
							if (data.pass == "1") {
//								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=all'
//									+ '&proType=' + data.proType + '&proTypeId=' + data.proTypeId + '&taskTypeCode=RWLX-WALKTOUR&taskTypeName=walktour任务');
								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=all'
										+ '&taskTypeCode=RWLX-WALKTOUR&taskTypeName=walktour任务');
							} else {
								alert(data.msg);
								return false;
							}
						}
					});
				}else{
					$.ajax({
						type: "POST",
						url: "?model=produce_apply_produceapply&action=taskCheck",
						data: {id : row.id,itemIds : itemIds},
						async: false,
						success: function(data) {
							data = eval("(" + data + ")");
							if (data.pass == "1") {
//								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=' + itemIds
//									+ '&proType=' + data.proType + '&proTypeId=' + data.proTypeId + '&taskTypeCode=RWLX-WALKTOUR&taskTypeName=walktour任务');
								showModalWin('?model=produce_task_producetask&action=toAddByNeed&applyId=' + row.id + '&applyItemIds=' + itemIds
										+ '&taskTypeCode=RWLX-WALKTOUR&taskTypeName=walktour任务');
							} else {
								alert(data.msg);
								return false;
							}
						}
					});
				}
			}
		}, {
			text: '打回',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.docStatus == "0" || row.docStatus == "1" || row.docStatus == "9") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin('?model=produce_apply_produceapply&action=toBack&id=' + row.id +
					'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
			}
		}, {
			text: '关闭',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.docStatus == "1" || row.docStatus == "2") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin('?model=produce_apply_produceapply&action=toClose&id=' + row.id, 1);
			}
		}, {
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function (row) {
				if ((row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') && row.projectName != '') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_produceapply&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx: comboEx,

		searchitems: [{
			display: "单据编号",
			name: 'docCode'
		}, {
			display: "合同编号",
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
		}, {
			display: '下单日期',
			name: 'applyDate'
		}, {
			display: '期望交货日期',
			name: 'hopeDeliveryDate'
		}, {
			display: '实际交货日期',
			name: 'actualDeliveryDate'
		}, {
			display: '物料编码',
			name: 'productCode'
		}, {
			display: '物料名称',
			name: 'productName'
		}, {
			display: '规格型号',
			name: 'pattern'
		}, {
			display: '规格类型',
			name: 'proType'
		}, {
			display: '备  注',
			name: 'remark'
		}]
	});
});