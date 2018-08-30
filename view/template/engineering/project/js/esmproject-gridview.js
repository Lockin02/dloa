var show_page = function() {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {
	$("#esmprojectGrid").yxgrid({
		model: 'engineering_project_esmproject',
		title: '项目汇总表',
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		isEditAction: false,
		showcheckbox: false,
		customCode: 'esmprojectGrid',
		isOpButton: false,
		param: {
			"officeName": $("#officeName").val(),
			"province": $("#province").val(),
			"beginDateSearch": $("#beginDateSearch").val(),
			"endDateSearch": $("#endDateSearch").val(),
			"status": $("#status").val(),
			"nature": $("#nature").val(),
			"contractTypeMix": $("#contractTypeMix").val(),
			"productLine": $("#productLine").val()
		},
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width: 140,
			process: function(v, row) {
				return (row.contractId == "0" || row.contractId == "") && row.contractType != 'GCXMYD-04' ? "<span style='color:blue' title='未关联合同号的项目'>" + v + "</span>" : v;
			}
		}, {
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width: 120,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
			}
		}, {
			name: 'exgross',
			display: '毛利率',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				if (v * 1 >= 0) {
					return v + " %";
				} else {
					return "<span class='red'>" + v + " %</span>";
				}
			},
			width: 60
		}, {
			name: 'budgetAll',
			display: '总预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeAllCount',
			display: '总决算(实时)',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 70
		}, {
			name: 'feeAllProcessCount',
			display: '费用进度(实时)',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 80
		}, {
			name: 'projectProcess',
			display: '工程进度',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 60
		}, {
			name: 'processDiff',
			display: '进度差异',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 60
		}, {
			name: 'planBeginDate',
			display: '预计开始',
			sortable: true,
			width: 80
		}, {
			name: 'planEndDate',
			display: '预计结束',
			sortable: true,
			width: 80
		}, {
			name: 'officeId',
			display: '区域ID',
			sortable: true,
			hide: true
		}, {
			name: 'officeName',
			display: '区域',
			sortable: true,
			width: 70
		}, {
			name: 'country',
			display: '国家',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'province',
			display: '省份',
			sortable: true,
			width: 70
		}, {
			name: 'city',
			display: '城市',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'attributeName',
			display: '项目属性',
			width: 70,
			process: function(v, row) {
				switch (row.attribute) {
					case 'GCXMSS-01' :
						return "<span class='red'>" + v + "</span>";
						break;
					case 'GCXMSS-02' :
						return "<span class='blue'>" + v + "</span>";
						break;
					case 'GCXMSS-03' :
						return "<span class='green'>" + v + "</span>";
						break;
					default :
						return v;
				}
			}
		}, {
			name: 'status',
			display: '项目状态',
			sortable: true,
			datacode: 'GCXMZT',
			width: 60
		}, {
			name: 'budgetField',
			display: '现场预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetPerson',
			display: '人力预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetEqu',
			display: '设备预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetOutsourcing',
			display: '外包预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetOther',
			display: '其他预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeAll',
			display: '总决算(财务确认)',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			hide: true
		}, {
			name: 'feeFieldCount',
			display: '现场决算(实时)',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feePerson',
			display: '人力决算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeEqu',
			display: '设备决算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeOutsourcing',
			display: '外包决算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeOther',
			display: '其他决算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeAllProcess',
			display: '费用进度(财务确认)',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			hide: true
		}, {
			name: 'feeFieldProcessCount',
			display: '现场费用进度(实时)',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 110
		}, {
			name: 'contractTypeName',
			display: '源单类型',
			sortable: true,
			hide: true
		}, {
			name: 'contractId',
			display: '鼎利合同id',
			sortable: true,
			hide: true
		}, {
			name: 'contractCode',
			display: '鼎利合同编号(源单编号)',
			sortable: true,
			width: 160,
			hide: true
		}, {
			name: 'contractTempCode',
			display: '临时合同编号',
			sortable: true,
			width: 160,
			hide: true
		}, {
			name: 'rObjCode',
			display: '业务编号',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'contractMoney',
			display: '合同金额',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'customerId',
			display: '客户id',
			sortable: true,
			hide: true
		}, {
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			hide: true
		}, {
			name: 'depName',
			display: '所属部门',
			sortable: true,
			hide: true
		}, {
			name: 'actBeginDate',
			display: '实际开始',
			sortable: true,
			width: 80
		}, {
			name: 'actEndDate',
			display: '实际完成',
			sortable: true,
			width: 80
		}, {
			name: 'managerName',
			display: '项目经理',
			sortable: true
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 70
		}, {
			name: 'ExaDT',
			display: '审批日期',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'peopleNumber',
			display: '总人数',
			sortable: true,
			width: 70
		}, {
			name: 'natureName',
			display: '性质1',
			sortable: true
		}, {
			name: 'nature2Name',
			display: '性质2',
			sortable: true
		}, {
			name: 'outsourcingName',
			display: '外包类型',
			sortable: true,
			width: 80
		}, {
			name: 'cycleName',
			display: '长/短期',
			sortable: true,
			width: 80
		}, {
			name: 'categoryName',
			display: '项目类别',
			sortable: true,
			width: 80
		}, {
			name: 'platformName',
			display: '方案及平台',
			sortable: true,
			width: 80
		}, {
			name: 'netName',
			display: '网络',
			sortable: true,
			width: 80
		}, {
			name: 'createTypeName',
			display: '建立方式',
			sortable: true,
			width: 80
		}, {
			name: 'signTypeName',
			display: '签订方式',
			sortable: true,
			width: 80
		}, {
			name: 'toolType',
			display: '工具类型',
			sortable: true,
			width: 80
		}, {
			name: 'updateTime',
			display: '最近更新',
			sortable: true,
			width: 120
		}],
		lockCol: ['projectName', 'projectCode'],//锁定的列名
		searchitems: [{
			display: '办事处',
			name: 'officeName'
		}, {
			display: '项目编号',
			name: 'projectCodeSearch'
		}, {
			display: '项目名称',
			name: 'projectName'
		}, {
			display: '项目经理',
			name: 'managerName'
		}, {
			display: '业务编号',
			name: 'rObjCodeSearch'
		}, {
			display: '鼎利合同号',
			name: 'contractCodeSearch'
		}, {
			display: '临时合同号',
			name: 'contractTempCodeSearch'
		}],
		// 审批状态数据过滤
		comboEx: [{
			text: "审批状态",
			key: 'ExaStatus',
			type: 'workFlow'
		}, {
			text: "项目状态",
			key: 'status',
			datacode: 'GCXMZT'
		}],
		// 默认搜索字段名
		sortname: "c.updateTime",
		// 默认搜索顺序 降序
		sortorder: "DESC"
	});
});