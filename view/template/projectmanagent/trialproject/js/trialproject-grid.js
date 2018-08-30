var show_page = function (page) {
    $("#trialprojectGrid").yxgrid("reload");
};
$(function () {
    $("#trialprojectGrid").yxgrid({
        model: 'projectmanagent_trialproject_trialproject',
        action: "pageJsons",
        title: '试用项目',
        param: {
            'serConArr': '1,3,4',
            'isFail': '0'
        },
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        customCode: 'trialprojectGrid',
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '试用项目编号',
            sortable: true
        }, {
            name: 'projectName',
            display: '试用项目名称',
            sortable: true
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
            }
        }, {
            name: 'beginDate',
            display: '试用开始时间',
            sortable: true
        }, {
            name: 'closeDate',
            display: '试用结束时间',
            sortable: true
        }, {
            name: 'budgetMoney',
            display: '预计金额',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'affirmMoney',
            display: '确认试用费用金额',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true
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
                            break;
                        } else {
                            return '未提交';
                            break;
                        }
                    case '1' :
                        return '审批中';
                        break;
                    case '2' :
                        return '待执行';
                        break;
                    case '3' :
                        return '执行中';
                        break;
                    case '4' :
                        return '已完成';
                        break;
                    case '5' :
                        return '已关闭';
                        break;
                    default :
                        return v;
                }
            }
        }, {
            name: 'applyName',
            display: '申请人',
            sortable: true
        }, {
            name: 'projectProcess',
            display: '项目进度',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v) + ' %';
            }
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
            sortable: true
        }, {
            name: 'areaPrincipal',
            display: '区域负责人',
            sortable: true
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
            name: 'isFail',
            display: '是否生效',
            sortable: true,
            process: function (v, row) {
                switch (v) {
                    case '0' :
                        return '生效';
                        break;
                    case '1' :
                        return '已转合同';
                        break;
                    case '2' :
                        return '手工关闭';
                        break;
                    default :
                        return v;
                }
            }
        }, {
            name: 'productLine',
            display: '执行区域',
            sortable: true,
            datacode: 'GCSCX',
            hide: true
        }],
        // 扩展右键菜单
        menusEx: [{
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
                if (row.ExaStatus == '部门审批' && row.isFail == '0') {
                    return true;
                }
                return false;
            },
            action: function (row) {

                showThickboxWin('controller/projectmanagent/trialproject/readview.php?itemtype=oa_trialproject_trialproject&pid='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
            }
        }, {
            text: '确认试用费用金额',
            icon: 'add',
            showMenuFn: function (row) {
                if ((row.ExaStatus == "未审批" || row.ExaStatus == "打回" ) && row.isFail == '0') {
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
                if ((row.ExaStatus == "未审批" || row.ExaStatus == "打回" || row.serCon == "3") && row.isFail == '0') {
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
        }],
        comboEx: [{
            text: '审批状态',
            key: 'ExaStatus',
            data: [{
                text: '未审批',
                value: '未审批'
            }, {
                text: '部门审批',
                value: '部门审批'
            }, {
                text: '打回',
                value: '打回'
            }, {
                text: '完成',
                value: '完成'
            }]
        }, {
            text: '确认状态',
            key: 'ExaStatusArr',
            data: [{
                text: '未确认',
                value: '未审批,打回'
            }, {
                text: '已确认',
                value: '部门审批,完成'
            }]
        }, {
            text: '提交状态',
            key: 'serCon',
            data: [{
                text: '未提交',
                value: '0'
            }, {
                text: '已提交',
                value: '1'
            }, {
                text: '打回',
                value: '2'
            }, {
                text: '延期申请',
                value: '3'
            }, {
                text: '延期申请打回',
                value: '4'
            }, {
                text: '延期申请审批',
                value: '5'
            }]
        }],
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