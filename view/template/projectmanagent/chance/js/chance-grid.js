var show_page = function(page) {
	$("#chanceGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [
//   {
//		text : "更新商机相关时间",
//		icon : 'add',
//		action : function(row) {
//			showThickboxWin("?model=projectmanagent_chance_chance&action=updateDateExcel"
//					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
//		}
//
//	},
	{
		text : "重置",
		icon : 'delete',
		action : function(row) {
			var listGrid = $("#chanceGrid").data('yxsubgrid');
			listGrid.options.extParam = {};
			$("#caseListWrap tr").attr('style',"background-color: rgb(255, 255, 255); ");
			listGrid.reload();
		}
	}, {
		name : 'view',
		text : "历史商机信息",
		icon : 'view',
		action : function(row) {
			// var url =
			// "?model=projectmanagent_chance_chance&action=chanceInfoList";
			// showModalDialog(url,
			// '',"dialogWidth:1000px;dialogHeight:600px;");
			showModalWin("?model=projectmanagent_chance_chance&action=chanceInfoList"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
		}
	},{
		// 导出EXCEL文件按钮
		name : 'output',
		text : "商机跟踪记录",
		icon : 'excel',
		action : function(row) {
			var chanceId = $('#chanceId').val();
			var i = 1;
			var colId = "";
			var colName = "";
			var status = $("#status").val();
			showThickboxWin("?model=projectmanagent_chance_chance&action=toOutputtExcel"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=270&width=500");

	}
}], SJDC = {
		name : 'export',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#chanceGrid").data('yxsubgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#chanceGrid").data('yxsubgrid').options.searchParam[t];
				}
			}
			var status = $("#status").val();
			var chanceType = $("#chanceType").val();
			var chanceLevel = $("#chanceLevel").val();
			var winRate = $("#winRate").val();
			var chanceStage = $("#chanceStage").val();
			var i = 1;
			var colId = "";
			var colName = "";
			$("#chanceGrid_hTable").children("thead").children("tr")
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
					.open("?model=projectmanagent_chance_chance&action=exportExcel&colId="
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
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal="
							+ searchConditionVal
							+ "&1width=200,height=200,top=200,left=200,resizable=yes")
		}
	}, importExcel = {
		text : "商机导入",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=projectmanagent_chance_chance&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	}, importGoodsExcel = {
		text : "商机产品导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=projectmanagent_chance_chance&action=toGoodsExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=getLimits',
		data : {
			'limitName' : '商机导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(importExcel);
				buttonsArr.push(importGoodsExcel);
			}
		}
	});
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
	$("#chanceGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceGridJson',
		title : '销售商机',
		leftLayout : true,
		customCode : 'chanceInfogrid',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
						+ data.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		lockCol:['flag'],//锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '沟通板',
			sortable : true,
			width : 40,
			process : function(v, row) {
			 if (row.id == "allMoney" || row.id == undefined) {
				 return "合计";
			 }
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
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
			name : 'chanceNatureName',
			display : '项目属性',
			sortable : true
//		}, {
//			name : 'chanceStage',
//			display : '商机阶段',
//			datacode : 'SJJD',
//			sortable : true,
//			process : function(v, row) {
//			    if(row.id == undefined){
//			    	return "";
//			    }
//					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=boostChanceStageInfo&id='
//							+ row.id
//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
//							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
//			}
		}, {
			name : 'winRate',
			display : '商机赢率(%)',
			datacode : 'SJYL',
			sortable : true,
			process : function(v, row) {
				if(row.id == undefined){
			    	return "";
			    }
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=winRateInfo&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'progress',
			display : '项目进展描述',
			sortable : true
		},{
		    name : 'goodsNameStr',
		    display : '产品内容',
		    width : 200,
		    sortable : false
		}, {
			name : 'predictContractDate',
			display : '预计合同签署日期',
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
			name : 'areaName',
			display : '归属区域',
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
			name : 'customerType',
			display : '客户类型',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'status',
			display : '商机状态',
			process : function(v,row) {
				if(row.id == undefined){
			    	return "";
			    }
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
			name : 'signSubject',
			display : '签约主体',
			sortable : true,
			datacode : 'QYZT',
			width : 60
		}, {
			name : 'boostTime',
//			display : '赢率/阶段更新时间',
			display : '赢率更新时间',
			sortable : true,
			width : 120
		}
//		, {
//			name : 'isTurn',
//			display : '是否转合同',
//			sortable : true,
//			width : 60,
//			process : function(v){
//			    if(v == '1'){
//			       return "√";
//			    }else {
//			       return "-";
//			    }
//			}
//		}
		, {
			name : 'contractCode',
			display : '合同号',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toContractViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}
//		, {
//			name : 'CExaStatus',
//			display : '合同审批状态',
//			sortable : true,
//			width : 60
//		}
		, {
			name : 'closeRegard',
			display : '关闭原因',
			sortable : true,
			width : 60
		}, {
			name : 'updateRecord',
			display : '商机更新记录',
			sortable : true,
			width : 400

		}],
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
			text : '商机赢率',
			key : 'winRate',
			datacode : 'SJYL'

//		}, {
//			text : '商机阶段',
//			key : 'chanceStage',
//			datacode : 'SJJD'
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
		// 高级搜索
		advSearchOptions : {
			modelName : 'chancegrid',
			// 选择字段后进行重置值操作
			selectFn : function($valInput) {
				$valInput.yxcombogrid_area("remove");
				$valInput.yxselect_user("remove");
			},
			searchConfig : [{
				name : '创建日期',
				value : 'c.createTime',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			},{
				name : '预计合同签署日期',
				value : 'c.predictContractDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			}, {
				name : '客户类型',
				value : 'c.customerType',
				type : 'select',
				datacode : 'KHLX'
			}, {
					name : '项目类型',
					value : 'c.chanceType',
					type : 'select',
					datacode : 'HTLX'
				}, {
					name : '销售项目属性',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-XSHT'
				}, {
					name : '服务项目属性',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-FWHT'
				}, {
					name : '租赁项目属性',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-ZLHT'
				}, {
					name : '研发项目属性',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-YFHT'
				}, {
				name : '商机状态',
				value : 'c.status',
				type : 'select',
				options : [{
					'dataName' : '跟踪中',
					'dataCode' : '5'
				}, {
					'dataName' : '暂停',
					'dataCode' : '6'
				}, {
					'dataName' : '关闭',
					'dataCode' : '3'
				}, {
					'dataName' : '已生成合同',
					'dataCode' : '4'
				}]
//			}, {
//				name : '商机阶段',
//				value : 'c.chanceStage',
//				type : 'select',
//				datacode : 'SJJD'
			}, {
				name : '商机赢率',
				value : 'c.winRate',
				type : 'select',
				datacode : 'SJYL'
			}, {
				name : '区域负责人',
				value : 'c.areaPrincipal',
				changeFn : function($t, $valInput, rowNum) {
					$valInput.yxcombogrid_area({
						hiddenId : 'areaPrincipalId' + rowNum,
						nameCol : 'areaPrincipal',
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}, {
				name : '商机负责人',
				value : 'c.prinvipalName',
				changeFn : function($t, $valInput, rowNum) {

					$valInput.yxselect_user({
						hiddenId : 'prinvipalId' + rowNum,
						nameCol : 'prinvipalName',
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}, {
				name : '省份',
				value : 'c.province'
			}, {
				name : '城市',
				value : 'c.city'
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
		sortname : "newUpdateDate",
		// 显示查看按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});
