var show_page = function(page) {
	$("#esmmemberGrid").yxsubgrid("reload");
};


//项目查看方法
//function viewProject(id) {
//	var skey = "";
//	$.ajax({
//		type: "POST",
//		url: "?model=engineering_project_esmproject&action=md5RowAjax",
//		data: { "id" : id },
//		async: false,
//		success: function(data){
//			skey = data;
//		}
//	});
//	showModalWin("?model=engineering_project_esmproject&action=viewTab&id="
//		+ id + "&skey=" + skey
//	);
//}

$(function() {
	$("#esmmemberGrid").yxsubgrid({
		model : 'engineering_member_esmmember',
		action : 'pageJsonCostMoney',
		title : '个人项目费用信息',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		noCheckIdValue : 'noId',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				width : 140
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 160
			}, {
				name : 'statusName',
				display : '项目状态',
				sortable : true,
				width : 80
			}, {
				name : 'memberId',
				display : '成员id',
				width : 80,
				hide : true
			}, {
				name : 'costMoney',
				display : '录入费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unconfirmMoney',
				display : '未确认费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'confirmMoney',
				display : '已确认费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'backMoney',
				display : '打回费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unexpenseMoney',
				display : '未报销费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expensingMoney',
				display : '在报销费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expenseMoney',
				display : '已报销费用',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}
		],
		// 主从表格设置
		subGridOptions : {
			url : '?model=engineering_cost_esmcostdetail&action=pageJsonCostMoney',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
					paramId : 'createId',// 传递给后台的参数名称
					colId : 'memberId'// 获取主表行数据的列名称
				},{
					paramId : 'projectId',// 传递给后台的参数名称
					colId : 'projectId'// 获取主表行数据的列名称
				}
			],
			subgridcheck : true,
			// 显示的列
			colModel : [{
					name : 'executionDate',
					display : '日志录入日期'
				},{
					name : 'costMoney',
					display : '录入费用',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'unconfirmMoney',
					display : '未确认费用',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'confirmMoney',
					display : '已确认费用',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'backMoney',
					display : '打回费用',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'unexpenseMoney',
					display : '未报销费用',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'expensingMoney',
					display : '在报销费用',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}, {
					name : 'expenseMoney',
					display : '已报销费用',
					width : 80,
					process : function(v){
						return moneyFormat2(v);
					}
				}
			]
		},
//		buttonsEx : [{
//			name : 'add',
//			text : "生成报销单",
//			icon : 'add',
//			action : function(row, rows, rowIds, g) {
//				if (row) {
//					//所有行
//					var allRows = g.getAllSubSelectRowDatas();
//
//					var dateArr = [];//选择的报销单日期
//					var projectId = '';//项目id
//					var sameProject = true;
//					var canExpense = 0;
//
////					$.showDump(allRows)
//					//循环载入选择日期
//					for(var i=0; i< allRows.length ; i++){
//						dateArr.push(allRows[i].executionDate);
//
//						//项目id处理
//						if(projectId == ''){
//							projectId = allRows[i].projectId;
//						}else{
//							if(projectId != allRows[i].projectId){
//								sameProject = false;
//							}
//						}
//
//						canExpense = accAdd(canExpense,allRows[i].unexpenseMoney,2);
//					}
//
//					//如果存在不同的项目，则报错
//					if(sameProject == false){
//						alert('不同的项目费用不能生成一张报销单，请重新选择！');
//						return false;
//					}
//
//					//如果可用金额为0，则报错
//					if(canExpense*1 == 0){
//						alert('可用报销金额为0，不能生成报销单！');
//						return false;
//					}
//
//					//报销单生成页面
//					if(dateArr.length > 0){
//						showModalWin("?model=finance_expense_expense&action=toEsmExpenseAdd&days="
//							+ dateArr.toString()
//							+ "&projectId="
//							+ projectId
//						);
//					}
//				} else {
//					alert('请先选择记录');
//				}
//			}
//		}],
//		menusEx : [{
//			name : 'viewProject',
//			text : "查看项目",
//			icon : 'view',
//			action : function(row){
//				viewProject(row.projectId);
//			}
//		}],
		menusEx : [{
			text : '详细报销',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=engineering_cost_esmcostdetail&action=costDetail&projectId=" + row.projectId,1,row.projectId);
			}
		}],
		
		// 审批状态数据过滤
		comboEx : [{
			text: "项目状态",
			key: 'pstatus',
			datacode : 'GCXMZT',
			value : 'GCXMZT02'
		}],
		searchitems : [{
			display : "项目名称",
			name : 'projectNameSearch'
		},{
			display : "项目编号",
			name : 'projectCodeSearch'
		}],
		sortorder : 'DESC',
		sortname : 'p.updateTime'
	});
});