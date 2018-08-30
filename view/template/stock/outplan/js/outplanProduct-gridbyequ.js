var show_page = function(page) {
	$("#pagebyequGrid").yxsubgrid("reload");
};
function planRate(id){
	showThickboxWin('?model=stock_outplan_outplanrate&action=page&id='
			+ id
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
}
$(function() {
	var docTypeArr = $("#docTypeArr").val();
	$("#pagebyequGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		param : {
			"docStatusArr" : docTypeArr
		},
		title : '发货计划',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'pagebyequGrid',
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].changeTips==1){
							$('#row' + data.collection[i].id).css('color', 'red');
						}
					}
				}
			}
		},
		comboEx : [{
			text : '发货类型',
			key : 'docType',
			data : [{
				text : '合同发货',
				value : 'oa_contract_contract'
			}, {
				text : '借用发货',
				value : 'oa_borrow_borrow'
			}, {
				text : '赠送发货',
				value : 'oa_present_present'
			}, {
				text : '换货发货',
				value : 'oa_contract_exchangeapply'
			}]
		}, {
			text : '发货状态',
			key : 'docStatus',
			data : [{
				text : '未发货',
				value : 'WFH'
			}, {
				text : '部分发货',
				value : 'BFFH'
			}, {
				text : '已完成',
				value : 'YWC'
			}, {
				text : '已关闭',
				value : 'YGB'
			}]
		}, {
			text : '加密狗完成进度',
			key : 'dongleRate',
			data : [{
				text : '未完成',
				value : '0'
			}, {
				text : '已完成',
				value : '1'
			}]
		}],
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'dongleRate',
			display : '加密狗完成进度',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.dongleRate == 0) {
					return "<img src='images/icon/icon057.gif' />";
				}else{
					return "";
				}
			}
		}, {
			name : 'status2',
			display : '执行状态标识',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.status == 'YZX') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		}, {
			name : 'planCode',
			display : '计划编号',
			width : 90,
			process : function(v, row) {
				if (row.changeTips == 1) {
					return "<font class='changeTipsRow' color=red>" + v
							+ "</font>";
				} else {
					return v;
				}
			},
			sortable : true
		}, {
			name : 'week',
			display : '周次',
			width : 50,
			hide : true,
			sortable : true
		}, {
			name : 'docApplicant',
			display : '源单申请人',
			width : 80,
			sortable : true
		}, {
			name : 'docApplicantId',
			display : '源单申请人Id',
			width : 50,
			hide : true,
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 150,
			sortable : true
		}, {
			name : 'docId',
			display : '合同Id',
			hide : true,
			sortable : true
		}, {
			name : 'rObjCode',
			display : '关联业务编号',
			width : 120,
			sortable : true
		}, {
			name : 'docCode',
			display : '源单号',
			width : 180,
			sortable : true
		}, {
			name : 'reterStart',
			display : '等级',
			width : 180,
			sortable : true,
			process:function(v,row){
				return "<div id='sign"+row.id+"'></div><input type='hidden' id='star"
				+row.id+
				"' value="
				+ v +
				" name='outplan[reterStart]'></input><script>$('#sign"
				+row.id+
				"').rater({value:$('#star"
				+row.id+
				"')[0].value,image:'js/jquery/raterstar/star.gif',max:5,url:'index1.php?model=stock_outplan_outplan&action=editstar&id="
				+row.id+
				"',before_ajax: function(ret) {if(confirm('要修改等级吗？')==false){$('#sign"
				+row.id+
				"').rater(this);return false;}}});</script>";
			}
		}, {
			name : 'docName',
			display : '源单名称',
			width : 180,
			hide : true,
			sortable : true
		}, {
			name : 'docType',
			display : '发货类型',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_contract_exchangeapply') {
					return "换货发货";
				} else if (v == 'oa_contract_contract') {
					return "合同发货";
				} else if (v == 'oa_borrow_borrow') {
					return "借用发货";
				} else if (v == 'oa_present_present') {
					return "赠送发货";
				}
			}
		}, {
			name : 'isTemp',
			display : '是否变更',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '是') : (v = '否');
				return v;
			},
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '下达日期',
			width : 75,
			sortable : true,
			hide : true
		}, {
			name : 'stockName',
			display : '发货仓库',
			sortable : true,
			hide : true
		}, {
			name : 'type',
			display : '性质',
			datacode : 'FHXZ',
			width : 70,
			sortable : true,
			hide : true
		}, {
			name : 'purConcern',
			display : '采购人员关注重点',
			hide : true,
			sortable : true
		}, {
			name : 'rate',
			display : '进度',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_outplanrate&action=updateRate&id='
						+row.id+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注：'+"<font color='gray'>"+v+"</font>"+'</a>';
			}
		}, {
			name : 'shipConcern',
			display : '发货人员关注',
			hide : true,
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '交货日期',
			width : 75,
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '计划发货日期',
			width : 75,
			sortable : true
		}, {
			name : 'status',
			display : '单据状态',
			width : 60,
			process : function(v) {
				if (v == 'YZX') {
					return "已执行";
				} else if (v == 'BFZX') {
					return "部分执行";
				} else if (v == 'WZX') {
					return "未执行";
				} else {
					return "未执行";
				}
			},
			sortable : true
		}, {
			name : 'isOnTime',
			display : '按时发货',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '是') : (v = '否');
				return v;
			},
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '下达状态',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '已下达') : (v = '未下达');
				return v;
			},
			sortable : true
		}, {
			name : 'docStatus',
			display : '发货状态',
			width : 70,
			process : function(v) {
				if (v == 'YWC') {
					return "已发货";
				} else if (v == 'BFFH') {
					return "部分发货";
				} else if (v == 'YGB') {
					return "已关闭";
				} else
					return "未发货";
			},
			sortable : true
		}, {
			name : 'delayType',
			display : '延期原因归类',
			hide : true,
			sortable : true
		}, {
			name : 'delayReason',
			display : '未发具体原因',
			hide : true,
			sortable : true
		}, {
			name : 'changeTips',
			display : '变更标识',
			hide : true,
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=byOutplanJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'mainId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				// name : 'productLineName',
				// width : 80,
				// display : '产品名称'
				// }, {
				name : 'productNo',
				width : 150,
				display : '产品编号',
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'productName',
				width : 200,
				display : '产品名称',
				process : function(v, data, rowData,$row) {
					if (data.changeTips == 1) {
						if (data.BToOTips == 1) {
							$row.attr("title", "该物料为借试用转销售的物料");
							return "<img src='images/icon/icon147.gif' />"+"<font color=red>" + v + "</font>";
						}else{
							return "<font color=red>" + v + "</font>";
						}
					} else {
						if (data.BToOTips == 1) {
							$row.attr("title", "该物料为借试用转销售的物料");
							return "<img src='images/icon/icon147.gif' />"+ v ;
						}else{
							return  v ;
						}
					}
					return "<font color=red>" + v + "</font>";
				}
			}, {
				name : 'number',
				display : '数量',
				width : 50,
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'unitName',
				display : '单位',
				width : 50,
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'executedNum',
				display : '已发货数量',
				width : 60,
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'shipNum',
				display : '已填发货单单数量',
				process : function(v,row) {
					if (v == '') {
						v = "0";
					}
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				},
				width : 120
			}]
		},

		menusEx : [{
			text : '查看详细',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=outplandetailTab&planId='
						+ row.id
						+ '&docType='
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			text : "取消变更提醒",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.changeTips == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('取消变更提醒后，该发货计划颜色将会恢复正常，确定取消提醒？')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=cancleTips&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 2) {
								alert('没有权限,需要开通权限请联系oa管理员');
							} else {
								alert("变更提醒已取消！");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus != 'YGB' && row.docStatus != 'YWC'
						&& row.issuedStatus == 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toEdit&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			name : 'issued',
			text : '下达',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.issuedStatus != 1 && row.docStatus != 'YGB') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=stock_outplan_outplan&action=issuedFun&skey="
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 2) {
								alert('没有权限,需要开通权限请联系oa管理员');
							} else {
								alert('下达成功！');
								$("#pagebyequGrid").yxsubgrid("reload");
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '填写发货单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.issuedStatus == 1 && row.docStatus != 'YGB'
						&& row.status != 'YZX') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_outplan_ship&action=toAdd&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			showMenuFn : function(row) {
				if (row.issuedStatus == '1') {
					return true;
				}
				return false;
			},
			text : '计划反馈单',
			icon : 'edit',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toFeedBack&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : '变更',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.issuedStatus == 1 && row.docStatus != 'YGB') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toChange&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : "完成加密狗备货",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dongleRate == 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要将加密狗备货进度置为已完成？！')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=selectDongleRate&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 2) {
								alert('没有权限,需要开通权限请联系oa管理员');
							} else {
								alert("加密狗备货已完成");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}, {
			text : "重启加密狗备货",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.dongleRate == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要将加密狗备货进度置为未完成？！')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=resetDongleRate&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 2) {
								alert('没有权限,需要开通权限请联系oa管理员');
							} else {
								alert("加密狗备货已完成");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}, {
			text : "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus != 'YGB') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要关闭发货计划？')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=closePlan&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							docType : row.docType
						},
						// async: false,
						success : function(data) {
							if (data == 2) {
								alert('没有权限,需要开通权限请联系oa管理员！');
							}else if ( data == 0 ) {
								alert('关闭失败！');
							} else {
								alert("关闭成功！");
								show_page();
							}
							return false;
						}
					});
				}
			}
//		}, {
//			text : "恢复",
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.docStatus == 'YGB') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (confirm('确定要重新启动发货计划？')) {
//					$.ajax({
//						type : 'POST',
//						url : '?model=stock_outplan_outplan&action=reopenPlan&skey='
//								+ row['skey_'],
//						data : {
//							id : row.id
//						},
//						// async: false,
//						success : function(data) {
//							if (data == 2) {
//								alert('没有权限,需要开通权限请联系oa管理员');
//							} else {
//								alert("恢复成功");
//								show_page();
//							}
//							return false;
//						}
//					});
//				}
//			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '计划编号',
			name : 'planCode'
		}, {
			display : '关联业务单编号',
			name : 'rObjCode'
		}, {
			display : '源单号',
			name : 'docCode'
		}]
	});
});