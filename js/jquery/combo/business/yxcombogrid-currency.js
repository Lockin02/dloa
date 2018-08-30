/**
 * 币别换算
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_currency', {
				options : {
					hiddenId : 'rateId',
					nameCol : 'Currency',
					width:550,
					gridOptions : {
						model : 'system_currency_currency',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'Currency',
                  					display : '币别',
                  					sortable : true
                              },{
                    					name : 'currencyCode',
                  					display : '币别编码',
                  					sortable : true
                              },{
                    					name : 'rate',
                  					display : '汇率',
                  					sortable : true
                              },{
                    					name : 'standard',
                  					display : '本位币',
                  					sortable : true
                              }],

						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : '币别',
									name : 'Currency'
								}],
						sortorder : "ASC",
						title : '货币管理'
					}
				}
			});

})(jQuery);
