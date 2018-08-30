var show_page = function (page) {
    $("#applyGrid").yxgrid("reload");
};

$(function () {
    $("#applyGrid").yxgrid({
        model: 'outsourcing_outsourcing_apply',
        action: "pageJsonDeal",
        title: '外包申请受理',
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        isOpAction: false,
        showcheckbox: false,
        bodyAlign: 'center',
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'applyCode',
                display: '单据编号',
                width: 155,
                sortable: true,
                process: function (v, row) {
                    return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
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
                name: 'officeName',
                display: '区域',
                width: 60,
                sortable: true
            }, {
                name: 'province',
                display: '省份',
                width: 60,
                sortable: true
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
                name: 'outType',
                display: '外包方式',
                width: 60,
                sortable: true,
                process: function (v, row) {
                    if (row.outType == 1) {
                        return "整包";
                    } else if (row.outType == 2) {
                        return "分包";
                    } else {
                        return "人员租赁";
                    }
                }
            }, {
                name: 'natureName',
                display: '项目类型',
                width: 70,
                sortable: true
            }, {
                name: 'personSum',
                display: '需求人员数量',
                width: 70,
                sortable: true
            }, {
                name: 'createName',
                display: '申请人',
                width: 60,
                sortable: true
            }, {
                name: 'createTime',
                display: '申请时间',
                width: 120,
                sortable: true
            }, {
                name: 'hopeDate',
                display: '期望到位时间',
                width: 80,
                sortable: true
            }, {
                name: 'ExaDT',
                display: '申请通过时间',
                width: 120,
                sortable: true
            }, {
                name: 'predictDate',
                display: '预计到位时间',
                width: 120
            }],

        lockCol: ['applyCode', 'state'],//锁定的列名
        //下拉过滤
        comboEx: [{
            text: '外包状态',
            key: 'state',
            value: '1',
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
        menusEx: [
            {
                text: "查看",
                icon: 'view',
                action: function (row) {
                    showModalWin("?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id, 'newwindow1', 'resizable=yes,scrollbars=yes');
                }
            }, {
                name: 'aduit',
                text: '申请审批情况',
                icon: 'view',
                showMenuFn: function (row) {
                    return row.exaStatus == '打回' || row.exaStatus == '完成' || row.exaStatus == '部门审批';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_apply&pid="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
            //    text: "立项",
            //    icon: 'add',
            //    showMenuFn: function (row) {
            //        if (row.state == '0') {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toAddByApply&applyId=" + row.id, '1');
            //    }
            //}, {
                text: "受理",
                icon: 'add',
                showMenuFn: function (row) {
                    return row.state == '1';
                },
                action: function (row) {
                    if (confirm("确认受理吗？")) {
                        $.ajax({
                            url: "?model=outsourcing_outsourcing_apply&action=deal",
                            data: {'id': row.id},
                            type: "post",
                            success: function(msg) {
                                if (msg == "1") {
                                    alert("受理成功");
                                    show_page();
                                } else {
                                    alert(msg);
                                }
                            }
                        });
                    }
                }
            }, {
                name: 'aduit',
                text: '打回',
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.state == '1';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toBackApply&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
                text: "关闭",
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.state == '1';
                },
                action: function (row) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toCloseApply&id=" + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
            //    text: "编辑",
            //    icon: 'edit',
            //    showMenuFn: function (row) {
            //        if ((row.appExaStatus == '未审批' || row.appExaStatus == '打回') && row.state == '1' && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toEdit&id=" + row.appId, '1');
            //    }
            //}, {
            //    text: "提交审批",
            //    icon: 'add',
            //    showMenuFn: function (row) {
            //        if ((row.appExaStatus == '未审批' || row.appExaStatus == '打回') && row.state == '1' && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showThickboxWin('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId=' + row.appId + '&flowMoney=0&billDept=' + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
            //    }
            //}, {
            //    text: "立项变更",
            //    icon: 'delete',
            //    showMenuFn: function (row) {
            //        if (( row.appExaStatus == '完成') && row.appId > 0 && row.state == '4') {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toChange&id=" + row.appId, '1');
            //    }
            //}, {
            //    text: "查看外包立项",
            //    icon: 'view',
            //    showMenuFn: function (row) {
            //        if ((row.state == '1' || row.state == '3' || row.state == '4') && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toViewTab&id=" + row.appId, '1');
            //    }
            //}, {
            //    name: 'aduit',
            //    text: '立项审批情况',
            //    icon: 'view',
            //    showMenuFn: function (row) {
            //        if ((row.appExaStatus == '打回' || row.appExaStatus == '完成' || row.appExaStatus == '部门审批') && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row, rows, grid) {
            //        if (row) {
            //            showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_approval&pid="
            //                + row.appId
            //                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
            //        }
            //    }
            //}, {
                name: 'aduit',
                text: '查看打回原因',
                icon: 'view',
                showMenuFn: function (row) {
                    return row.state == '2';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toBackView&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
                name: 'aduit',
                text: '查看关闭原因',
                icon: 'view',
                showMenuFn: function (row) {
                    return row.state == '5';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toCloseView&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }
        ],
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