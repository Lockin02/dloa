// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#myApplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#myApplyGrid").yxsubgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_plan_basic',
		action : 'myListPageJson',
		title : '采购申请列表',
		isToolBar : false,
		showcheckbox : false,
		param : {
			'state' : '0'
		},

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '采购类型',
					name : 'purchTypeCName',
					sortable : false
				}, {
					display : '采购申请编号',
					name : 'planNumb',
					sortable : true,
					width : 120
				}, {
					display : '审批状态',
					name : 'ExaStatus',
					sortable : true
				}, {
					display : '申请源单据号',
					name : 'sourceNumb',
					sortable : true,
					width : 120
				}, {
					display : '批次号',
					name : 'batchNumb',
					sortable : true,
					width : 120
				}, {
					display : '申请人',
					name : 'createName',
					sortable : true
				}, {
					display : '申请时间 ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}, {
					display : '希望完成时间 ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}, {
					display : '申请原因 ',
					name : 'applyReason',
					sortable : true,
					width : 200
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCategoryName',
						display : '物料类别',
						width : 50
					}, {
						name : 'productNumb',
						display : '物料编号'
					}, {
						name : 'productName',
						width : 200,
						display : '物料名称',
						process : function(v, data) {
							if (v == "") {
								return data.inputProductName;
							}
							return v;
						}
					},{
						name : 'amountAll',
						display : "申请数量",
						width : 70
					}, {
						name : 'amountAllOld',
						display : "原申请数量",
						width : 70
					},{
						name : 'dateIssued',
						display : "申请日期"
					}, {
						name : 'dateHope',
						display : "希望完成日期"
					}, {
						name : 'isBack',
						display : "是否打回",
						process : function(v, data) {
							return v == 1 ? "是" : "否";
						}
					}]
		},
		// 扩展按钮
		buttonsEx : [{
			name : 'export',
			text : '导出',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_plan_basic&action=toExport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}, {
			name : 'export',
			text : '导出时效表',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_plan_basic&action=toExportAging"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}, {
			name : 'export',
			text : '导出批次物料',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_plan_basic&action=toExportProduceEqu"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600");
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "未提交" || row.ExaStatus == "打回"
						|| row.ExaStatus == "物料确认打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=toEdit&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "未提交" || row.ExaStatus == "打回"
						|| row.ExaStatus == "物料确认打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					// location =
					// 'controller/purchase/plan/ewf_index.php?actTo=ewfSelect&billId='
					// + row.id+'&purchType='+row.purchType
					// + '&examCode=oa_purch_plan_basic&formName=采购申请审批';
					switch (row.purchType) {
						case 'assets' :
							location = 'controller/purchase/plan/ewf_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ '&purchType='
									+ row.purchType
									+ '&examCode=oa_purch_plan_basic&formName=资产采购申请审批&billDept='+ row.departId;
							break;
						case 'rdproject' :
							location = 'controller/purchase/plan/ewf_rdproject_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ '&examCode=oa_purch_plan_basic&formName=研发采购申请审批';
							break;
						case 'produce' :
							location = 'controller/purchase/plan/ewf_produce_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ '&examCode=oa_purch_plan_basic&formName=生产采购申请审批';
							break;
					}
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '撤消审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "部门审批" ) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(confirm('确定要撤消审批吗？')){
						switch (row.purchType) {
							case 'assets' :
								location = 'controller/purchase/plan/ewf_index.php?actTo=delWork&billId='
										+ row.id
										+ '&purchType='
										+ row.purchType
										+ '&examCode=oa_purch_plan_basic&formName=资产采购申请审批';
								break;
							case 'rdproject' :
								location = 'controller/purchase/plan/ewf_rdproject_index.php?actTo=delWork&billId='
										+ row.id
										+ '&examCode=oa_purch_plan_basic&formName=研发采购申请审批';
								break;
							case 'produce' :
								location = 'controller/purchase/plan/ewf_produce_index.php?actTo=delWork&billId='
										+ row.id
										+ '&examCode=oa_purch_plan_basic&formName=生产采购申请审批';
								break;
						}
					}
				} else {
					alert("请选中一条数据");
				}
			}

		},{
			text : '变更',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "完成") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=toChange&id="
							+ row.id + "&skey=" + row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "未提交"||row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type : "POST",
							url : "?model=purchase_plan_basic&action=deletesInfo",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功!');
									show_page();
								}
							}
						});
					}
				}
			}
		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "完成" || row.ExaStatus == "打回")
						&& (row.purchType == "assets"
								|| row.purchType == "rdproject" || row.purchType == "produce")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_plan_basic&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		// 快速搜索
		searchitems : [{
					display : '采购申请编号',
					name : 'seachPlanNumb'
				}, {
					display : '物料编号',
					name : 'productNumb'
				}, {
					display : '物料名称',
					name : 'productName'
				}, {
					display : '申请源单据号',
					name : 'sourceNumb'
				}, {
					display : '批次号',
					name : 'batchNumb'
				}],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "updateTime",
		// 默认搜索顺序
		sortorder : "DESC",
		// 显示查看按钮
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});