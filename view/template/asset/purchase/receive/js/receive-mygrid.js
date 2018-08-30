var show_page = function(page) {
	$("#receiveGrid").yxgrid("reload");
};
$(function() {
	$("#receiveGrid").yxgrid({
		model : 'asset_purchase_receive_receive',
		title : '我的验收单',
		param : {'createId':$('#createId').val()},
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '表单编号',
			sortable : true,
			width : 120
		}, {
			name : 'type',
			display : '验收类型',
			process : function(v) {
				if(v == '0')
					return '采购';
				if(v == '1')
					return '物料转资产';
			},
			width : 80
		}, {
			name : 'code',
			display : '采购单编号',
			sortable : true,
			width : 120
		}, {
			name : 'purchaseContractCode',
			display : '采购订单编号',
			sortable : true,
			width : 120
		}, {
			name : 'requireinCode',
			display : '物料转资产编号',
			sortable : true,
			width : 130
		}, {
			name : '',
			display : '源单申请人',
			sortable : true,
			process : function(v,row) {
				if(row.applyId != ''){
					v = row.salvage;
				}else if(row.purchaseContractId != ''){
					v = row.sendName;
				}else if(row.requireinId != ''){
					v = row.applyName;
				}
				return v;
			},
			width : 90
		}, {
			name : 'limitYears',
			display : '验收日期',
			sortable : true,
			width : 80
		}, {
			name : 'salvage',
			display : '验收人',
			sortable : true,
			width : 90
		}, {
			name : 'deptName',
			display : '验收部门',
			sortable : true,
			width : 80
		}, {
			name : 'amount',
			display : '验收金额',
			sortable : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'result',
			display : '验收结果',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true,
			width : 80
		}],
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
//				text : '部门审批',
//				value : '部门审批'
//			}, {
				text : '待提交',
				value : '待提交'
			}, {
				text : '完成',
				value : '完成'
//			}, {
//				text : '打回',
//				value : '打回'
			}]
		},{
			text : '验收类型',
			key : 'type',
			data : [{
				text : '采购',
				value : '0'
			}, {
				text : '物料转资产',
				value : '1'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '源单编号',
			name : 'relDocCode'
		}, {
			display : '验收部门',
			name : 'deptName'
		}, {
			display : '验收结果',
			name : 'result'
		}],
		toAddConfig : {
			formWidth : 900,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 600
		},
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 600
		},
		toEditConfig : {
			/**
			 * 编辑表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 编辑表单默认高度
			 */
			formHeight : 600,
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},
		// 扩展右键菜单
		menusEx : [{
//			text : '转为资产卡片',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "完成")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				window.location='?model=asset_purchase_receive_receiveItem&action=page&receiveId='
//						+ row.id
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
//			}
//		},{
//			text : '提交审批',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin('controller/asset/purchase/receive/ewf_index.php?actTo=ewfSelect&billId='
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				} else {
//					alert("请选中一条数据");
//				}
//			}
//
//		}, {
//			name : 'aduit',
//			text : '审批情况',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "完成" || row.ExaStatus == "打回")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_receive&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		}, {
			text : '撤回验收单',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
					    type: "POST",
					    url: "?model=asset_purchase_receive_receiveItem&action=isCardJson",
					    data: {"receiveId" : row.id , "isCard" : 1},
					    async: false,
					    success: function(data){
					   		if(data == 0){
								if(confirm('确认要撤回该验收单么？')){
									$.ajax({
										type : "POST",
										url : "?model=asset_purchase_receive_receive&action=ajaxRevocation",
					   					data: {"id" : row.id},
					   					async: false,
										success : function(msg) {
											msg=strTrim(msg);
											if(msg == "1"){
												alert('撤销成功!');
//												$("#receiveGrid").yxgrid("reload");
//												$.ajax({
//													type:"post",
//													url: "?model=asset_purchase_receive_receive&action=revocateSendMail",
//													data: {"id" : row.id}
//												})
												$("#receiveGrid").yxgrid("reload");
											}else{
//												alert('撤销成功。');
												alert('撤销失败');
												$("#receiveGrid").yxgrid("reload");
											}
										}
									});
								}
					   	    }else{
								alert('验收单已生成资产卡片,不能进行撤回操作');
					   	    }
						}
					});
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_receive_receive&action=ajaxdeletes",
	   					data: {"id" : row.id},
						success : function(msg) {
							if(msg == "1"){
								alert('删除成功');
								$("#receiveGrid").yxgrid("reload");
							}else{
								alert('删除失败');
								$("#receiveGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}]
	});
});