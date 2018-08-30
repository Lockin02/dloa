var show_page = function(page) {
	$("#shippingGrid").yxsubgrid("reload");
};

function hasEqu(objId) {
	var equNum = 0
	$.ajax({
		type: 'POST',
		url: '?model=projectmanagent_borrow_borrowequ&action=getEquById',
		data: {
			id: objId
		},
		async: false,
		success: function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	var limits = $('#limits').val();
	if (limits == '客户') {
		var ifshow = true;
		var param = {
			'ExaStatusArr': "完成,变更审批中",
			'lExaStatusArr': "完成,变更审批中",
			'DeliveryStatus2': 'WFH,BFFH',
			'limits': limits
		};
	} else {
		var ifshow = false;
		var param = {
			'ExaStatusArr': '免审,完成,变更审批中',
			'lExaStatusArr': "完成,变更审批中",
			'DeliveryStatus2': 'WFH,BFFH',
			'isproShipconditionAs': '1',
			'limits': limits
		};
	}
	param.isDelayApply = 1;
	$("#shippingGrid")
		.yxsubgrid(
		{
			model: 'projectmanagent_borrow_borrow',
			action: 'shipmentsPageJson',
			param: param,
			title: limits + '借试用发货',
			// 按钮
			isViewAction: false,
			isAddAction: false,
			isEditAction: false,
			showcheckbox: false,
			isDelAction: false,
			comboEx: [
				{
					text: '下达状态',
					key: 'makeStatus',
					data: [
						{
							text: '未下达',
							value: 'WXD'
						},
						{
							text: '部分下达',
							value: 'BFXD'
						},
						{
							text: '已下达',
							value: 'YXD'
						}
					]
				},
				{
					text: '发货状态',
					key: 'DeliveryStatus',
					data: [
						{
							text: '未发货',
							value: 'WFH'
						},
						{
							text: '部分发货',
							value: 'BFFH'
						}
					]
				}
			],
//						 buttonsEx : [{
//							 name : 'export',
//							 text : "发货数据导出",
//							 icon : 'excel',
//							 action : function(row) {
//								 window.open("?model=projectmanagent_borrow_borrow&action=exportExcel&limits="
//								 + "&1width=200,height=200,top=200,left=200,resizable=yes")
//						 	}
//						 }],
			// 列信息
			colModel: [
				{
					name: 'status2',
					display: '下达状态',
					sortable: false,
					width: '20',
					align: 'center',
					process: function(v, row) {
						if (row.isDelayApply == '1') {
							return "<img src='images/icon/icon071.gif' />";
						}else if (row.makeStatus == 'YXD') {
							return "<img src='images/icon/icon073.gif' />";
						} else {
							return "<img src='images/icon/green.gif' />";
						}
					}
				},
				{
					name: 'rate',
					display: '进度',
					sortable: false,
					process: function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
							+ row.id
							+ "&docType=oa_borrow_borrow"
							+ "&objCode="
							+ row.objCode
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注 : '
							+ "<font color='gray'>"
							+ v
							+ "</font>" + '</a>';
					}
				},
				{
					name: 'isship',
					display: '个人借用处理',
					sortable: false,
					process: function(v, row) {
						if (v == '0') {
							return "-";
						} else if (v == '1') {
							return "<span style='color:red'>√<span>";
						} else {
							return "-";
						}
					},
					width: 50,
					hide: ifshow
				},
				{
					display: '物料审批状态',
					name: 'lExaStatus',
					sortable: true,
					hide: true
				},
				{
					display: '物料审批表Id',
					name: 'lid',
					sortable: true,
					hide: true
				},
				{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				},
				{
					name: 'ExaDT',
					display: '建立时间',
					width: '75',
					sortable: true
				},
				{
					name: 'deliveryDate',
					display: '交货日期',
					width: 80,
					sortable: true
				},
				{
					name: 'chanceId',
					display: '商机Id',
					sortable: true,
					hide: true
				},
				{
					name: 'customerName',
					display: '客户名称',
					width: 160,
					sortable: true,
					show: ifshow
				},
				{
					name: 'objCode',
					display: '关联业务编号',
					width: '150',
					sortable: true
				},
				{
					name: 'Code',
					display: '编号',
					width: '150',
					sortable: true,
					process: function(v, row) {
						if (row.changeTips == 1) {
							return "<font color = '#FF0000'>"
								+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
								+ row.id + "&objType=oa_borrow_borrow"
								+ "&linkId="
								+ row.linkId
								+ "&skey="
								+ row['skey_']
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#FF0000'>" + v + "</font>"
								+ '</a>';
						} else {
							return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
								+ row.id + "&objType=oa_borrow_borrow"
								+ "&linkId="
								+ row.linkId
								+ "&skey="
								+ row['skey_']
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
						}
					}
				},
				{
					name: 'Type',
					display: '类型',
					width: '40',
					sortable: true
				},
				{
					name: 'limits',
					display: '范围',
					width: '40',
					sortable: true
				},
				{
					name: 'dealStatus',
					display: '处理状态',
					sortable: true,
					process: function(v) {
						if (v == '0') {
							return "未处理";
						} else if (v == '1') {
							return "已处理";
						} else if (v == '2') {
							return "变更未处理";
						} else if (v == '3') {
							return "已关闭";
						}
					},
					width: 50
				},
				{
					name: 'DeliveryStatus',
					display: '发货状态',
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
					},
					width: '60',
					sortable: true
				},
				{
					name: 'makeStatus',
					display: '下达状态',
					sortable: true,
					process: function(v) {
						if (v == 'WXD') {
							return "未下达";
						} else if (v == 'BFXD') {
							return "部分下达";
						} else if (v == 'YXD') {
							return "已下达";
						} else if (v == 'WXFH') {
							return "无需发货";
						}
					},
					width: 60,
					sortable: true
				},
				{
					name: 'customerName',
					display: '客户名称',
					show: ifshow,
					sortable: true
				},
				{
					name: 'createName',
					display: '申请人',
					width: 80,
					sortable: true
				},
				{
					name: 'salesName',
					display: '销售负责人',
					width: 80,
					show: ifshow,
					sortable: true
				},
				{
					name: 'scienceName',
					display: '技术负责人',
					width: 80,
					show: ifshow,
					sortable: true
				},
				{
					name: 'remark',
					display: '备注',
					width: 280,
					sortable: true
				},
				{
					name: 'ExaStatus',
					display: '审批状态',
					width: 90,
					hide: true,
					sortable: true
				}
			],
			/**
			 * 快速搜索
			 */
			searchitems: [
				{
					display: '编号',
					name: 'Code'
				},
				{
					display: '销售负责人',
					name: 'salesName'
				},
				{
					display: '申请人',
					name: 'createNmae'
				},
				{
					display: '申请日期',
					name: 'createTime'
				},
				{
					display: '客户名称',
					name: 'customerName'
				},
				{
					display: '物料名称',
					name: 'productName'
				},
				{
					display: '物料编码',
					name: 'productNo'
				},
				{
					display: '序列号',
					name: 'serialName2'
				}
			],
			sortname: 'ExaDT',
			sortorder: 'DESC',
			// 主从表格设置
			subGridOptions: {
				subgridcheck: true,
				url: '?model=common_contract_allsource&action=equJson',// 获取从表数据url
				// 传递到后台的参数设置数组
				param: [
					{
						'docType': 'oa_borrow_borrow'
					},
					{
						paramId: 'borrowId',// 传递给后台的参数名称
						colId: 'id'// 获取主表行数据的列名称
					}
				],
				// 显示的列
				afterProcess: function(data, rowDate, $tr) {
					if (data.number <= data.executedNum) {
						$tr.find("td").css("background-color", "#A1A1A1");
					}
				},
				// 显示的列
				colModel: [
					{
						name: 'productNo',
						display: '物料编号',
						process: function(v, data, rowData, $row) {
							if (data.changeTips == 1) {
								return '<img title="变更编辑的物料" src="images/changeedit.gif" />' + v;
							} else if (data.changeTips == 2) {
								return '<img title="变更新增的物料" src="images/new.gif" />' + v;
							} else {
								return v;
							}
						},
						width: 95
					},
					{
						name: 'productName',
						width: 200,
						display: '物料名称',
						process: function(v, data, rowData, $row) {
							if (data.changeTips != 0) {
								return "<font color=red>" + v + "</font>";
							} else {
								return v;
							}
						}
					},
					{
						name: 'productModel',
						display: '规格型号'
						// ,width : 40
					},
					{
						name: 'number',
						display: '数量',
						width: 40
					},
					{
// name : 'lockNum',
// display : '锁定数量',
// width : 50,
// process : function(v) {
// if (v == '') {
// return 0;
// } else
// return v;
// }
// }, {
						name: 'exeNum',
						display: '库存数量',
						width: 50,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'lockedNum',
						display : '锁存量',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'issuedShipNum',
						display: '已下达发货数量',
						width: 90,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'executedNum',
						display: '已发货数量',
						width: 60
					},
					{
						name: 'issuedPurNum',
						display: '已下达采购数量',
						width: 90,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'issuedProNum',
						display: '已下达生产数量',
						width: 90,
						process: function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					},
					{
						name: 'backNum',
						display: '退库数量',
						width: 60
					},
					{
						name: 'arrivalPeriod',
						display: '标准交货期',
						width: 80,
						process: function(v) {
							if (v == null) {
								return '0';
							} else {
								return v;
							}
						}
					}
				]
			},
			// 扩展右键菜单
			menusEx: [
				{
					text: '查看详细',
					icon: 'view',
					action: function(row) {
						showOpenWin('?model=stock_outplan_outplan&action=viewByBorrow&id='
						+ row.id
						+ "&objType=oa_borrow_borrow"
						+ "&linkId=" + row.lid
						+ "&skey=" + row['skey_']);
					}
				},
				{
					text: '锁定库存',
					icon: 'lock',
					showMenuFn: function(row) {
						if (row.DeliveryStatus != 'YFH' && row.isship == '0'
							&& (row.ExaStatus == '完成' || row.ExaStatus == '免审' || row.createId == 'quanzhou.luo')// 罗权洲新增的单据
							&& row.DeliveryStatus != 'TZFH') {
							return true;
						}
						return false;
					},
					action: function(row) {
						var objCode = row.objCode;
						showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.id
						+ "&objCode="
						+ objCode
						+ "&objType=oa_borrow_borrow&skey="
						+ row['skey_']);
					}
				},
				{
					text: '下达发货计划',
					icon: 'add',
					showMenuFn: function(row) {
						if ((row.dealStatus == 1 || row.dealStatus == 3) && row.isship == '0'
							&& (row.ExaStatus == '完成' || row.ExaStatus == '免审' || row.createId == 'quanzhou.luo')// 罗权洲新增的单据
							&& row.makeStatus != 'YXD'
							&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
							return true;
						}
						return false;
					},
					action: function(row, rows, rowIds, g) {
						var idArr = g
							.getSubSelectRowCheckIds(rows);
						showModalWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&equIds="
						+ idArr
						+ "&docType=oa_borrow_borrow"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
					}
				},
				{
					text: '下达采购申请',
					icon: 'edit',
					showMenuFn: function(row) {
						if ((row.dealStatus == 1 || row.dealStatus == 3) && row.isship == '0'
							&& (row.ExaStatus == '完成' || row.ExaStatus == '免审' || row.createId == 'quanzhou.luo')// 罗权洲新增的单据
							&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
							return true;
						}
						return false;
					},
					action: function(row, rows, rowIds, g) {
						var idArr = g.getSubSelectRowCheckIds(rows);
						if (row.orderCode == '')
							var codeValue = row.orderTempCode;
						else
							var codeValue = row.orderCode;
						showModalWin('?model=purchase_external_external&action=purchasePlan&orderId='
						+ row.id
						+ "&orderCode="
						+ row.Code
						+ "&orderName="
						+ "&purchType=oa_borrow_borrow"
						+ "&skey="
						+ row['skey_']
						+ "&equIdArr="
						+ idArr
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
					}
				},
				{
					text: '下达生产申请',
					icon: 'add',
					showMenuFn: function(row) {
						if ((row.dealStatus == 1 || row.dealStatus == 3)
							&& (row.ExaStatus == '完成' || row.ExaStatus == '免审' || row.createId == 'quanzhou.luo')// 罗权洲新增的单据
							&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
							return true;
						}
						return false;
					},
					action: function(row, rows, rowIds, g) {
						var eqIdArr = g
							.getSubSelectRowCheckIds(rows);
						showOpenWin("?model=produce_apply_produceapply&action=toApply&relDocId="
						+ row.id
						+ "&equIds="
						+ eqIdArr
						+ "&relDocType=BORROW"
						+ "&skey=" + row['skey_']);
					}
				},
				{
					text: "关闭需求",
					icon: 'delete',
					showMenuFn: function(row) {
						if (row.DeliveryStatus != 'TZFH' && row.isship == '0' && row.isDelayApply == '0') {
							return true;
						}
						return false;
					},
					action: function(row) {
						$.ajax({
							type: 'POST',
							url: '?model=contract_common_allcontract&action=closeCont&skey='
							+ row['skey_'],
							data: {
								id: row.id,
								type: 'oa_borrow_borrow'
							},
							// async: false,
							success: function(data) {
								alert("关闭成功");
								show_page();
								return false;
							}
						});
					}
				},
				{
					text: '回致仓库',
					icon: 'edit',
					showMenuFn: function(row) {
						if (row.isship == '1') {
							return true;
						}
						return false;
					},
					action: function(row) {
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=toBackStorage&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
					}
				},
				{
					text: '延期提醒',
					icon: 'edit',
					showMenuFn: function(row) {
						if (row.isDelayApply == '1') {
							return true;
						}
						return false;
					},
					action: function(row) {
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=toNoticeDelayApply&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
					}
				}
			]
		});
});