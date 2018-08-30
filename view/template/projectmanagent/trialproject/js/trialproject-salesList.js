var show_page = function () {
    $("#salesListGrid").yxgrid("reload");
};
$(function () {

    // 功能按钮
    var buttonsArr = [];
    // 右键菜单
    var menuArr = [{
        text: '查看',
        icon: 'view',
        action: function (row) {
            showModalWin('?model=projectmanagent_trialproject_trialproject&action=viewTab&id='
                + row.id
                + "&skey="
                + row['skey_']
                + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
        }
    }, {
        text: '审批情况',
        icon: 'view',
        showMenuFn: function (row) {
            return row.ExaStatus == '部门审批';
        },
        action: function (row) {
            showThickboxWin('controller/projectmanagent/trialproject/readview.php?itemtype=oa_trialproject_trialproject&pid='
                + row.id
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
        }
    }];

    $.ajax({
        type: 'POST',
        url: '?model=projectmanagent_trialproject_trialproject&action=getLimitArr',
        async: false,
        success: function (data) {
            if (data != "") {
                data = eval("(" + data + ")");

                // 导航功能权限
                if (data['导出权限'] && data['导出权限'] == 1) {
                    buttonsArr = [{
                        name: 'add',
                        text: "导出",
                        icon: 'excel',
                        action: function () {
                            var searchConditionKey = "";
                            var searchConditionVal = "";
                            for (var t in $("#salesListGrid").data('yxgrid').options.searchParam) {
                                if (t != "") {
                                    searchConditionKey += t;
                                    searchConditionVal += $("#salesListGrid").data('yxgrid').options.searchParam[t];
                                }
                            }
                            var i = 1;
                            var colId = "";
                            var colName = "";
                            $("#salesListGrid_hTable").children("thead").children("tr")
                                .children("th").each(function () {
                                    if ($(this).css("display") != "none"
                                        && $(this).attr("colId") != undefined) {
                                        colName += $(this).children("div").html() + ",";
                                        colId += $(this).attr("colId") + ",";
                                        i++;
                                    }
                                });
                            var searchSql = $("#salesListGrid").data('yxgrid').getAdvSql();
                            var searchArr = [];
                            searchArr[0] = searchSql;
                            searchArr[1] = searchConditionKey;
                            searchArr[2] = searchConditionVal;
                            window.open("?model=projectmanagent_trialproject_trialproject&action=exportExcel&colId="
                                + colId
                                + "&colName="
                                + colName
                                + "&searchConditionKey="
                                + searchConditionKey
                                + "&searchConditionVal="
                                + searchConditionVal)
                        }
                    }];
                }

                // 右键功能权限
                if (data['操作权限'] && data['操作权限'] == 1) {
                    menuArr.push({
                        text: '确认试用费用金额',
                        icon: 'add',
                        showMenuFn: function (row) {
                            if (!(row.serCon == "0" || row.serCon == "2") && (row.ExaStatus == "未审批" || row.ExaStatus == "打回" ) && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            showModalWin('?model=projectmanagent_trialproject_trialproject&action=serConedit&id='
                                + row.id
                                + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                        }
                    }, {
                        text: '打回单据',
                        icon: 'delete',
                        showMenuFn: function (row) {
                            if (!(row.serCon == "0" || row.serCon == "2") && (row.ExaStatus == "未审批" || row.ExaStatus == "打回" || row.serCon == "3") && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            if (window.confirm(("确定打回吗？"))) {
                                showThickboxWin('?model=projectmanagent_trialproject_trialproject&action=toBackBill&id='
                                    + row.id
                                    + "&applyName="
                                    + row.applyName
                                    + "&serCon="
                                    + row.serCon
                                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=700');
                            }
                        }
                    }, {
                        text: '生成项目',
                        icon: 'add',
                        showMenuFn: function (row) {
                            if ((row.ExaStatus == "完成" && row.serCon == "1") && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            if (row.status != '2') {
                                alert('操作失败，只有待执行的项目才能生成项目！');
                                return false;
                            }
                            showModalWin('?model=engineering_project_esmproject&action=toAddProject&contractId='
                                + row.id
                                + "&contractCode="
                                + row.projectCode
                                + "&contractType=GCXMYD-04"
                            );
                        }
                    }, {
                        text: '延期申请确认',
                        icon: 'add',
                        showMenuFn: function (row) {
                            if (row.serCon == '3' && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            showModalWin('?model=projectmanagent_trialproject_extension&action=toEdit&id='
                                + row.id
                                + "&skey="
                                + row['skey_']
                                + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                        }
                    });
                }
            }
        }
    });

    $("#salesListGrid").yxgrid({
        model: 'projectmanagent_trialproject_trialproject',
        action: 'trialprojectPageJson',
        title: '试用项目',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        isOpButton: false,
        customCode: 'trialprojectGrid',
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '试用号',
            sortable: true
        }, {
            name: 'projectName',
            display: '试用项目名称',
            sortable: true
        }, {
            name: 'beginDate',
            display: '试用开始时间',
            sortable: true,
            width: 80
        }, {
            name: 'closeDate',
            display: '试用结束时间',
            sortable: true,
            width: 80
        }, {
            name: 'budgetMoney',
            display: '预计金额',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'affirmMoney',
            display: '确认预算金额',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'serCon',
            display: '提交状态',
            sortable: true,
            process: function (v, row) {
                if (row.id == "noId") {
                    return '';
                }
                switch (v) {
                    case '0' :
                        return '未提交';
                        break;
                    case '1' :
                        return '已提交';
                        break;
                    case '2' :
                        return '打回';
                        break;
                    case '3' :
                        return '延期申请';
                        break;
                    case '4' :
                        return '延期申请打回';
                        break;
                    case '5' :
                        return '延期申请审批';
                        break;
                    default :
                        return v;
                }
            },
            width: 80
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width: 80
        }, {
            name: 'status',
            display: '项目状态',
            sortable: true,
            process: function (v, row) {
                if (row.id == "noId") {
                    return '';
                }
                switch (v) {
                    case '0' :
                        if (row.serCon == '1') {
                            return '成本确认中';
                        } else {
                            return '未提交';
                        }
                    case '1' :
                        return '审批中';
                    case '2' :
                        return '待执行';
                    case '3' :
                        return '执行中';
                    case '4' :
                        return '已完成';
                    case '5' :
                        return '已关闭';
                    default :
                        return v;
                }
            },
            width: 80
        }, {
            name: 'applyName',
            display: '申请人',
            sortable: true,
            width: 80
        }, {
            name: 'applyNameId',
            display: '申请人ID',
            sortable: true,
            hide: true
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true
        }, {
            name: 'customerType',
            display: '客户类型Type',
            sortable: true,
            hide: true
        }, {
            name: 'customerTypeName',
            display: '客户类型',
            sortable: true
        }, {
            name: 'customerWay',
            display: '客户联系方式',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'province',
            display: '省份',
            sortable: true,
            hide: true
        }, {
            name: 'city',
            display: '城市',
            sortable: true,
            hide: true
        }, {
            name: 'areaName',
            display: '归属区域',
            sortable: true,
            width: 80
        }, {
            name: 'areaPrincipal',
            display: '区域负责人',
            sortable: true,
            width: 80
        }, {
            name: 'areaPrincipalId',
            display: '区域负责人Id',
            sortable: true,
            hide: true
        }, {
            name: 'areaCode',
            display: '区域编号（ID）',
            sortable: true,
            hide: true
        }, {
            name: 'projectDescribe',
            display: '试用要求描述',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'updateTime',
            display: '修改时间',
            sortable: true,
            hide: true
        }, {
            name: 'updateName',
            display: '修改人名称',
            sortable: true,
            hide: true
        }, {
            name: 'updateId',
            display: '修改人Id',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '创建时间',
            sortable: true,
            hide: true
        }, {
            name: 'createName',
            display: '创建人名称',
            sortable: true,
            hide: true
        }, {
            name: 'createId',
            display: '创建人ID',
            sortable: true,
            hide: true
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true,
            width: 120,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + v
                    + '</a>';
            }
        }, {
            name: 'contractId',
            display: '合同id',
            sortable: true,
            hide: true
        }, {
            name: 'isFail',
            display: '是否生效',
            sortable: true,
            process: function (v) {
                switch (v) {
                    case '0' :
                        return '生效';
                    case '1' :
                        return '已转合同';
                    case '2' :
                        return '手工关闭';
                    default :
                        return v;
                }
            },
            width: 80
        }, {
            name: 'turnStatus',
            display: '转正状态',
            sortable: true,
            width: 80
        }, {
            name: 'turnDate',
            display: '转正时间',
            sortable: true,
            width: 80
        }, {
            name: 'turnProject',
            display: '转正项目编号',
            sortable: true,
            process: function (v) {
                return initProjectCode(v);
            },
            width: 120
        }, {
            name: 'projectDays',
            display: '工期',
            sortable: true,
            width: 80
        }, {
            name: 'actDate',
            display: '已执行天数',
            sortable: true,
            width: 80
        }, {
            name: 'budgetAll',
            display: '预算',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'feeAllCount',
            display: '决算',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'extensionNum',
            display: '延期次数',
            sortable: true,
            process: function (v) {
                return v + " 次"
            },
            width: 80
        }, {
            name: 'winRate',
            display: '商机赢率',
            sortable: true,
            process: function (v) {
                return v + "%"
            },
            width: 80
        }],
        comboEx: [
            {
                text: '项目状态',
                key: 'status',
                data: [{
                    text: '未提交/确认中',
                    value: '0'
                }, {
                    text: '审批中',
                    value: '1'
                }, {
                    text: '待执行',
                    value: '2'
                }, {
                    text: '执行中',
                    value: '3'
                }, {
                    text: '已完成',
                    value: '4'
                //}, {
                //    text: '已关闭',
                //    value: '5'
                }]
            },{
                text : '提交状态',
                key : 'serCon',
                data : [{
                    text : '未提交',
                    value : '0'
                }, {
                    text : '已提交',
                    value : '1'
                }, {
                    text : '打回',
                    value : '2'
                }, {
                    text : '延期申请',
                    value : '3'
                }, {
                    text : '延期申请打回',
                    value : '4'
                }, {
                    text : '延期申请审批',
                    value : '5'
                }]
            }, {
                text: '转正状态',
                key: 'turnStatus',
                data: [{
                    text: '无',
                    value: '无'
                }, {
                    text: '未转正',
                    value: '未转正'
                }, {
                    text: '已转正',
                    value: '已转正'
                }]
            }
        ],
        buttonsEx: buttonsArr,
        // 扩展右键菜单
        menusEx: menuArr,
        /**
         * 快速搜索
         */
        searchitems: [{
            display: '项目编号',
            name: 'projectCode'
        }, {
            display: '项目名称',
            name: 'projectName'
        }, {
            display: '客户名称',
            name: 'customerName'
        }, {
            display: '申请人',
            name: 'applyName'
        }]
    });
});