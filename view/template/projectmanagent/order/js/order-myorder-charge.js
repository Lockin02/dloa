var show_page = function(page) {
	$("#chargeOrderGrid").yxgrid("reload");
};
$(function() {
	$("#chargeOrderGrid").yxgrid({
		model : 'projectmanagent_order_order',
		title : '已关闭的合同',
		action : 'myOrderPageJson',
		param : {
			'states' : '3,9',
			"prinvipalId" : $("#userId").val()
		},
		isDelAction : false,
		isToolBar : false, //是否显示工具栏
		showcheckbox : false,
		customCode :'myordercharge',
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
			width : 210
		}, {
			name : 'orderTempCode',
			display : '临时合同号',
			sortable : true,
			width : 210
		}, {
			name : 'orderName',
			display : '合同名称',
			sortable : true,
			width : 210
		}, {
			name : 'customerName',
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
					name : 'objCode',
					display : '业务编号',
					width : 150
				}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='
						+ row.id
						+ '&perm=view'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
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
				window.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.id
								+ '&type=oa_sale_order'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '附件上传',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_order'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		},{

			text : '共享合同',
			icon : 'add',

			action : function(row) {

				showThickboxWin('?model=projectmanagent_order_order&action=toShare&id='
				        + row.id
				        +"&skey="+row['skey_']
	                    +'&type=oa_sale_order'
				        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');

			}
		}],
		/**
		 * 快速搜索
		 */
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
		sortname : "createTime",
		//设置新增页面宽度
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//设置编辑页面宽度
		toEditConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//设置查看页面宽度
		toViewConfig : {
			formHeight : 500,
			formWidth : 900
		}

	});
});