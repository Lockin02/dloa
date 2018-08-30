/** 
 *  报废卡片库列表
 * */
var show_page = function(page) {
	$("#cardStockList").yxgrid("reload");
};
$(function() {
	$("#cardStockList").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '报废资产出售',
	    isViewAction : false,
	    isEditAction : false,
	    isAddAction : false,
	    isDelAction : false,
	    param : {
		    useStatusCode : 'SYZT-YBF',
		    isSell : '0'
	    },

		colModel : [{
                display : '资产Id',
                name : 'id',
                sortable : true,
                hide : true
            },{
                display : '卡片编号',
                name : 'assetCode',
                sortable : true
            },{
                display : '资产名称',
                name : 'assetName',
                sortable : true
            },{
                display : '规格型号',
                name : 'spec',
                sortable : true
            },{
                display : '购置日期',
                name : 'buyDate',
                sortable : true
            },{
                display : '资产原值',
                name : 'origina',
                sortable : true,
                //列表格式化千分位
                process : function(v){
					return moneyFormat2(v);
				}
            },{
				display : '残值',
				name : 'salvage',
				sortable : true,
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			},{
				display : '净值',
				name : 'netValue',
				sortable : true,
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			},{
				display : '已提折旧',
				name : 'depreciation',
				sortable : true,
				// 列表格式化千分位
				process : function(v) {
						return moneyFormat2(v);
				}
			},{
				display : '出售状态',
				name : 'isSell',
				sortable : true,
				process : function(val) {
						if (val == "0") {
							return "未出售";
						}
						if (val == "1") {
							return "已出售";
						}
					}
			},{
				display : '清理状态',
				name : 'isDel',
				sortable : true,
				process : function(val) {
						if (val == "0") {
							return "未清理";
						}
						if (val == "1") {
							return "已清理";
						}
					}
			},{
                display : '备注',
                name : 'remark',
                sortable : true
            }],
        buttonsEx : [{
			name : 'Add',
			text : "确认选择",
			icon : 'add',
			action: function(row,rows,idArr) {
				if(row){
					showThickboxWin("?model=asset_disposal_sell&action=toScrapAssetList&assetIdArr="
							+ idArr
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1050");
				}else{
					alert('请选择一行数据');
				}
			}
        }],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},{
			name : 'aduit',
			text : '填写出售单',
			icon : 'add',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_disposal_sell&action=toScrapAssetList&assetIdArr="
							+row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1050");
				}
			}
		}],

		searchitems : [{
			display : '卡片编号',
			name : 'assetCode'
		},{
			display : '资产名称',
			name : 'assetName'
		}],
		// 默认搜索字段名
			sortname : "id",
		// 默认搜索顺序 降序
			sortorder : "DESC"

	});
});
