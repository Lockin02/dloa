/**
 * 仓库基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_require',{
		options : {
			hiddenId : 'requireId',
			nameCol : 'requireNo',
			gridOptions : {
				showcheckbox : false,
				model : 'flights_require_require',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'ticketMsg',
					display : '订票状态',
					width:70,
					sortable : true,
					process : function(v, row) {
						if (v == "0" || v == "") {
							return "未生成";
						} else {
							return "已生成";
						}
					},
					hide : true
				},{
					name : 'requireNo',
					display : '订票需求号',
					width:150,
					sortable : true
				}, {
					name : 'airName',
					display : '乘机人',
					sortable : true
				}, {
					name: 'ticketType',
					display: '机票类型',
					sortable: true,
					process: function(v) {
						if (v == "10") {
							return '单程';
						} else if (v == "11") {
							return '往返';
						} else if (v == "12") {
							return '联程';
						}
					},
					width : 80
				},{
					name : 'startPlace',
					display : '出发地点',
					sortable : true
				},{
					name : 'endPlace',
					display : '到达地点',
					width:100,
					sortable : true
				},{
					name : 'startDate',
					display : '出发日期',
					sortable : true
				}],
				searchitems: [{
					display: "订票需求号",
					name: 'requireNoSearch'
				},{
					display: "乘机人",
					name: 'airNameSearch'
				},{
					display: "出发城市",
					name: 'startPlaceSearch'
				},{
					display: "到达城市",
					name: 'endPlaceSearch'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);