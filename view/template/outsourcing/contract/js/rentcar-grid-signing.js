var show_page = function(page) {
	$("#rentcarGrid").yxgrid("reload");
};

$(function() {
	$("#rentcarGrid").yxgrid({
		model : 'outsourcing_contract_rentcar',
		param : { "signedStatus" : '0','statusArr' : '2,3,4' },
		title : '未签收的租车合同',
		bodyAlign : 'center',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'createDate',
			display: '录入日期',
			sortable: true,
			width : 70
		},{
			name: 'orderCode',
			display: '鼎利合同编号',
			sortable: true,
			width : 130,
			process : function(v,row){
				if (row.status == 4) {
					return "<a href='#' style='color:red' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				} else{
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				}
			}
		},{
			name: 'contractNature',
			display: '合同性质',
			sortable: true,
			width : 75
		},{
			name: 'contractType',
			display: '合同类型',
			sortable: true,
			width : 75
		},{
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'orderName',
			display: '合同名称',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'signCompany',
			display: '签约公司',
			sortable: true,
			width : 150,
			align : 'left'
		},{
			name: 'companyProvince',
			display: '公司省份',
			sortable: true,
			width : 70
		},{
			name: 'ownCompany',
			display: '归属公司',
			sortable: true,
			width : 80
		},{
			name: 'linkman',
			display: '联系人',
			sortable: true,
			hide : true,
			width : 60
		},{
			name: 'phone',
			display: '联系电话',
			sortable: true,
			hide : true,
			width : 85
		},{
			name: 'address',
			display: '联系地址',
			sortable: true,
			hide : true,
			width : 150,
			align : 'left'
		},{
			name: 'signDate',
			display: '签约日期',
			sortable: true,
			width : 70
		},{
//			name: 'payedMoney',
//			display: '已付金额',
//			sortable: true,
//			process : function(v) {
//				return moneyFormat2(v);
//			},
//			align : 'left'
//		},{
			name: 'orderMoney',
			display: '合同金额',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
            name: 'contractStartDate',
            display: '合同开始日期',
            sortable: true,
            width : 75
        },{
            name: 'contractEndDate',
            display: '合同结束日期',
            sortable: true,
            width : 75
        },{
            name: 'rentUnitPrice',
            display: '租赁费用(元/月/辆)',
            sortable: true,
            width : 100,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        },{
            name: 'fuelCharge',
            display: '燃油费(元/公里)',
            sortable: true,
            width : 85,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        },{
			name: 'returnMoney',
			display: '返款金额',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
			name: 'status',
			display: '状态',
			sortable: true,
			width : 70,
			process : function(v,row){
				if(v == 0){
					return "未提交";
				}else if(v == 1){
					return "审批中";
				}else if(v == 2){
					return "执行中";
				}else if(v == 3){
					return "已关闭";
				}else if(v == 4){
					return "变更中";
				}
			}
		},{
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width : 70
		},{
			name: 'signedStatus',
			display: '合同签收',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '未签收';
				}else {
					return '已签收';
				}
			}
		},{
			name: 'objCode',
			display: '业务编号',
			sortable: true
		},{
			name: 'isNeedStamp',
			display: '是否需要盖章',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '否';
				}else {
					return '是';
				}
			}
		},{
			name: 'isStamp',
			display: '是否已盖章',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '否';
				}else {
					return '是';
				}
			}
		},{
			name: 'stampType',
			display: '盖章类型',
			sortable: true,
			width : 150,
			align : 'left'
		},{
//			name: 'rentalcarCode',
//			display: '租车申请Code',
//			sortable: true
//		},{
//			name: 'rentUnitPrice',
//			display: '租赁单价（元/月/辆）',
//			sortable: true
//		},{
//			name: 'oilPrice',
//			display: '油价',
//			sortable: true
//		},{
//			name: 'fuelCharge',
//			display: '燃油费单价',
//			sortable: true
//		},{
			name: 'createName',
			display: '申请人',
			sortable: true,
			width : 80
		},{
			name: 'updateTime',
			display: '更新时间',
			sortable: true,
			width : 120
		}],

		menusEx : [{
			text : '签收合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '4') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=outsourcing_contract_rentcar&action=toSign&id="
					+ row.id
					+ "&skey=" + row.skey_
				);
			}
		}],

		comboEx : [{
			text: "状态",
			key: 'status',
			data : [{
				text : '执行中',
				value : '2'
			},{
				text : '已关闭',
				value : '3'
			},{
				text : '变更中',
				value : '4'
			}]
		},{
			text: "合同性质",
			key: 'contractNatureCode',
			datacode : 'ZCHTXZ'
		},{
			text: "合同类型",
			key: 'contractTypeCode',
			datacode : 'ZCHTLX'
		}],

		// 主从表格设置
		subGridOptions: {
			url: '?model=outsourcing_contract_NULL&action=pageItemJson',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'XXX',
				display: '从表字段'
			}]
		},

		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_contract_rentcar&action=viewTab&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems: [{
			display: "录入日期",
			name: 'createTimeSea'
		},{
			display: "鼎利合同编号",
			name: 'orderCode'
		},{
			display: "项目名称",
			name: 'projectName'
		},{
			display: "项目编号",
			name: 'projectCode'
		},{
			display: "合同名称",
			name: 'orderName'
		},{
			display: "签约公司",
			name: 'signCompany'
		},{
			display: "签约日期",
			name: 'signDateSea'
		}]
	});
});