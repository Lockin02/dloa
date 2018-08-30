var show_page = function(page) {
	$("#arrivalGrid").yxsubgrid("reload");
};
$(function() {
			$("#arrivalGrid").yxsubgrid({
				model : 'purchase_arrival_arrival',
               	title : '未执行收料通知单',
               	showcheckbox:false,
               	isEditAction:false,
               	isViewAction:false,
               	isDelAction:false,
               	param:{'state':"0"},
						//列信息
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'arrivalCode',
      					display : '收料单号',
      					sortable : true,
      					width:180
                  },{
        					name : 'state',
      					display : '收料通知单状态',
      					sortable : true,
						process : function(v, row) {
							if (row.state == '0') {
								return "未执行";
							} else {
								return "已执行";
							}
						}
                  },{
        					name : 'purchaseId',
      					display : '订单id',
      					hide : true
                  },{
        					name : 'purchaseCode',
      					display : '采购订单编号',
      					sortable : true,
      					width:180
                  },{
        					name : 'arrivalType',
      					display : '收料类型',
      					sortable : true,
                  		datacode:'ARRIVALTYPE'
                  },{
        					name : 'supplierName',
      					display : '供应商名称',
      					sortable : true,
      					width:150
                  },{
        					name : 'supplierId',
      					display : '供应商id',
      					hide : true
                  },{
        					name : 'purchManId',
      					display : '采购员ID',
      					hide : true
                  },{
        					name : 'purchManName',
      					display : '采购员',
      					sortable : true
                  },{
        					name : 'purchMode',
      					display : '采购方式',
      					hide : true,
                  		datacode:'cgfs'
                  },{
        					name : 'stockId',
      					display : '收料仓库Id',
      					hide : true
                  },{
        					name : 'stockName',
      					display : '收料仓库名称',
      					sortable : true
                  }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=purchase_arrival_equipment&action=pageJson',
			param : [{
						paramId : 'arrivalId',
						colId : 'id'
					}],
			colModel : [ {
						name : 'sequence',
						display : '物料编号'
					}, {
						name : 'productName',
						width : 200,
						display : '物料名称'
					},{
						name : 'batchNum',
						display : "批次号"
					},{
						name : 'arrivalDate',
						display : "收料日期"
					},{
						name : 'month',
						display : "月份"
					}, {
						name : 'arrivalNum',
						display : "收料数量"
					},{
						name : 'storageNum',
						display : "已入库数量"
					}]
		},
		// 扩展右键菜单
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="
							+ row.id
							+"&skey="+row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800");
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			name : 'edit',
			text : '编辑',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=init&id="
							+ row.id
							+"&skey="+row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		},
			{
			    text:'删除',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.state==0){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_arrival_arrival&action=deletesConfirm",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==0){//判断是否有生成质检
			    		                alert('该收料单已生成质检申请单，不可删除');
			    		                show_page();
			    		            }else{
							    		if(window.confirm("确认要删除?")){
							    		     $.ajax({
							    		         type:"POST",
							    		         url:"?model=purchase_arrival_arrival&action=deletesInfo",
							    		         data:{
							    		         	id:row.id
							    		         },
							    		         success:function(msg){
							    		            if(msg==1){
							    		                alert('删除成功!');
							    		                show_page();
							    		            }
							    		         }
							    		     });
							    		}
			    		            }
			    		         }
			    		     });
			    	}
			    }
			}],
                              toAddConfig : {

									/**
									 * 新增表单默认宽度
									 */
									formWidth :1000,
									/**
									 * 新增表单默认高度
									 */
									formHeight :500
								},
				searchitems : [{
					display : '收料单号',
					name : 'arrivalCode'
				}, {
					display : '采购员',
					name : 'purchManName'
				},{
					display : '供应商',
					name : 'supplierName'
				},{
					display : '物料名称',
					name : 'productName'
					},{
					display : '物料编号',
					name : 'sequence'
				}],
				// 默认搜索顺序
				sortorder : "DESC",
				sortname:"updateTime"
 		});
 });