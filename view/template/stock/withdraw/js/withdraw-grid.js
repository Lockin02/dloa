var show_page = function() {
	$("#withdrawGrid").yxsubgrid("reload");
};
$(function() {
	$("#withdrawGrid").yxsubgrid({
		model: 'stock_withdraw_withdraw',
		title: '收货通知单',
		showcheckbox: false,
		isAddAction: false,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		isOpButton: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'docStatus',
			display: '赔偿状态',
			sortable: true,
			align: 'center',
			process: function(v) {
				switch (v) {
					case '0' :
						return '<img src="images/icon/cicle_grey.png" title="暂无"/>';
					case '1' :
						return '<img src="images/icon/cicle_yellow.png" title="待生成赔偿单"/>';
					case '2' :
						return '<img src="images/icon/cicle_green.png" title="已完成赔偿单"/>';
					default:
				}
			},
			width: 50
		}, {
			name: 'planCode',
			display: '通知单号',
			width: 110,
			sortable: true
		}, {
			name: 'docType',
			display: '源单类型',
			sortable: true,
			width: 60,
			process: function(v) {
				if (v == 'oa_contract_exchange') {
					return '换货';
				}
			}
		}, {
			name: 'customerName',
			display: '客户名称',
			width: 180,
			sortable: true
		}, {
			name: 'docId',
			display: '源单Id',
			sortable: true,
			hide: true
		}, {
			name: 'docCode',
			display: '源单号',
			width: 120,
			sortable: true
		}, {
			name: 'planIssuedDate',
			display: '通知单下达日期',
			width: 80,
			sortable: true
		}, {
			name: 'issuedStatus',
			display: '下达状态',
			width: 80,
			process: function(v) {
				return v == '1' ? "已下达" : "未下达";
			},
			sortable: true,
			hide: true
		}, {
		//	name: 'docStatus',
		//	display: '收货状态',
		//	width: 80,
		//	sortable: true,
		//	hide: true
		//}, {
			name: 'shipPlanDate',
			display: '计划收货日期',
			width: 80,
			sortable: true
		}, {
			name: 'status',
			display: '单据状态',
			process: function(v) {
				switch (v) {
					case 'YZX' :
						return '已处理';
					case 'BFZX' :
						return '正在处理';
					case 'WZX' :
						return '待处理';
					default :
						return '待处理';
				}
			},
			width: 80,
			sortable: true
		}, {
			name: 'docApplicant',
			display: '源单申请人',
			sortable: true
		}],
		// 主从表格设置
		subGridOptions: {
			url: '?model=stock_withdraw_withdraw&action=equJson',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				width: 80,
				display: '物料编号'
			}, {
				name: 'productName',
				width: 150,
				display: '物料名称'
			}, {
				name: 'productModel',
				width: 120,
				display: '型号/版本'
			}, {
				name: 'unitName',
				display: '单位',
				width: 60
			}, {
				name: 'number',
				display: '数量',
				width: 70
			}, {
				name: 'qualityNum',
				display: '报检数量',
				width: 70
			}, {
				name: 'qPassNum',
				display: '合格数量',
				width: 70
			}, {
				name: 'qBackNum',
				display: '不合格数量',
				width: 70
			}, {
				name: 'executedNum',
				display: '已收货数量',
				width: 70
			}, {
				name: 'compensateNum',
				display: '赔偿数量',
				width: 70
			}]
		},
		searchitems: [{
			display: "通知单号",
			name: 'planCode'
		}, {
			display: "源单号",
			name: 'docCode'
		}, {
			display: "源单名称",
			name: 'docName'
		}],
		//过滤数据
		comboEx: [{
			text: '单据状态',
			key: 'statusArr',
			value: 'WZX,BFZX',
			data: [{
				text: '已处理',
				value: 'YZX'
			}, {
				text: '正在处理',
				value: 'BFZX'
			}, {
				text: '待处理',
				value: 'WZX'
			}, {
				text: '待处理,正在处理',
				value: 'WZX,BFZX'
			}]
		}, {
			text: '赔偿状态',
			key: 'docStatus',
			data: [{
				text: '暂无',
				value: '0'
			}, {
				text: '待生成赔偿单',
				value: '1'
			}, {
				text: '已生成赔偿单',
				value: '2'
			}]
		}],
		menusEx: [{
			text: '查看',
			icon: 'view',
			action: function(row) {
				showOpenWin('?model=stock_withdraw_withdraw&action=toView&id='
				+ row.id);
			}
		}, {
			text: '提交质检',
			icon: 'add',
			showMenuFn: function(row) {
				return row.status == "BFZX" || row.status == "WZX";
			},
			action: function(row) {
				if (row) {
					showOpenWin("?model=produce_quality_qualityapply&action=toAdd&relDocId="
						+ row.id
						+ "&relDocType=ZJSQYDHH"
						+ "&relDocCode=" + row.planCode
						, 1, 500, 900, row.id
					);
				}
			}
		}, {
			text: '填写入库通知单',
			icon: 'add',
			showMenuFn: function(row) {
				return row.status == "BFZX";
			},
			action: function(row) {
				showOpenWin('?model=stock_withdraw_innotice&action=toAdd&id='
				+ row.id
				+ "&skey="
				+ row['skey_']
				+ '&docType='
				+ row.docType
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text: '下达赔偿单',
			icon: 'add',
			showMenuFn: function(row) {
				//如果是设备遗失，直接确认
				return row.docStatus == "1";
			},
			action: function(row) {
				if (row) {
					showOpenWin("?model=finance_compensate_compensate&action=toAdd&relDocId="
						+ row.id + "&skey=" + row['skey_'] + "&relDocType=PCYDLX-02"
						, 1, 700, 1100, row.id);
				}
			}
		}, {
			text: '完成赔偿',
			icon: 'delete',
			showMenuFn: function(row) {
				//设备遗失不能进行此操作
				return row.docStatus == "1";
			},
			action: function(row) {
				if (window.confirm(("确定要进行此操作吗?"))) {
					$.ajax({
						type: "POST",
						url: "?model=stock_withdraw_withdraw&action=ajaxState",
						data: {
							id: row.id,
							state: 2
						},
						success: function(msg) {
							if (msg == 1) {
								alert('操作成功！');
							} else {
								alert('操作失败！');
							}
							show_page();
						}
					});
				}
			}
		}]
	});
});