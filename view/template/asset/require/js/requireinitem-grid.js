var show_page = function(page) {
	$("#requireinItemGrid").yxgrid("reload");
};
$(function() {
	$("#requireinItemGrid").yxgrid({
		model : 'asset_require_requireinitem',
		title : '新增卡片信息',
		showcheckbox : false,
		isToolBar : false,
		param : {
			'mainId' : $("#requireId").val()
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'isCardFlag',
			display : '',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.cardStatus == 2) {
					return "<img src='images/icon/icon071.gif' />";
				}else{
					return "";
				}
			}
		}, {
			display : '申请id',
			name : 'mainId',
			sortable : true,
			hide : true
		}, {
			name : 'productId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '物料编号',
			width : '150',
			sortable : true
		}, {
			name : 'productName',
			display : '物料名称',
			width : '150',
			sortable : true
		}, {
			name : 'spec',
			display : '规格',
			sortable : true
		}, {
			name : 'number',
			display : '数量',
			width : '80',
			sortable : true
		}, {
			name : 'executedNum',
			display : '出库数量',
			width : '80',
			sortable : true
		}, {
			name : 'receiveNum',
			display : '验收数量',
			width : '80',
			sortable : true
		}, {
			name : 'cardNum',
			display : '生成卡片数量',
			width : '80',
			sortable : true
		}, {
			name : 'cardStatus',
			display : '生成卡片状态',
			sortable : true,
			process : function(v, row) {
				if (v == '0')
					return "待出库";
				if (v == '1')
					return "待签收";
				if (v == '2')
					return "待生成";
				if (v == '3')
					return "部分生成";
				if (v == '4')
					return "已生成";
			}
		}],
		comboEx : [{
			text : '生成卡片状态',
			key : 'cardStatus',
			data : [{
				text : '待出库',
				value : '0'
			}, {
				text : '待签收',
				value : '1'
			}, {
				text : '待生成',
				value : '2'
			}, {
				text : '部分生成',
				value : '3'
			}, {
				text : '已生成',
				value : '4'
			}]
		}],
		buttonsEx : [{
			name : 'Review',
			text : "返回",
			icon : 'view',
			action : function() {
				location.href="?model=asset_require_requirein";
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '生成资产卡片',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.cardStatus == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//待生成的卡片数量=出库数量-已生成卡片数量
					var num = row.executedNum-row.cardNum;
					showOpenWin("?model=asset_assetcard_assetTemp&action=toadd&assetName="
							+ row.productName
							+ "&productId="
							+ row.productId
							+ "&productCode="
							+ row.productCode
							+ "&productName="
							+ row.productName
							+ "&spec="
							+ row.spec
							+ "&num="
							+ num
							+ "&requireinItemId="
							+ row.id
							+ "&requireinId="
							+ row.mainId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				} else {
					alert("请选中一条数据");
				}
			}
		}]
	});
});