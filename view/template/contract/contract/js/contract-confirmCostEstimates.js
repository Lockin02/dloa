var show_page = function () {
    var listGrid = $("#costGrid").data('yxgrid');
    listGrid.options.newp = 1;
    $("#costGrid").yxgrid("reload");
};

$(function () {
    $("#costGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'costEstimatesJson',
        param: {
            'states': '0,1,2,4,5,6',
            'isTemp': '0',
            'isEngConfirmStr': '1',
            'isSubApp': '1',
            'isNoDeal' : $("#isNoDealVal").val()
//			,'engConfirm' : '0'
        },
        title: '合同信息',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        // 扩展右键菜单
        menusEx: [{
            text: '查看',
            icon: 'view',
            showMenuFn: function (row) {
                if (row) {
                    return true;
                }
                return false;
            },
            action: function (row) {
                showModalWin('?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
            }
        }, {
            text: '确认成本概算',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.engConfirm == '0' || row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
                    return true;
                }

                return false;
            },
            action: function (row) {
                showThickboxWin('?model=contract_contract_contract&action=confirmCostView&id='
                    + row.id
                    + "&type=Ser"
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=750&width=1000');
            }
        }, {
            text: '打回',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.engConfirm == '0') {
                    return true;
                }
                return false;
            },
            action: function (row) {
                showThickboxWin("?model=contract_common_relcontract&action=toRollBack&docType=oa_contract_contract&actType=2&id="
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
                // if (window.confirm(("确定要打回?"))) {
                //     $.ajax({
                //         type: "POST",
                //         url: "?model=contract_common_relcontract&action=ajaxBack",
                //         data: {
                //             id: row.id
                //         },
                //         success: function (msg) {
                //             if (msg == 1) {
                //                 alert('打回成功！');
                //                 $("#costGrid").yxgrid("reload");
                //             }
                //         }
                //     });
                // }
            }
        }, {
            text: '发布项目章程',
            icon: 'add',
            showMenuFn: function (row) {
                return (row.ExaStatus == "完成" || row.ExaStatus == "变更中") && row.projectStatus != "1";
            },
            action: function (row, rows, grid) {
                if (row) {
//                    if (row.projectRate * 1 == 100) {
//                        alert('工作量比例已达到100%，不能继续下达');
//                        return false;
//                    }
                    showOpenWin("?model=engineering_charter_esmcharter&action=toAdd&contractId="
                        + row.id);
                }
            }
        }],
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '建立时间',
            sortable: true,
            width: 130
        }, {
            name: 'isNeedStamp',
            display: '是否盖章',
            sortable: true,
            width: 80,
            process: function (v, row) {
                if (row.id == "allMoney" || row.id == undefined) {
                    return "";
                } else {
                    if (v == '0') {
                        return "否";
                    } else {
                        return "是";
                    }
                }
            },
            hide: true
        }, {
            name: 'contractType',
            display: '合同类型',
            sortable: true,
            datacode: 'HTLX',
            width: 60
        }, {
            name: 'businessBelongName',
            display: '签约公司',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'contractNatureName',
            display: '合同属性',
            sortable: true,
            width: 60,
            process: function (v) {
                if (v == '') {
                    return v;
                    // return "金额合计";
                } else {
                    return v;
                }
            },
            hide: true
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true,
            width: 100,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
            }
        }, {
            name: 'contractName',
            display: '合同名称',
            sortable: true,
            width: 180
        }, {
            name: 'contractMoney',
            display: '合同金额',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'moduleName',
            display: '板块',
            sortable: true,
            width: 70
        }, {
            name: 'areaName',
            display: '归属区域',
            sortable: true,
            width: 80
        }, {
            name: 'prinvipalName',
            display: '合同负责人',
            sortable: true,
            width: 80
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width: 60
        }, {
            name: 'state',
            display: '合同状态',
            sortable: true,
            process: function (v) {
                if (v == '0') {
                    return "未提交";
                } else if (v == '1') {
                    return "审批中";
                } else if (v == '2') {
                    return "执行中";
                } else if (v == '3') {
                    return "已关闭";
                } else if (v == '4') {
                    return "已完成";
                } else if (v == '5') {
                    return "已合并";
                } else if (v == '6') {
                    return "已拆分";
                } else if (v == '7') {
                    return "异常关闭";
                }
            },
            width: 60
        }, {
            name: 'engConfirmCost',
            display: '确认状态',
            sortable: true,
            width: 60,
            process: function (v) {
                return v == "1" ? "已确认" : "未确认";
            }
        }, {
            name: 'projectStatus',
            display: '立项状态',
            sortable: true,
            width: 60,
            process: function (v) {
                return v == "1" ? "完成" : "未完成";
            }
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'customerId',
            display: '客户Id',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'customerType',
            display: '客户类型',
            sortable: true,
            datacode: 'KHLX',
            width: 70,
            hide: true
        }, {
            name: 'signStatus',
            display: '签收状态',
            sortable: true,
            width: 80,
            process: function (v, row) {
                if (v == '0') {
                    return "未签收";
                } else if (v == '1') {
                    return "已签收";
                } else if (v == '2') {
                    return "变更未签收";
                }
            },
            hide: true
        }, {
            name: 'areaPrincipal',
            display: '区域负责人',
            sortable: true,
            hide: true
        }, {
            name: 'objCode',
            display: '业务编号',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'prinvipalDept',
            display: '负责人部门',
            sortable: true,
            hide: true
        }, {
            name: 'prinvipalDeptId',
            display: '负责人部门Id',
            sortable: true,
            hide: true
        }],
        comboEx: [{
            text: '确认状态',
            key: 'engConfirmCost',
            data: [{
                text: '未确认',
                value: '0'
            }, {
                text: '已确认',
                value: '1'
            }]
        }, {
            text: '立项状态',
            key: 'projectStatus',
            data: [{
                text: '完成',
                value: '1'
            }, {
                text: '未完成',
                value: '0'
            }]
        }],
        /**
         * 快速搜索
         */
        searchitems: [{
            display: '合同编号',
            name: 'contractCode'
        }, {
            display: '合同名称',
            name: 'contractName'
        }, {
            display: '客户名称',
            name: 'customerName'
        }, {
            display: '业务编号',
            name: 'objCode'
        }],
        sortname: "createTime"
    });

    // 添加待处理勾选项
    var checkStr = '<div class="btnseparator"></div>' +
        '<div class="fbutton" title="" id="noDealCheckBoxWrap" style="position: relative;"><div>' +
        '<div style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;"></div>' +
        '<input id="noDealCheckBox" type="checkbox" checked disabled> 待处理' +
        '</div></div>';
    $("#costGrid-op").after(checkStr);

    $("#noDealCheckBoxWrap").click(function(){
        var isNoDeal = (!$("#noDealCheckBox").is(":checked"))? "1" : "0";
        $("#isNoDealVal").val(isNoDeal);
        if(isNoDeal == "1"){
            $("#noDealCheckBox").attr("checked",true);
        }else{
            $("#noDealCheckBox").removeAttr("checked");
        }

        $("#costGrid").data('yxgrid').options.param.isNoDeal = isNoDeal;
        show_page();
    });
});