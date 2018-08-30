var show_page = function () {
    $("#baseinfoGrid").yxgrid("reload");
};

$(function () {
    $("#baseinfoGrid").yxgrid({
        model: 'yxlicense_license_baseinfo',
        title: 'license基本信息',
        isEditAction: false,
        isDelAction: false,
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'isUse',
                display: '启用',
                sortable: true,
                width: 30,
                align: 'center',
                process: function (v, row) {
                    if (row.isUse == '1') {
                        return '<img src="images/icon/ok3.png" title="生效"/>';
                    }
                }
            },
            {
                name: 'name',
                display: '名称',
                sortable: true,
                width: 150,
                process: function (v, row) {
                    return "<a href='javascript:void(0)' " +
                    "onclick='showModalWin(\"?model=yxlicense_license_category&action=preview&id=" + row.id
                    + "&licenseName=" + row.name + "\")'>" + v + "</a>";
                }
            },
            {
                name: 'remark',
                display: '备注',
                sortable: true,
                width: 280
            },
            {
                name: 'createName',
                display: '创建人',
                sortable: true
            },
            {
                name: 'createTime',
                display: '创建日期',
                sortable: true,
                width: 130
            }
        ],
        toViewConfig: {
            formHeight: 200,
            formWidth: 400
        },
        menusEx: [
            {
                text: '编辑',
                icon: 'edit',
                action: function (row, rows, grid) {
                    $.post('?model=yxlicense_license_baseinfo&action=checkExists', {'id': row.id},
                        function (data) {
                            if (data == '1') {
                                alert('此模板已被使用，无法编辑，请复制后再进行编辑');
                            } else {
                                showThickboxWin("?model=yxlicense_license_baseinfo&action=init&id="
                                + row.id
                                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=850");
                            }
                        });
                }
            },
            {
                text: '删除',
                icon: 'delete',
                action: function (row, rows, grid) {
                    $.post('?model=yxlicense_license_baseinfo&action=checkExists', {'id': row.id},
                        function (data) {
                            if (data == '1') {
                                alert('此模板已被使用，无法删除');
                            } else {
                                if (confirm('确认删除？')) {
                                    $.post('?model=yxlicense_license_baseinfo&action=ajaxdeletes',
                                        {id: row.id},
                                        function (data) {
                                            if (data == '1') {
                                                alert('删除成功');
                                                show_page();
                                            }
                                        }
                                    );
                                }
                            }
                        });
                }
            },
            {
                text: '分类',
                icon: 'view',
                action: function (row, rows, grid) {
                    $.post('?model=yxlicense_license_baseinfo&action=checkExists', {'id': row.id},
                        function (data) {
                            if (data == '1') {
                                showModalWin("?model=yxlicense_license_category&action=page&id="
                                + row.id + "&name="
                                + row.name
                                + "&isUse=1");
                            } else {
                                showModalWin("?model=yxlicense_license_category&action=page&id="
                                + row.id + "&name="
                                + row.name);
                            }
                        });
                }
            },
            {
                text: '关闭',
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.isUse == '1';
                },
                action: function (row, rows, grid) {
                    if (confirm("确认关闭模板？")) {
                        $.post('?model=yxlicense_license_baseinfo&action=closeTemp', {'id': row.id},
                            function (data) {
                                if (data == '1') {
                                    show_page();
                                }
                            });
                    }
                }
            },
            {
                text: '开启',
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.isUse == '0';
                },
                action: function (row, rows, grid) {
                    var appendStr = row.nXmlFileName != "" ? "复制的license会覆盖原license的关联规则，" : "";
                    if (confirm(appendStr + "确认启用license吗？")) {
                        $.post('?model=yxlicense_license_baseinfo&action=openTemp', {'id': row.id},
                            function (data) {
                                if (data == '1') {
                                    show_page();
                                }
                            });
                    }
                }
            },
            {
                text: '预览',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin("?model=yxlicense_license_category&action=preview&id="
                        + row.id + "&licenseName=" + row.name
                    );
                }
            },
            {
                text: '复制',
                icon: 'add',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=yxlicense_license_baseinfo&action=toCopy&id="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=700");
                }
            },
            {
                text: '导出',
                icon: 'excel',
                action: function (row, rows, grid) {
                    window.open(
                        "?model=yxlicense_license_baseinfo&action=exportExcel&id="
                        + row.id + "&licenseName=" + row.name,
                        "", "width=200,height=200,top=200,left=200");
                }

            }
        ],
        searchitems: [
            {
                display: "名称",
                name: 'name'
            }
        ],
        //过滤数据
        comboEx: [{
            text: '启用',
            key: 'isUse',
            data: [{
                text: '是',
                value: '1'
            }, {
                text: '否',
                value: '0'
            }]
        }]
    });
});