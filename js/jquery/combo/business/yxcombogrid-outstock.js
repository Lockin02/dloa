/**
 * 下拉发货单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outstock', {
				options : {
					hiddenId : 'id',
					nameCol : 'applyCode',
					gridOptions : {
					model : 'stock_outstock_outapply',
					colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'applyCode',
                  					display : '出库申请单号',
                  					sortable : true
                              },{
                    					name : 'docType',
                  					display : '出库类型',
                  					hide : true
                              },{
                    					name : 'relDocName',
                  					display : '关联单据名称',
                  					sortable : true
                              },{
                    					name : 'relDocId',
                  					display : '关联单据ID',
                  					hide : true
                              },{
                    					name : 'stockName',
                  					display : '发料仓库',
                  					sortable : true
                              },{
                    					name : 'chargeName',
                  					display : '负责人名称',
                  					sortable : true
                              },{
                    					name : 'sendName',
                  					display : '发料人',
                  					hide : true
                              }],
						// 快速搜索
						searchitems : [{
									display : '出库申请单号',
									name : 'applyCode'
								}],
						// 默认搜索字段名
						sortname : "applyCode",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);