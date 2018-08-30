var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	//获取需求申请状态的值
	var isRecognize = $("#isRecognize").val();
	$("#requirementGrid").yxsubgrid({
		model : 'asset_require_requirement',
		param : {
			// 'ExaStatusArr' : '完成,金额确认审批'
			'isSubmit' : '1',
			'isRecognizeFlag' : '2,4'
		},
		title : '资产需求申请',
		isToolBar : false, // 是否显示工具栏
		showcheckbox : false,
		// comboEx : [{
		// text : '确认状态',
		// key : 'isRecognize',
		// data : [{
		// text : '未确认',
		// value : '0'
		// }, {
		// text : '已确认',
		// value : '1'
		// }]
		// }],
		// 列信息
		colModel : [{
			name : 'status2',
			display : '状态',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.isRecognize == 5) {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '需求编号',
			width : 120,
			sortable : true
		}, {
			name : 'expectAmount',
			display : '预计金额',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'isRecognize',
			display : '需求申请状态',
			process : function(v) {
				if(v==0){
					return "未确认";
				}else if(v==1){
					return "已确认";
				}else if(v==3){
					return "确认审批中";
				}else if(v==5){
					return "采购中";
				}else if(v==6){
					return "生成资产卡片";
				}else if(v==7){
					return "申请人签收";
				}else if(v==8){
					return "已完成";
				}
			},
			sortable : true
		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
			process : function(v) {
				if (v == 'WFH') {
					return "未发货";
				} else if (v == 'YFH') {
					return "已发货";
				} else {
					return "部分发货";
				}
			},
			sortable : true,
			width : 70
		}, {
			name : 'requireInStatus',
			display : '物料转资产状态',
			process : function(v) {
				if (v == '1') {
					return "待出库";
				} else if (v == '2') {
					return "待验收";
				} else if (v == '3') {
					return "待生成资产卡片";
				} else if (v == '4') {
					return "已完成";
				} else {
					return "----";
				}
			},
			sortable : true,
			width : 100
		}, {
			name : 'userName',
			display : '使用人',
			sortable : true,
			width : 80
		}, {
			name : 'userDeptName',
			display : '使用部门',
			sortable : true,
			width : 80
		}, {
			name : 'applyName',
			display : '申请人',
			sortable : true,
			width : 80
		}, {
			name : 'applyDeptName',
			display : '申请部门',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 80
		}, {
			// name : 'useName',
			// display : '资产用途',
			// sortable : true
			// }, {
			name : 'remark',
			display : '备注',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_require_requireitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'description',
				width : 200,
				display : '设备描述'
			}, {
				name : 'number',
				width : 80,
				display : '数量'
			}, {
				name : 'executedNum',
				display : '已发货数量',
				width : 80
			}, {
				name : 'dateHope',
				display : '期望交货日期',
				width : 80
			}, {
				name : 'purchDept',
				display : '采购部门',
				process : function(v) {
					if (v == '0') {
						return "行政部";
					}else if(v == '1'){
						return "交付部";
					}else{
						return "";
					}
				},
				width : 80
			}, {
				name : 'purchAmount',
				display : '采购数量',
				width : 80
			}]
		},
		comboEx : [{
			text : '申请状态',
			key : 'isRecognize',
			data : [{
				text : '未确认',
				value : '0'
			}, {
				text : '已确认',
				value : '1'
			}, {
				text : '确认审批中',
				value : '3'
			}, {
				text : '采购中',
				value : '5'
			}, {
				text : '生成资产卡片',
				value : '6'
			}, {
				text : '申请人签收',
				value : '7'
			}, {
				text : '已完成',
				value : '8'
			}],
			value : isRecognize
		},{
			text : '物料转资产状态',
			key : 'requireInStatus',
			data : [{
				text : '待出库',
				value : '1'
			}, {
				text : '待验收',
				value : '2'
			}, {
				text : '待生成资产卡片',
				value : '3'
			}, {
				text : '已完成',
				value : '4'
			}, {
				text : '暂无申请',
				value : '0'
			}]
		},{
			text : '是否完成',
			key : 'DeliveryStatusArr',
			data : [{
				text : '未完成',
				value : 'WFH,BFFH'
			}, {
				text : '已完成',
				value : 'YFH'
			}]
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toViewTab&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'],1,700,1900);

				}
			}
		}, {
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isRecognize == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_require_requirement&action=toBackDetail&id="
							+ row.id + "&skey=" + row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=650')
				}
			}
		}, {
//			text : '打回',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.isRecognize == 0) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				// alert(row.moneyAll);
//				if (window.confirm(("确定打回吗？"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=asset_require_requirement&action=reback&id="
//								+ row.id,
//						success : function(msg) {
//							if (msg == 1) {
//								alert('单据已打回！');
//								$("#requirementGrid").yxsubgrid("reload");// 重新加载
//							} else {
//								alert('打回失败！');
//							}
//						}
//					});
//					$.ajax({
//						type : "POST",
//						url : "?model=asset_require_requirement&action=rebackMail&id="
//								+ row.id
//					});
//				}
//			}
//		}, {
			text : '确认金额',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSubmit == '1' && row.ExaStatus == ''
						&& row.isRecognize == '0'
						&& row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toRecognize&id="
							+ row.id + "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '借用申请',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_daily_borrow&action=toAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '领用申请',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_daily_charge&action=toAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '采购申请',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_purchase_apply_apply&action=toRequireAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '物料转资产申请',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.DeliveryStatus != 'YFH' && row.requireInStatus != '4') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			name : 'cancel',
			text : '撤消审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isRecognize == 3) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_requirement'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤销审批！');
						    	show_page();
								return false;
							}else{
								if(confirm('确定要撤消审批吗？')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/require/ewf_index_require.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		searchitems : [{
			display : "需求编号",
			name : 'requireCode'
		}, {
			display : "使用人",
			name : 'userName'
		}, {
			display : "使用部门",
			name : 'userDeptName'
		}, {
			display : "申请人",
			name : 'applyName'
		}, {
			display : "申请部门",
			name : 'applyDeptName'
		}]
	});
});