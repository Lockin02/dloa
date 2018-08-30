var show_page = function(page) {
	$("#applyGrid").yxsubgrid("reload");
};
$(function() {
	$("#applyGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		title : '我的资产采购申请',
		showcheckbox : false,
		param : {
			"createId" : $("#createId").val(),
			"isSetMyList" : 'true'
		},
		isDelAction : false,
		isAddAction:false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'applyTime',
			display : '申请日期',
			sortable : true,
			width : 80
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width : 110
		}, {
			name : 'purchaseDept',
			display : '采购部门',
			sortable : true,
			process : function(v){
				if(v == "1"){
					return '交付部';
				}else if(v == "2"){
					return '动悉行政部';
				}else{
					return '行政部';
				}
			},
			width : 80
		}, {
			name : 'applicantName',
			display : '申请人名称',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : '使用人名称',
			sortable : true
		}, {
			name : 'useDetName',
			display : '使用部门',
			sortable : true,
			width : 80
		}, {
//			name : 'purchCategory',
//			display : '采购种类',
//			sortable : true,
//			datacode : 'CGZL'
//		}, {
			name : 'assetUse',
			display : '资产用途',
			sortable : true
		}, {
			name : 'state',
			display : '单据状态',
			sortable : true,
			width : 80
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
				name : 'issuedAmount',
				display : "下达数量",
				width : 70,
				process : function(v){
					if(v == ""){
						return 0;
					}else{
						return v;
					}

				}
			}, {
				name : 'dateHope',
				display : "希望交货日期"
			}, {
				name : 'remark',
				display : "备注"
			}]
		},
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '待提交',
				value : '待提交'
			}, {
				text : '完成',
				value : '完成'
			}, {
				text : '打回',
				value : '打回'
			}]
		}],
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
			formWidth : 1100,
			/**
			 * 编辑表单默认高度
			 */
			formHeight : 600,
			showMenuFn : function(row) {
				if ((row.state == "未提交" || row.state == "打回") && (row.ExaStatus == "待提交" || row.ExaStatus == "打回")) {
					return true;
				}
				return false;
			}
		},
		// 扩展右键菜单
		menusEx : [{
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == "已确认" && (row.ExaStatus == "已提交" || row.ExaStatus == "打回")) {
					if(row.purchaseDept == "1"){
						return false;
					}
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				// 获取单据操作人的归属部门
				var responseText = $.ajax({
					url:'index1.php?model=deptuser_user_user&action=ajaxGetUserInfo',
					data : {'userId':row.createId},
					type : "POST",
					async : false
				}).responseText;
				var billDept = '';
				if(responseText != '' && responseText != 'false'){
					var resultObj = eval("("+responseText+")");
					billDept = resultObj.DEPT_ID;
				}

				if (row) {
					showThickboxWin('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&billDept='+billDept
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'cancel',
			text : '撤消审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
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
							examCode : 'oa_asset_purchase_apply'
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
									    url: "controller/asset/purchase/apply/ewf_index.php?actTo=delWork&billId=",
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
//					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_purchase_apply&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.state == "未提交" || row.state == "打回") && (row.ExaStatus == '待提交' || row.ExaStatus == '打回')) {
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