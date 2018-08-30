/**
 * 物料基本信息下拉表格组件
 */
(function($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_product', {
        isDown: false,
        setValue: function(rowData) {
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
        options: {
            hiddenId: 'id',
            nameCol: 'productCode',
            openPageOptions: {
                url: '?model=stock_productinfo_productinfo&action=selectProduct',
                width: '750'
            },
            closeCheck: false, // 关闭状态,不可选择
            closeAndStockCheck: false, //关闭且校验库存
            gridOptions: {
//                showcheckbox: false,
                model: 'stock_productinfo_productinfo',
                action: 'pageJson',
                param: {
                    // "ext1" : "WLSTATUSKF"
                },
                pageSize: 10,
                // 列信息
                colModel: [{
                        display: '物料编码',
                        name: 'productCode',
                        width: 80
                    }, {
                        display: '物料名称',
                        name: 'productName',
                        width: 180
                    }, {
                        display: '所属分类',
                        name: 'proType'
                    }, {
                        display: '规格型号',
                        name: 'pattern',
                        width: 80
                    }, {
                        name: 'warranty',
                        display: '保修期(月)',
                        width: 60,
                        hide: true
                    }, {
                        display: '状态',
                        name: 'ext1',
                        process: function(v) {
                            if (v == "WLSTATUSKF") {
                                return "开放";
                            } else {
                                return "关闭";
                            }
                        }
                    }, {
                        display: '单位',
                        name: 'unitName',
                        hide: true
                    }, {
                        display: '辅助单位',
                        name: 'aidUnit',
                        hide: true
                    }, {
                        display: '换算率',
                        name: 'converRate',
                        hide: true
                    }],
                // 快速搜索
                searchitems: [{
                        display: '物料编码',
                        name: 'productCode'
                    }, {
                        display: '物料名称',
                        name: 'productName'
                    }],
                // 默认搜索字段名
                sortname: "ext1 desc,id",
                // 默认搜索顺序
                sortorder: "asc"
            }
        }
    });
})(jQuery);