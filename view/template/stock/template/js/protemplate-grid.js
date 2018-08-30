var show_page = function(page) {
    $("#protemplateGrid").yxgrid("reload");
};
$(function() {
    $("#protemplateGrid").yxgrid({
        model: 'stock_template_protemplate',
        title: '物料模板配置表',
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        },
        {
            name: 'templateName',
            display: '模板名称',
            width: 200,
            sortable: true
        },
        {
            name: 'remark',
            display: '备注',
            width: 200,
            sortable: true
        },
        {
            name: 'createName',
            display: '创建人',
            sortable: true,
            hide: true
        },
        {
            name: 'createId',
            display: '创建人id',
            sortable: true,
            hide: true
        },
        {
            name: 'createTime',
            display: '创建日期',
            sortable: true,
            hide: true
        },
        {
            name: 'updateName',
            display: '修改人',
            sortable: true,
            hide: true
        },
        {
            name: 'updateId',
            display: '修改人id',
            sortable: true,
            hide: true
        },
        {
            name: 'updateTime',
            display: '修改日期',
            sortable: true,
            hide: true
        }],
        // 主从表格设置
        subGridOptions: {
            url: '?model=stock_template_protrmplateitem&action=pageItemJson',
            param: [{
                paramId: 'mainId',
                colId: 'id'
            }],
            colModel: [{
                name: 'templateName',
                display: '物料名称'
            }]
        },

        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [{
            display: "模板名称",
            name: 'templateName'
        }]
    });
});