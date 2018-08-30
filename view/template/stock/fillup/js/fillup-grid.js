var show_page = function(page) {
	$("#fillupGrid").yxsubgrid("reload");
};
$(function() {
	$("#fillupGrid").yxsubgrid({
		model : 'stock_fillup_fillup',
		title : '补库计划',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'fillupCode',
					display : '单据编号',
					sortable : true
				}, {
					name : 'stockId',
					display : '仓库id',
					sortable : true,
					hide : true
				}, {
					name : 'stockCode',
					display : '仓库代码',
					sortable : true
				}, {
					name : 'stockName',
					display : '仓库名称',
					sortable : true,
					width : 200

				}, {
					name : 'auditStatus',
					display : '提交状态',
					sortable : true

				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '审批时间',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '修改人id',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '修改人',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '创建时间',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '制单',
					sortable : true
				}, {
					name : 'createId',
					display : '创建人id',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '修改日期',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					width:'350',
					sortable : true
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_fillup_filluppro&action=pageJson',
			param : [{
						paramId : 'fillUpId',
						colId : 'id'
					}],
			colModel : [{
						name : 'sequence',
						display : '物料编号'
					}, {
						name : 'productName',
						width : 200,
						display : '物料名称'
					}, {
						name : 'stockNum',
						display : "补库数量"
					}, {
						name : 'amountAllOld',
						display : "申请数量"
					},{
						name : 'issuedPurNum',
						display : "已下达采购数量"
					}]
		},
		toAddConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 820,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		},
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 820,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		},
		toEditConfig : {
			action : 'toEdit',
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 820,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		},
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showcheckbox : false,
		menuWidth : 130,
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_fillup_fillup&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'delete',
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待审核' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确认删除？')) {
					$.ajax({
								type : "POST",
								url : "?model=stock_fillup_fillup&action=ajaxdeletes",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										show_page();
										alert('删除成功！');
									} else {
										alert('删除失败，该对象可能已经被引用!');
									}

								}
							})
				}
			}
		}, {
			name : 'edit',
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待审核' || row.ExaStatus == '打回') {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_fillup_fillup&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&&width=820");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'sumbit',
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待审核' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.get('index1.php', {
								model : 'stock_fillup_fillup',
								action : 'changeAuditStatus',
								id : row.id
							}, function(data) {
								if (data == 0) {
									showThickboxWin("controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId="
											+ row.id
											+ "&examCode=oa_stock_fillup&formName=补库审批"
											+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");
									// location =
									// 'controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId='
									// + row.id
									// +
									// '&examCode=oa_stock_fillup&formName=补库审批';
								} else {
									alert("数据有误");
								}
							})

				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'aduit',
			text : '关联的采购申请',
			icon : 'view',
			showMenuFn : function(row) {
				if ( row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
				showThickboxWin("?model=purchase_plan_basic&action=toSourceList&purchType=stock&sourceId="
						+ row.id
						+ "&sourceCode="
						+ row.fillupCode
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=950");
				}
			}
		}, {
			name : 'audit',
			text : '下达采购申请', // add by can
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成') {
					var flag = false;
					$.ajax({ // 判断是否可以显示“下达采购计划右键”
						type : "post",
						async : false,
						url : "?model=stock_fillup_fillup&action=isAddPlan",
						data : "id=" + row.id,
						success : function(data) {
							if (data == 1) {
								return true;
							} else {
								return false;
							}
						}
					});
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_external_external&action=toAdd&purchType=stock&sourceId="
						+ row.id
						+ "&sourceCode="
						+ row.fillupCode
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=950");
			}
		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成'
						|| row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_stock_fillup&pid="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}],
		// 快速搜索
		searchitems : [{
					display : '单据编号',
					name : 'fillupCodeX'
				}, {
					display : '仓库名称',
					name : 'stockName'
				}],
		comboEx : [{
					text : '审批状态',
					key : 'ExaStatus',
					data : [{
								text : '部门审批',
								value : '部门审批'
							}, {
								text : '待审核',
								value : '待审核'
							}, {
								text : '完成',
								value : '完成'
							}, {
								text : '打回',
								value : '打回'
							}]
				}]
	});
});