var show_page = function(page) {
	$("#budgetbaseinfoGrid").yxgrid("reload");
};
$(function() {
	var flag = $("#flag").val();
	if (flag == "all") {
		var flagTF = true;
	} else {
		var flagTF = false;
	}
	$("#budgetTypeTree").yxtree({
		url : '?model=equipment_budget_budgetType&action=getTreeData',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var goodsbaseinfoGrid = $("#budgetbaseinfoGrid").data('yxgrid');
				goodsbaseinfoGrid.options.param['budgetTypeId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				goodsbaseinfoGrid.reload();
			}
		}
	});
	buttonsArr = [{
		name : 'add',
		text : "设备导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=equipment_budget_budgetbaseinfo&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	},{
		name : 'add',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";

			var budgetTypeId = $("#budgetbaseinfoGrid").data('yxgrid').options.param.budgetTypeId;

			for (var t in $("#budgetbaseinfoGrid").data('yxgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#budgetbaseinfoGrid").data('yxgrid').options.searchParam[t];
				}
			}
			var i = 1;
			var colId = "";
			var colName = "";
			$("#budgetbaseinfoGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
						if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined) {
							colName += $(this).children("div").html() + ",";
							colId += $(this).attr("colId") + ",";
							i++;
						}
					})
			var searchSql = $("#budgetbaseinfoGrid").data('yxgrid').getAdvSql()
			var searchArr = [];
			searchArr[0] = searchSql;
			searchArr[1] = searchConditionKey;
			searchArr[2] = searchConditionVal;
			window.open("?model=equipment_budget_budgetbaseinfo&action=exportExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&budgetTypeId="
							+ budgetTypeId
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal="
							+ searchConditionVal)
		}
	},{
		name : 'delete',
		text : "清空数据",
		icon : 'delete',
		action : function(row) {
				if (window.confirm("确认要清除数据吗?")) {
					if (window.confirm("清除后数据将无法恢复，再次确认要清除吗？")){
						  $.ajax({
							type : "POST",
							url : "?model=equipment_budget_budgetbaseinfo&action=ajaxEmptyData",
							data : {},
							success : function(msg) {
								if (msg == 1) {
									show_page();
									alert('操作成功！');
								} else {
									alert('操作失败!');
								}
							}
						});
					}
				}
			}
	}],
	$("#budgetbaseinfoGrid").yxgrid({
		model : 'equipment_budget_budgetbaseinfo',
		param : {
			goodsTypeId : -1
		},
		showcheckbox : false,
		isDelAction : false,
		isEditAction : flagTF,
		isAddAction : flagTF,
		title : '设备管理',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetTypeName',
			display : '所属分类',
			sortable : true,
			width : 100
		}, {
			name : 'equCode',
			display : '物料编号',
			sortable : true,
			width : 100
		}, {
			name : 'equName',
			display : '物料名称',
			sortable : true,
			width : 100
		}, {
			name : 'pattern',
			display : '规格型号',
			sortable : true,
			width : 100
		}, {
			name : 'brand',
			display : '品牌',
			sortable : true,
			width : 60
		}, {
			name : 'quotedPrice',
			display : '报价',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'useEndDate',
			display : '报价有效期',
			sortable : true
		}, {
			name : 'unitName',
			display : '单位',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}, {
			name : 'latestPrice',
			display : '最新采购价格',
			sortable : true,
			width : 80,
			process : function(v){
			   return v;
			}
		}, {
			name : 'useStatus',
			display : '是否启用',
			sortable : true,
			process:function(v){
			   if(v == '0' || v == ''){
			      return "关闭";
			   }else if(v == '1'){
			      return "启用";
			   }
			}
		}],
		menusEx : [
//		{
//			text : '配置编辑',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (flag == "all") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				showModalWin("?model=equipment_budget_deploy&action=toEditConfig&equId="
//						+ row.id
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=1150");
//			}
//		}, {
//			text : '配置预览',
//			icon : 'view',
//			action : function(row, rows, grid) {
//
//				showModalWin("?model=equipment_budget_deploy&action=toViewConfig&equId="
//						+ row.id
//						// + "&skey="
//						// + row['skey_']
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
//			}
//		}, {
//			text : '填写预算表',
//			icon : 'add',
//			action : function(row, rows, grid) {
//				showModalWin("?model=equipment_budget_budget&action=toAdd&equId="
//						+ row.id
//						// + "&skey="
//						// + row['skey_']
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
//			}
//		},
		{
			text : '启用',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.useStatus == "0" || row.useStatus == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认启用?")) {
					$.ajax({
						type : "POST",
						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '1'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('启用成功！');
							} else {
								alert('启用失败!');
							}
						}
					});
				}
			}
		}, {
			text : '停用',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.useStatus == "0" || row.useStatus == "") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认停用?")) {
					$.ajax({
						type : "POST",
						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '0'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('操作成功！');
							} else {
								alert('操作失败!');
							}
						}
					});
				}
			}
		}, {
			name : 'view',
			text : "操作日志",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_equ_budget_baseinfo"
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}
//		, {
//			text : '删除',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (flag == "all") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (window.confirm("确认要删除?")) {
//					$.ajax({
//						type : "POST",
//						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxdeletes",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								show_page();
//								alert('删除成功！');
//							} else {
//								alert('删除失败!');
//							}
//						}
//					});
//				}
//			}
//		}
		],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=equipment_budget_budgetbaseinfo&action=toAdd&parentName="
						+ $("#parentName").val()
						+ "&parentId="
						+ $("#parentId").val()

						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
			}
		},
		buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit',
			formHeight : 400,
			formWidth : 750
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 400,
			formWidth : 750
		},
		searchitems : [{
			display : "所属分类",
			name : 'budgetTypeName'
		}, {
			display : "物料名称",
			name : 'equName'
		}, {
			display : "物料编号",
			name : 'equCode'
		}]
	});
});