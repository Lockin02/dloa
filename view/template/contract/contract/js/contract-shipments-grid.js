var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};

$(function() {
	var shipCondition = $('#shipCondition').val();
	var typeDate = $.ajax({
		type : 'POST',
		url : "?model=projectmanagent_shipment_shipmenttype&action=getSelection",
		async : false
	}).responseText;
	var typeDate2 = [];
	if (typeDate) {
		typeDate = eval("(" + typeDate + ")");

		if (typeDate) {
			var o = {
				value : 0,
				text : '空类型'
			};
			typeDate2.push(o);
			for (var k = 0, kl = typeDate.length; k < kl; k++) {
				if (k == 0) {
				}
				o = {
					value : typeDate[k].value,
					text : typeDate[k].text
				};
				typeDate2.push(o);
			}
		}
	}
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'shipmentsJson',
		title : '发货需求',
		subgridcheck : true,
		param : {
			'states' : '2,4',
			'shipCondition' : shipCondition,
			'DeliveryStatusArr' : 'WFH,BFFH',
			'lExaStatusArr' : '完成',
			'ExaStatusArr' : "完成"
		},

		title : '发货需求',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractShipInfo',
		event : {
			'afterload' : function(data, g) {
				$("#listSql").val(g.listSql);
			}
		},
		// 扩展右键菜单
		/*
		 * event:{ row_success : function(){ var options = { max : 5, value :
		 * $(), image : 'js/jquery/raterstar/star2.gif', width : 16, height :
		 * 16, } $('.sign').rater(options); } },
		 */
//		buttonsEx : [{
//			name : 'export',
//			text : "发货数据导出",
//			icon : 'excel',
//			action : function(row) {
//				var colId = "";
//				var colName = "";
//				$("#contractGrid_hTable").children("thead").children("tr")
//						.children("th").each(function() {
//							if ($(this).css("display") != "none"
//									&& $(this).attr("colId") != undefined) {
//								if ($(this).attr("colId") != 'test') {
//									colName += $(this).children("div").html()
//											+ ",";
//									colId += $(this).attr("colId") + ",";
//								}
//								i++;
//							}
//						})
//				window.open("?model=contract_contract_contract&action=contExportExcel&colId="
//								+ colId + "&colName=" + colName)
//			}
//		}],
		menusEx : [{
			text : '查看合同',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&linkId="
						+ row.linkId);
			}
		}, {
			text : '设置分类',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=common_contract_allsource&action=toSetType&id='
						+ row.id
						+ "&docType=oa_contract_contract&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');
			}
		}, {
			text : '锁定库存',
			icon : 'lock',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.DeliveryStatus != 'YFH'
						&& row.DeliveryStatus != 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				var objCode = row.objCode;
				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.id + "&objCode=" + objCode
						+ "&objType=oa_contract_contract&skey=" + row['skey_']);
			}
		}, {
			text : '下达发货计划',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& shipCondition != 1
						&& row.ExaStatus == '完成'
						&& row.lExaStatus == '完成'
						&& row.makeStatus != 'YXD'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var idArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.id + "&equIds=" + idArr
						+ "&docType=oa_contract_contract" + "&skey="
						+ row['skey_']);
			}
		}, {
			text : '下达采购申请',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& row.ExaStatus == '完成'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var idArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=purchase_external_external&action=toAddByContract&contractId="
						+ row.id
						+ "&purchType="
						+ row.contractType
						+ "&contractName="
						+ row.contractName
						+ "&contractCode="
						+ row.contractCode
						+ "&objCode="
						+ row.objCode
						+ "&equIds="
						+ idArr
						+ "&skey="
						+ row['skey_']);
			}
		}, {
			text : '下达生产申请',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& row.ExaStatus == '完成'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var eqIdArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=produce_apply_produceapply&action=toApply&relDocId="
						+ row.id
						+ "&equIds="
						+ eqIdArr
						+ "&relDocType=CONTRACT" + "&skey=" + row['skey_']);
			}
		}, {
			text : '下达加密锁任务',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& row.ExaStatus == '完成'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var eqIdArr = g.getSubSelectRowCheckIds(rows);
				showModalWin("?model=stock_delivery_encryption&action=toAdd&sourceDocId="
						+ row.id
						+ "&equIds="
						+ eqIdArr
						+ "&skey=" + row['skey_']);
			}
		}, {
			text : "关闭需求",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus != 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=common_contract_allsource&action=closeCont&skey='
							+ row['skey_'],
					data : {
						id : row.id,
						type : 'oa_contract_contract'
					},
					// async: false,
					success : function(data) {
						alert("关闭成功");
						show_page();
						return false;
					}
				});
			}
		}
//		, {
//			text : "关闭发货物料",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.DeliveryStatus != 'TZFH') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, rowIds, g) {
//				var idArr = g.getSubSelectRowCheckIds(rows);
//				showOpenWin("?model=stock_outplan_outplan&action=toCloseOutMat&id="
//						+ row.id + "&equIds=" + idArr
//						+ "&docType=oa_contract_contract" + "&skey="
//						+ row['skey_']);
//			}
//		}
		],

		// 列信息
		colModel : [{
			name : 'status2',
			display : '下达状态',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.makeStatus == 'YXD') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		}, {
			name : 'isMeetProduction',
			display : '是否满足生产',
			sortable : false,
			width : 80,
			align : 'center',
			process : function(v) {
				if (v == '0') {
					return "<img src='images/icon/green.gif' title='满足'/>";
				} else {
					return "<img src='images/icon/red.gif' title='不满足'/>";
				}
			}
		}, {
			display : '创建时间',
			name : 'createTime',
			sortable : true,
			hide : true,
			width : 70
		}, {
			display : '审批通过日期',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
		}, {
			name : 'contractRate',
			display : '进度',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_contract_contract"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注：'
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '合同物料审批状态',
			name : 'lExaStatus',
			sortable : true,
			hide : true
		}, {
			display : '合同物料审批表Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'contractType',
			display : '合同类型',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				if (row.isBecome != '0') {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
			width : 150
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 150
		}, {
			name : 'customerId',
			display : '客户Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			// name : 'sign',
			// display : '等级',
			// width : 100,
			// process:function(v,row){
			// return "<div
			// id='sign"+row.id+"'></div><input
			// type='hidden' id='star"
			// +row.id+
			// "' value="
			// + v +
			// "
			// name='contract[sign]'></input><script>$('#sign"
			// +row.id+
			// "').rater({value:$('#star"
			// +row.id+
			// "')[0].value,image:'js/jquery/raterstar/star.gif',max:5,url:'index1.php?model=contract_contract_contract&action=editstar&id="
			// +row.id+
			// "',before_ajax: function(ret)
			// {if(confirm('要修改等级吗？')==false){$('#sign"
			// +row.id+
			// "').rater(this);return
			// false;}}});</script>";
			// }
			// }, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 60
		}, {
			name : 'dealStatus',
			display : '处理状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
				} else if (v == '2') {
					return "变更未处理";
				} else if (v == '3') {
					return "已关闭";
				}
			},
			width : 50
		}, {
			name : 'makeStatus',
			display : '下达状态',
			sortable : true,
			process : function(v) {
				if (v == 'BFXD') {
					return "部分下达";
				} else if (v == 'YXD') {
					return "已下达";
				} else {
					return "未下达"
				}
			},
			width : 50
		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
			sortable : true,
			process : function(v) {
				if (v == 'BFFH') {
					return "部分发货";
				} else if (v == 'YFH') {
					return "已发货";
				} else if (v == 'WFH') {
					return "未发货"
				} else if (v == 'TZFH') {
					return "停止发货"
				}
			},
			width : 50
		}, {
			name : 'objCode',
			display : '业务编号',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'customTypeName',
			display : '自定义类型',
			sortable : true,
			width : 80
		}, {
			name : 'warnDate',
			display : '提醒日期',
			sortable : true,
			width : 80
		}, {
            name : 'grandDays',
            display : '累计天数',
            width : 80
        }, {
            name : 'maxShipPlanDate',
            display : '预计完成交付日期',
            width : 80
        }],
		comboEx : [{
			text : '类型',
			key : 'contractType',
			data : [{
				text : '销售合同',
				value : 'HTLX-XSHT'
			}, {
				text : '服务合同',
				value : 'HTLX-FWHT'
			}, {
				text : '租赁合同',
				value : 'HTLX-ZLHT'
			}, {
				text : '研发合同',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '下达状态',
			key : 'makeStatus',
			data : [{
				text : '未下达',
				value : 'WXD'
			}, {
				text : '部分下达',
				value : 'BFXD'
			}, {
				text : '已下达',
				value : 'YXD'
			}]
		}, {
			text : '发货状态',
			key : 'DeliveryStatus',
			data : [{
				text : '未发货',
				value : 'WFH'
			}, {
				text : '部分发货',
				value : 'BFFH'
			}]
		}, {
			text : '自定义类型',
			key : 'customTypeId',
			data : typeDate2
//			,value : '0'  // Edit By zengzx, bug：898
		}],
		// 主从表格设置
		subGridOptions : {
			subgridcheck : true,
			url : '?model=common_contract_allsource&action=equJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				'docType' : 'oa_contract_contract'
			}, {
				paramId : 'contractId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			afterProcess : function(data, rowDate, $tr) {
				if (data.number <= data.executedNum) {
					$tr.find("td").css("background-color", "#A1A1A1");
				}
			},
			colModel : [{
				name : 'productCode',
				display : '物料编号',
				process : function(v, data, rowData, $row) {
					if (data.changeTips == 1) {
						return '<img title="变更编辑的物料" src="images/changeedit.gif" />'
								+ v;
					} else if (data.changeTips == 2) {
						return '<img title="变更新增的物料" src="images/new.gif" />'
								+ v;
					} else {
						return v;
					}
				},
				width : 95
			}, {
				name : 'productName',
				width : 200,
				display : '物料名称',
				process : function(v, data, rowData, $row) {
					if (data.changeTips != 0) {
						if (data.isBorrowToorder == 1) {
							$row.attr("title", "该物料为借试用转销售的物料");
							return "<img src='images/icon/icon147.gif' title='借试用转销售物料'/>"
									+ "<font color=red>" + v + "</font>";
						} else {
							return "<font color=red>" + v + "</font>";
						}
					} else {
						if (data.isBorrowToorder == 1) {
							$row.attr("title", "该物料为借试用转销售的物料");
							return "<img src='images/icon/icon147.gif'  title='借试用转销售物料'/>"
									+ v;
						} else {
							return v;
						}
					}
					if (row.changeTips != 0) {
						return "<font color = 'red'>" + v + "</font>"
					} else
						return v;
				}
			}, {
				name : 'productModel',
				display : '规格型号'
					// ,process : function(v, data, rowData, $row, $tr) {
					// $tr.removeClass();
					// $tr.css({
					// "background" : "red"
					// });
					// $tr.find("td").css("backgroup-color", "red");
					// }
					}, {
						name : 'number',
						display : '数量',
						width : 40
					}, {
						name : 'exeNum',
						display : '库存数量',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'lockedNum',
						display : '锁存量',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedShipNum',
						display : '已下达发货数量',
						width : 90
					// ,process : function(v, row) {
					// return '<a href="javascript:void(0)"
					// onclick="javascript:showOpenWin'
					// +'(\'?model=contract_contract_contract&action=init&perm=view&id='
					// + row.contractId
					// +
					// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
					// + v
					// + '</a>';
					// }
					}, {
						name : 'executedNum',
						display : '已发货数量',
						// process : function(v, row) {
						// return '<a href="javascript:void(0)"
						// onclick="javascript:showOpenWin'
						// +'(\'?model=contract_contract_contract&action=init&perm=view&id='
						// + row.contractId
						// +
						// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						// + v
						// + '</a>';
						// },
						width : 60
					}, {
						name : 'issuedPurNum',
						display : '已下达采购数量',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedProNum',
						display : '已下达生产数量',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'isMeetProduction',
						display : '是否满足生产',
						sortable : false,
						width : 80,
						align : 'center',
						process : function(v,row) {
							if(row.meetProductionRemark != ''){
								if(v == '1'){
									return "<span class='red'>" + row.meetProductionRemark + "</span>";
								}else{
									return row.meetProductionRemark;
								}
							}else{
								if (v == '0') {
									return "满足生产";
								} else if (v == '1') {
									return "<span class='red'>不满足生产</span>";
								}
							}
						}
					}, {
						name : 'backNum',
						display : '退库数量',
						width : 60,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'encryptionNum',
						display : '加密锁任务数量',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else {
								return v;
							}
						}
					}, {
						name : 'arrivalPeriod',
						display : '标准交货期',
						width : 80,
						process : function(v) {
							if (v == null) {
								return '0';
							} else {
								return v;
							}
						}
					}]
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}, {
			display : '合同名称',
			name : 'contractName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '业务编号',
			name : 'objCode'
		}, {
			display : '申请人',
			name : 'createName'
		}],
		sortname : "id"
	});

});
/*
 * ).then(function(){ var options = { max : 5, value : 0, image :
 * 'js/jquery/raterstar/star2.gif', width : 16, height : 16, }
 * $('#star').rater(options); });
 */