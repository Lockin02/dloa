/**
 * ������Ʒ������
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
				// ����Ϣ
				colModel : [{
						name : 'id',
						display : 'id',
						hide : true
					},{
						display : '��Ʒ����',
						name : 'goodsName',
						width : 180
					},{
						display : '��ע',
						name : 'remark',
						width : 130
					}],
				// ��������
				searchitems : [{
					display : '��Ʒ����',
					name : 'goodsName'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);