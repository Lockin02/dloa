/** 物料信息列表* */

var show_page = function() {
	$("#proTypeTree").yxtree("reload");
	$("#productinfoGrid").yxgrid("reload");
};

$(function() {

	$("#tree").yxtree({
		url: '?model=stock_productinfo_producttype&action=getTreeDataByParentId',
		event: {
			"node_click": function(event, treeId, treeNode) {
				var productinfoGrid = $("#productinfoGrid").data('yxgrid');

				productinfoGrid.options.extParam['proTypeId'] = treeNode.id;
				$("#proTypeId").val(treeNode.id);
				$("#proType").val(treeNode.name);
				$("#arrivalPeriod").val(treeNode.submitDay);
				productinfoGrid.reload();
			}
		}
	});

	$("#productinfoGrid").yxgrid({
		customCode: 'productinfolistgrid',
		model: 'stock_productinfo_productinfo',
		action: 'pageProInfoJson',
		title: '物料信息管理',
		isToolBar: true,
		isViewAction: false,
		showcheckbox: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			display: '物料类型',
			name: 'proType',
			sortable: true
		}, {
			display: '物料编码',
			name: 'productCode',
			width: 80,
			sortable: true
		}, {
			display: 'k3编码',
			name: 'ext2',
			width: 80,
			sortable: true
		}, {
			display: '物料名称',
			name: 'productName',
			sortable: true,
			width: 200
		}, {
			display: '关联物料编码',
			name: 'relProductCode',
			width: 80,
			sortable: true,
			hide: true
		}, {
			display: '关联物料名称',
			name: 'relProductName',
			sortable: true,
			width: 200,
			hide: true
		}, {
			name: 'pattern',
			display: '规格型号',
			sortable: true
		}, {
			name: 'priCost',
			display: '单价',
			sortable: true,
			hide: true
		}, {
			name: 'unitName',
			display: '单位',
			width: 50,
			sortable: true
		}, {
			name: 'businessBelongName',
			display: '归属公司',
			width: 70,
			sortable: true
		}, {
			display: '状态',
			name: 'ext1',
			process: function(v) {
				if (v == "WLSTATUSKF") {
					return "开放";
				} else {
					return "关闭";
				}
			},
			sortable: true,
			width: 50
		}, {
			name: 'aidUnit',
			display: '辅助单位',
			sortable: true,
			hide: true
		}, {
			name: 'converRate',
			display: '换算率',
			sortable: true,
			hide: true
		}, {
			name: 'warranty',
			display: '保修期(月)',
			hide: true,
			sortable: true
		}, {
			name: 'arrivalPeriod',
			display: '发货周期(天)',
			hide: true,
			sortable: true
		}, {
			name: 'purchPeriod',
			display: '采购周期(天)',
			sortable: true,
			hide: true
		}, {
			name: 'accountingCode',
			display: '会计科目代码',
			sortable: true,
			datacode: 'KJKM',
			hide: true
		}, {
			name: 'changeProductCode',
			display: '替代物料编码',
			sortable: true,
			hide: true
		}, {
			name: 'changeProductName',
			display: '替代物料名称',
			sortable: true,
			hide: true
		}, {
			name: 'closeReson',
			display: '关闭原因',
			sortable: true,
			hide: true
		}, {
			name: 'leastPackNum',
			display: '最小包装量',
			sortable: true,
			hide: true
		}, {
			name: 'leastOrderNum',
			display: '最小订单量',
			sortable: true,
			hide: true
		}, {
			name: 'material',
			display: '材料',
			sortable: true,
			hide: true
		}, {
			name: 'brand',
			display: '品牌',
			sortable: true,
			hide: true
		}, {
			name: 'color',
			display: '颜色',
			sortable: true,
			hide: true
		}, {
			name: 'purchUserName',
			display: '采购负责人',
			sortable: true,
			hide: true
		}, {
			display: '工程启用',
			name: 'esmCanUse',
			process: function(v) {
				if (v == "1") {
					return "是";
				} else {
					return "否";
				}
			},
			sortable: true,
			width: 50,
			hide: true
		}, {
			name: 'createName',
			display: '创建人',
			sortable: true,
			hide: true
		}, {
			name: 'updateName',
			display: '修改人',
			sortable: true,
			hide: true
		}],
		toAddConfig: {
			toAddFn: function(p) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toAdd&proType="
				+ $("#proType").val()
				+ "&proTypeId="
				+ $("#proTypeId").val()
				+ "&arrivalPeriod="
				+ $("#arrivalPeriod").val()
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=590&width=900");
			}
		},
		menusEx: [{
			name: 'edit',
			text: "修改",
			icon: 'edit',
			action: function(row) {
				var editTypeResult = false;
				$.ajax({
					type: "POST",
					async: false,
					url: "?model=stock_productinfo_productinfo&action=checkProType",
					data: {
						typeId: row.proTypeId
					},
					success: function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				});
				if (editTypeResult) {// 物料类型权限
					if ($("#productUpdate").val() != '1') {// 更新权限
						var editReresult = false;
						$.ajax({
							type: "POST",
							async: false,
							url: "?model=stock_productinfo_productinfo&action=checkExistBusiness",
							data: {
								id: row.id
							},
							success: function(result) {
								if (result == 1)
									editReresult = true;
							}
						});
						if (editReresult) {// 修改权限
							showThickboxWin("?model=stock_productinfo_productinfo&action=init&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

						} else {
							alert("此物料已经存在关联业务信息,不可以修改,请联系管理员!");
						}
					} else {
						showThickboxWin("?model=stock_productinfo_productinfo&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

					}
				} else {
					alert("此类型物料你没有管理权限!");
				}

			}
		}, {
			name: 'view',
			text: "查看",
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
				+ row.id
				+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			name: 'view',
			text: "操作日志",
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
				+ row.id
				+ "&tableName=oa_stock_product_info"
				+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			text: '更新',
			icon: 'edit',
			showMenuFn: function() {
				return $("#productUpdate").val() == '1';
			},
			action: function(row) {
				var editTypeResult = false;
				$.ajax({
					type: "POST",
					async: false,
					url: "?model=stock_productinfo_productinfo&action=checkProType",
					data: {
						typeId: row.proTypeId
					},
					success: function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				});
				if (editTypeResult) {// 物料类型权限
					showThickboxWin('?model=stock_productinfo_productinfo&action=toUpdate&id='
					+ row.id
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("你没有此物料类型的管理权限!")
				}
			}
		}, {
			text: '检测关联',
			icon: 'view',
			action: function(row) {
				showThickboxWin('?model=stock_productinfo_productinfo&action=toViewRelation&id='
				+ row.id
				+ "&skey="
				+ row['skey_']
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
			}
		}, {
			text: 'BOM清单',
			icon: 'edit',
			action: function(row) {
				showModalWin('?model=stock_material_material&action=toEditList&productId='
				+ row.id + '&productCode=' + row.productCode + '&productName=' + row.productName
				+ "&skey="
				+ row['skey_']);
			}
		}],
		buttonsEx: [{
			text: "导入",
			icon: 'excel',
			items: [{
				text: "导入物料",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}, {
				text: "更新物料",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadUpdateExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}, {
				text: "更新成本",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUpdatePriceExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}, {
				text: "更新K3",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadK3Excel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}]
		}, {
			text: "导出",
			icon: 'excel',
			items: [{
				name: 'expport',
				text: "导出物料",
				icon: 'excel',
				action: function() {
					window.open("?model=stock_productinfo_productinfo&action=toExportExcel", "",
						"width=200,height=200,top=200,left=200");
				}
			}, {
				name: 'expport',
				text: "导出物料配件",
				icon: 'excel',
				action: function() {
					var proTypeId = $("#proTypeId").val();
					window.open("?model=stock_productinfo_productinfo&action=toArmatureExcel&proTypeId=" + proTypeId, "",
						"width=800,height=800,top=200,left=200");
				}
			}]
		}, {
			name: 'editaccess',
			text: "更新配件",
			icon: 'edit',
			action: function() {
				if ($("#proTypeId").val() != "") {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toEditProAccess&proTypeId=" + $("#proTypeId").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				} else {
					alert("请选择物料分类！");
				}
			}
		}],
		// 高级搜索
		advSearchOptions: {
			modelName: 'productinfolist',
			searchConfig: [{
				name: '物料编号',
				value: 'c.productCode'
			}, {
				name: '物料名称',
				value: 'c.productName'
			}, {
				name: '规格型号',
				value: 'c.pattern'
			}, {
				name: '物料类型',
				value: 'c.proType'
			}, {
				name: '物料状态',
				value: 'c.ext1',
				type: 'select',
				datacode: 'WLSTATUS'
			}, {
				name: 'K3编码',
				value: 'c.ext2'
			}, {
				name: '归属公司',
				value: 'c.businessBelongName'
			}]
		},
		searchitems: [{
			display: '物料编码',
			name: 'productCode'
		}, {
			display: '物料名称',
			name: 'productName'

		}, {
			display: '归属类型',
			name: 'ext3'
		}, {
			display: '品牌',
			name: 'brand'
		}, {
			display: '规格型号',
			name: 'pattern'
		}, {
			name: 'ext2',
			display: 'K3编码'
		}, {
			name: 'businessBelongName',
			display: '归属公司'
		}],
		sortname: "ext1 desc,id",
		// 默认搜索顺序
		sortorder: "asc"
	});
});