var show_page = function(page) {
	$("#receiveItemGrid").yxgrid("reload");
};
$(function() {
	$("#receiveItemGrid").yxgrid({
		model : 'asset_purchase_receive_receiveItem',
		title : '新增卡片信息',
		showcheckbox : false,
		isToolBar : false,
		param : {
			'receiveId' : $("#receiveId").val()
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'requireinId',
			name : 'requireinId',
			sortable : true,
			hide : true
		}, {
			display : 'requireinCode',
			name : 'requireinCode',
			sortable : true,
			hide : true
		}, {
			name : 'isCardFlag',
			display : '',
			sortable : false,
			width : '20',
			align : 'center',
			process : function(v, row) {
				if (row.cardStatus == '2') {
					return '<img src="images/icon/cicle_green.png"/>';
				}else{
					return '<img src="images/icon/cicle_yellow.png"/>';
				}
			}
		}, {
			display : '验收id',
			name : 'receiveId',
			sortable : true,
			hide : true
		}, {
			name : 'assetId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'assetName',
			display : '物料名称',
			width : '150',
			sortable : true
		}, {
			name : 'assetCode',
			display : '物料编号',
			width : '150',
			sortable : true
		}, {
			name : 'spec',
			display : '规格',
			sortable : true
		}, {
			name : 'checkAmount',
			display : '数量',
			width : '80',
			sortable : true
		}, {
			name : 'cardNum',
			display : '生成卡片数量',
			width : '80',
			sortable : true
		}, {
			name : 'cardStatus',
			display : '是否已生成卡片',
			sortable : true,
			process : function(v, row) {
				if (v == '0') 
					return "未生成";
				if (v == '1') 
					return "部分生成";
				if (v == '2') 
					return "已生成";
			}
		}],
		comboEx : [{
			text : '生成卡片状态',
			key : 'cardStatus',
			data : [{
				text : '未生成',
				value : '0'
			}, {
				text : '部分生成',
				value : '1'
			}, {
				text : '已生成',
				value : '2'
			}]
		}],
		buttonsEx : [{
			name : 'Review',
			text : "返回",
			icon : 'view',
			action : function() {
				location.href="?model=asset_purchase_receive_receive";
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '生成资产卡片',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.cardStatus != '2') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//待生成的卡片数量 = 数量 - 已生成卡片数量
					var num = row.checkAmount - row.cardNum;
					showOpenWin("?model=asset_assetcard_assetTemp&action=toadd&assetName="
							+ row.assetName
							+ "&productId="
							+ row.assetId
							+ "&productCode="
							+ row.assetCode
							+ "&productName="
							+ row.assetName
							+ "&spec="
							+ row.spec
							+ "&brand="
							+ row.brand
							+ "&num="
							+ num
							+ "&requireinId="
							+ row.requireinId
							+ "&requireinItemId="
							+ row.requireinItemId
							+ "&receiveItemId="
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				} else {
					alert("请选中一条数据");
				}
			}
		}]
	});
});