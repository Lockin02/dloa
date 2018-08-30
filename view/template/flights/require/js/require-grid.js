var show_page = function(page) {
	$("#requireGrid").yxsubgrid("reload");
};

//编号日期高亮
function requireNoRed(v){
	var strArr = v.split('');
	var newStr = '';
	for(var i = 0;i < strArr.length ; i++){
		if(i == 4){
			newStr += "<span class='blue'>" + strArr[i];
		}else if(i == 11){
			newStr += strArr[i] + "</span>";
		}else{
			newStr += strArr[i];
		}
	}
	return newStr;
}

$(function() {
	$("#requireGrid").yxsubgrid({
		model: 'flights_require_require',
		title: '订票需求',
		showcheckbox : false,
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'requireNo',
			display: '订票需求号',
			sortable: true,
			width: 120,
			process : function(v){
				return requireNoRed(v);
			}
		},
		{
			name: 'requireId',
			display: '申请人ID',
			hide: true,
			sortable: true
		},
		{
			name: 'requireName',
			display: '申请人',
//			hide: true,
			sortable: true
		},
		{
			name: 'requireTime',
			display: '申请日期',
			sortable: true,
			hide: true,
			width : 70
		},
		{
			name: 'companyName',
			display: '所在公司',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'deptName',
			display: '所在部门',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'airName',
			display: '乘机人',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'airPhone',
			display: '手机号码',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'cardTypeName',
			display: '证件类型',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'birthDate',
			display: '出生日期',
			hide: true,
			sortable: true
		},
		{
			name: 'flyStartTime',
			display: '起始时段',
			hide: true,
			sortable: true
		},
		{
			name: 'flyEndTime',
			display: '结束时段',
			hide: true,
			sortable: true
		},
		{
			name: 'ticketType',
			display: '机票类型',
			sortable: true,
			process: function(v) {
				if (v == "10") {
					return '单程';
				} else if (v == "11") {
					return '往返';
				} else if (v == "12") {
					return '联程';
				}
			},
			width : 80
		},
		{
			name: 'startPlace',
			display: '出发城市',
			sortable: true,
			width : 80
		},
		{
			name: 'middlePlace',
			display: '中转城市',
			sortable: true,
			width : 80
		},
		{
			name: 'endPlace',
			display: '到达城市',
			sortable: true,
			width : 80
		},
		{
			name: 'startDate',
			display: '出发时间',
			sortable: true,
			width : 80
		},
		{
			name: 'twoDate',
			display: '第二天中转日期',
			sortable: true,
			width : 85
		},
		{
			name: 'comeDate',
			display: '返回时间',
			sortable: true,
			width : 80
		},
		{
			display: '订票状态',
			name: 'ticketMsg',
			sortable: true,
			process: function(v) {
				if (v == "0") {
					return '未生成';
				} else if (v == "1") {
					return ' 已生成';
				}
			},
			width : 70
		},
		{
			name: 'ExaStatus',
			display: '审批状态',
			width : 70
		},
		{
			name: 'ExaDT',
			display: '审批时间',
			sortable: true,
			width : 70
		},
		{
			name: 'detailType',
			display: '费用归属类型',
			sortable: true,
			process: function(v) {
				if (v == "1") {
					return '部门费用';
				} else if (v == "2") {
					return '工程项目费用';
				} else if (v == "3") {
					return '研发项目费用';
				} else if (v == "4") {
					return '售前费用';
				} else if (v == "5") {
					return '售后费用';
				}
			},
			hide: true,
			width : 80
		},
		{
			name: 'costBelongComId',
			display: '费用归属公司Id',
			hide: true,
			sortable: true
		},
		{
			name: 'costBelongCom',
			display: '费用归属公司',
			hide: true,
			sortable: true
		},
		{
			name: 'costBelongDeptId',
			display: '费用归属部门Id',
			hide: true,
			sortable: true
		},
		{
			name: 'costBelongDeptName',
			display: '费用归属部门',
			hide: true,
			sortable: true
		},
		{
			name: 'projectCode',
			display: '合同编号',
			hide: true,
			sortable: true
		},
		{
			name: 'projectId',
			display: '合同Id',
			hide: true,
			sortable: true
		},
		{
			name: 'projectName',
			display: '合同名称',
			hide: true,
			sortable: true
		},
		{
			name: 'proManagerName',
			display: '项目经理',
			hide: true,
			sortable: true
		},
		{
			name: 'contractCode',
			display: '合同编码',
			hide: true,
			sortable: true
		},
		{
			name: 'contractId',
			display: '合同Id',
			hide: true,
			sortable: true
		},
		{
			name: 'contractName',
			display: '合同名称',
			hide: true,
			sortable: true
		},
		{
			name: 'customerId',
			display: '客户ID',
			hide: true,
			sortable: true
		},
		{
			name: 'customerName',
			display: '客户名称',
			hide: true,
			sortable: true
		},
		{
			name: 'createName',
			display: '创建人',
			hide: true,
			sortable: true
		},
		{
			name: 'createTime',
			display: '创建时间',
			hide: true,
			sortable: true
		},
		{
			name: 'updateName',
			display: '更新人',
			hide: true,
			sortable: true
		},
		{
			name: 'updateTime',
			display: '更新时间',
			sortable: true,
			width : 130
		}],
		// 主从表格设置
		subGridOptions: {
			url: '?model=flights_require_requiresuite&action=pageJson&cardNoLimit=1',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'employeeTypeName',
				display: '员工类型',
				sortable: true
			},
			{
				name: 'airName',
				display: '姓名',
				sortable: true
			},
			{
				name: 'airPhone',
				display: '手机号码',
				sortable: true
			},
			{
				name: 'cardTypeName',
				display: '证件类型',
				sortable: true,
				width : 80
			},
			{
				name: 'cardNo',
				display: '证件号码',
				sortable: true,
				width : 140
			},
			{
				name: 'validDate',
				display: '证件有效期',
				sortable: true,
				width : 80
			},
			{
				name: 'birthDate',
				display: '出生日期',
				sortable: true,
				width : 80
			},
			{
				name: 'nation',
				display: '国籍',
				sortable: true
			},
			{
				name: 'tourAgencyName',
				display: '常旅客机构',
				sortable: true
			},
			{
				name: 'tourCardNo',
				display: '常旅客卡号',
				sortable: true
			}]
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_require_require&action=viewTab&id=" + rowData[p.keyField]+"&cardNoLimit=1");
			}
		},
		// 扩展右键菜单
		menusEx: [{
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function(row) {
				if (row.ExaStatus != "待提交") {
					return true;
				}
				return false;
			},
			action: function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_flights_require&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		},
		{
			name: 'ExaStatus',
			text: '录入订票信息',
			icon: 'add',
			showMenuFn: function(row) {
				if (row.ticketMsg == "0" && row.ExaStatus != '打回') {
					return true;
				}
				return false;
			},
			action: function(row, rows, grid) {
				showModalWin("index1.php?model=flights_message_message&action=toAddPush&id="
						+ row.id
						+ "&requireNo="
						+ row.requireNo
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1300");
			}
		}],
		//过滤数据
		comboEx : [{
			text : '订票状态',
			key : 'ticketMsg',
			data : [{
				text : '已生成',
				value : '1'
			}, {
				text : '未生成',
				value : '0'
			}]
		},{
			text : '审批状态',
			key : 'ExaStatus',
			type : 'workFlow'
		}],
		searchitems: [{
			display: "订票需求号",
			name: 'requireNoSearch'
		},{
			display: "乘机人",
			name: 'airNameSearch'
		},{
			display: "出发城市",
			name: 'startPlaceSearch'
		},{
			display: "中转城市",
			name: 'middlePlaceSearch'
		},{
			display: "到达城市",
			name: 'endPlaceSearch'
		}],
		sortname : "c.createTime"
	});
});