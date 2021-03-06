// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".wtjGrid").yxgrid("reload");
};
$(function() {
	$(".wtjGrid").yxgrid({
		//如果传入url，则用传入的url，否则使用model及action自动组装

		model : 'engineering_serviceContract_serviceContract',
		action : 'myPagejson',
        param : {
			'ExaStatus' : '未审批' , "orderPrincipalId" : $("#userId").val()
		},
		title : '未审批服务合同',
		showcheckbox : false,
		isToolBar : false,
		customCode : 'serwtj',
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
                    name : 'cusName',
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
              }, {
					name : 'objCode',
					display : '业务编号',
					width : 150
				}
          ],
		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id="
							+ row.id + "&skey="+row['skey_'])
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&id="
							+ row.id + "&skey="+row['skey_'])
				} else {
					alert("请选中一条数据");
				}
			}
		},{
				text: "删除",
				icon: 'delete',
				action: function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_serviceContract_serviceContract&action=ajaxdeletesOrder",
							data : {
								id : row.id,
								type : "service"
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page(1);
								}else{
									alert("删除失败! ");
								}
							}
						});
					}
				}
			}, {
			text : '提交审核',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/engineering/serviceContract/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		},{
				text : '审批情况',
				icon : 'view',
	            showMenuFn : function (row) {
	               if (row.ExaStatus=='保存'){
	                   return true;
	               }
	                   return true;
	            },
				action : function(row) {

					     showThickboxWin('controller/engineering/serviceContract/readview.php?itemtype=oa_sale_service&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');

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
				                      +'&type=oa_sale_service'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   },{
			text : '附件上传',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_service'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
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