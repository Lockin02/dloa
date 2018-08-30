// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".rentalcontractYwgGrid").yxgrid("reload");
};
$(function() {
	$(".rentalcontractYwgGrid").yxgrid({
		//如果传入url，则用传入的url，否则使用model及action自动组装
		//						 url :
		model : 'contract_rental_rentalcontract',
		action : 'myOrderPageJson',
		param : {
			'states' : '3,9' , "hiresId" : $("#userId").val()
		},
		title : '已关闭的合同',
		showcheckbox : false,
		isToolBar : false,
		customCode : 'renlistywg',
		//列信息
		colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
					name : 'orderCode',
  					display : '鼎利合同号',
  					sortable : true,
  					width : 210
              },{
					name : 'orderTempCode',
  					display : '临时合同号',
  					sortable : true,
  					width : 210
              },{
					name : 'orderName',
  					display : '合同名称',
  					sortable : true,
  					width : 210
              },{
                    name : 'tenant',
                    display : '客户名称',
                    sortable : true,
                    width : 150
              },{
                    name : 'orderTempMoney',
                    display : '预计合同金额',
                    sortable : true,
                    width : 100,
                    process : function(v){
  						return moneyFormat2(v);
  					}
              },{
                    name : 'orderMoney',
                    display : '签约合同金额',
                    sortable : true,
                    width : 100,
                    process : function(v){
  						return moneyFormat2(v);
  					}
              },{
                    name : 'areaName',
                    display : '归属区域',
                    sortable : true,
                    width : 100
              },{
					name : 'state',
  					display : '合同状态',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "未提交";
  						}else if(v == '1'){
  							return "审批中";
  						}else if(v == '2'){
  							return "执行中";
  						}else if(v == '3'){
  							return "已关闭";
  						}else if(v == '4'){
  						    return "已完成";
  						}
  					},
  					width : 90
              },{
					name : 'ExaStatus',
  					display : '审批状态',
  					sortable : true,
  					width : 100
              },{
    				name : 'sign',
  					display : '是否签约',
  					sortable : true,
  					width : 70
              },{
    				name : 'orderstate',
  					display : '纸质合同状态',
  					sortable : true,
  					width : 100
              },{
    				name : 'parentOrder',
  					display : '父合同名称',
  					sortable : true,
  					hide : true
              },{
    				name : 'invoiceMoney',
  					display : '开票金额',
  					sortable : true,
  					width : 80,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
    				name : 'incomeMoney',
  					display : '已收金额',
  					sortable : true,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              }, {
					name : 'objCode',
					display : '业务编号',
					width : 150
				}],

		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id="
							+ row.id + "&skey="+row['skey_'])
				} else {
					alert("请选中一条数据");
				}
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
				                      + row.id
				                      +'&type=oa_sale_lease'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   },{
			text : '附件上传',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_lease'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		   },{

			text : '共享合同',
			icon : 'add',

			action : function(row) {

				showThickboxWin('?model=projectmanagent_order_order&action=toShare&id='
				        + row.id
				        +"&skey="+row['skey_']
	                    +'&type=oa_sale_lease'
				        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');

			}
		}],
		//快速搜索
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
		// title : '客户信息',
		//业务对象名称
		//						boName : '供应商联系人',
		sortname : "createTime",
		//显示查看按钮
		isViewAction : false,
		//						isAddAction : true,
		isEditAction : false,
		isDelAction : false

	});

});