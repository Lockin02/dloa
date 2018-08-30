/** 报废资产出售信息列表
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};
$(function() {
	$("#datadictList").yxgrid({
		model : 'asset_disposal_scrapitem',
		//调用c层的scrapitem的scrapCardJson()方法从卡片表里获取要显示的字段
		action : 'scrapCardJson',
		param:{allocateID:$("#allocateID").val(),billNo:$("#billNo").val()},
		title : '报废资产出售',
		//isToolBar : true,
		  showcheckbox : false,
		  isViewAction : false,
		  isEditAction : false,
		  isAddAction : false,
		  isDelAction : false,

		colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            },
            {
                display : '报废单Id',
                name : 'allocateID',
                sortable : true,
                hide : true
            },
//            {
//                display : '报废单编号',
//                name : 'billNo',
//                sortable : true
//            },
            {
                display : '卡片编号',
                name : 'assetCode',
                sortable : true
            },
            {
                display : '资产名称',
                name : 'assetName',
                sortable : true
            },
            {
				display : '资产Id',
				name : 'assetId',
                hide : true
			},
            {
                display : '规格型号',
                name : 'spec',
                sortable : true
            },
            {
                display : '购置日期',
                name : 'buyDate',
                sortable : true
            },
            {
                display : '资产原值',
                name : 'origina',
                sortable : true,
                //列表格式化千分位
                process : function(v){
					return moneyFormat2(v);
				}
            },
            {
				display : '残值',
				name : 'salvage',
				sortable : true,
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			},
			{
				display : '净值',
				name : 'netValue',
				sortable : true,
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			},
			{
				display : '已提折旧',
				name : 'depreciation',
				sortable : true,
				// 列表格式化千分位
				process : function(v) {
						return moneyFormat2(v);
				}
			},
			//报废从表里的出售状态（原做法）
//			{
//				display : '出售状态',
//				name : 'sellStatus',
//				sortable : true
//			},
			//资产卡片表里的出售状态
			{
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
			},
			{
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
			},
      		{
                display : '备注',
                name : 'remark',
                sortable : true
            }],
		buttonsEx : [{
			name : 'Review',
			text : "返回",
			icon : 'view',
			action : function() {
				history.back();
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.assetId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},{
			name : 'aduit',
			text : '填写出售单',
			icon : 'add',
			    showMenuFn:function(row){
			    	if((row.isSell=="0"&&row.isDel=="0")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_disposal_sell&action=toScrapAsset&allocateID="
							+ row.allocateID
							+"&assetCode="
							+row.assetCode
							+"&assetId="
							+row.assetId
							+"&scrapitemId="
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
