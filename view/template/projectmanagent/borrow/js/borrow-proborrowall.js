var show_page = function() {
	$("#proborrowAllGrid").yxsubgrid("reload");
};

$(function() {
	var buttonsArr = [{
		name: 'Add',
		text: "新增",
		icon: 'add',
		action: function() {
			showOpenWin('?model=projectmanagent_borrow_borrow&action=toProAdd');
		}
	}];

	$("#proborrowAllGrid").yxsubgrid({
		model: 'projectmanagent_borrow_borrow',
		action: 'pageJsonStaff',
		param: {
			'limits': '员工'
		},
		title: '所有借试用(员工)',
		//按钮
		isViewAction: false,
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'Code',
			display: '编号',
			sortable: true,
			width: 150
		}, {
			name: 'Type',
			display: '类型',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'limits',
			display: '范围',
			sortable: true,
			width: 60
		}, {
			name: 'createName',
			display: '申请人',
			sortable: true
		}, {
			name: 'beginTime',
			display: '开始日期',
			sortable: true
		}, {
			name: 'closeTime',
			display: '截止日期',
			sortable: true
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 90,
			process: function (v,row) {
				if(row.lExaStatus != '变更审批中'){
					return v;
				}else{
					return '变更审批中';
				}
			}
		}, {
			name: 'ExaDT',
			display: '审批时间',
			sortable: true,
			hide: true,
			process: function (v,row){
				if(row['ExaStatus'] == "部门审批"){
					return '';
				}else{
					return v;
				}
			}
		}, {
			name: 'status',
			display: '单据状态',
			sortable: true,
			process: function(v) {
				if (v == '0') {
					return "正常";
				} else if (v == '1') {
					return "部分归还";
				} else if (v == '2') {
					return "关闭";
				} else if (v == '3') {
					return "退回";
				} else if (v == '4') {
					return "续借申请中"
				} else if (v == '5') {
					return "转至执行部"
				} else if (v == '6') {
					return "转借确认中"
				}
			}
		}, {
			name: 'DeliveryStatus',
			display: '发货状态',
			sortable: true,
			process: function(v) {
				if (v == 'WFH') {
					return "未发货";
				} else if (v == 'YFH') {
					return "已发货";
				} else if (v == 'BFFH') {
					return "部分发货";
				} else if (v == 'TZFH') {
					return "停止发货";
				}
			}
		}, {
			name: 'backStatus',
			display: '归还状态',
			sortable: true,
			process: function(v) {
				if (v == '0') {
					return "未归还";
				} else if (v == '1') {
					return "已归还";
				} else if (v == '2') {
					return "部分归还";
				}
			}
		}, {
			name: 'reason',
			display: '申请理由',
			sortable: true,
			width: 200
		}, {
			name: 'timeType',
			display: '借用期限',
			sortable: true,
			width: 60
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 200
		}],
		// 主从表格设置
		subGridOptions: {
			url: '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param: [{
				paramId: 'borrowId',// 传递给后台的参数名称
				colId: 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel: [{
				name: 'productNo',
				width: 200,
				display: '产品编号',
				process: function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
				}
			}, {
				name: 'productName',
				width: 200,
				display: '产品名称',
				process: function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
				}
			}, {
				name: 'number',
				display: '申请数量',
				width: 80
			}, {
				name: 'executedNum',
				display: '已执行数量',
				width: 80
			}, {
				name: 'backNum',
				display: '已归还数量',
				width: 80
			}]
		},
		comboEx: [{
			text: '审批状态',
			key: 'ExaStatus',
			data: [{
				text: '未审批',
				value: '未审批'
			}, {
				text: '物料确认',
				value: '物料确认'
			}, {
				text: '变更审批中',
				value: '变更审批中'
			}, {
				text: '部门审批',
				value: '部门审批'
			}, {
				text: '完成',
				value: '完成'
			},{
				text: '免审',
				value: '免审'
			}]
		}, {
			text: '发货状态',
			key: 'DeliveryStatus',
			data: [{
				text: '未发货',
				value: 'WFH'
			}, {
				text: '已发货',
				value: 'YFH'
			}, {
				text: '部分发货',
				value: 'BFFH'
			}, {
				text: '停止发货',
				value: 'TZFH'
			}]
		}, {
			text: '单据状态',
			key: 'status',
			data: [{
				text: '正常',
				value: '0'
			}, {
				text: '部分归还',
				value: '1'
			}, {
				text: '关闭',
				value: '2'
			}, {
				text: '退回',
				value: '3'
			}, {
				text: '续借申请中',
				value: '4'
			}, {
				text: '转至执行部',
				value: '5'
			}, {
				text: '转借确认中',
				value: '6'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems: [{
			display: '编号',
			name: 'Code'
		}, {
			display: '申请人',
			name: 'createName'
		}, {
			display: '申请日期',
			name: 'createTime'
		}, {
			display: 'K3物料名称',
			name: 'productNameKS'
		}, {
			display: '系统物料名称',
			name: 'productName'
		}, {
			display: 'K3物料编码',
			name: 'productNoKS'
		}, {
			display: '系统物料编码',
			name: 'productNo'
		},{
			display : '序列号',
			name : 'serialName2'
		}],
		buttonsEx: buttonsArr,
		// 扩展右键菜单
		menusEx: [{
			text: '查看',
			icon: 'view',
			action: function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=proViewTab&id="
					+ row.id + "&skey=" + row['skey_']);
				}
			}
		}, {
			text: '归还物料',
			icon: 'add',
			showMenuFn: function(row) {
				return (row.ExaStatus == '完成' || row.ExaStatus == '免审') && row.backStatus != '1'
					&& $("#returnLimit").val() == "1";
			},
			action: function(row) {
				showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
			}
		}]
	});
});