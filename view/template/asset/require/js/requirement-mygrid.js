var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	$("#requirementGrid").yxsubgrid({
		model : 'asset_require_requirement',
		action : 'myPageJson',
		title : '我的资产申请',
		isToolBar : false, // 是否显示工具栏
		showcheckbox : false,
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
//			display : 'isSubmit',
//			name : 'isSubmit',
//			hide : true
//		}, {
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
			// name : 'recognizeAmount',
			// display : '确认金额',
			// sortable : true,
			// process : function(v) {
			// return moneyFormat2(v);
			// }
			// }, {
			name : 'isRecognize',
			display : '需求申请状态',
			process : function(v) {
				if(v==0){
					return "未确认";
				}else if(v==1){
					return "已确认";
				}else if(v==2){
					return "撤回";
				}else if(v==3){
					return "确认审批中";
				}else if(v==4){
					return "打回";
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
			width : 70
		}, {
//			name : 'useName',
//			display : '资产用途',
//			sortable : true,
//			width : 80
//		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
//			hide : true,
			sortable : true,
			process : function(v) {
				if (v == 'WFH') {
					return "未发货";
				} else if (v == 'YFH') {
					return "已发货";
				} else {
					return "部分发货";
				}
			},
			width : 80
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 80
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
			}]
		},
		buttonsEx : [{
			name : 'add',
			text : "新增",
			icon : 'add',
			action : function(row) {
				alert("您好，新OA已上线，请到新OA提交需求申请。谢谢！");
				return false;
				window.open("?model=asset_require_requirement&action=toadd")
			}
		}],
		// buttonsEx : [{
		// name : 'add',
		// text : "新增",
		// icon : 'add',
		// action : function(row) {
		// window.open("?model=asset_require_requirement&action=toadd")
		// }
		// }],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toViewTab&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isRecognize == 4||row.isSubmit == '0' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toEdit&id="
							+ row.id + "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '撤回',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isRecognize == 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				// alert(row.moneyAll);
				if (window.confirm(("确定撤回吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_require_requirement&action=rollback&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('单据已撤回！');
								$("#requirementGrid").yxsubgrid("reload");// 重新加载
							} else {
								alert('撤回失败！');
							}
						}
					});
				}
			}
		}, {
			text : '打回原因',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isRecognize == 4) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_require_requireback&action=pagebyrequire&requireId="+ row.id
					+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750")
				}
			}
		}, {
			text : '签收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.DeliveryStatus != 'WFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=signPage&requireId="
							+ row.id + "&skey=" + row['skey_'])
				}
			}
				// }, {
				// text : '提交审核',
				// icon : 'add',
				// showMenuFn : function(row) {
				// if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row, rows, grid) {
				// if (row) {
				// showThickboxWin('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId='
				// + row.id
				// +
				// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				// }
				// }
		}],
		comboEx : [{
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
//		}, {
//			display : "设备描述",
//			name : 'productName'
		}]
	});
});