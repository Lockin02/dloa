var show_page = function (page) {
	$("#allregisterGrid").yxgrid("reload");
};

// 检验是否存在需要更新的金额
function chkOldCostUpdate(id,useCarDate){
	var dataList = $.ajax({
		type : "POST",
		url : '?model=outsourcing_vehicle_register&action=statisticsJson',
		data : {
			dir : 'ASC',
			allregisterId : id,
			useCarDateLimit : useCarDate
		},
		async : false
	}).responseText;
	var flag = true;
	if(dataList != ''){
		dataList = eval("("+dataList+")");
		if(dataList.length > 0){
			$.each(dataList,function(i,data){
				var payInfoMoney1 = (data.payInfoMoney1 > 0)? data.payInfoMoney1 : 0;
				var payInfoMoney2 = (data.payInfoMoney2 > 0)? data.payInfoMoney2 : 0;
				var realNeedPayCost1 = (data.realNeedPayCost1 > 0 && data.payInfoMoney1 != '-')? data.realNeedPayCost1 : 0;
				var realNeedPayCost2 = (data.realNeedPayCost2 > 0 && data.payInfoMoney2 != '-')? data.realNeedPayCost2 : 0;
				var allCost = Number(realNeedPayCost1) + Number(realNeedPayCost2);

				console.log("all: "+allCost);
				console.log("money1: "+payInfoMoney1);
				console.log("money2: "+payInfoMoney2);
				flag = (flag)? Number(allCost) == (Number(payInfoMoney1) + Number(payInfoMoney2)) : flag;
			});
		}
	}
	return flag;
}

$(function () {
	$("#allregisterGrid").yxgrid({
		model: 'outsourcing_vehicle_allregister',
		param: {
			'projectManagerIdSea': $("#userId").val()
		},
		title: '租车登记汇总',
		bodyAlign: 'center',
		showcheckbox: false,
		isAddAction: false,
		isDelAction: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'state',
			display: '状态',
			sortable: true,
			width: 60,
			process: function (v) {
				switch (v) {
				case '0':
					return '未提交';
					break;
				case '1':
					return '审批中';
					break;
				case '2':
					return '审批完成';
					break;
				case '3':
					return '打回';
					break;
				default:
					return '';
				}
			}
		}, {
			name: 'useCarDate',
			display: '用车时间',
			sortable: true,
			process: function (v) {
				return v.substr(0, 7);
			},
			width: 60
		}, {
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width: 200
		}, {
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width: 200
		}, {
			name: 'projectManager',
			display: '项目经理',
			sortable: true,
			width: 160
		}, {
			name: 'actualUseDay',
			display: '实际用车天数',
			sortable: true,
			width: 80
		}, {
			name: 'effectMileage',
			display: '有效里程',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'rentalCarCost',
			display: '租车费（元）',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'reimbursedFuel',
			display: '实报实销油费（元）',
			width: 120,
			type: 'statictext',
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'gasolineKMCost',
			display: '按公里计价油费（元）',
			width: 120,
			type: 'statictext',
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'parkingCost',
			display: '停车费（元）',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'tollCost',
			display: '路桥费（元）',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'mealsCost',
			display: '餐饮费（元）',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'accommodationCost',
			display: '住宿费（元）',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'overtimePay',
			display: '加班费（元）',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'specialGas',
			display: '特殊油费（元）',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'allCost',
			display: '总费用（元）',
			sortable: true,
			process: function (v, row) {
				var sum = parseFloat(row.rentalCarCost ? row.rentalCarCost : 0)
						+ parseFloat(row.reimbursedFuel ? row.reimbursedFuel : 0)
						+ parseFloat(row.gasolineKMCost ? row.gasolineKMCost : 0)
						+ parseFloat(row.parkingCost ? row.parkingCost : 0)
						+ parseFloat(row.tollCost ? row.tollCost : 0)
						+ parseFloat(row.mealsCost ? row.mealsCost : 0)
						+ parseFloat(row.accommodationCost ? row.accommodationCost : 0)
						+ parseFloat(row.overtimePay ? row.overtimePay : 0)
						+ parseFloat(row.specialGas ? row.specialGas : 0);
				return moneyFormat2(sum, 2);
			}
		}, {
			name: 'effectLogTime',
			display: '有效LOG时长',
			sortable: true
		}],

		buttonsEx: [{
			name: 'excelOut',
			text: "导出",
			icon: 'excel',
			action: function (row) {
				showThickboxWin("?model=outsourcing_vehicle_allregister&action=toExcelOut"
					+ "&userId=" + $("#userId").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=800");
			}
		}],

		menusEx: [{
			text: "提交",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == '0' || row.state == '3') {
					var nowData = new Date();
					var nowYear = nowData.getFullYear(); //获取年
					var nowMonth = nowData.getMonth() + 1; //获取月
					var year = parseInt(row.useCarDate.substr(0, 4));
					var month = parseInt(row.useCarDate.substr(5, 2));
					if (nowYear < year) {
						return false;
					} else if (nowMonth <= month &&nowYear == year) {
						return false;
					}
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				var useCarMonth = row.useCarDate;
				var chkresult = chkOldCostUpdate(row.id,useCarMonth.substr(0, 7));

				if(chkresult){
					var rs = $.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_allregister&action=isCanSubmit",
						data: {
							'id': row.id,
							'limitUseCarDate': useCarMonth.substr(0, 7)
						},
						async: false
					}).responseText;

					if (rs == 'true') {
						$.ajax({
							type: "POST",
							url: "?model=outsourcing_vehicle_rentalcar&action=getOfficeInfoForId",
							data: {
								'projectId': row.projectId
							},
							async: false,
							success: function (data) {
								if (data) {
									showThickboxWin('controller/outsourcing/vehicle/ewf_register.php?actTo=ewfSelect&billId=' + row.id
										+ "&billArea=" + data
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								} else {
									showThickboxWin('controller/outsourcing/vehicle/ewf_register.php?actTo=ewfSelect&billId=' + row.id
										+ '&billDept='
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}
							}
						});
					} else if (rs == 'false'){
						alert('部分记录没有关联的合同！');
					} else if(rs == "hasNoDone"){
						alert('含有未填报费用表的非款项合同记录，不能提交租车登记汇总审批。');
					}else{
						alert('提交条件验证有误,请重试或联系管理员！');
						// alert('费用超出项目预算！');
					}
				}else{
					alert('含有需要更新的支付金额,请更新相应的填报费用后再提交。');
				}
			}
		}, {
			text: "打回",
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.state == '0' || row.state == '3') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=outsourcing_vehicle_allregister&action=toBack&id=" + row.id);
				}
			}
		}, {
			text: "付款与报销",
			icon: 'add',
			showMenuFn: function (row) {
				// if (row.state == '2' && row.ExaStatus == '完成') {
				// 	return true;
				// }
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=outsourcing_vehicle_allregister&action=toPayment&id=" + row.id);
				}
			}
		}, {
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_allregister&pid=" + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx: [{
			text: "状态",
			key: 'state',
			data: [{
				text: '未提交',
				value: '0'
			}, {
				text: '审批中',
				value: '1'
			}, {
				text: '审批完成',
				value: '2'
			}, {
				text: '打回',
				value: '3'
			}]
		}],

		toEditConfig: {
			showMenuFn: function (row) {
				if (row.state == '0' || row.state == '3') {
					return true;
				}
				return false;
			},
			toEditFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toEdit&id=" + get[p.keyField], '1');
				}
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		searchitems: [{
			display: "用车时间",
			name: 'useCarDateSea'
		}, {
			display: "项目编号",
			name: 'projectCodeSea'
		}, {
			display: "项目名称",
			name: 'projectNameSea'
		}, {
			display: "项目经理",
			name: 'projectManagerSea'
		}, {
			display: "实际用车天数",
			name: 'actualUseDaySea'
		}]
	});

});