(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_msginfo', {
		options : {
			hiddenId : 'id',
			nameCol : 'airName',
			gridOptions : {
				showcheckbox : false,
				model : 'flights_message_message',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'msgType',
					display : '结算类型',
					sortable : true,
					width : 50,
					process : function(v){
						switch(v){
							case "0" : return '正常';break;
							case "1" : return '改签';break;
							case "2" : return '退票';break;
						}
					}
				},{
					name : 'airName',
					display : '乘机人',
					sortable : true,
					width : 80
				},{
		            name: 'startDate',
		            display: '乘机日期',
		            sortable: true,
					width : 70
		        },{
		            name: 'flightNumber',
		            display: '车次/航班号',
		            width: 120,
		            sortable: true
		        },{
		            name: 'airline',
		            display: '航空公司',
		            sortable: true
		        },
		        {
		            name: 'costPay',
		            display: '实际需付金额',
		            sortable: true,
					width : 70,
					process : function(v){
						return moneyFormat2(v);
					}
		        }],
				// 快速搜索
				searchitems : [{
					display : '航班号',
					name : 'flightNumberSearch'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);