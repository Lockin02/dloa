var show_page = function(page) {
	$("#chanceEquListGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [], $("#chanceEquListGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceEquListJson',
		title : '销售商机',
		leftLayout : false,
		customCode : 'chanceEquListGrid',

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceType',
			display : '商机类型',
			datacode : 'SJLX',
			sortable : true
		}, {
			name : 'productName',
			display : '设备名称',
			sortable : true,
			width : 200
		}, {
			name : 'productId',
			display : '设备ID',
			sortable : true,
			width : 200,
			hide : true
		}, {
			name : 'numberSum',
			display : '数量',
			sortable : true
		}, {
			name : 'winRate',
			display : '赢率',
			datacode : 'SJYL',
			sortable : true
		}],
		buttonsEx : buttonsArr,
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_chance_chance&action=chanceEquInfoJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'productId',// 传递给后台的参数名称
				colId : 'productId'// 获取主表行数据的列名称
			}, {
				paramId : 'winRate',// 传递给后台的参数名称
				colId : 'winRate'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				name : 'chanceCode',
				width : 100,
				display : '商机编号'
			}, {
				name : 'chanceName',
				display : '商机名称',
				width : 150,
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
			}, {
				name : 'status',
				display : '商机状态',
				process : function(v) {
					if (v == 0) {
						return "跟踪中";
					} else if (v == 3) {
						return "关闭";
					} else if (v == 4) {
						return "已生成合同";
					} else if (v == 5) {
						return "跟踪中"
					} else if (v == 6) {
						return "暂停"
					}
				},
				sortable : true,
				width : 50
			}, {
				name : 'productName',
				display : '设备名称',
				width : 200
			}, {
				name : 'number',
				display : '数量',
				width : 50
			}, {
			//	name : 'chanceStage',
			//	display : '商机阶段',
			//	datacode : 'SJJD',
			//	sortable : true,
			//	width : 50
			//}, {
				name : 'predictContractDate',
				display : '预计合同签署日期',
				sortable : true
			}]
		},
//		// 扩展右键菜单
//		menusEx : [{
//			text : '销售备货申请',
//			icon : 'add',
//			action : function(row) {
//				if (row) {
//					showThickboxWin("?model=projectmanagent_stockup_stockup&action=toAdd"
//					        + "&sourceType=chanceEqu"
//					        + "&sourceId=" + row.productId
//							+ "&skey="
//							+ row['skey_']
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1000");
//				}
//			}
//
//		}],
		comboEx : [{
					text : '商机状态',
					key : 'status',
					value : '5',
					data : [{
								text : '跟踪中',
								value : '5'
							}, {
								text : '暂停',
								value : '6'
							}, {
								text : '关闭',
								value : '3'
							}, {
								text : '已生成合同',
								value : '4'
							}]
				}],
		// 快速搜索
		searchitems : [{
			display : '商机编号',
			name : 'chanceCode'
		}, {
			display : '商机名称',
			name : 'chanceName'
		}, {
			display : '设备名称',
			name : 'productName'
		}],
		// 默认搜索顺序
		sortorder : "DSC",
		// 显示查看按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});
