var show_page = function () {
    $("#esmbudgetGrid").yxgrid("reload");
};

$(function () {
    //缓存项目id
    var projectId = $("#projectId").val();

    //表格部分
    $("#esmbudgetGrid").yxgrid({
        model: 'engineering_budget_esmbudget',
        title: '项目费用预算',
        param: {
            "projectId": projectId
        },
        isAddAction: false,
        isDelAction: false,
        noCheckIdValue: 'noId',
        isOpButton: false,
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'parentName',
                display: '费用大类',
                sortable: true,
                process: function (v, row) {
                    switch (row.budgetType) {
                        case 'budgetField' :
                            return '<span class="blue">' + v + '</span>';
                            break;
                        case 'budgetPerson' :
                            return '<span class="green">' + v + '</span>';
                            break;
                        case 'budgetOutsourcing' :
                            return '<span style="color:gray">' + v + '</span>';
                            break;
                        case 'budgetOther' :
                            return '其他预算';
                            break;
                        case 'budgetTrial' :
                            return '<span style="color:orange">' + v + '</span>';
                            break;
                        case 'budgetFlights' :
                            return '<span style="color:lightseagreen;">' + v + '</span>';
                            break;
                        default :
                            return v;
                    }
                }
            },
            {
                name: 'budgetName',
                display: '费用小类',
                sortable: true,
                width: 120
            },
            {
                name: 'projectCode',
                display: '项目编号',
                sortable: true,
                hide: true
            },
            {
                name: 'projectName',
                display: '项目名称',
                sortable: true,
                hide: true
            },
            {
                name: 'price',
                display: '单价',
                align: 'right',
                process: function (v, row) {
                    if (row.customPrice == "1") {
                        return "<span class='blue' title='自定义价格'>" + moneyFormat2(v) + "</span>";
                    } else if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//导入的数据,已审核的数据标绿
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80
            },
            {
                name: 'numberOne',
                display: '数量1',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + v + "</span>";
                        } else {//导入的数据,已审核的数据标绿
                            return "<span class='green'>" + v + "</span>";
                        }
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'numberTwo',
                display: '数量2',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + v + "</span>";
                        } else {//导入的数据,已审核的数据标绿
                            return "<span class='green'>" + v + "</span>";
                        }
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'amount',
                display: '预算',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//导入的数据,已审核的数据标绿
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80
            },
            {
                name: 'actFee',
                display: '决算',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//导入的数据,已审核的数据标绿
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'amountWait',
                display: '待审核预算',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//导入的数据,已审核的数据标绿
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'actFeeWait',
                display: '待审核决算',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//导入的数据,已审核的数据标绿
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'feeProcess',
                display: '费用进度',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//导入的数据,未审核的数据标红
                            return "<span class='red'>" + v + " %</span>";
                        } else {//导入的数据,已审核的,超过100%的数据标红，不超的标绿
                            if (v * 1 > 100) {
                                return "<span class='red'>" + v + " %</span>";
                            } else {
                                return "<span class='green'>" + v + " %</span>";
                            }
                        }
                    } else {
                        if (v * 1 > 100) {
                            return "<span class='red'>" + v + " %</span>";
                        } else {
                            return v == "" ? "" : v + " %";
                        }
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'budgetType',
                display: '费用属性',
                sortable: true,
                process: function (v) {
                    switch (v) {
                        case 'budgetField' :
                            return '<span class="blue">现场预算</span>';
                            break;
                        case 'budgetPerson' :
                            return '<span class="green">人力预算</span>';
                            break;
                        case 'budgetOutsourcing' :
                            return '<span style="color:gray">外包预算</span>';
                            break;
                        case 'budgetOther' :
                            return '其他预算';
                            break;
                        default:
                            return '';
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'remark',
                display: '备注说明',
                sortable: true,
                process: function (v, row) {
                    if (row.isImport == 1) {//后台导入的数据,添加标红备注提醒
                        if (row.actFee == undefined) {//变更页面显示
                            return "<span class='red'>后台导入数据</span>";
                        } else {
                            if (row.status == 0) {
                                return "<span class='red'>后台导入数据，未审核</span>";
                            } else {
                                return "<span class='green'>后台导入数据，已审核</span>";
                            }
                        }
                    } else {
                        return v;
                    }
                },
                width: 250
            }
        ],
        buttonsEx: [
            {
                name: 'add',
                text: "新增预算",
                icon: 'add',
                items: [
                    {
                        text: '现场预算',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //判断项目是否可以进行变更
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //如果不可变更
                            if (canChange == false) {
                                alert('项目变更审批中，请等待审批完成后再进行变更操作！');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddField&projectId="
                                + projectId, 1, 600, 1000, 'toAddField');
                        }
                    },
                    {
                        text: '人力预算',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //判断项目是否可以进行变更
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //如果不可变更
                            if (canChange == false) {
                                alert('项目变更审批中，请等待审批完成后再进行变更操作！');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddPerson&projectId="
                                + projectId, 1, 600, 1000, 'toAddPerson');
                        }
                    },
                    {
                        text: '外包预算',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //判断项目是否可以进行变更
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //如果不可变更
                            if (canChange == false) {
                                alert('项目变更审批中，请等待审批完成后再进行变更操作！');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddOutsourcing&projectId="
                                + projectId, 1, 600, 1000, 'toAddOutsourcing');
                        }
                    },
                    {
                        text: '其他预算',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //判断项目是否可以进行变更
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //如果不可变更
                            if (canChange == false) {
                                alert('项目变更审批中，请等待审批完成后再进行变更操作！');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddOther&projectId="
                                + projectId, 1, 600, 1000, 'toAddOther');
                        }
                    }
                ]
            },
            {
                text: "删除预算",
                icon: 'delete',
                name: 'batchAdd',
                action: function (row, rows) {
                    if (row) {
                        var canChange = true;
                        var changeId = '';
                        //判断项目是否可以进行变更
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                            data: {
                                "projectId": projectId
                            },
                            async: false,
                            success: function (data) {
                                if (data * 1 == -1) {
                                    canChange = false;
                                }else{
                                    changeId = data;
                                }
                            }
                        });
                        //如果不可变更
                        if (canChange == false) {
                            alert('项目变更审批中，请等待审批完成后再进行变更操作！');
                            return false;
                        }
                        if (confirm('确认删除选中的预算项么？')) {
                            var idArr = [];//正常id
                            for (var i = 0; i < rows.length; i++) {
                                if (rows[i].isImport == 1) {
                                    alert('不能删除后台导入的数据！');
                                    return false;
                                } else {
                                    idArr.push(rows[i].id);
                                }
                            }
                            // try {
                            //     changeId = rows[i].changeId ? rows[i].changeId : '';
                            // } catch (err) {
                            //
                            // }
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxdeletes",
                                data: {
                                    "id": idArr.toString(),
                                    "changeId": changeId,
                                    "projectId": projectId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('删除成功！');
                                        show_page();
                                    } else {
                                        alert('删除失败!');
                                    }
                                }
                            });
                        }
                    } else {
                        alert('请先选择至少一条记录');
                    }
                }
            }
        ],
        // 扩展右键菜单
        menusEx: [
            {
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    //导入的数据不允许删除操作
                    if (row.isImport == 1) {
                        return false;
                    }
                },
                action: function (row) {
                    if (row) {
                        var canChange = true;
                        //判断项目是否可以进行变更
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                            data: {
                                "projectId": projectId
                            },
                            async: false,
                            success: function (data) {
                                if (data * 1 == -1) {
                                    canChange = false;
                                }
                            }
                        });

                        //如果不可变更
                        if (canChange == false) {
                            alert('项目变更审批中，请等待审批完成后再进行变更操作！');
                            return false;
                        }
                        if (window.confirm(("确定要删除?"))) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxdeletes",
                                data: {
                                    "id": row.id,
                                    'changeId': row.changeId,
                                    "projectId": projectId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('删除成功！');
                                        show_page();
                                    } else {
                                        alert("删除失败! ");
                                    }
                                }
                            });
                        }
                    }
                }
            },
            {
                text: '确认',
                icon: 'add',
                showMenuFn: function (row) {
                    //只有导入的数据需要审批操作
                    if (row.isImport == 0 || row.status == 1 || row.actFeeWait == undefined) {
                        return false;
                    }
                },
                action: function (row) {
                    if (row) {
                        if (window.confirm(("审批通过?"))) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxAuditing",
                                data: {
                                    "id": row.id
                                },
                                async: false,
                                success: function (data) {
                                    if (data == 1) {
                                        alert('审批成功!');
                                        show_page();
                                    } else {
                                        alert('审批失败!');
                                    }
                                }
                            });
                        }
                    }
                }
            },
            {
                text: '打回',
                icon: 'delete',
                showMenuFn: function (row) {
                    //只有导入的数据需要打回操作
                    if (row.isImport == 0 || row.status == 1 || row.actFeeWait == undefined) {
                        return false;
                    }
                },
                action: function (row) {
                    if (row) {
                        if (window.confirm(("确定打回?"))) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxBack",
                                data: {
                                    "id": row.id
                                },
                                async: false,
                                success: function (data) {
                                    if (data == 1) {
                                        alert('打回成功!');
                                        show_page();
                                    } else {
                                        alert('打回失败!');
                                    }
                                }
                            });
                        }
                    }
                }
            }
        ],
        toEditConfig: {
            formWidth: 950,
            formHeight: 500,
            showMenuFn: function (row) {
                //导入的数据不允许删除操作
                if (row.isImport == 1) {
                    return false;
                }
            },
            action: 'toEdit',
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                var canChange = true;
                //判断项目是否可以进行变更
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                    data: {
                        "projectId": $("#projectId").val()
                    },
                    async: false,
                    success: function (data) {
                        if (data * 1 == -1) {
                            canChange = false;
                        }
                    }
                });

                //如果不可变更
                if (canChange == false) {
                    alert('项目变更审批中，请等待审批完成后再进行变更操作！');
                    return false;
                }
                if (row.changeId) {
                    return showThickboxWin("?model=engineering_budget_esmbudget&action=toEditChange&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
                } else {
                    return showThickboxWin("?model=engineering_budget_esmbudget&action=toEdit&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
                }
            }
        },
        toViewConfig: {
            formWidth: 900,
            formHeight: 400,
            action: 'toView',
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                if (row.changeId) {
                    if (row.isImport == 1) {//由项目费用维护导入的数据
                        showThickboxWin("?model=engineering_cost_esmcostmaintain&action=toSearchDetailList&projectId=" + row.projectId + "&parentName=" + row.parentName
                            + "&budgetName=" + row.budgetName + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                    } else {
                        if (row.isChanging == "0") {
                            showThickboxWin("?model=engineering_budget_esmbudget&action=toViewChange&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                        } else {
                            showThickboxWin("?model=engineering_budget_esmbudget&action=toViewChange&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                        }
                    }
                } else {
                    if (row.isImport == 1) {//由项目费用维护导入的数据
                        showThickboxWin("?model=engineering_cost_esmcostmaintain&action=toSearchDetailList&projectId=" + row.projectId + "&parentName=" + row.parentName
                            + "&budgetName=" + row.budgetName + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                    } else {
                        showThickboxWin("?model=engineering_budget_esmbudget&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                    }
                }
            }
        },
        //过滤数据
        comboEx: [
            {
                text: '费用属性',
                key: 'budgetType',
                data: [
                    {
                        text: '现场预算',
                        value: 'budgetField'
                    },
                    {
                        text: '人力预算',
                        value: 'budgetPerson'
                    },
                    {
                        text: '外包预算',
                        value: 'budgetOutsourcing'
                    },
                    {
                        text: '其他预算',
                        value: 'budgetOther'
                    },
                    {
                        text: '机票费用',
                        value: 'budgetFlights'
                    },
                    {
                        text: '试用项目',
                        value: 'budgetTrial'
                    }
                ]
            }
        ],
        searchitems: [
            {
                display: "费用小类",
                name: 'budgetNameSearch'
            },
            {
                display: "费用大类",
                name: 'parentNameSearch'
            }
        ],
        sortname: 'c.budgetType,c.parentName',
        sortorder: 'ASC'
    });
});