var show_page = function (page) {
	$("#pickingGrid").yxsubgrid("reload");
};

$(function () {
	//数据过滤条件
//	var param = {};
//	var comboEx = [];
//	if ($("#finish").val() == 'yes') {
//		var isAdd = false;
//		param = {
//			createId : $("#userId").val(),
//			docStatusIn : '2,4,5'
//		};
//		var comboExArr = {
//				text: '单据状态',
//				key: 'docStatus',
//				data: [{
//					text: '审批完成',
//					value: '2'
//				}, {
//					text: '申请出库',
//					value: '4'
//				}, {
//					text: '出库完成',
//					value: '5'
//				}]
//			};
//	} else {
//		var isAdd = true;
//		param = {
//			createId : $("#userId").val(),
//			docStatusIn : '0,1,3'
//		};
//		var comboExArr = {
//			text: '单据状态',
//			key: 'docStatus',
//			data: [{
//				text: '未提交',
//				value: '0'
//			}, {
//				text: '审批中',
//				value: '1'
//			}, {
//				text: '打回',
//				value: '3'
//			}]
//		};
//	}
//	comboEx.push(comboExArr);

	$("#pickingGrid").yxsubgrid({
		model: 'produce_plan_picking',
		param: {createId : $("#userId").val()},
		title: '生产领料申请单',
//		isAddAction: isAdd,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'relDocCode',
			display: '源单编号',
			sortable: true,
			width: 130,
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
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '单据状态',
			sortable: true,
			process: function (v) {
				switch (v) {
				case '0':
					return "未提交";
					break;
				case '1':
					return "审批中";
					break;
				case '2':
					return "审批完成";
					break;
				case '3':
					return "打回";
					break;
				case '4':
					return "申请出库";
					break;
				case '5':
					return '出库完成';
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'docDate',
			display: '单据日期',
			sortable: true
		}, {
			name: 'relDocName',
			display: '源单名称',
			sortable: true,
			width: 200
		}, {
			name: 'relDocType',
			display: '源单类型',
			sortable: true
		}, {
			name: 'createName',
			display: '申请人',
			sortable: true
		}, {
			name: 'moduleName',
			display: '所属板块',
			sortable: true
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 400
		}],

		// 主从表格设置
		subGridOptions: {
			url: '?model=produce_plan_pickingitem&action=pageJson',
			param: [{
				paramId: 'pickingId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '物料编码',
				sortable: true,
				width: 120
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
				name: 'applyNum',
				display: '申请数量',
				width: 60,
				sortable: true
			}, {
				name: 'realityNum',
				display: '出库数量',
				width: 60,
				sortable: true
			}, {
				name: 'planDate',
				display: '计划领料日期',
				width: 80,
				sortable: true
			}, {
				name: 'realityDate',
				display: '实际领料日期',
				width: 80,
				sortable: true
			}, {
				name: 'relDocCode',
				display: '源单编号',
				sortable: true,
				width: 130,
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
				name: 'relDocName',
				display: '源单名称',
				sortable: true,
				width: 200
			}, {
				name: 'customerName',
				display: '客户名称',
				width: 200,
				sortable: true
			}]
		},

		//扩张右键菜单
		menusEx: [{
			text: "导出",
			icon: 'excel',
			action: function (row, rows, grid) {
				if (row) {
					window.open("?model=produce_plan_picking&action=excelOut&id=" + row.id);
				}
			}
		}, {
			text: "提交审批",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin('controller/produce/plan/ewf_index.php?actTo=ewfSelect&billId=' + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}, {
			text: '撤销审批',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == "部门审批") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("确认要撤销审批?")) {
						$.ajax({
							type: "POST",
							url: "?model=common_workflow_workflow&action=isAuditedContract",
							data: {
								billId: row.id,
								examCode: 'oa_produce_picking'
							},
							success: function (msg) {
								if (msg == '1') {
									alert('操作失败，原因：\n1.已撤销申请,不能重复撤销\n2.单据已经存在审批信息，不能撤销审批');
									return false;
								} else {
									$.ajax({
										type: 'GET',
										url: 'controller/produce/plan/ewf_index.php?actTo=delWork',
										data: {
											billId: row.id
										},
										async: false,
										success: function (data) {
											alert(data);
											show_page(1);
										}
									});
								}
							}
						});
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text: '删除',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.docStatus == '0' || row.docStatus == '3') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type: "POST",
						url: "?model=produce_plan_picking&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page(1);
							}else{
								alert('删除失败！');
							}
						}
					});
				}
			}
		}, {
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_picking&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}, {
			name: 'add',
			text: '退料申请',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == '2' || row.docStatus == '4' || row.docStatus == '5') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=produce_plan_back&action=toAddByPicking&pickingId=" + row.id, 1);
				}
			}
		}],
		buttonsEx:[{
	        name: 'view',
	        text: "高级查询",
	        icon: 'view',
	        action: function () {
	            showThickboxWin("?model=produce_plan_picking&action=toSearch&"
	            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
	        }
		}],
		comboEx: [{
			text: '单据状态',
			key: 'docStatus',
			data: [{
				text: '未提交',
				value: '0'
			}, {
				text: '审批中',
				value: '1'
			}, {
				text: '审批完成',
				value: '2'
			}, {
				text: '打回',
				value: '3'
			}, {
				text: '申请出库',
				value: '4'
			}, {
				text: '出库完成',
				value: '5'
			}]
		}],

		toAddConfig: {
			toAddFn: function () {
				showModalWin("?model=produce_plan_picking&action=toAdd", '1');
			}
		},
		toEditConfig: {
			showMenuFn: function (row) {
				if (row.docStatus == '0' || row.docStatus == '3') {
					return true;
				}
				return false;
			},
			toEditFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					if (get['relDocId'] > 0) {
						showModalWin("?model=produce_plan_picking&action=toEdit&id=" + get[p.keyField], '1');
					} else {
						showModalWin("?model=produce_plan_picking&action=toEdit&isCaculate=true&id=" + get[p.keyField], '1');
					}
				}
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_picking&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		}, {
			name: 'docDate',
			display: '单据日期'
		}, {
			name: 'irelDocCode',
			display: '源单编号(合同号)'
		}, {
			name: 'irelDocName',
			display: '源单名称'
		}, {
			name: 'irelDocType',
			display: '源单类型'
		}, {
			name: 'icustomerName',
			display: '客户名称'
		}, {
			name: 'iproductCode',
			display: '物料编码'
		}, {
			name: 'iproductName',
			display: '物料名称'
		}, {
			name: 'createName',
			display: '申请人'
		}]
	});
});