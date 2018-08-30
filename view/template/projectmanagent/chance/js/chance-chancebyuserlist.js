var show_page = function(page) {
	$("#chanceGrid").yxgrid("reload");
};
$(function() {
	$("#chanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		title : '我的销售商机',
		param : {
			'status' : '0',
			'prinvipalId' : $("#userId").val(),
			'isTemp' : '0'
		},
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
						+ data.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		showcheckbox : false,
		formHeight : 600,
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
			name : 'chanceTypeName',
			display : '项目类型',
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
		}, {
			name : 'predictContractDate',
			display : '预计合同签署日期',
			sortable : true
		}, {
			name : 'predictExeDate',
			display : '预计合同执行日期',
			sortable : true
		}, {
			name : 'contractPeriod',
			display : '合同执行周期（月）',
			sortable : true
		}, {
			name : 'progress',
			display : '项目进展描述',
			sortable : true
		}, {
			name : 'Province',
			display : '所属省',
			sortable : true
		}, {
			name : 'City',
			display : '所属市',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '区域负责人',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '商机负责人',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '商机负责人',
			sortable : true
		}, {
			name : 'customerType',
			display : '客户类型',
			datacode : 'KHLX',
			sortable : true
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
			sortable : true
		}, {
			name : 'rObjCode',
			display : 'oa业务编号',
			sortable : true
		}, {
			name : 'contractCode',
			display : '合同号',
			sortable : true
		}],

		comboEx : [{
			text : '商机类型',
			key : 'chanceType',
			datacode : 'SJLX'
		}, {
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
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&perm=view&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}],
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
		}],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "ASC",
		// 显示查看按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false

			// toAddConfig : {
			// text : '新建',
			// icon : 'add',
			// /**
			// * 默认点击新增按钮触发事件
			// */
			//
			// toAddFn : function(p) {
			// self.location =
			// "?model=projectmanagent_chance_chance&action=toAdd";
			//			}
			//		}
	});
});