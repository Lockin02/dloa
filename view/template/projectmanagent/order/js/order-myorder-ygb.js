var show_page = function(page) {
	$("#orderYgbGrid").yxgrid("reload");
};
$(function() {
	$("#orderYgbGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'ygbOrderJson',
		title : '已关闭的合同',
		param : { 'states' : '3,9' },

		isDelAction : false,
		isToolBar : false, //是否显示工具栏
		showcheckbox : false,
		customCode : 'ygb',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'ExaDT',
					display : '建立时间',
					sortable : true,
					width : 80
				},{
					name : 'tablename',
					display : '合同类型',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'oa_sale_order') {
							return "销售合同";
						} else if (v == 'oa_sale_service') {
							return "服务合同";
						} else if (v == 'oa_sale_lease') {
							return "租赁合同";
						} else if (v == 'oa_sale_rdproject') {
							return "研发合同";
						}
					}
				}, {
					name : 'orderCode',
					display : '鼎利合同号',
					sortable : true,
					width : 180
				}, {
					name : 'orderTempCode',
					display : '临时合同号',
					sortable : true,
					width : 180
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 100
				}, {
					name : 'orderName',
					display : '合同名称',
					sortable : true,
					width : 150
				}, {
					name : 'orderTempMoney',
					display : '预计合同金额',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'orderMoney',
					display : '签约合同金额',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'invoiceMoney',
					display : '开票金额',
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'incomeMoney',
					display : '已收金额',
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 60
				}, {
					name : 'sign',
					display : '是否签约',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '归属区域',
					sortable : true,
					width : 60
				}, {
					name : 'prinvipalName',
					display : '合同负责人',
					sortable : true,
					width : 80
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
						} else if (v == '5') {
							return "已合并";
						} else if (v == '5') {
							return "已拆分";
						}
					},
					width : 60
				}, {
					name : 'softMoney',
					display : '软件金额',
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'hardMoney',
					display : '硬件金额',
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'serviceMoney',
					display : '服务金额',
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'repairMoney',
					display : '维修金额',
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'objCode',
					display : '业务编号',
					width : 150
				}],
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
		}],
          menusEx : [{
			text : '查看',
			icon : 'view',
			action: function(row){
				  if(row.tablename == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id=' + row.orgid + "&skey="+row['skey_'] );
				  } else if (row.tablename == 'oa_sale_service'){
				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orgid + "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_lease'){
                     showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orgid + "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_rdproject') {
                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orgid + "&skey="+row['skey_'])
                  }

			}
		   },{

			text : '共享合同',
			icon : 'add',

			action : function(row) {

				showThickboxWin('?model=projectmanagent_order_order&action=toShare&id='
				        + row.orgid
	                    +'&type='
	                    +row.tablename
				        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');

			}
		},{
			text : '导出',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   },{
			text : '附件上传',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
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
			display : '业务编号',
			name : 'objCode'
		}],
		sortname : "createTime",
          //设置新增页面宽度
          toAddConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //设置编辑页面宽度
          toEditConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //设置查看页面宽度
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          }

	});
});