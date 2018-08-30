var show_page = function(page) {
	$("#templist").yxgrid("reload");
};
$(function() {
	$("#templist").yxgrid({
		model: 'projectmanagent_order_order',
		action : 'customizelistJson',
		param : {'ExaStatusV' : "完成"},
		title: '临时物料信息',
			isViewAction : false,
			isEditAction : false,
			isDelAction : false,
			isAddAction : false,
			showcheckbox : false,
		// 扩展右键菜单

		menusEx : [{
			text : '合同信息',
			icon : 'view',
			action: function(row){
				  if(row.tablename == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id=' + row.orderId + "&skey="+row['skey_']);
				  } else if (row.tablename == 'oa_sale_service'){
				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_lease'){
                     showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_rdproject') {
                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  }

			}
		},{
			text : '处理',
			icon : 'edit',
			action : function(row) {
				 showThickboxWin('?model=stock_productinfo_productinfo&action=handle&id='
				    + row.orgId
				    +'&type='
				    +row.tablename
				    +'&placeValuesBefore&TB_iframe=true&modal=false&height=100&width=300');
//				if(confirm("【确认是否将物料新增至物料信息】")){
//				    showThickboxWin('?model=stock_productinfo_productinfo&action=tempadd&id='
//				    + row.id
//				    +'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
//				}else{
//				    alert("不新增");
//				}
			}
		}],

		//列信息
		colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						},{
			        name : 'tablename',
			        display : '合同类型',
			        sortable : true,
			        process : function(v){
  						if( v == 'oa_sale_order'){
  							return "销售合同";
  						}else if(v == 'oa_sale_service'){
  							return "服务合同";
  						}else if(v == 'oa_sale_lease'){
  							return "租赁合同";
  						}else if(v == 'oa_sale_rdproject'){
  							return "研发合同";
  						}
  					}
			          }, {
							display : '所属合同ID',
							name : 'orderId',
							sortable : true,
							hide : true
						}, {
							display : '鼎力合同号',
							name : 'orderCode',
							sortable : true,
							width : 210
						},{
							display : '临时合同号',
							name : 'orderTempCode',
							sortable : true,
							width : 210
						},{
							display : '合同状态',
							name : 'ExaStatus',
							sortable : true,
							hide : true,
							width : 60
						},{
							display : '所属合同',
							name : 'orderName',
							sortable : true,
							width : 150
						},{
							display : '物料名称',
							name : 'productName',
							sortable : true
						}, {
							name : 'productModel',
							display : '规格型号',
							sortable : true
						},  {
							name : 'number',
							display : '数量',
							sortable : true
						},{
							name : 'price',
							display : '单价',
							sortable : true,
							process : function(v){
								return moneyFormat2(v);
				            }
						},{
							name : 'money',
							display : '金额',
							sortable : true,
							process : function(v){
								return moneyFormat2(v);
				            }
						},{
							name : 'projArraDT',
							display : '计划交货日期',
							sortable : true
						},{
							name : 'remark',
							display : '备注',
							sortable : true,
							width : 200
						},{
							name : 'warranty',
							display : '保修期(月)',
							hide : true,
							sortable : true
						} ],
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

		 /**
			 * 快速搜索
			 */
		searchitems : [
		{
			display : '合同编号',
			name : 'orderCodeOrTempSearchV'
		},
		{
			display : '合同名称',
			name : 'orderName'
		}]


	});
});