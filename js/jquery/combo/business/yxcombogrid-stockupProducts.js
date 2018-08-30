/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockupProducts', {
		isDown : false,
		setValue : function(rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options : {
			hiddenId : 'id',
			nameCol : 'typeName',
			openPageOptions : {
				url : '?model=stockup_basic_products&action=pageSelect',
				width : '750'
			},
			gridOptions : {
				showcheckbox : false,
				model : 'stockup_basic_products',
				action : 'jsonSelect',
				//列信息
						colModel : [{
         								name : 'id',
										display : '产品ID',
										hide : true
							        },{
											name : 'productName',
											display : '产品名称',
											width:100,
											sortable : true
										},{
										name : 'productCode',
										display : '产品编号',
										width:100,
										sortable : true
		                              },{
											name : 'remark',
											display : '备注',
											width:200,
											sortable : true
									  }],
						// 快速搜索
						searchitems : [{
								display : '产品名称',
								name : 'productName'
							}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);