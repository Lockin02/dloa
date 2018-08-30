var show_page = function (page) {
	$("#arrivalMyGrid").yxsubgrid("reload");
};

$(function () {
	$("#arrivalMyGrid").yxsubgrid({
		model: 'purchase_arrival_arrival',
		title: '收料通知单',
		action: 'myPageJson',
		isEditAction: false,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		showcheckbox: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'arrivalCode',
			display: '收料单号',
			sortable: true,
			width: 180
		}, {
			name: 'state',
			display: '收料通知单状态',
			sortable: true,
			process: function (v, row) {
				if (row.state == '0') {
					return "未执行";
				} else {
					return "已执行";
				}
			}
		}, {
			name: 'purchaseId',
			display: '订单id',
			hide: true
		}, {
			name: 'purchaseCode',
			display: '采购订单编号',
			sortable: true
		}, {
			name: 'arrivalType',
			display: '收料类型',
			sortable: true,
			datacode: 'ARRIVALTYPE'
		}, {
			name: 'supplierName',
			display: '供应商名称',
			sortable: true,
			width: 150
		}, {
			name: 'supplierId',
			display: '供应商id',
			hide: true
		}, {
			name: 'purchManId',
			display: '采购员ID',
			hide: true
		}, {
			name: 'purchManName',
			display: '采购员',
			sortable: true
		}, {
			name: 'purchMode',
			display: '采购方式',
			hide: true,
			datacode: 'cgfs'
		}, {
			name: 'stockId',
			display: '收料仓库Id',
			hide: true
		}, {
			name: 'stockName',
			display: '收料仓库名称',
			sortable: true
		}],

		// 主从表格设置
		subGridOptions: {
			url: '?model=purchase_arrival_equipment&action=pageJson',
			param: [{
				paramId: 'arrivalId',
				colId: 'id'
			}],
			colModel: [{
				name: 'sequence',
				display: '物料编号'
			}, {
				name: 'productName',
				width: 200,
				display: '物料名称'
			}, {
				name: 'batchNum',
				display: "批次号"
			}, {
				name: 'arrivalDate',
				display: "收料日期"
			}, {
				name: 'month',
				display: "月份"
			}, {
				name: 'arrivalNum',
				display: "收料数量"
			}, {
				name: 'storageNum',
				display: "已入库数量"
			}, {
				name: 'isQualityBack',
				display: "是否打回",
				process: function (v) {
					switch (v) {
					case '0':
						return '否';
						break;
					case '1':
						return '是';
						break;
					default:
						return '';
					}
				}
			}]
		},

		// 扩展右键菜单
		menusEx: [{
			name: 'view',
			text: '查看',
			icon: 'view',
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id=" + row.id
						+ "&skey=" + row['skey_']+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name: 'edit',
			text: '编辑',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state == 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=init&id=" + row.id
						+ "&skey=" + row['skey_'] + "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text: '提交质检',
			icon: 'add',
			showMenuFn: function (row) {
				var flag = false;
				$.ajax({
					type: "POST",
					url: "?model=purchase_arrival_equipment&action=getIsQualityBackByArrivalId",
					data: {
						arrivalId: row.id
					},
					async: false,
					success: function (msg) {
						if (msg == '1') {
							flag = true;
						}
					}
				});

				return flag;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin('?model=produce_quality_qualityapply&action=toAddByArrival&relDocId=' + row.id
						+ '&relDocType=ZJSQYDSL&relDocCode=' + row.arrivalCode);
				}
			}
		}, {
			name: 'sendEmail',
			text: '发送收料通知',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=toReceiveNotice&id=" + row.id
						+ "&skey=" + row['skey_'] + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text: '删除',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.state == 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					$.ajax({
						type: "POST",
						url: "?model=purchase_arrival_arrival&action=deletesConfirm",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == 0) { //判断是否有生成质检
								alert('该收料单已生成质检申请单，不可删除');
								show_page();
							} else {
								if (window.confirm("确认要删除?")) {
									$.ajax({
										type: "POST",
										url: "?model=purchase_arrival_arrival&action=deletesInfo",
										data: {
											id: row.id
										},
										success: function (msg) {
											if (msg == 1) {
												alert('删除成功!');
												show_page();
											}
										}
									});
								}
							}
						}
					});
				}
			}
		}],

		// 扩展菜单
		buttonsEx: [{
			name: 'Add',
			text: "上查",
			icon: 'search',
			action: function (row, rows, idArr) {
				if (row) {
					if (idArr.length > 1) {
						alert('一次只能对一条记录进行上查');
						return false;
					}
					if (row.relDocType != "") {
						showModalWin("?model=common_search_searchSource&action=upList&objType=arrival&orgObj=" + row.relDocType +
							"&ids=" + row.id);
					} else {
						alert('没有相关联的单据');
					}
				} else {
					alert('请先选择记录');
				}
			}
		}, {
			name: 'Add',
			text: "下查",
			icon: 'search',
			action: function (row, rows, idArr) {
				if (row) {
					if (idArr.length > 1) {
						alert('一次只能对一条记录进行下查');
						return false;
					}

					$.ajax({
						type: "POST",
						url: "?model=common_search_searchSource&action=checkDown",
						data: {
							"objId": row.id,
							"objType": 'arrival'
						},
						async: false,
						success: function (data) {
							if (data != "") {
								showModalWin("?model=common_search_searchSource&action=downList&objType=arrival&orgObj=" + data +
									"&objId=" + row.id);
							} else {
								alert('没有相关联的单据');
							}
						}
					});
				} else {
					alert('请先选择记录');
				}
			}
		}],

		toAddConfig: {
			/**
			 * 新增表单默认宽度
			 */
			formWidth: 1000,
			/**
			 * 新增表单默认高度
			 */
			formHeight: 500
		},

		comboEx: [{
			text: '收料通知单状态',
			key: 'state',
			data: [{
				text: '未执行',
				value: '0'
			}, {
				text: '已执行',
				value: '2'
			}]
		}],

		searchitems: [{
			display: '收料单号',
			name: 'arrivalCode'
		}, {
			display: '采购员',
			name: 'purchManName'
		}, {
			display: '供应商',
			name: 'supplierName'
		}, {
			display: '物料名称',
			name: 'productName'
		}, {
			display: '物料编号',
			name: 'sequence'
		}],

		// 默认搜索顺序
		sortorder: "DESC",
		sortname: "updateTime"
	});
});