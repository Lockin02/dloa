var show_page = function () {
    $("#applyGrid").yxgrid("reload");
};
$(function () {
    $("#applyGrid").yxgrid({
        model: 'outsourcing_outsourcing_apply',
        title: '外包申请',
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        isOpAction: false,
        showcheckbox: false,
        param: {exaStatusArr: '部门审批,完成,打回'},
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'exaStatus',
                display: '审批状态',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (row.exaStatus && row.exaStatus != 0) {
                        return row.exaStatus;
                    } else {
                        return "未提交";
                    }
                }
            }, {
                name: 'state',
                display: '外包状态',
                sortable: true,
                width: 70,
                align: 'center',
                process: function (v) {
                    switch (v) {
                        case '0':
                            return '未提交';
                        case '1':
                            return '受理中';
                        case '2':
                            return '已打回';
                        case '3':
                            return '审批中';
                        case '4':
                            return '完成审批';
                        case '5':
                            return '关闭';
                        case '6':
                            return '实施中';
                        default:
                            return '';
                    }
                }
            }, {
                name: 'applyCode',
                display: '单据编号',
                width: 150,
                sortable: true,
                process: function (v, row) {
                    return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id + "\")'>" + v + "</a>";
                }
            }, {
                name: 'projecttName',
                display: '项目名称',
                width: 120,
                sortable: true
            }, {
                name: 'projectCode',
                display: '项目编号',
                width: 120,
                sortable: true
            }, {
                name: 'projectClientType',
                display: '项目客户类型',
                width: 120,
                sortable: true
            }, {
                name: 'projectCharge',
                display: '项目负责人',
                width: 120,
                sortable: true
            }, {
                name: 'outType',
                display: '外包方式',
                width: 90,
                sortable: true,
                process: function (v, row) {
                    if (row.outType == 1) {
                        return "整包";
                    } else if (row.outType == 2) {
                        return "分包";
                    } else {
                        return "人员租赁/自由人";
                    }
                }
            }, {
                name: 'createName',
                display: '申请人',
                width: 60,
                sortable: true
            }, {
                name: 'createTime',
                display: '申请日期',
                sortable: true,
                process: function (v, row) {
                    return row.createTime.substr(0, 10);
                }
            }],
        menusEx: [
            {
                text: "查看",
                icon: 'view',
                action: function (row) {
                    showModalWin("?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id, 'newwindow1', 'resizable=yes,scrollbars=yes');
                }
            }, {
                name: 'aduit',
                text: '审批情况',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.exaStatus == '打回' || row.exaStatus == '完成' || row.exaStatus == '部门审批') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_apply&pid="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
                name: 'aduit',
                text: '打回查看',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.state == '2') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toBackView&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }
        ],
        //下拉过滤
        comboEx: [{
            text: '外包状态',
            key: 'state',
            data: [{
                text: '未提交',
                value: '0'
            }, {
                text: '受理中',
                value: '1'
            }, {
                text: '已打回',
                value: '2'
            }, {
                text: '审批中',
                value: '3'
            }, {
                text: '完成审批',
                value: '4'
            }, {
                text: '关闭',
                value: '5'
            }, {
                text: '实施中',
                value: '6'
            }]
        }, {
            text: '外包方式',
            key: 'outType',
            data: [{
                text: '整包',
                value: '1'
            }, {
                text: '分包',
                value: '2'
            }, {
                text: '人员租赁',
                value: '3'
            }]
        }],
        searchitems: [{
            display: '申请单号',
            name: 'applyCodeSearch'
        }, {
            display: '项目名称',
            name: 'projecttNameLike'
        }, {
            display: '项目编号',
            name: 'projectCodeLike'
        }, {
            display: '项目负责人',
            name: 'projectCharge'
        }, {
            display: '申请人',
            name: 'createNameLike'
        }, {
            display: '申请日期',
            name: 'createTimeLike'
        }]
    });
});