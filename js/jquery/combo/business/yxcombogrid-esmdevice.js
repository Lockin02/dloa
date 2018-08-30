/**
 * 物料基本信息下拉表格组件
 */
(function ($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_esmdevice', {
        isDown: false,
        setValue: function (rowData) {
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
            nameCol: 'device_name',
            openPageOptions: {
                url: '?model=engineering_device_esmdevice&action=selectDevice',
                width: '750'
            },
            checkNum: true,//验证设备库存数量
            gridOptions: {
                isTitle: true,
                title: '设备选择',
                showcheckbox: false,
                model: 'engineering_device_esmdevice',
                action: 'selectJson',
                pageSize: 10,
                // 列信息
                colModel: [
                    {
                        display: 'id',
                        name: 'id',
                        sortable: true,
                        hide: true
                    },
                    {
                        name: 'deviceType',
                        display: '设备类型',
                        sortable: true,
                        width: 150
                    },
                    {
                        name: 'device_name',
                        display: '设备名称',
                        sortable: true,
                        width: 150
                    },
                    {
                        name: 'unit',
                        display: '单位',
                        sortable: true,
                        width: 60
                    }
                ],
                // 快速搜索
                searchitems: [
                    {
                        display: '设备名称',
                        name: 'device_nameSearch'
                    }
                ],
                // 默认搜索字段名
                sortname: "typeid,id",
                // 默认搜索顺序
                sortorder: "asc"
            }
        }
    });
})(jQuery);