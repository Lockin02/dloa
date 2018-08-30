var show_page = function () {
    $("#propertiesGrid").yxgrid("reload");
};
$(function () {
    // 继承上面的事件
    var combogrid = window.dialogArguments[0];
    var p = combogrid.options;
    var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
    if (eventStr.row_dblclick) {
        var dbclickFunLast = eventStr.row_dblclick;
        eventStr.row_dblclick = function(e, row, data) {
            dbclickFunLast(e, row, data);
            window.returnValue = row.data('data');
            window.close();
        };
    } else {
        eventStr.row_dblclick = function(e, row, data) {
            window.returnValue = row.data('data');
            window.close();
        };
    }

    var gridOptions = combogrid.options.gridOptions;

    $("#propertiesGrid").yxgrid({
        model: 'goods_goods_properties',
        action : gridOptions.action,
        param : gridOptions.param,
        title: '双击选择产品属性',
        isAddAction : false,
        isEditAction: false,
        isViewAction : false,
        isDelAction : false,
        isOpButton : false,
        showcheckbox : false,
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
        searchitems: [{
            display: "属性名称",
            name: 'propertiesName'
        }],
        sortname : gridOptions.sortname,
        sortorder : gridOptions.sortorder,
        // 把事件复制过来
        event : eventStr
    });
});