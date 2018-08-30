var show_page = function(page) {
	$("#shippingGrid").yxsubgrid("reload");
};

function hasEqu(objId) {
	var equNum = 0
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_borrow_borrowequ&action=getEquById',
		data : {
			id : objId
		},
		async : false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	var ifshow = true;
	var limits=$('#limits').val();
	var param = { 'outSea':$('#outSeaIds').val(),'ExaStatusArr' : '免审,完成','isshipments' : '1','DeliveryStatus2' : '0,2','limits' : limits };
	$("#shippingGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'shipmentsPageJson',

        param : param,
		title : '海外' + limits + '借试用发货',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		isDelAction : false,

		buttonsEx : [{
			name : 'export',
			text : "发货数据导出",
			icon : 'excel',
			action : function(row) {
				window.open("?model=contract_common_allcontract&action=borrowExportExcel&limits="
								+ limits
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		}],
		// 列信息
		colModel : [{
			name : 'status2',
			display : '下达状态',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.issuedStatus == 'YXD') {
					return "<img src='images/icon/icon073.gif' />";
				} else if( (!ifshow || row.isproShipcondition==1)&& row.issuedStatus != 'YXD' ){
					return "<img src='images/icon/green.gif' />";
				} else {
					return "";
				}
			}
		}, {
			name : 'rate',
			display : '进度',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_borrow_borrow"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注 : '+"<font color='gray'>"+v+"</font>"+'</a>';
			}
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '借试用发货标志',
			name : 'isproShipcondition',
			sortable : true,
			hide : true
		},{
			display : 'isship',
			name : 'isship',
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '建立时间',
			width : '75',
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '交货日期',
			width : 80,
			sortable : true
		}, {
			name : 'chanceId',
			display : '商机Id',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 160,
			sortable : true,
			hide : ifshow
		}, {
			name : 'objCode',
			display : '关联业务编号',
			width : '150',
			sortable : true
		}, {
			name : 'Code',
			display : '编号',
			width : '150',
			sortable : true
		}, {
			name : 'Type',
			display : '类型',
			width : '40',
			sortable : true
		}, {
			name : 'limits',
			display : '范围',
			width : '40',
			sortable : true
		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
			process : function(v) {
				if (v == '0') {
					return "未发货";
				} else if (v == '1') {
					return "已发货";
				} else if (v == '2') {
					return "部分发货";
				} else if (v == '3') {
					return "停止发货";
				}
			},
			width : '60',
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '下达状态',
			sortable : true,
			process : function(v) {
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
			width : 60,
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			hide : true,
			sortable : true
		}, {
			name : 'createName',
			display : '申请人',
			width : 80,
			sortable : true
		}, {
			name : 'salesName',
			display : '销售负责人',
			width : 80,
			sortable : true
		}, {
			name : 'scienceName',
			display : '技术负责人',
			width : 80,
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			width : 280,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			width : 90,
			hide : true,
			sortable : true
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		}, {
			display : '关联业务编号',
			name : 'objCode'
		}],
		sortname : 'ExaDT',
		sortorder : 'DESC',
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'borrowId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
						name : 'productName',
						width : 200,
						display : '产品名称',
						process : function(v,row){
					    	if(row.changeTips == 1 || row.changeTips == 2){
					    		return "<font color = 'red'>"+ v + "</font>"
					    	}else
					    		return v;
					    }
					}, {
					    name : 'number',
					    display : '数量',
						width : 40
					},{
					    name : 'lockNum',
					    display : '锁定数量',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'exeNum',
					    display : '库存数量',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'issuedShipNum',
					    display : '已下达发货数量',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'executedNum',
					    display : '已发货数量',
						width : 60
					},{
					    name : 'issuedPurNum',
					    display : '已下达采购数量',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'issuedProNum',
					    display : '已下达生产数量',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'backNum',
					    display : '退库数量',
						width : 60
					},{
					    name : 'projArraDate',
					    display : '计划交货日期',
						width : 80,
					    process : function(v){
					    	if( v == null ){
					    		return '无';
					    	}else{
					    		return v;
					    	}
					    }
					}]
		},
		// 扩展右键菜单
		menusEx : [{
// text : '查看',
// icon : 'view',
// action : function(row) {
// if (row) {
// showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id="
// + row.id
// + "&skey="
// + row['skey_']
// );
// }
// }
// }, {
			text : '查看详细',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByBorrow&id='
						+ row.id + "&objType=oa_borrow_borrow"
						+ "&skey="
						+ row['skey_']);
			}
		},{
			text : '下达发货计划',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.issuedStatus != 'YXD' && (row.DeliveryStatus == 0 || row.DeliveryStatus == 2)) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&docType=oa_borrow_borrow"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
			}
		},{
			text : '下达采购申请',
			icon : 'edit',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus == 0 || row.DeliveryStatus == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if( row.orderCode == '' )
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
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
			}
		}, {
			text : '下达生产任务',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus == 0
						|| row.DeliveryStatus == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin("?model=produce_protask_protask&action=toAdd&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&docType=oa_borrow_borrow"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
			}
		}, {
// text : '进度备注',
// icon : 'edit',
// action : function(row) {
// showThickboxWin('?model=stock_outplan_contractrate&action=page&docId='
// + row.id
// + "&docType=oa_borrow_borrow"
// + "&objCode="
// + row.objCode
// + "&skey="
// + row['skey_']
// +
// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
// }
// }, {
			text : '锁定库存',
			icon : 'lock',
			showMenuFn : function(row) {
				if (hasEqu(row.id) != 0 && row.DeliveryStatus == 0
						|| row.DeliveryStatus == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.id + "&objCode=" + row.Code
						+ "&skey="
						+ row['skey_']
						+ "&objType=oa_borrow_borrow");
			}
		}, {
			text : "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus != 3) {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=contract_common_allcontract&action=closeCont&skey='+row['skey_'],
					data : {
						id : row.id,
						type : 'oa_borrow_borrow'
					},
					// async: false,
					success : function(data) {
						alert("关闭成功");
						show_page();
						return false;
					}
				});
			}
		}, {
			text : "恢复发货",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == 3) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要恢复发货？')) {
					$.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=recoverCont&skey='+row['skey_'],
						data : {
							id : row.id,
							type : 'oa_borrow_borrow'
						},
						// async: false,
						success : function(data) {
							alert("恢复成功");
							show_page();
							return false;
						}
					});
				}
			}
		}]
	});

});