var show_page = function(page) {
	$("#allregisterGrid").yxgrid("reload");
};

$(function() {
    var paramData={};
    if($("#projectId").val()>0){
        paramData={
            'ExaStatusArr' : "部门审批,完成,打回",
            'projectId':$("#projectId").val()
        };
    }else{
        paramData={
            'ExaStatusArr' : "部门审批,完成,打回"
        };
    }
	$("#allregisterGrid").yxgrid({
		model: 'outsourcing_vehicle_allregister',
		action : 'messageJson',
		param : paramData,
		title: '用车信息（汇总）',
		bodyAlign : 'center',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,

		//列信息
		colModel: [{
			name: 'allRegisterId',
			display: '租车登记汇总ID',
			sortable: true,
			hide: true
		},{
			name: 'registerId',
			display: '租车登记ID',
			sortable: true,
			hide: true
		},{
			name: 'state',
			display: '状态',
			sortable: true,
			width : 60,
			process : function (v) {
				switch (v) {
					case '0' : return '未提交';break;
					case '1' : return '未审批';break;
					case '2' : return '审批完成';break;
					case '3' : return '打回';break;
					default : return '';
				}
			}
		},{
			name: 'useCarDate',
			display: '用车时间',
			sortable: true,
			process : function (v) {
				return v.substr(0, 7);
			},
			width : 60
		},{
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width : 200
		},{
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width : 200
		},{
			name: 'projectType',
			display: '项目类型',
			sortable: true,
			width : 70
		},{
            name: 'projectManager',
            display: '项目经理',
            sortable: true,
            width : 80
        },{
			name: 'officeName',
			display: '区  域',
			sortable: true,
			width : 70
		},{
			name: 'province',
			display: '省  份',
			sortable: true,
			width : 70
		},{
			name: 'city',
			display: '地  市',
			sortable: true,
			width : 70
		},{
			name: 'suppName',
			display: '供应商名称',
			sortable: true,
			width : 120
		},{
			name: 'rentalContractCode',
			display: '租车合同编号',
			sortable: true,
			width : 120
		},{
            name: 'contractType',
            display: '合同类型',
            sortable: true,
            width :80
        },{
			name: 'carNum',
			display: '车牌号',
			sortable: true,
			width : 100
		},{
			name: 'rentalPropertyCode',
			display: '租车性质',
			sortable: true,
			width : 80,
			hide : true
		},{
			name: 'UseDay',
			display: '合同用车天数',
			sortable: true,
			width : 80,
			process : function(v ,row) {
				if (row.rentalPropertyCode == 'ZCXZ-01') {
					return row.contractUseDay;
				}
				return row.registerNum;
			}
		},{
			name: 'registerNum',
			display: '实际用车天数',
			sortable: true,
			width : 80
		},{
			name: 'startMileage',
			display: '本月初里程（公里）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 120
		},{
			name: 'endMileage',
			display: '本月末里程（公里）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 120
		},{
			name: 'effectMileage',
			display: '有效里程（公里）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 120
		},{
			name: 'gasolineKMPrice',
			display: '按公里计价油费单价（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 160
		},{
			name: 'gasolineKMCost',
			display: '按公里计价油费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 140
		},{
			name: 'reimbursedFuel',
			display: '实报实销油费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			},
			width : 140
		},{
			name: 'rentalCarCost',
			display: '租车费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'parkingCost',
			display: '停车费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'tollCost',
			display: '路桥费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'mealsCost',
			display: '餐饮费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'accommodationCost',
			display: '住宿费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'overtimePay',
			display: '加班费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'specialGas',
			display: '特殊油费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name: 'allCost',
			display: '总费用（元）',
			sortable: true,
			process : function (v ,row) {
				var sum = parseFloat(row.rentalCarCost ? row.rentalCarCost : 0)
						+ parseFloat(row.reimbursedFuel ? row.reimbursedFuel : 0)
						+ parseFloat(row.gasolineKMCost ? row.gasolineKMCost : 0)
						+ parseFloat(row.parkingCost ? row.parkingCost : 0)
						+ parseFloat(row.tollCost ? row.tollCost : 0)
						+ parseFloat(row.mealsCost ? row.mealsCost : 0)
						+ parseFloat(row.accommodationCost ? row.accommodationCost : 0)
						+ parseFloat(row.overtimePay ? row.overtimePay : 0)
						+ parseFloat(row.specialGas ? row.specialGas : 0);
				return moneyFormat2(sum ,2);
			}
		},{
			name: 'effectLogTime',
			display: '有效LOG时长',
			sortable: true
		},{
			name: 'estimate',
			display: '评价',
			sortable: true,
			width : 350,
			align : 'left'
		},{
			name: 'remark',
			display: '备注',
			sortable: true,
			width : 600,
			align : 'left'
		}],

		lockCol : ['useCarDate','projectCode'], //锁定的列名

		//扩展菜单
		buttonsEx : [{
			name : 'exportOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_vehicle_allregister&action=toExcelOutMessage"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		}],

		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toRecord&id=" + get['allRegisterId'] + "&registerId=" + get['registerId'],'1');
				}
			}
		},

		comboEx : [{
			text: "状态",
			key: 'state',
			data : [{
				text : '未审批',
				value : '1'
			},{
				text : '审批完成',
				value : '2'
			},{
				text : '打回',
				value : '3'
			}]
		}],

		searchitems: [{
			display: "用车时间",
			name: 'useCarDateSea'
		},{
			display: "项目编号",
			name: 'projectCodeSea'
		},{
			display: "项目名称",
			name: 'projectNameSea'
		},{
			display: "区  域",
			name: 'officeNameSea'
		},{
			display: "省  份",
			name: 'actualUseDaySea'
		},{
			display: "地  市",
			name: 'actualUseDaySea'
		},{
			display: "供应商名称",
			name: 'suppNameSea'
		},{
			display: "租车合同编号",
			name: 'rentalContractCodeSea'
		},{
			display: "评  价",
			name: 'estimate'
		},{
			display: "备  注",
			name: 'remark'
		}]
	});

});