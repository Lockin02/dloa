var show_page = function () {
    $("#costajustGrid").yxgrid("reload");
};

$(function () {
    $("#costajustGrid").yxgrid({
        model: 'finance_costajust_costajust',
        title: '成本调整单',
        isDelAction: false,
        isEditAction: false,
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'stockbalId',
                display: '期初余额id',
                hide: true,
                process: function (v, row) {
                    return v + "<input type='hidden' id='isLater" + row.id + "' value='unde'/>";
                }
            },
            {
                name: 'formNo',
                display: '单据号',
                sortable: true,
                width: 140
            },
            {
                name: 'formType',
                display: '单据类型',
                sortable: true,
                datacode: 'CBTZ'
            },
            {
                name: 'formDate',
                display: '日期',
                sortable: true
            },
            {
                name: 'stockName',
                display: '仓库名称',
                sortable: true
            },
            {
                name: 'deptName',
                display: '部门',
                sortable: true
            },
            {
                name: 'salesman',
                display: '业务员',
                sortable: true
            },
            {
                name: 'createName',
                display: '创建人名称',
                sortable: true
            },
            {
                name: 'createTime',
                display: '创建时间',
                sortable: true,
                width: 140
            }
        ],
        toAddConfig: {
            formWidth: 900,
            formHeight: 500
        },
        toViewConfig: {
            formWidth: 900,
            formHeight: 500
        },
        // 扩展列表按钮
        buttonsEx: [
            {
                text: '导入',
                icon: 'excel',
                action: function () {
                    showThickboxWin("?model=finance_costajust_costajust&action=toImport"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=600")
                }
            }
        ],
        menusEx: [
            {
                text: "编辑",
                icon: 'edit',
                showMenuFn: function (row) {
                    var thisLater = $("#isLater" + row.id);
                    if (thisLater.val() == 'unde') {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_period_period&action=isLaterPeriod",
                            data: {"thisDate": row.formDate },
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    thisLater.val(1);
                                } else {
                                    thisLater.val(0);
                                }
                            }
                        });
                    }
                    if (thisLater.val() == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin("?model=finance_costajust_costajust"
                        + "&action=init"
                        + "&id="
                        + row.id
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
                        + "&width=900");
                }
            },
            {
                text: "删除",
                icon: 'delete',
                showMenuFn: function (row) {
                    var thisLater = $("#isLater" + row.id);
                    if (thisLater.val() == 'unde') {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_period_period&action=isLaterPeriod",
                            data: {"thisDate": row.formDate },
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    thisLater.val(1);
                                } else {
                                    thisLater.val(0);
                                }
                            }
                        });
                    }
                    if (thisLater.val() == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("确定要删除?"))) {
                        if (row.stockbalId != "") {
                            $.ajax({
                                type: "POST",
                                url: "?model=finance_costajust_costajust&action=deleteChange",
                                data: {
                                    "id": row.id,
                                    "stockbalId": row.stockbalId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('删除成功！');
                                        show_page();
                                    } else {
                                        alert('删除失败');
                                    }
                                }
                            });
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "?model=finance_costajust_costajust&action=ajaxdeletes",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('删除成功！');
                                        show_page();
                                    } else {
                                        alert('删除失败');
                                    }
                                }
                            });
                        }
                    }
                }
            }
        ]
    });
});