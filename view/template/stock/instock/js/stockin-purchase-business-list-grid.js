var show_page = function(page) {
	$("#purhasestorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#purhasestorageGrid").yxsubgrid({
		model : 'stock_instock_stockin',
		action : 'pageBusinessListJson',
		title : "外购入库单列表",
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : true,

		menusEx : [{
			name : 'view',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_instock_stockin&action=toView&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}, {
			// name : 'edit',
			// text : "修改",
			// icon : 'edit',
			// showMenuFn : function(row) {
			// if (row.docStatus == "WSH") {
			// return true;
			// }
			// return false;
			// },
			// action : function(row, rows, grid) {
			// showModalWin("?model=stock_instock_stockin&action=toEdit&id="
			// + row.id + "&docType=" + row.docType + "&skey="
			// + row['skey_']);
			// showThickboxWin("?model=stock_instock_stockin&action=toEdit&id="
			// + row.id
			// + "&docType="
			// + row.docType
			// +
			// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1120");
			// }
			// }, {
			// name : 'addred',
			// text : "下推红色单",
			// icon : 'business',
			// showMenuFn : function(row) {
			// if (row.docStatus == "YSH" && row.isRed == "0") {
			// return true;
			// } else {
			// return false;
			// }
			//
			// },
			// action : function(row, rows, grid) {
			// showModalWin("?model=stock_instock_stockin&action=toAddRed&id="
			// + row.id + "&docType=" + row.docType + "&skey="
			// + row['skey_']);
			// }
			// }, {
			name : 'pushpurch',
			text : "下推采购发票",
			icon : 'business',
			showMenuFn : function(row) {
				return true;
			},
			action : function(row, rows, grid) {
				if (row.invoiceStatus == '已录') {
					alert('发票已录，不能继续该操作');
					return false;
				}
				showModalWin("?model=finance_invpurchase_invpurchase&action=toAddForPushDown&id="
						+ row.id);
			}
				// }, {
				// name : 'unlock',
				// text : "反审核",
				// icon : 'unlock',
				// showMenuFn : function(row) {
				// if (row.docStatus == "YSH") {
				// var cancelAudit = false;
				// $.ajax({
				// type : "POST",
				// async : false,
				// url : "?model=stock_instock_stockin&action=cancelAuditLimit",
				// data : {
				// docType : row.docType
				// },
				// success : function(result) {
				// if (result == 1)
				// cancelAudit = true;
				// }
				// })
				// return cancelAudit;
				// } else {
				// return false;
				// }
				//
				// },
				// action : function(row, rows, grid) {
				// var canAudit = true;// 是否已结账
				// var closedAudit = true;// 是否已关账
				// $.ajax({
				// type : "POST",
				// async : false,
				// url : "?model=finance_period_period&action=isLaterPeriod",
				// data : {
				// thisDate : row.auditDate
				// },
				// success : function(result) {
				// if (result == 0)
				// canAudit = false;
				// }
				// })
				// $.ajax({
				// type : "POST",
				// async : false,
				// url : "?model=finance_period_period&action=isClosed",
				// data : {},
				// success : function(result) {
				// if (result == 1)
				// closedAudit = false;
				// }
				// })
				// if (closedAudit) {
				// if (canAudit) {
				// if (window.confirm("确认进行反审核吗?")) {
				// $.ajax({
				// type : "POST",
				// url : "?model=stock_instock_stockin&action=cancelAudit",
				// data : {
				// id : row.id,
				// docType : row.docType
				// },
				// success : function(result) {
				// show_page();
				// if (result == 1) {
				// alert('单据反审核成功！');
				// } else {
				// alert('单据反审核失败,请确认!');
				// }
				// }
				// });
				//
				// }
				// } else {
				// alert("单据所在财务周期已经结账，不能进行反审核，请联系财务人员进行反结账！")
				// }
				// } else {
				// alert("财务已关账，不能进行反审核，请联系财务人员进行反关账！")
				// }
				// }
				// }, {
				// text : '删除',
				// icon : 'delete',
				// showMenuFn : function(row) {
				// if (row.docStatus == "WSH") {
				// return true;
				// }
				// return false;
				// },
				// action : function(row, rows, grid) {
				// if (window.confirm("确认要删除?")) {
				// $.ajax({
				// type : "POST",
				// url : "?model=stock_instock_stockin&action=ajaxdeletes",
				// data : {
				// id : row.id
				// },
				// success : function(msg) {
				// if (msg == 1) {
				// show_page();
				// alert('删除成功！');
				// } else {
				// alert('删除失败，该对象可能已经被引用!');
				// }
				// }
				// });
				// }
				// }
		}, {
			name : 'view',
			text : "打印",
			icon : 'print',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_instock_stockin&action=toPrint&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}],
		param : {
			'docType' : 'RKPURCHASE'
		},

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'isRed',
					display : '红蓝色',
					sortable : true,
					align : 'center',
					width : '35',
					process : function(v, row) {
						if (row.isRed == '0') {
							return "<img src='images/icon/hblue.gif' />";
						} else {
							return "<img src='images/icon/hred.gif' />";
						}
					}
				}, {
					name : 'docCode',
					display : '单据编号',
					sortable : true
				}, {
					name : 'auditDate',
					display : '单据日期',
					sortable : true
				}, {
					name : 'docType',
					display : '入库类型',
					sortable : true,
					hide : true
				}, {
					name : 'invoiceStatus',
					display : '发票状态',
					sortable : true,
					width : 70
				}, {
					name : 'relDocId',
					display : '源单id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : '源单类型',
					sortable : true,
					datacode : 'RKDYDLX'
				}, {
					name : 'relDocCode',
					display : '源单编号',
					sortable : true
				}, {
					name : 'relDocName',
					display : '源单名称',
					sortable : true,
					hide : true

				}, {
					name : 'contractCode',
					display : '合同编号',
					sortable : true,
					hide : true
				}, {
					name : 'contractName',
					display : '合同名称',
					sortable : true,
					hide : true
				}, {
					name : 'purOrderCode',
					display : '采购订单编号',
					sortable : true
				}, {
					name : 'inStockId',
					display : '收料仓库id',
					sortable : true,
					hide : true
				}, {
					name : 'inStockCode',
					display : '收料仓库代码',
					sortable : true,
					hide : true
				}, {
					name : 'inStockName',
					display : '收料仓库名称',
					sortable : true
				}, {
					name : 'supplierId',
					display : '供应商id',
					sortable : true,
					hide : true
				}, {
					name : 'supplierName',
					display : '供应商名称',
					sortable : true
				}, {
					name : 'clientName',
					display : '客户名称',
					sortable : true,
					hide : true
				}, {
					name : 'purchMethod',
					display : '采购方式',
					sortable : true,
					datacode : 'cgfs',
					hide : true
				}, {
					name : 'payDate',
					display : '付款日期',
					sortable : true,
					hide : true
				}, {
					name : 'accountingCode',
					display : '往来科目',
					sortable : true,
					datacode : 'KJKM',
					hide : true
				}, {
					name : 'purchaserName',
					display : '采购员名称',
					sortable : true
				}, {
					name : 'docStatus',
					display : '单据状态',
					sortable : true,
					width : 50,
					process : function(v, row) {
						if (row.docStatus == 'WSH') {
							return "未审核";
						} else {
							return "已审核";
						}
					}
				}, {
					name : 'catchStatus',
					display : '钩稽状态',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					hide : true

				}, {
					name : 'purchaserCode',
					display : '采购员编号',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorName',
					display : '验收人名称',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorCode',
					display : '验收人编号',
					sortable : true,
					hide : true
				}, {
					name : 'chargeName',
					display : '负责人名称',
					sortable : true,
					hide : true
				}, {
					name : 'chargeCode',
					display : '负责人编号',
					sortable : true,
					hide : true
				}, {
					name : 'custosName',
					display : '保管人名称',
					sortable : true,
					hide : true
				}, {
					name : 'custosCode',
					display : '保管人编号',
					sortable : true,
					hide : true
				}, {
					name : 'auditerName',
					display : '审核人名称',
					sortable : true,
					hide : true
				}, {
					name : 'auditerCode',
					display : '审核人编号',
					sortable : true,
					hide : true
				}, {
					name : 'accounterName',
					display : '记账人',
					sortable : true,
					hide : true
				}, {
					name : 'accounterCode',
					display : '记账人编号',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '制单',
					sortable : true
				}, {
					name : 'auditerName',
					display : '审核人',
					sortable : true
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_instock_stockinitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编号'
					}, {
						name : 'productName',
						width : 200,
						display : '物料名称'
					}, {
						name : 'actNum',
						display : "实收数量"
					}, {
						name : 'serialnoName',
						display : "序列号",
						width : '500'
					}, {
						name : 'unHookNumber',
						display : '未勾稽数量'
					}, {
						name : 'unHookAmount',
						display : '未勾稽金额',
						process : function(v) {
							return moneyFormat2(v);
						}
					}]
		},
		comboEx : [{
					text : '单据状态',
					key : 'docStatus',
					data : [{
								text : '未审核',
								value : 'WSH'
							}, {
								text : '已审核',
								value : 'YSH'
							}]
				},{
					text : '发票状态',
					key : 'dealStatus',
					value : 'undo',
					data : [{
								text : '已录',
								value : 'done'
							}, {
								text : '未录',
								value : 'undo'
							}]
				}],
		searchitems : [{
					display : "供应商",
					name : "supplierName"
				},{
					display : '批次号',
					name : 'batchNum'
				}, {
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '采购订单编号',
					name : 'searchPurOrderCode'
				}, {
					display : '收料仓库名称',
					name : 'inStockName'
				}, {
					display : '物料代码',
					name : 'productCode'
				}, {
					display : '物料名称',
					name : 'productName'
				}, {
					display : '物料序列号',
					name : 'serialnoName'
				}, {
					display : '物料规格型号',
					name : 'pattern'
				}],
		sortorder : "DESC",
		buttonsEx : [{
			name : 'Add',
			text : "下推采购发票",
			icon : 'add',
			action : function(row, rows, idArr) {
				if (row) {
					var markIdSupplier = "";
					var markIsRed = "";
					var isSame = true;
					for (var i = 0; i < rows.length; i++) {
						if (markIdSupplier != ""
								&& markIdSupplier != rows[i].supplierId) {
							var isSame = false;
						}
						if (markIsRed != "" && markIsRed != rows[i].isRed) {
							var isSame = false;
						}
						markIdSupplier = rows[i].supplierId;
						markIsRed = rows[i].isRed;
					}
					if (isSame == false) {
						alert('不同供应商的入库单或者不同颜色单据不能下推成一张采购发票');
						return false;
					}
					idStr = idArr.toString();
					showModalWin("?model=finance_invpurchase_invpurchase"
									+ "&action=toAddForPushDown" + "&id="
									+ idStr, 1);
				} else {
					alert('请先选择记录');
				}
			}
		}, {
			name : 'Add',
			text : "上查",
			icon : 'search',
			action : function(row, rows, idArr) {
				if (row) {
					if (idArr.length > 1) {
						alert('一次只能对一条记录进行上查');
						return false;
					}
					if (row.relDocType != "") {
						showModalWin("?model=common_search_searchSource&action=upList&objType=stockin&orgObj="
								+ row.relDocType + "&ids=" + row.relDocId);
					} else {
						alert('没有相关联的单据');
					}
				} else {
					alert('请先选择记录');
				}
			}
		}, {
			name : 'Add',
			text : "下查",
			icon : 'search',
			action : function(row, rows, idArr) {
				if (row) {
					if (idArr.length > 1) {
						alert('一次只能对一条记录进行下查');
						return false;
					}

					$.ajax({
						type : "POST",
						url : "?model=common_search_searchSource&action=checkDown",
						data : {
							"objId" : row.id,
							'objType' : 'stockin'
						},
						async : false,
						success : function(data) {
							if (data != "") {
								showModalWin("?model=common_search_searchSource&action=downList&objType=stockin&orgObj="
										+ data + "&objId=" + row.id);
							} else {
								alert('没有相关联的单据');
							}
						}
					});
				} else {
					alert('请先选择记录');
				}
			}
		}, {
			name : 'Add',
			text : "高级搜索",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=stock_instock_stockin&action=toAdvancedSearch&docType=RKPURCHASE"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
			}
		}],
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=stock_instock_stockin&action=toAdd&docType=RKPURCHASE")
			},
			formWidth : 880,
			formHeight : 600
		}

	});
});