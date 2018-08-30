/**
 * 下拉产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_goodsproperties', {
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
			nameCol : 'propertiesName',
            openPageOptions : {
                url : '?model=goods_goods_properties&action=pageSelect',
                width : '750'
            },
			gridOptions : {
				showcheckbox : false,
				model : 'goods_goods_properties',
				pageSize : 10,
                // 列信息
                colModel: [{
                    display: 'id',
                    name: 'id',
                    sortable: true,
                    hide: true
                }, {
                    name: 'parentName',
                    display: '上级属性名称',
                    sortable: true,
                    hide: true
                }, {
                    name: 'propertiesName',
                    display: '属性名称',
                    width: 120,
                    sortable: true
                }, {
                    name: 'orderNum',
                    display: '排序',
                    width: '30',
                    sortable: true
                }, {
                    name: 'propertiesType',
                    display: '配置项类型',
                    sortable: true,
                    width: '80',
                    process: function (v) {
                        if (v == "0") {
                            return "单项选择";
                        } else if (v == "1") {
                            return "多项选择";
                        } else if (v == "2") {
                            return "文本输入";
                        } else {
                            return v;
                        }
                    }
                }, {
                    name: 'isLeast',
                    display: '至少选中一项',
                    sortable: true,
                    width: '90',
                    align: 'center',
                    process: function (v) {
                        if (v == "on") {
                            return "√";
                        } else {
                            return v;
                        }
                    }
                }, {
                    name: 'isInput',
                    align: 'center',
                    display: '允许直接输入值',
                    sortable: true,
                    width: '90',
                    process: function (v) {
                        if (v == "on") {
                            return "√";
                        } else {
                            return v;
                        }
                    }
                }, {
                    name: 'remark',
                    display: '备注',
                    width: 200,
                    sortable: true
                }],
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