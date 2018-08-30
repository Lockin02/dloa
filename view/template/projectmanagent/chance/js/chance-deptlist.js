var show_page = function(page) {
	$("#deptchanceGrid").yxgrid("reload");
};
$(function() {
	$("#deptchanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		title : '部门销售商机',

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'chanceName',
					display : '商机名称',
					sortable : true
				},{
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
								+ row.oldId
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
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
				//				+ "<font color = '#4169E1'>"
				//				+ v
				//				+ "</font>"
				//				+ '</a>';
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
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					}
				}, {
					name : 'predictContractDate',
					display : '预计合同签署日期',
					sortable : true,
					hide : true
				}, {
					name : 'predictExeDate',
					display : '预计合同执行日期',
					sortable : true,
					hide : true
				}, {
					name : 'contractPeriod',
					display : '合同执行周期（月）',
					sortable : true,
					hide : true
				}, {
					name : 'rObjCode',
					display : 'oa业务编号',
					sortable : true,
					hide : true
				}, {
					name : 'contractCode',
					display : '合同号',
					sortable : true,
					hide : true
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
				}],
		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + row.id + "&skey="+row['skey_']
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
				}
			}

		},{

			text : '指定项目团队',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3' || row.status == '4'|| row.status == '6') {
					return false;
				}
				return true;
			},

			action : function(row) {

				showThickboxWin('?model=projectmanagent_chance_chance&action=deptTrackman&id='
						+ row.id + "&skey="+row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
		}],
		//快速搜索
		searchitems : [{
			display : '商机名称',
			name : 'chanceName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}],
		//默认搜索顺序
		sortorder : "DSC",
		//显示查看按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});