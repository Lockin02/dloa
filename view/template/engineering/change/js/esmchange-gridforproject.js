var show_page = function () {
    $("#esmchangeGrid").yxgrid("reload");

    //刷新项目编辑页面
    reloadTab('项目计划');
    reloadTab('项目预算');
    reloadTab('项目设备');
};

//重新刷新tab
function reloadTab(thisVal) {
    var tt = window.parent.$("#tt");
    var tb = tt.tabs('getTab', thisVal);
    try {
        tb.panel('options').headerCls = tb.panel('options').thisUrl;
    } catch(e) {
        console.log(e);
    }
}

$(function () {
    var isManager = $("#isManager").val();
    $("#esmchangeGrid").yxgrid({
        model: 'engineering_change_esmchange',
        title: '项目变更申请单',
        param: {"projectId": $("#projectId").val()},
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'applyDate',
            display: '申请日期',
            sortable: true,
            width: 70
        }, {
            name: 'newBudgetAll',
            display: '总预算(新)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetAll) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetAll',
            display: '总预算(旧)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetField',
            display: '现场预算(新)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetField) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetField',
            display: '现场预算(旧)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetPerson',
            display: '人力预算(新)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetPerson) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetPerson',
            display: '人力预算(旧)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetEqu',
            display: '设备预算(新)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetEqu) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetEqu',
            display: '设备预算(旧)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetOutsourcing',
            display: '外包预算(新)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetOutsourcing) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetOutsourcing',
            display: '外包预算(旧)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetOther',
            display: '其他预算(新)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetOther) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetOther',
            display: '其他预算(旧)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newPlanEndDate',
            display: '项目结束日期(新)',
            sortable: true,
            width: 100,
            process: function (v, row) {
                if (v != row.orgPlanEndDate) {
                    return "<span class='red'>" + v + "</span>";
                }
                return v;
            }
        }, {
            name: 'orgPlanEndDate',
            display: '项目结束日期(旧)',
            sortable: true,
            width: 100
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width: 60
        }, {
            name: 'ExaDT',
            display: '审批日期',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'changeDescription',
            display: '变更说明',
            sortable: true,
            width: 150
        }, {
            name: 'createTime',
            display: '创建日期',
            sortable: true,
            width: 120,
            hide: true
        }],
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=engineering_change_esmchange&action=toView&id=" + rowData[p.keyField], 1);
            }
        },
        // 扩展右键菜单
        menusEx: [{
            text: '提交变更',
            icon: 'add',
            showMenuFn: function (row) {
                return isManager != "0" && (row.ExaStatus == "待提交" || row.ExaStatus == '打回');
            },
            action: function (row) {
                //判断项目计划下级任务中百分比是否等于100
                var result = $.ajax({
                    type: 'GET',
                    url: '?model=engineering_change_esmchangeact&action=workRateCountNew&changeId=' + row.id + '&parentId=-1',
                    async: false
                }).responseText;
                result = eval("(" + result + ")");
                if (result.count < 100) {
                    alert('错误!\n项目任务-下级任务中 ' + result.parentName + ' 工作占比总和' + result.count + '%,未达100%');
                    return false;
                }
                if (result.count > 100) {
                    alert('错误!\n项目任务-下级任务中 ' + result.parentName + ' 工作占比总和' + result.count + '%,超过100%');
                    return false;
                }
                //试用项目时，验证项目实施周期及预算是否超出原PK申请时的设置
                if (row.contractType == 'GCXMYD-04') {
                    var isPKOverproof = false;
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_change_esmchange&action=isPKOverproof",
                        data: {'id': row.id},
                        async: false,
                        success: function (data) {
                            if (data == 1) {
                                alert("项目实施周期超出原PK申请时的设置，请修改后重新提交审批!");
                                isPKOverproof = true;
                            }
                            if (data == 2) {
                                alert("项目预算超出原PK申请时的设置，请修改后重新提交审批!");
                                isPKOverproof = true;
                            }
                        }
                    });
                    if(isPKOverproof){
                        return false;
                    }
                    if (confirm('项目当前满足变更不需要审批的条件，是否确认变更？')) {
                        //更新变更记录的计划开始和计划结束
                        $.ajax({
                            type: 'POST',
                            url: '?model=engineering_change_esmchange&action=ajaxChange',
                            data: {'id': row.id},
                            success: function (data) {
                                if (data == "1") {
                                    alert('变更完成');
                                    show_page();
                                } else {
                                    alert('变更失败');
                                }
                            }
                        });
                    }
                } else {
                    if (row.contractType == 'GCXMYD-01') {
                        if (row.newBudgetAll * 1 > $("#estimates").val() * 1) {
                            alert("项目预算不能大于项目概算，请修改后重新提交审批!");
                            return false;
                        }
                    }
                    showThickboxWin('?model=engineering_change_esmchange&action=toEdit&id='
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                }
            }
        }, {
            name: 'aduit',
            text: '审批情况',
            icon: 'view',
            showMenuFn: function (row) {
                return row.ExaStatus != "待提交";
            },
            action: function (row) {
                showThickboxWin("controller/common/readview.php?itemtype=oa_esm_change_baseinfo&pid="
                + row.id
                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
            }
        }, {
            text: '删除',
            icon: 'delete',
            showMenuFn: function (row) {
                return isManager != "0" && (row.ExaStatus == "待提交" || row.ExaStatus == '打回');
            },
            action: function (rowData, rows, rowIds, g) {
                g.options.toDelConfig.toDelFn(g.options, g);
            }
        }],
        // 审批状态数据过滤
        comboEx: [{
            text: "审批状态",
            key: 'ExaStatus',
            type: 'workFlow'
        }]
    });
});