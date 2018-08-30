/**
 * 清理资产出售信息列表
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};
$(function() {
	$billNo = $("#billNo").val(),
	// alert($("#sellID").val()),
	$("#datadictList").yxgrid({
		model : 'asset_disposal_scrapitem',
		//调用c层的sellitem的sellCardJson()方法从卡片表里获取要显示的字段
		action : 'scrapCardJson',
		param : {
			allocateID : $("#allocateID").val(),
			billNo : $("#billNo").val()
		},

		title : '清理资产报废',
		// isToolBar : true,
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
		}, {
			display : '报废单Id',
			name : 'allocateID',
			sortable : true,
			hide : true
		},{
			//从卡片表获取的
			display : '资产类别',
			name : 'assetTypeName',
			sortable : true,
			width : 70
		}, {
			display : '卡片编号',
			name : 'assetCode',
			sortable : true,
			width : 160
		},  {
			display : '资产名称',
			name : 'assetName',
			sortable : true,
			width : 130
		}, {
			display : '资产Id',
			name : 'assetId',
			hide : true
		}, {
			display : '规格型号',
			name : 'spec',
			sortable : true
		}, {
			display : '购置日期',
			name : 'buyDate',
			sortable : true,
			width : 70
		}, {
			//从卡片表里获取的
			display : '资产原值',
			name : 'origina',
			sortable : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 70
		}, {
			display : '残值',
			name : 'salvage',
			sortable : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 70
		}, {
			display : '已提折旧',
			name : 'depreciation',
			sortable : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 70
		}, {
			//从卡片表里获取的
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
			},
			width : 70
		}, {
			display : '备注',
			name : 'remark',
			sortable : true,
			width : 120
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
				window
						.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
								+ row.assetId
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			name : 'aduit',
			text : '清理',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.isDel == "0")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					// alert(row.sellID);
					// alert(row.assetCode);
					// alert(row.assetId);
					showThickboxWin("?model=asset_assetcard_clean&action=toAdd&billID="
							+ row.allocateID
							+ "&billType=scrap"
							+ "&billNo="
							+ $billNo
							+ "&assetTypeName="
							+ row.assetTypeName
							+ "&assetCode="
							+ row.assetCode
							+ "&assetId="
							+ row.assetId
							+ "&assetName="
							+ row.assetName
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1050");
				}
			}
		}],

		searchitems : [{
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '资产名称',
			name : 'assetName'
		}],
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序
		sortorder : "DESC"

	});
});
