var show_page = function(page) {
	$("#checkinfoGrid").yxgrid("reload");

};
$(function() {
	$("#checkinfoGrid").yxgrid({
		model : 'stock_check_checkinfo',
		title : '盘亏毁损 管理',
		param : {
			checkType : "SHORTAGE"
		},
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true

				}, {
					name : 'docCode',
					display : '单据编号',
					sortable : true,
					width : 150

				}, {
					name : 'checkType',
					display : '盘点类型',
					sortable : true,
					process : function(val) {
						if (val = "SHORTAGE") {
							return "盘亏毁损";
						} else {
							return "盘盈入库"
						}
					}
				}, {
					name : 'stockId',
					display : '仓库id',
					sortable : true,
					hide : true

				}, {
					name : 'stockName',
					display : '仓库名称',
					sortable : true,
					width : 200
				}, {
					name : 'auditStatus',
					display : '单据状态',
					sortable : true,
					process : function(v, row) {
						if (row.auditStatus == "WPD") {
							return "未盘点";
						} else {
							return "已盘点"
						}
					}
				}, {
					name : 'dealUserId',
					display : '经办人id',
					sortable : true,
					hide : true
				}, {
					name : 'dealUserName',
					display : '经办人',
					sortable : true,
					hide : true

				}, {
					name : 'auditUserId',
					display : '审核人id',
					sortable : true,
					hide : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					hide : true
				}, {
					name : 'ExaDT',
					display : '审批时间',
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
					name : 'createTime',
					display : '创建日期',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '录入人',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '修改人id',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '修改日期',
					sortable : true,
					hide : true
				}, {
					name : 'auditUserName',
					display : '审核人',
					sortable : true
				}],
		// 按钮
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showcheckbox : false,
		menuWidth : 130,
		// 右键菜单按钮
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_check_checkinfo&action=init&id="
							+ row.id
							+ "&checkType="
							+ row['checkType']
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 500 + "&width=" + 1000);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'delete',
			text : '删除',
			icon : 'delete',
			// 该方法判断右键菜单是否要出现该项
			showMenuFn : function(row) {
				if (row.auditStatus == "WPD") {
					return true;
				} else {
					return false;
				}

			},
			action : function(row) {
				if (confirm('确认删除？')) {
					$.ajax({
						type : "POST",
						url : "?model=stock_check_checkinfo&action=ajaxdeletes",
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
				if (row.auditStatus == "WPD") {
					return true;
				} else {
					return false;
				}

			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_check_checkinfo&action=toEdit&id="
							+ row.id
							+ "&checkType="
							+ row['checkType']
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 500 + "&width=" + 1000);
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			name : 'view',
			text : "打印",
			icon : 'print',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_check_checkinfo&action=toPrint&id="
						+ row.id
						+ "&checkType="
						+ row['checkType']
						+ "&skey="
						+ row['skey_']
						+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 500 + "&width=" + 1000);
			}
		}, {
			name : 'unlock',
			text : "反审核",
			icon : 'unlock',
			showMenuFn : function(row) {
				if (row.auditStatus == "YPD") {
					return true;
				} else {
					return false;
				}

			},
			action : function(row, rows, grid) {
				var canAudit = true;// 是否已结账
				var closedAudit = true;// 是否已关账
				$.ajax({
							type : "POST",
							async : false,
							url : "?model=finance_period_period&action=isLaterPeriod",
							data : {
								thisDate : row.auditDate
							},
							success : function(result) {
								if (result == 0)
									canAudit = false;
							}
						})
				$.ajax({
							type : "POST",
							async : false,
							url : "?model=finance_period_period&action=isClosed",
							data : {},
							success : function(result) {
								if (result == 1)
									closedAudit = false;
							}
						})
				if (closedAudit) {
					if (canAudit) {
						if (window.confirm("确认进行反审核吗?")) {
							$.ajax({
								type : "POST",
								url : "?model=stock_check_checkinfo&action=cancelAudit",
								data : {
									id : row.id,
									checkType : row.checkType
								},
								success : function(result) {
									show_page();

									if (result == 1) {
										alert('单据反审核成功！');
									} else {
										alert('单据反审核失败,请确认!');
									}
								}
							});

						}
					} else {
						alert("单据所在财务周期已经结账，不能进行反审核，请联系财务人员进行反结账！");
					}
				} else {
					alert("财务已关账，不能进行反审核，请联系财务人员进行反关账！");
				}
			}
		}

		],
		// 快速搜索
		searchitems : [{
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '仓库名称',
					name : 'stockName'
				}],
		comboEx : [{
					text : '单据状态',
					key : 'auditStatus',
					data : [{
								text : '未盘点',
								value : 'WPD'
							}, {
								text : '已盘点',
								value : 'YPD'
							}]
				}],
		sortorder : "DESC",
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=stock_check_checkinfo&action=toAdd&checkType=SHORTAGE")
			}
		}

	})

})