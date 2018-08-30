/**
 * 下拉客户表格组件
 */
(function ($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_projectall', {
        isDown: true,
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
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            openPageOptions: {
                url: '?model=rdproject_project_rdproject&action=pageForAll',
                width: '750'
            },
            gridOptions: {
                showcheckbox: false,
                title: "项目信息",
                isTitle: true,
                model: 'rdproject_project_rdproject',
                action: 'pageJsonForAll',
                // 列信息
                colModel: [
                    {
                        display: '项目类型',
                        name: 'projectType',
                        width: 70,
                        process: function (v) {
                            if (v == 'esm') {
                                return '工程项目';
                            } else if (v == 'con') {
                                return '产品项目';
                            } else {
                                return '研发项目';
                            }
                        }
                    },
                    {
                        display: '项目编号',
                        name: 'projectCode',
                        width: 150
                    },
                    {
                        display: '项目名称',
                        name: 'projectName',
                        width: 180
                    },
                    {
                        display: '项目经理',
                        name: 'managerName',
                        width: 90
                    }
                ],
                // 快速搜索
                searchitems: [
                    {
                        display: '项目名称',
                        name: 'projectNameSearch'
                    },
                    {
                        display: '项目编号',
                        name: 'projectCodeSearch'
                    }
                ],
                // 默认搜索字段名
                sortname: "id",
                // 默认搜索顺序
                sortorder: "DESC",
                //过滤数据
                comboEx: [
                    {
                        text: '项目类型',
                        key: 'projectType',
                        data: [
                            {
                                text: '研发项目',
                                value: 'rd'
                            },
                            {
                                text: '工程项目',
                                value: 'esm'
                            }
                        ]
                    }
                ]
            }
        }
    });
})(jQuery);