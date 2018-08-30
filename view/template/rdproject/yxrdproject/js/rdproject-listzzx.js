// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".rdprojectListZzxGrid").yxgrid("reload");
};
$(function() {
	$(".rdprojectListZzxGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装

		model : 'rdproject_yxrdproject_rdproject',
		action : 'myOrderPageJson',
		param : {
			'states' : '2',
			"orderPrincipalId" : $("#userId").val()
		},
		title : '在执行的研发合同',
		showcheckbox : false,
		isToolBar : false,
		customCode : 'rdlistzzx',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'orderCode',
			display : '鼎利合同号',
			sortable : true,
			width : 210,
			process : function(v,row){
                         if(row.isR == 1){
                             return "<font color = '#0000FF'>" +v;
                         }else if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'orderTempCode',
			display : '临时合同号',
			sortable : true,
			width : 210,
			process : function(v,row){
                         if(row.isR == 1){
                             return "<font color = '#0000FF'>" +v;
                         }else if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'orderName',
			display : '合同名称',
			sortable : true,
			width : 210,
			process : function(v,row){
                         if(row.isR == 1){
                             return "<font color = '#0000FF'>" +v;
                         }else if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'cusName',
			display : '客户名称',
			sortable : true,
			width : 150
		}, {
			name : 'orderTempMoney',
			display : '预计合同金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'orderMoney',
			display : '签约合同金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		},{
			name : 'applyedMoney',
  			display : '已申请开票金额',
  			sortable : false,
  			process : function(v){
  				return moneyFormat2(v);
  			}
        }, {
			name : 'invoiceMoney',
			display : '开票金额',
			sortable : false,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'incomeMoney',
			display : '已收金额',
			sortable : false,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'surOrderMoney',
			display : '签约合同应收账款余额',
			sortable : false,
			width : 120,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(accSub(row.orderMoney,row.incomeMoney,2)) + "</font>"
			}
		}, {
			name : 'surincomeMoney',
			display : '财务应收账款余额',
			sortable : false,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(accSub(row.invoiceMoney,row.incomeMoney,2)) + "</font>"
			}
		}, {
			name : 'areaName',
			display : '归属区域',
			sortable : true,
			width : 100
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
			width : 90
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 100
		}, {
			name : 'sign',
			display : '是否签约',
			sortable : true,
			width : 70
		}, {
			name : 'orderstate',
			display : '纸质合同状态',
			sortable : true,
			width : 100
		}, {
			name : 'parentOrder',
			display : '父合同名称',
			sortable : true,
			hide : true
		}, {
					name : 'objCode',
					display : '业务编号',
					width : 150
				}],

		// 扩展右键菜单
		menusEx : [{
			text : '取消变更提醒',
			icon : 'back',
			showMenuFn : function(row) {
				if (row.isBecome == '1') {
					return true;
				}
				return false;
			},
			action: function(row){
                 if (window.confirm(("确定要取消变更提醒吗？"))) {
                 	$.ajax({
						type : "POST",
						url : "?model=rdproject_yxrdproject_rdproject&action=cancelBecome&id=" + row.id,
						success : function(msg) {
                               $(".rdprojectListZzxGrid").yxgrid("reload");
						}
					});
                 }
			}
		   },{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_'])
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '转为正式合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.orderCode == '' && row.ExaStatus == '完成') {
					return true;
				} else {
					return false;

				}

			},
			action : function(row) {
				showOpenWin('?model=rdproject_yxrdproject_rdproject&action=toBecomeContract&id='
						+ row.id
						+ '&perm=edit'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '开票申请',
			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.orderCode == ''){
//				    if(row.orderTempMoney - row.applyedMoney > 0){
//				       return true;
//				    }else{
//				       return false;
//				    }
//				}else{
//				    if(row.orderMoney - row.applyedMoney > 0){
//				       return true;
//				    }else{
//				       return false;
//				    }
//				}
//			},
			action : function(row) {
				if (row.orderCode != "") {
					showModalWin(
							'?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
									+ row.id + '&invoiceapply[objCode]='
									+ row.orderCode
									+ '&invoiceapply[objType]=KPRK-07', 1);
				} else {
					showModalWin(
							'?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
									+ row.id + '&invoiceapply[objCode]='
									+ row.orderTempCode
									+ '&invoiceapply[objType]=KPRK-08', 1);
				}
			}
		}, {

			text : '变更合同',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批' || row.ExaStatus == '变更审批中') {
					return false;
				}
				return true;
			},
			action : function(row) {
				location = '?model=rdproject_yxrdproject_rdproject&action=toChange&changer=changer&id='
						+ row.id + "&skey=" + row['skey_'];
			}
		}, {

			text : '关闭合同',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '3' || row.ExaStatus == '部门审批'
						|| row.ExaStatus == '变更审批中') {
					return false;
				}
				return true;
			},
			action : function(row) {
               alert("合同关闭功能暂时停用，有问题请联系OA管理员");
//				showThickboxWin('?model=rdproject_yxrdproject_rdproject&action=CloseOrder&id='
//						+ row.id
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}, {
			text : '导出',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.id
								+ '&type=oa_sale_rdproject'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '附件上传',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_order_order&action=toUploadFile&id='
						+ row.id
						+ '&type=oa_sale_rdproject'
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		}, {

			text : '退货申请',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isR == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=projectmanagent_return_return&action=toAdd&orderId='
						+ row.id + "&orderType=oa_sale_rdproject");
			}
		},{

			text : '共享合同',
			icon : 'add',

			action : function(row) {

				showThickboxWin('?model=projectmanagent_order_order&action=toShare&id='
				        + row.id
				        +"&skey="+row['skey_']
	                    +'&type=oa_sale_rdproject'
				        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');

			}
		}],
		// 快速搜索
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		}, {
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		},{
			display : '业务编号',
			name : 'objCode'
		}],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		sortname : "isBecome desc,ExaDT",
		// 显示查看按钮
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false

	});

});