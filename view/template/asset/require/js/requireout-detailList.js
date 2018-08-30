var show_page = function(page) {
	$("#requireoutGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireoutGrid").yxgrid({
		model : 'asset_require_requireout',
		action : 'jsonDetail',
		title : '资产转物料明细列表',
		isToolBar : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '申请编号',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requireout&action=toView&id=" + row.mainId
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			name : 'assetId',
			display : '资产id',
			sortable : true,
			hide : true
		}, {
			name : 'assetCode',
			display : '资产编号',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_assetcard_assetcard&action=init&perm=view&id=" + row.assetId
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 120
		}, {
			name : 'assetName',
			display : '资产名称',
			sortable : true,
			width : 120
		}, {
			name : 'salvage',
			display : '资产残值',
			sortable : true,
			width : 80
		}, {
			name : 'productId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '物料编号',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=stock_productinfo_productinfo&action=view&id=" + row.productId 
            		+ "&tableName=oa_stock_product_info" 
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 80
		}, {
			name : 'productName',
			display : '物料名称',
			sortable : true,
			width : 120
		}, {
			name : 'spec',
			display : '型号',
			sortable : true,
			width : 120
		}, {
			name : 'number',
			display : '申请数量',
			sortable : true,
			width : 50
		}, {
			name : 'executedNum',
			display : '入库数量',
			sortable : true,
			width : 50
		}, {
			name : 'applyName',
			display : '申请人',
			sortable : true,
			width : 80
		}, {
			name : 'applyDeptName',
			display : '申请部门',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 70
		}, {
			name : 'detaiRemark',
			display : '备注',
			sortable : true,
			width : 200
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin("?model=asset_require_requireout&action=toView&id="
							+ row.mainId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}, {
			text : '资产信息',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin("?model=asset_assetcard_assetcard&action=init&perm=view&id="
							+ row.assetId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}, {
			text : '物料信息',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
							+ row.productId
							+ "&tableName=oa_stock_product_info"
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}],
		searchitems : [{
			display : "申请编号",
			name : 'requireCode'
		}, {
			display : "申请人",
			name : 'applyName'
		}, {
			display : "申请部门",
			name : 'applyDeptName'
		},{
			display : "资产编号",
			name : 'assetCode'
		},{
			display : "资产名称",
			name : 'assetName'
		},{
			display : "物料编号",
			name : 'productCode'
		},{
			display : "物料名称",
			name : 'productName'
		}]
	});
});