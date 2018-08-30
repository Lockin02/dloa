var show_page = function(page) {
	$("#chargeOrderGrid").yxsubgrid("reload");
};
var shipCondition = getQuery('shipCondition',document.URL);
function hasEqu( orgid,type ){
	var equNum = 0
	 $.ajax({
		type : 'POST',
		url : '?model=contract_common_allcontract&action=getEquById',
		data : {
			id : orgid,
			type : type
		},
	    async: false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
function cusNum(orgid,type){
    var cusNum = 0
     $.ajax({
          type : 'POST',
          url : '?model=contract_common_allcontract&action=cusById',
          data : { id : orgid,
                   type : type
                 },
          async: false,
          success : function (data){
                cusNum = data;
                return false ;
          }
     })
     return cusNum ;
}
$(function() {
	$("#chargeOrderGrid").yxsubgrid({
		model : 'projectmanagent_order_order',
		title : '合同发货',
		action : 'shipmentsPageJson',
		customCode : 'ordershipments',
		param : {"ExaStatusArr":"完成,变更审批中","states" : "2,4","DeliveryStatus2" : "7,10","shipCondition" : shipCondition},
		isDelAction : false,
		isToolBar : false, // 是否显示工具栏
		showcheckbox : false,
		buttonsEx : [{
			name : 'export',
			text : "发货数据导出",
			icon : 'excel',
			action : function(row) {
				window.open("?model=contract_common_allcontract&action=contExportExcel"
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
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		}, {
			name : 'rate',
			display : '进度',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.orgid
						+ "&docType="
						+ row.tablename
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注：'+"<font color='gray'>"+v+"</font>"+'</a>';
			}
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'isBecome',
			display : '变更改变日期标志',
			width : 80,
			sortable : true,
			hide : true
		},{
			name : 'objCode',
			display : '合同业务编码',
			width : 80,
			sortable : true
		},{
			name : 'ExaDT',
			display : '建立时间',
			width : 80,
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '交货日期',
			width : 80,
			sortable : true
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 160,
			sortable : true,
			process : function(v,row){
                         if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'orderCode',
			display : '鼎利合同号',
			width : 160,
			sortable : true,
			process : function(v,row){
                         if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'orderTempCode',
			display : '临时合同号',
			width : 160,
			sortable : true,
			process : function(v,row){
                         if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'orderName',
			display : '合同名称',
			width : 180,
			sortable : true,
			hide : true
		}, {
			name : 'tablename',
			display : '合同类型',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "销售合同";
				}else if (v == 'oa_sale_lease') {
					return "租赁合同";
				}else if (v == 'oa_sale_service'){
				    return "服务合同";
				}else if (v == 'oa_sale_rdproject'){
				    return "研发合同";
				}
			}
		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
			sortable : true,
			process : function(v) {
				if (v == '7') {
					return "未发货";
				} else if (v == '8') {
					return "已发货";
				} else if (v == '10') {
					return "部分发货";
				} else if (v == '11') {
					return "停止发货";
				}
			},
			width : 60,
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
			name : 'state',
			display : '合同状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未提交";
				} else if (v == '1') {
					return "审批中";
				} else if (v == '2') {
					return "执行中";
				} else if (v == '3') {
					return "已关闭";
				} else if (v == '4') {
					return "已完成";
				}
			},
			width : 60,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
//			hide : true,
			width : 60,
			sortable : true
		}, {
			name : 'sign',
			display : '是否签约',
			width : 50,
			sortable : true
		}, {
			name : 'orderstate',
			display : '纸质合同状态',
			width : 75,
			sortable : true
		}, {
			name : 'parentOrder',
			display : '父合同名称',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '申请人',
			sortable : true,
			hide : true
		}, {
			name : 'shipCondition',
			display : '发货条件',
			sortable : true,
			hide : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=contract_common_relcontract&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
						paramId : 'orderId',// 传递给后台的参数名称
						colId : 'orgid'// 获取主表行数据的列名称

					},{paramId : 'docType',colId : 'tablename'}],
			// 显示的列
			colModel : [{
						name : 'productName',
						width : 200,
						display : '产品名称',
						process : function(v, data, rowData,$row) {
					    	if(data.changeTips == 1 || data.changeTips == 2){
								if (data.isBorrowToorder == 1) {
									$row.attr("title", "该物料为借试用转销售的物料");
									return "<img src='images/icon/icon147.gif' />"+"<font color = 'red'>"+ v + "</font>";
								}else{
					 		   		return "<font color = 'red'>"+ v + "</font>";
								}
					    	}else{
								if (data.isBorrowToorder == 1) {
									$row.attr("title", "该物料为借试用转销售的物料");
									return "<img src='images/icon/icon147.gif' />" + v;
								}else{
					 		   		return v;
								}
					    	}
					    }
					},{
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
//		lockCol:['orderCode','orderTempCode','orderName'],// 锁定的列名
		comboEx : [ {
			text : '合同类型',
			key : 'tablename',
			data : [ {
				text : '销售合同',
				value : 'oa_sale_order'
			}, {
				text : '租赁合同',
				value : 'oa_sale_lease'
			},{
				text : '服务合同',
				value : 'oa_sale_service'
			},{
				text : '研发合同',
				value : 'oa_sale_rdproject'
			}  ]
		} ,{
			text : '发货状态',
			key : 'DeliveryStatus',
			data : [ {
				text : '未发货',
				value : '7'
			}, {
//				text : '已发货',
//				value : '8'
//			},{
				text : '部分发货',
				value : '10'
//			},{
//				text : '停止发货',
//				value : '11'
			}]
		}],
		menusEx : [{
//			text : '查看',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ( cusNum( row.orgid,row.tablename )== 0 ) {
//					return true;
//				}
//				return false;
//			},
//			action: function(row){
//				  if(row.tablename == 'oa_sale_order'){
//				     showOpenWin('?model=projectmanagent_order_order&action=init&id='
//						+ row.orgid
//                        + '&perm=view'
//                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
//				  } else if (row.tablename == 'oa_sale_service'){
//				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toShipView&perm=view&id=" + row.orgid)
//                  } else if (row.tablename == 'oa_sale_lease'){
//                     showOpenWin("?model=contract_rental_rentalcontract&action=toShipView&perm=view&id=" + row.orgid)
//                  } else if (row.tablename == 'oa_sale_rdproject') {
//                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toShipView&perm=view&id=" + row.orgid)
//                  }
//
//			}
//		}, {
			text : '查看详细',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByOrder&id='
					+ row.orgid
					+ "&objType="
					+ row.tablename
					+ "&skey="
					+ row['skey_']
				);
			}
        },{
			text : '下达发货计划',
			icon : 'add',
			showMenuFn : function(row) {
				if ( shipCondition == 0 && row.ExaStatus != '变更审批中' && row.issuedStatus != 'YXD' && cusNum( row.orgid,row.tablename )== 0 &&( row.DeliveryStatus == 7 || row.DeliveryStatus == 10 )) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.orgid
						+ "&docType="
						+ row.tablename
						+ "&skey="
						+ row['skey_']);
			}
		},{
			text : '下达采购申请',
			icon : 'edit',
			showMenuFn : function(row) {
				if ( row.ExaStatus != '变更审批中' && cusNum( row.ExaStatus != '变更审批中' && row.orgid,row.tablename )== 0  &&( row.DeliveryStatus == 7 || row.DeliveryStatus == 10 )) {
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
						+ row.orgid
						+ "&orderCode="
						+ codeValue
						+ "&objCode="
						+ row.objCode
						+ "&orderName="
						+ row.orderName
						+ "&purchType="
						+ row.tablename
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
			}
		},{
			text : '下达生产任务',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.ExaStatus != '变更审批中' && cusNum( row.orgid,row.tablename )== 0 &&( row.DeliveryStatus == 7 || row.DeliveryStatus == 10 )) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin("?model=produce_protask_protask&action=toAdd&id="
						+ row.orgid
						+ "&docType="
						+ row.tablename
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
			}
		},{
//			text : '进度备注',
//			icon : 'edit',
//			action : function(row) {
//				showThickboxWin('?model=stock_outplan_contractrate&action=page&docId='
//						+ row.orgid
//						+ "&docType="
//						+ row.tablename
//						+ "&objCode="
//						+ row.objCode
//						+ "&skey="
//						+ row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
//			}
//		}, {
			text : '锁定库存',
			icon : 'lock',
			showMenuFn : function(row) {
				if ( row.ExaStatus != '变更审批中' && cusNum( row.orgid,row.tablename )== 0 && row.DeliveryStatus != 8 && row.DeliveryStatus != 9 ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if( row.orderCode == '' )
					var codeValue = row.orderTempCode;
				else
					var codeValue = row.orderCode;
				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.orgid
						+ "&objCode="
						+ codeValue
						+ "&objType="
						+ row.tablename
						+ "&skey="
						+ row['skey_']
					);
			}
        },{
            text: "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus != 11 ) {
					return true;
				}
				return false;
			},
            action: function(row){
            	if(confirm('确定要关闭发货？')){
					 $.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=closeCont&skey=' + row['skey_'],
						data : {
							id : row.orgid,
							type : row.tablename
						},
	//				    async: false,
						success : function(data) {
							alert("关闭成功");
							show_page();
							return false;
						}
					});
           		}
            }
        },{
            text: "恢复发货",
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus == 11 ) {
					return true;
				}
				return false;
			},
            action: function(row){
            	if(confirm('确定要恢复发货？')){
					 $.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=recoverCont&skey=' + row['skey_'],
						data : {
							id : row.orgid,
							type : row.tablename
						},
	//				    async: false,
						success : function(data) {
							alert("恢复成功");
							show_page();
							return false;
						}
					});
          		}
            }
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		},{
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		},{
			display : '客户名称',
			name : 'customer'
		},{
			display : '申请人',
			name : 'createName'
		}],
		sortname : 'isBecome desc,ExaDT',
		sortorder : 'DESC'
	});
});