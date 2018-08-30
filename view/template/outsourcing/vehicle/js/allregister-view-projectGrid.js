var show_page = function(page) {
	$("#allregisterGrid").yxgrid("reload");
};

$(function() {
	$("#allregisterGrid").yxgrid({
		model: 'outsourcing_vehicle_allregister',
		param : {
			'projectId' : $("#projectId").val()
		},
		title: '租车登记汇总',
		bodyAlign : 'center',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
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
					case '1' : return '审批中';break;
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
			name: 'projectManager',
			display: '项目经理',
			sortable: true,
			width : 160
		},{
			name: 'actualUseDay',
			display: '实际用车天数',
			sortable: true,
			width : 80
		},{
			name: 'effectMileage',
			display: '有效里程',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'rentalCarCost',
			display: '租车费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'reimbursedFuel',
			display : '实报实销油费（元）',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMCost',
			display : '按公里计价油费（元）',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'parkingCost',
			display: '停车费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'tollCost',
			display: '路桥费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'mealsCost',
			display: '餐饮费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'accommodationCost',
			display: '住宿费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'overtimePay',
			display: '加班费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name: 'specialGas',
			display: '特殊油费（元）',
			sortable: true,
			process : function (v) {
				return moneyFormat2(v ,2);
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
		}],

		menusEx : [{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_allregister&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: "状态",
			key: 'state',
			data : [{
				text : '未提交',
				value : '0'
			},{
				text : '审批中',
				value : '1'
			},{
				text : '审批完成',
				value : '2'
			},{
				text : '打回',
				value : '3'
			}]
		}],

		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
		searchitems: [{
			display: "用车时间",
			name: 'useCarDateSea'
		},{
			display: "实际用车天数",
			name: 'actualUseDaySea'
		}]
	});

});