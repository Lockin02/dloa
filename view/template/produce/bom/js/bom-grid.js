var show_page = function(page) {
	$("#bomGrid").yxsubgrid("reload");
};
$(function() {
	$("#bomGrid").yxsubgrid({
		model : 'produce_bom_bom',
		title : 'BOM表',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : 'BOM单号',
					sortable : true
				}, {
					name : 'productCode',
					display : '物料编码',
					sortable : true
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					width : 200
				}, {
					name : 'pattern',
					display : '规格型号',
					sortable : true
				}, {
					name : 'unitName',
					display : '单位',
					sortable : true,
					width : 50
				}, {
					name : 'properties',
					display : '物料属性',
					sortable : true,
					datacode : 'WLSX'
				}, {
					name : 'proNum',
					display : '数量',
					sortable : true,
					width : 50
				}, {
					name : 'useStatus',
					display : '使用状态',
					sortable : true,
					process : function(v) {
						if (v == "0") {
							return "使用中";
						} else {
							return "未使用";
						}
					}
				}, {
					name : 'version',
					display : '版本',
					sortable : true,
					width : 50
				}, {
					name : 'auditerName',
					display : '审核人',
					sortable : true,
					hide : true
				}, {
					name : 'docStatus',
					display : '审核状态',
					sortable : true,
					process : function(v) {
						if (v == "WSH") {
							return "未审核";
						} else {
							return "已审核";
						}
					}
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '创建人',
					sortable : true,
					hide : true

				}, {
					name : 'createTime',
					display : '创建日期',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '修改人',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '修改日期',
					sortable : true,
					hide : true
				}], // 主从表格设置
		subGridOptions : {
			url : '?model=produce_bom_bomitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编码'
					}, {
						name : 'productName',
						display : '物料名称',
						width : 200
					}, {
						name : 'pattern',
						display : '规格型号'
					}, {
						name : 'properties',
						display : '物料属性',
						datacode : 'WLSX'
					}, {
						name : 'unitName',
						display : '单位'
					}, {
						name : 'useNum',
						display : '用量'
					}]
		},
		menusEx : [{
			text : "查看",
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=produce_bom_bom&action=toView&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus == 'WSH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=produce_bom_bom&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
				}
			}
		}, {
			text : '启用',
			icon : 'business',
			showMenuFn : function(row) {
				if (row.useStatus == '1' && row.docStatus == 'YSH') {
					return true;
				} else
					return false;
			},
			action : function(row) {
				if (window.confirm(("确定启用吗？"))) {
					$.ajax({
								type : "GET",
								url : "?model=produce_bom_bom&action=actUseStatus&id="
										+ row.id
										+ "&productId="
										+ row.productId,
								success : function(result) {
									alert(result)
									if (result == 0) {
										alert("启用成功!")
										show_page();
									} else {
										alert("启用失败");
									}

								}
							});
				}
			}
		}, {
			text : '审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus == 'WSH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定审核吗？"))) {
					$.ajax({
								type : "POST",
								url : "?model=produce_bom_bom&action=audit&id="
										+ row.id,
								success : function(result) {
									if (result == 0) {
										alert("审核成功!")
										show_page();
									} else {
										alert("审核失败");
									}

								}
							});
				}
			}
		}, {
			text : '反审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus == 'YSH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定反审核吗？"))) {
					$.ajax({
								type : "GET",
								url : "?model=produce_bom_bom&action=cancelAudit&id="
										+ row.id,
								success : function(result) {
									if (result == 0) {
										alert("反审核成功!")
										show_page();
									} else {
										alert("反审核失败");
									}

								}
							});
				}
			}
		}, {
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == 'WSH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
								type : "POST",
								url : "?model=produce_bom_bom&action=ajaxdeletes&id="
										+ row.id,
								success : function(msg) {
									if (msg == 1) {
										alert('删除成功')
										$("#bomGrid").yxsubgrid("reload");
									} else {
										alert('删除失败，该对象可能已经被引用!')
									}
								}
							});
				}
			}
		}],
		comboEx : [{
					text : "审核状态",
					key : 'docStatus',
					data : [{
								text : '已审核',
								value : 'YSH'
							}, {
								text : '未审核',
								value : 'WSH'
							}]
				}],
		toAddConfig : {
			formWidth : '1100px',
			formHeight : 600
		},
		toEditConfig : {
			formWidth : '1100px',
			formHeight : 600,
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "BOM单号",
					name : 'docCode'
				}, {
					display : "物料编码",
					name : 'productCode'
				}, {
					display : "物料名称",
					name : 'productName'
				}]
	});
});