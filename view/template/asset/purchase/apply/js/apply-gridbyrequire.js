var show_page = function(page) {
	$("#applyGrid").yxsubgrid("reload");
};
$(function() {
	$("#applyGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		title : '资产采购申请',
		showcheckbox : false,
		param : {
			"relDocId" : $("#requireId").val(),
			"ifShow" : "0"
		},
		isDelAction : false,
		isAddAction:false,
		toEditConfig : {
			/**
			 * 编辑表单默认宽度
			 */
			formWidth : 1100,
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
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true
		}, {
			name : 'applyTime',
			display : '申请日期',
			sortable : true
		}, {
			name : 'applicantName',
			display : '申请人名称',
			sortable : true
		}, {
			name : 'userName',
			display : '使用人名称',
			sortable : true
		}, {
			name : 'useDetName',
			display : '使用部门',
			sortable : true
		}, {
			name : 'assetUse',
			display : '资产用途',
			sortable : true
		}, {
			name : 'purchaseDept',
			display : '采购部门',
			sortable : true,
			width : 80,
			process : function(v) {
				if (v == '0') {
					return "行政部";
				} else if (v == '1') {
					return "交付部";
				} else {
					return "";

				}
			}
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 90
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=pageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				name : 'inputProductName',
				width : 200,
				display : '物料名称'
			}, {
				name : 'pattem',
				display : "规格"
			}, {
				name : 'unitName',
				display : "单位",
				width : 50
			}, {
				name : 'applyAmount',
				display : "申请数量",
				width : 70
			}, {
				name : 'dateHope',
				display : "希望交货日期"
			}, {
				name : 'remark',
				display : "备注"
			}]
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		}, {
			display : '使用部门',
			name : 'useDetName'
		}, {
			display : '物料名称',
			name : 'productName'
		}],
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
		// 扩展右键菜单
		menusEx : [{
			text : '提交申请',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_purchase_apply_apply&action=ajaxSubmitConfirm&actType=submit&Id=' + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '撤回申请',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "完成" && row.state != '已撤回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
//					if(row.purchaseDept == "0"){
//						alert('行政采购不支持撤回操作');
//						return false;
//					}
					//获取可撤回数量
					var rs;
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_apply_apply&action=canBackForm",
						data : {"id" : row.id},
					    async: false,
						success : function(msg) {
							rs = msg;
						}
					});
					//
					if(rs == "1"){
						if(confirm('单据未下达采购任务,可进行整单撤回，选择 【确定】 整单撤回，选择 【取消】按明细撤回')){
							if(confirm('确认进行整单撤回吗？')){
								$.ajax({
									type : "POST",
									url : "?model=asset_purchase_apply_apply&action=backForm",
									data : {"id" : row.id},
									success : function(msg) {
										if(msg == "1"){
											alert('撤销成功');
											$("#applyGrid").yxsubgrid("reload");
										}else{
											alert('撤销失败');
											$("#applyGrid").yxsubgrid("reload");
										}
									}
								});
							}
						}else{
							showThickboxWin("?model=asset_purchase_apply_apply&action=toBackDetail&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
						}
					}else if(rs == "0"){
						alert('单据已全部下达完，不能进行此操作');
					}else{
						showThickboxWin("?model=asset_purchase_apply_apply&action=toBackDetail&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
					}
				} else {
					alert("请选中一条数据");
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
						url : "?model=asset_purchase_apply_apply&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#applyGrid").yxsubgrid("reload");
						}
					});
				}
			}
		}]
	});
});