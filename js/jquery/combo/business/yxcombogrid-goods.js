/**
 * 下拉产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_goods', {
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
			nameCol : 'goodsName',
            openPageOptions : {
                url : '?model=goods_goods_goodsbaseinfo&action=pageSelect',
                width : '750'
            },
			gridOptions : {
				showcheckbox : false,
				model : 'goods_goods_goodsbaseinfo',
				pageSize : 10,
				// 列信息
				colModel : [{
						name : 'id',
						display : 'id',
						hide : true
					},{
						display : '产品名称',
						name : 'goodsName',
						width : 180
					},{
						display : '备注',
						name : 'remark',
						width : 130
					}],
				// 快速搜索
				searchitems : [{
					display : '产品名称',
					name : 'goodsName'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);