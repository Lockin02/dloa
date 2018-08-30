var show_page = function() {
	$("#goodsbaseinfoGrid").yxgrid("reload");
};

$(function() {
	$("#goodsTypeTree").yxtree({
		url: '?model=goods_goods_goodstype&action=getTreeData&isSale=' + $("#isSale").val()+'&typeId='+$("#typeId").val(),
		event: {
			node_click: function(event, treeId, treeNode) {
				var goodsbaseinfoGrid = $("#goodsbaseinfoGrid")
					.data('yxgrid');
				goodsbaseinfoGrid.options.param['goodsTypeId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				goodsbaseinfoGrid.reload();
			}
		}
	});

	$("#goodsbaseinfoGrid").yxgrid({
		model: 'goods_goods_goodsbaseinfo',
		param: {'useStatus': 'WLSTATUSKF'},
		title: '步骤1：选择产品',
		showcheckbox: false,
		isAddAction: false,
		isDelAction: false,
		isEditAction: false,
		isOpButton: false,
		autoload: false,
		// 列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'goodsTypeName',
			display: '所属分类名称',
			sortable: true,
			width: 80
		}, {
			name: 'goodsName',
			display: '产品名称',
			sortable: true,
			width: 200
		}, {
			name: 'exeDeptName',
			display: '产品线',
			sortable: true,
			width: 80
		}
//		, {
//			name: 'auditDeptName',
//			display: '执行区域',
//			sortable: true
//		}
		, {
			name: 'unitName',
			display: '单位',
			sortable: true,
			width: 50
		}, {
			name: 'useStatus',
			display: '状态',
			sortable: true,
			datacode: 'WLSTATUS',
			width: 50
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 250
		}, {
			name: 'createName',
			display: '创建人',
			sortable: true,
			hide: true
		}, {
			name: 'createTime',
			display: '创建日期',
			sortable: true,
			hide: true
		}, {
			name: 'updateName',
			display: '修改人',
			sortable: true,
			hide: true
		}, {
			name: 'updateTime',
			display: '修改日期',
			sortable: true,
			hide: true
		}],
		buttonsEx: [{
			name: 'Add',
			text: "选择",
			icon: 'add',
			action: function(rowData) {
				if (rowData) {
					var thisId = rowData.id;
					window.location = '?model=contract_contract_product&action=toSetProductInfo'
					+ "&isMoney="
					+ $("#isMoney").val()
					+ "&isSale="
					+ $("#isSale").val()
					+ "&rowNum="
					+ $("#rowNum").val()
					+ "&componentId="
					+ $("#componentId").val()
					+ "&isFrame="
					+ $("#isFrame").val()
                    + "&typeId="
                    + $("#typeId").val()
			        + "&notEquSlt="
                    + $("#notEquSlt").val()
					+ '&goodsId='
					+ thisId
					+ "&goodsName="
					+ rowData.goodsName
					+ "&isEncrypt="
					+ rowData.isEncrypt
					+ "&isCon="
					+ $("#isCon").val()
					+ "&exeDeptName="
					+ rowData.exeDeptName
					+ "&exeDeptCode="
					+ rowData.exeDeptCode
					+ "&auditDeptName="
					+ rowData.auditDeptName
					+ "&auditDeptCode="
					+ rowData.auditDeptCode;
				} else {
					alert('请先选择记录');
				}
			}
		}],
		event: {
			row_dblclick: function(e, row, rowData) {
				if (rowData) {
					if (rowData.useStatus == "WLSTATUSGB") {
						alert("该产品已关闭，不可以选择!");
					} else {
						var thisId = rowData.id;
						window.location = '?model=contract_contract_product&action=toSetProductInfo'
						+ "&isMoney="
						+ $("#isMoney").val()
						+ "&isSale="
						+ $("#isSale").val()
						+ "&rowNum="
						+ $("#rowNum").val()
						+ "&componentId="
						+ $("#componentId").val()
						+ "&isFrame="
						+ $("#isFrame").val()
                        + "&typeId="
                        + $("#typeId").val()
							+ "&notEquSlt="
							+ $("#notEquSlt").val()
						+ '&goodsId='
						+ thisId
						+ "&goodsName="
						+ rowData.goodsName
						+ "&isEncrypt="
						+ rowData.isEncrypt
						+ "&isCon="
						+ $("#isCon").val()
						+ "&exeDeptName="
						+ rowData.exeDeptName
						+ "&exeDeptCode="
						+ rowData.exeDeptCode
						+ "&auditDeptName="
						+ rowData.auditDeptName
						+ "&auditDeptCode="
						+ rowData.auditDeptCode;
					}

				} else {
					alert('请先选择记录');
				}
			}
		},
		menusEx: [{
			text: '配置预览',
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=goods_goods_properties&action=toPreView&goodsId="
				+ row.id
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}],
		toViewConfig: {
			action: 'toView'
		},
		// 审批状态数据过滤
		comboEx: [{
			text: "状态",
			key: 'useStatus',
			value: 'WLSTATUSKF',
			datacode: 'WLSTATUS'
		}, {
			text: "产品线",
			key: 'exeDeptCode',
			datacode: 'HTCPX'
		}],
		searchitems: [{
			display: "产品名称",
			name: 'goodsName'
		}, {
			display: "物料编号",
			name: 'productCode'
		}, {
			display: "物料名称",
			name: 'productName'
		}]
	});
});