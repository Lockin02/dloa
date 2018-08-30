var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};

/**
 *
 * @param useCarDate 用车日期
 * @param projectCode 项目编号
 * @param suppCode 供应商编码
 * @param suppName 供应商
 * @param carNum 车牌号码
 * @returns {boolean}
 */
var chkExistRecords = function(useCarDate,projectCode,suppCode,suppName,carNum){
	useCarMonth = (useCarDate != '')? useCarDate.substr(0,7) : '';// 对应月份

	var chkResult = $.ajax({
		type : "POST",
		url : "?model=outsourcing_vehicle_register&action=ajaxChkRentCarRecord",
		data : {
			useCarMonth : useCarMonth,
			projectCode : projectCode,
			suppCode : suppCode,
			carNum : carNum
		},
		async: false
	}).responseText;

	if(chkResult == 'false' || chkResult == ''){
		return true;
	}else{
		alert("此 "+useCarMonth+" 月份内, 已生成项目为【"+projectCode+"】,供应商为【"+suppName+"】且车牌号为 【"+carNum+"】的登记汇总信息（审批状态为审批中或完成）, 不允许再填报与此相关的记录, 请与项目经理沟通解决。");
		return false;
	}
};

$(function() {
	$("#registerGrid").yxgrid({
		model : 'outsourcing_vehicle_register',
		param : {
			'createId' : $("#createId").val()
		},
		title : '租车登记表',
		bodyAlign : 'center',
		isOpButton : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'state',
			display : '状态',
			sortable : true,
			width : 60,
			process : function (v) {
				switch (v) {
					case '0' : return '保存';break;
					case '1' : return '已提交';break;
					case '2' : return '打回';break;
					default : return '';
				}
			}
		},{
			name : 'driverName',
			display : '司机姓名',
			sortable : true,
			width : 70
		},{
			name : 'createName',
			display : '录入人',
			sortable : true,
			width : 80
		},{
			name : 'createTime',
			display : '录入时间',
			sortable : true,
			width : 120
		},{
			name : 'useCarDate',
			display : '用车日期',
			sortable : true,
			width : 80
		},{
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 200
		},{
			name : 'province',
			display : '省份',
			sortable : true,
			width : 80
		},{
			name : 'city',
			display : '城市',
			sortable : true,
			width : 80
		},{
			name : 'carNum',
			display : '车牌',
			sortable : true,
			width : 80
		},{
			name : 'carModel',
			display : '车型',
			sortable : true,
			width : 100
		},{
			name : 'startMileage',
			display : '开始里程',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'endMileage',
			display : '结束里程',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'effectMileage',
			display : '有效里程',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolinePrice',
			display : '油价（元/升）',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMPrice',
			display : '按公里计价油费单价（元）',
			sortable : true,
			width : 150,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'reimbursedFuel',
			display : '实报实销油费（元）',
			sortable : true,
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMCost',
			display : '按公里计价油费（元）',
			sortable : true,
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'parkingCost',
			display : '停车费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'tollCost',
			display : '路桥费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'rentalCarCost',
			display : '租车费（元）',
			sortable : true,
			process : function (v ,row) {
				if (row.rentalPropertyCode == 'ZCXZ-02') {
					return moneyFormat2(row.shortRent ,2);
				} else {
					return '';
				}
			}
		},{
			name : 'mealsCost',
			display : '餐饮费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'accommodationCost',
			display : '住宿费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'overtimePay',
			display : '加班费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'specialGas',
			display : '特殊油费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'effectLogTime',
			display : '有效LOG时长',
			sortable : true
		}],

		buttonsEx : [{
			name : 'batchSub',
			text : "批量提交",
			icon : 'add batchSubBtn',
			action : function (row,rows,idArr) {
				$(".batchSubBtn").css("background","url(./js/jquery/images/grid/load.gif) no-repeat 1px");
				setTimeout(function(){
					var disPassNum = 0;
					if(rows){
						$.each(rows,function(i,item){
							if(item.state == 1){
								disPassNum += 1;
							}
						});
					}else{
						alert("至少选择一条记录。");
						$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
						return false;
					}

					if(disPassNum > 0){
						alert("提交记录中含有已提交记录,请检查后重试。");
						$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
					}else{
						var idStr = idArr.toString();
						var chkResult = $.ajax({
							type: "POST",
							url: "?model=outsourcing_vehicle_register&action=isCanBatchAdd",
							data: {
								'ids' : idStr
							},
							async: false
						}).responseText;
						chkResult = eval("("+chkResult+")");
						if(chkResult.error == '1'){
							alert(chkResult.msg);
							$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
						}else{
							$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
							if (window.confirm(("确定要提交勾选的记录吗?\n[注意: 如果提交的数据过多的话可能会需要较长的时间,请耐心等待,不要关闭当前页面!]"))) {
								$(".batchSubBtn").css("background","url(./js/jquery/images/grid/load.gif) no-repeat 1px");
								$.ajax({
									type: "POST",
									url: "?model=outsourcing_vehicle_register&action=ajaxBatchSubmit",
									data: {'ids' : idStr },
									success : function(msg) {
										if (msg == 1) {
											alert('提交成功！');
										}else {
											alert('提交失败！');
										}
										$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
										$("#registerGrid").yxgrid("reload");
									}
								});
							}
						}
					}
				},200);
			}
		},{
			name : 'excelIn',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showModalWin('?model=outsourcing_vehicle_register&action=toExcelIn');
			}
		},{
			name : 'excelOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin('?model=outsourcing_vehicle_register&action=toExcelOut'
					+ '&createId=' + $("#createId").val()
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800');
			}
		}],

		menusEx : [{
			text : "提交",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '0' || row.state == '2') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				var isCanAdd = $.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_register&action=isCanAdd",
						data: {
							'projectId' : row.projectId,
							'useCarDate' : row.useCarDate,
							'carNum' : row.carNum
						},
						async: false
					}).responseText;

				if (isCanAdd == 0) {
					alert('项目上该车牌已存在用车日期为' + row.useCarDate + '的记录');
					return false;
				}else if(!chkExistRecords(row.useCarDate,row.projectCode,row.suppCode,row.suppName,row.carNum)){
					return false;
				}

				if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_register&action=ajaxSubmit",
						data: {'id' : row.id },
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
							}else {
								alert('提交失败！');
							}
							$("#registerGrid").yxgrid("reload");
						}
					});
				}
			}
        },{
			text : "变更",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == '1') {
					var tmp = $.ajax({
									type: "POST",
									url: "?model=outsourcing_vehicle_register&action=isChange",
									data: {'allregisterId' : row.allregisterId },
									async: false,
									success : function(msg) {
									}
								}).responseText;

					if (tmp == 1) {
						return true;
					}
					return false;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showModalWin('?model=outsourcing_vehicle_register&action=toChange&id=' + row.id);
			}
        },{
			text : "变更原因",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('?model=outsourcing_vehicle_register&action=toChangeReason&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=780&width=1000");
			}
		},{
			name : 'view',
			text : "操作日志",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcing_register"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],

		comboEx : [{
			text: "状态",
			key: 'state',
			data : [{
				text : '保存',
				value : '0'
			},{
				text : '已提交',
				value : '1'
			},{
				text : '打回',
				value : '2'
			}]
		}],

		toAddConfig : {
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_vehicle_register&action=toAdd");
			}
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.state == '0' || row.state == '2') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_register&action=toEdit&id=" + get[p.keyField],'1');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_register&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if (row.state == '0' || row.state == '2') {
					return true;
				}
				return false;
			},
			toDelFn : function(p ,g) {
				var rowIds = g.getCheckedRowIds();
				var rowObjs = g.getCheckedRows();
				for (var i = 0 ;i < rowObjs.length ;i++) {
					if (rowObjs[i].state == '1') {
						var rowNum = $("#row" + rowObjs[i].id).children().eq(1).text();
						alert('第' + rowNum + '行的数据不能删除！（已提交的数据不能删除）');
						return false;
					}
				}
				if (rowIds[0]) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_vehicle_register&action=ajaxdeletes",
							data : {
								id : g.getCheckedRowIds().toString()
							},
							success : function(msg) {
								if (msg == 1) {
									g.reload();
									alert('删除成功！');
								} else {
									alert('删除失败!');
								}
							}
						});
					}
				} else {
					alert('请选择一行记录！');
				}
			}
		},

		searchitems : [{
			display : "司机姓名",
			name : 'driverNameSea'
		},{
			display : "录入人",
			name : 'createNameSea'
		},{
			display : "录入时间",
			name : 'createTimeSea'
		},{
			display : "用车日期",
			name : 'useCarDateSea'
		},{
			display : "项目名称",
			name : 'projectNameSea'
		},{
			display : "省份",
			name : 'provinceSea'
		},{
			display : "城市",
			name : 'citySea'
		}]
	});
});