var show_page = function() {
	$("#safetystockGrid").yxgrid("reload");
};
$(function() {
	$("#safetystockGrid").yxgrid({
		model : 'stock_safetystock_safetystock',
		action : 'pageJsonCount',
		title : '安全库存列表',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
            name : 'manageDept',
            display : '管理部门',
            width : '80',
            sortable : true
        }, {
			name : 'productId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '物料编号',
			width : '80',
			sortable : true
		}, {
			name : 'productName',
			display : '物料名称',
			width : '200',
			sortable : true
		}, {
			name : 'pattern',
			display : '规格型号',
			width : '100',
			sortable : true
		}, {
			name : 'unitName',
			display : '单位',
			width : '50',
			sortable : true
		}, {
			name : 'saleStock',
			display : '库存数量',
			width : '60',
			sortable : true
		}, {
			name : 'oldEquStock',
			display : '旧设备数量',
			width : '60',
			sortable : true
		}, {
			name : 'minNum',
			display : '最低库存',
			width : '50',
			process : function(v, row) {
				return '<span class="red">' + v + '</span>';
			},
			sortable : true
		}, {
			name : 'maxNum',
			display : '最高库存',
			width : '50',
			sortable : true
		}, {
			name : 'loadNum',
			display : '在途数量',
			width : '50',
			sortable : true
		}, {
			name : 'useFull',
			display : '用途',
			sortable : true,
			hide : true
		}, {
			name : 'moq',
			display : 'MOQ',
			sortable : true,
			hide : true
		}, {
			name : 'price',
			display : '采购单价',
			width : '50',
			sortable : true,
			hide : true
		}, {
			name : 'purchUserCode',
			display : '采购员编码',
			sortable : true,
			hide : true
		}, {
			name : 'purchUserName',
			display : '采购员',
			sortable : true,
            width : 80
		}, {
			name : 'prepareDay',
			display : '备货周期(天)',
			width : '80',
			sortable : true
		}, {
			name : 'minAmount',
			display : '最小库存金额',
			width : '80',
			sortable : true
		}, {
			name : 'isFillUp',
			display : '是否下达补库',
			sortable : true,
			hide : true
		}, {
			name : 'fillNum',
			display : '补库数量',
			sortable : true,
			hide : true
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
			display : '修改人',
			sortable : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			width : '150'
		}],
		buttonsEx : [{
			name : 'analyse',
			text : "分析",
			icon : 'business',
			action : function(row, rows, grid) {
				showModalWin("?model=stock_safetystock_safetystock&action=toAnalyse");
			}
		}, {
			name : 'expport',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				window.open("?model=stock_safetystock_safetystock&action=toExportExcel","", "width=200,height=200,top=200,left=200");
			}
		}, {
			name : 'business',
			text : "有效期设置",
			icon : 'edit',
			action : function(row) {
				showThickboxWin("?model=system_configenum_configenum&action=toEdit&id=3&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400");
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "物料编号",
			name : 'productCode'
		}, {
			display : "物料名称",
			name : 'productName'
		}]
	});
});