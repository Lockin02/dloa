$(function() {
	disDT();
})
function disDTreload() {
	disDT();
	var chanceGrid = $("#chanceListGrid").data('yxsubgrid');
	chanceGrid.options.extParam['timingDate'] = $("#hideDT").val();
	chanceGrid.reload();
}
var show_page = function(page) {
	$("#chanceListGrid").yxsubgrid("reload");
};
//处理日期
function disDT() {
	//处理保存日期，如果日期小于 15号，则将日期定为上个月30号，如果日期大于15号，则将日期定为本月15号
	var timingDT = $("#timingDT").val();
	var dates = timingDT.split("-");
	var fTime = new Date(dates[0], dates[1], dates[2]);
	//	var fTime = new Date(timingDT);
	year = fTime.getFullYear();
	month = (fTime.getMonth());
	day = (fTime.getDate());
	if (day < 15) {
		if (month == '1') {
			var newDate = (year - 1) + "-12-30";
		} else {
			if ((month - 1) == "2") {
				D = new Date(year, (month - 1), 0);
				var listDay = D.getDate();
				var newDate = year + "-" + (month - 1) + "-" + listDay;
			} else {
				var newDate = year + "-" + (month - 1) + "-" + "30";
			}
		}
	} else {
		var newDate = year + "-" + month + "-15";
	}
	$("#hideDT").val(newDate);
}

$(function() {
	buttonsArr = [],
	SJDC = {
		name : 'export',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#chanceListGrid").data('yxsubgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#chanceListGrid")
							.data('yxsubgrid').options.searchParam[t];
				}
			}
			var status = $("#status").val();
			var chanceType = $("#chanceType").val();
			var chanceLevel = $("#chanceLevel").val();
			var winRate = $("#winRate").val();
			var chanceStage = $("#chanceStage").val();
			var timingDate = $("#hideDT").val();
			var i = 1;
			var colId = "";
			var colName = "";
			$("#chanceListGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
						if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined
								&& $(this).children("div").text() != "+") {

							colName += $(this).children("div").html() + ",";
							colId += $(this).attr("colId") + ",";
							i++;
						}
					})
			window
					.open("?model=projectmanagent_chance_chance&action=historyChanceExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&status="
							+ status
							+ "&chanceType="
							+ chanceType
							+ "&chanceLevel="
							+ chanceLevel
							+ "&winRate="
							+ winRate
							+ "&chanceStage="
							+ chanceStage
							+ "&timingDate="
							+ timingDate
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal="
							+ searchConditionVal
							+ "&1width=200,height=200,top=200,left=200,resizable=yes")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=getLimits',
		data : {
			'limitName' : '商机信息列表导出'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(SJDC);
			}
		}
	});

	$("#chanceListGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceListJson',
		param : {
			'timingDate' : $("#hideDT").val()
		},
		// event : {
		// 'row_dblclick' : function(e, row, data) {
		// showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
		// + data.id + "&skey="+row['skey_']
		// +
		// "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
		// );
		// }
		// },
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'oldId',
			name : 'oldId',
			sortable : true,
			hide : true
		}, {
			name : 'timingDate',
			display : '定时保存时间',
			sortable : true
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
						+ row.oldId
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
			datacode : 'HTLX',
			sortable : true
		}, {
			name : 'winRate',
			display : '商机赢率与承诺(%)',
			datacode : 'SJYL',
			sortable : true
		}, {
			name : 'chanceStage',
			display : '项目进展阶段',
			datacode : 'SJJD',
			sortable : true
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
			name : 'contractTurnDate',
			display : '转合同日期',
			sortable : true
		}, {
			name : 'rObjCode',
			display : 'oa业务编号',
			sortable : true
		}, {
			name : 'contractCode',
			display : '合同号',
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
		}],
		//		buttonsEx : [{
		//			name : 'export',
		//			text : "导出",
		//			icon : 'excel',
		//			action : function(row) {
		//				var status = $("#status").val();
		//				var chanceType = $("#chanceType").val();
		//				var i = 1;
		//				var colId = "";
		//				var colName = "";
		//				$("#chanceListGrid_hTable").children("thead").children("tr")
		//						.children("th").each(function() {
		//							if ($(this).css("display") != "none"
		//									&& $(this).attr("colId") != undefined) {
		//								colName += $(this).children("div").html() + ",";
		//								colId += $(this).attr("colId") + ",";
		//								i++;
		//							}
		//						})
		//				window
		//						.open("?model=projectmanagent_chance_chance&action=exportExcel&colId="
		//								+ colId
		//								+ "&colName="
		//								+ colName
		//								+ "&status="
		//								+ status
		//								+ "&chanceType="
		//								+ chanceType
		//								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
		//			}
		//		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_chance_goods&action=timingPageJson&timingDate='
					+ $("#hideDT").val(),// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'chanceId',// 传递给后台的参数名称
				colId : 'oldId'// 获取主表行数据的列名称

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
				width : 80
			}]
		},
		buttonsEx : buttonsArr,
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
		}, {
			text : '商机盈率',
			key : 'winRate',
			datacode : 'SJYL'

		//}, {
		//	text : '商机阶段',
		//	key : 'chanceStage',
		//	datacode : 'SJJD'
		}],
		//扩展右键菜单
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