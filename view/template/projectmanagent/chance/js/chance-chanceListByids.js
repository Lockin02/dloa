var show_page = function(page) {
	$("#chanceListByidsGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [],
	$("#chanceListByidsGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceGridJson',
		param : {"ids" : $("#ids").val()},
		title : '销售商机',
		customCode : 'chancegridA',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
						+ data.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '建立时间',
			sortable : true
		}, {
			name : 'newUpdateDate',
			display : '最近更新时间',
			sortable : true
		}, {
			name : 'chanceCode',
			display : '商机编号',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'chanceName',
			display : '项目名称',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : '项目总额',
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'chanceType',
			display : '项目类型',
			datacode : 'SJLX',
			sortable : true
		}, {
		//	name : 'chanceStage',
		//	display : '商机阶段',
		//	datacode : 'SJJD',
		//	sortable : true,
		//	process : function(v, row) {
		//		return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=boostChanceStageInfo&id='
		//				+ row.id
		//				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
		//				+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
		//	}
		//}, {
			name : 'winRate',
			display : '商机赢率',
			datacode : 'SJYL',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=winRateInfo&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		},{
		    name : 'goodsNameStr',
		    display : '产品内容',
		    width : 200,
		    sortable : false
		}],
		buttonsEx : buttonsArr,
		comboEx : [{
			text : '商机盈率',
			key : 'winRate',
			data : [{
				text : '0%',
				value : '0'
			}, {
				text : '25%',
				value : '25'
			}, {
				text : '50%',
				value : '50'
			}, {
				text : '80%',
				value : '80'
			}, {
				text : '100%',
				value : '100'
			}]
		//}, {
		//	text : '商机阶段',
		//	key : 'chanceStage',
		//	data : [{
		//		text : '阶段一',
		//		value : 'SJJD01'
		//	}, {
		//		text : '阶段二',
		//		value : 'SJJD02'
		//	}, {
		//		text : '阶段三',
		//		value : 'SJJD03'
		//	}, {
		//		text : '阶段四',
		//		value : 'SJJD04'
		//	}, {
		//		text : '阶段五',
		//		value : 'SJJD05'
		//	}, {
		//		text : '阶段六',
		//		value : 'SJJD06'
		//	}]
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}

		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_chance_goods&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'chanceId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
				name : 'goodsName',
				width : 200,
				display : '产品名称'
			}, {
				name : 'number',
				display : '数量',
				width : 80
			}, {
				name : 'money',
				display : '金额',
				width : 80,
				process : function(v, row) {
					if (v == '') {
						return "0.00";
					} else {
						return moneyFormat2(v);
					}
				}
			}]
		},
		// 快速搜索
		searchitems : [{
			display : '商机编号',
			name : 'chanceCode'
		}, {
			display : '商机名称',
			name : 'chanceName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '产品名称',
			name : 'goodsName'
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
