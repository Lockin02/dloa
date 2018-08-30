var show_page = function(page) {
	$("#chanceGoodsListGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [], $("#chanceGoodsListGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceGoodsJson',
		title : '销售商机',
		leftLayout : false,
		customCode : 'chanceGoodsListGrid',

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceIds',
			display : '商机ids',
			sortable : true,
			hide : true
		}, {
			name : 'goodsName',
			display : '产品名称',
			sortable : true,
			width : 200
		}, {
			name : 'goodsId',
			display : '产品代码',
			sortable : true,
			width : 100
		}, {
			name : 'chanceNum',
			display : '商机数量',
			sortable : true,
			width : 100,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=chanceListByids&ids='
						+ row.chanceIds
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'numberSum',
			display : '产品数量',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : '总计金额',
			sortable : true,
			width : 150,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}],
		buttonsEx : buttonsArr,
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_chance_chance&action=chanceGoodsInfoJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'conProductId',// 传递给后台的参数名称
				colId : 'goodsId'// 获取主表行数据的列名称
			}
//			,{"status":$("#status").val()}
			],
			// 显示的列
			colModel : [{
				name : 'goodsName',
				display : '产品名称',
				width : 150
			}, {
				name : 'goodsId',
				display : '产品代码',
				width : 50
			}, {
				name : 'chanceNum',
				display : '商机数量',
				width : 50,
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=chanceListByids&ids='
							+ row.chanceIds
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000\')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
			}, {
				name : 'numberSum',
				display : '产品数量'
			}, {
				name : 'chanceMoney',
				display : '总计金额',
				width : 80,
				process : function(v, row) {
					if (v == '') {
						return "0.00";
					} else {
						return moneyFormat2(v);
					}
				}
			}, {
				name : 'winRate',
				display : '商机赢率',
				datacode : 'SJYL',
				width : 80
			//}, {
			//	name : 'chanceStage',
			//	display : '商机阶段',
			//	datacode : 'SJJD',
			//	width : 80
			}]
		},
//		comboEx : [{
//			text : '商机状态',
//			key : 'status',
//			value : '5',
//			data : [{
//				text : '跟踪中',
//				value : '5'
//			}, {
//				text : '暂停',
//				value : '6'
//			}, {
//				text : '关闭',
//				value : '3'
//			}, {
//				text : '已生成合同',
//				value : '4'
//			}]
//		}],
		// 快速搜索
		searchitems : [{
			display : '商机编号',
			name : 'chanceCode'
		}, {
			display : '商机名称',
			name : 'chanceName'
		}, {
			display : '产品名称',
			name : 'goodsNameSer'
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
