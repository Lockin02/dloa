var show_page = function(page) {
	$("#shareGrid").yxgrid("reload");
};
$(function() {
	$("#shareGrid").yxgrid({
        param : {"toshareNameId": $("#userId").val()},
		model : 'contract_common_share',
		isViewAction : false,
		isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        showcheckbox : false,
		sortorder : "DESC",
		title : '共享的合同',
		menusEx : [
		{
			text : '查看',
			icon : 'view',
			action: function(row){
				  if(row.orderType == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id=' + row.orderId + "&skey="+row['skey_']);
				  } else if (row.orderType == 'oa_sale_service'){
				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.orderType == 'oa_sale_lease'){
                     showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.orderType == 'oa_sale_rdproject') {
                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  }

			}
		},{
			text : '导出',
			icon : 'add',
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.orderId
				                      +'&type='
				                      +row.orderType
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   }],
		// 表单
			colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'orderType',
                  					display : '共享合同类型',
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
                              },{
                    					name : 'orderName',
                  					display : '共享合同名称',
                  					sortable : true,
                  					width : 150
                              },{
                    					name : 'shareName',
                  					display : '共享人',
                  					sortable : true
                              },{
                    					name : 'shareDate',
                  					display : '共享时间',
                  					sortable : true
                              },{
                    					name : 'toshareName',
                  					display : '被共享人',
                  					sortable : true
                              }],
                              comboEx : [ {
									text : '合同类型',
									key : 'orderType',
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
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		}]
	});
});